<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Booking;
use App\Time_slot;
use App\User;
use App\Result;
use App\Bookings_category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreResultRequest;

class LadderController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Ladder Controller
    |--------------------------------------------------------------------------
    |
    | This controller contains the page views and logic for the ladder section of the application.
    | The methods included in this controller, render a list of results and matches were a result is required to be entered. The methods inside this controller also work out a players
    | ladder statistics to rendered in thier player profile.
    |
    */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * View the squash leader board
     */
    public function leader_board()
    {


        return view('ladder.index');

    }

    /**
     * View player profile
     */
    public function view_profile()
    {

        return view('ladder.profile');

    }

    /**
     * View player profile
     */
    public function view_rules()
    {

        return view('ladder.rules');

    }

    /**
     * Store result of a ladder match. Determine if the user was the winner then calculate points based on a score or a result by default.
     * Save the result accordingly in the Results Table.
     *
     * @return Redirect back to same page to see the result has been entered, under list of results.
     */
    public function save_result(StoreResultRequest $request)
    {

            $this->calculate_result($request);
            session()->flash('flash_message', 'Your result has been entered');

        return redirect('ladder/my-matches');

    }

    /**
     * Display a list of ladder matches the user has played. Divide the list into matches with a result and no results.
     * From the no results list the user can enter a result for that match.
     * @return view List of ladder matches with no results and results.
     */
    public function view_matches()
    {

        $no_results = Result::where('player_id', '=', \Auth::user()->id)->whereRaw('created_at = updated_at')->get();

        $results = Result::where('player_id', '=', \Auth::user()->id)->whereRaw('created_at != updated_at')->orderBy('created_at', 'DESC')->take(5)->get();

        $list_no_results = $this->get_no_results($no_results);

        $list_results = $this->list_results($results);

        return view('ladder.show', compact('list_no_results', 'list_results'));

    }


    /**
     * Get the squash ladder with members names, points and positions
     * @return JSON object
     */
    public function get_ladder()
    {


        $ladder = $this->the_ladder();

        foreach ($ladder as $key => $person) {

            $person['dataname'] = User::withTrashed()->find($person->player_id)->first_name . '-' . User::withTrashed()->find($person->player_id)->last_name;
            $person['name'] = User::withTrashed()->find($person->player_id)->first_name . ' ' . User::withTrashed()->find($person->player_id)->last_name;
            $person['photo'] = User::withTrashed()->find($person->player_id)->user_photo_file;
            $person['points'] = intval($person->points);
            $person['position'] = $key;
        }

        return response()->json($ladder);

    }

    /**
     * Get member profile information with their winning/lossing percentage, games played
     * and ladder position.
     * @return JSON Object
     */
    public function get_profile(Request $request)
    {


        $user_id = User::withTrashed()->where('first_name', '=', $request->input('first_name'))->where('last_name', '=', $request->input('last_name'))->pluck('id');

        $wins = Result::where('player_id', '=', $user_id)->sum('winner');

        $matches = Result::where('player_id', '=', $user_id)->count('winner');

        $points = Result::where('player_id', '=', $user_id)->sum('points');

        $loss = $matches - $wins;

        if ($wins || $matches != null) {

            $winning_percentage = 100 * ($wins / $matches);

            $losing_percentage = ($winning_percentage == 0) ? 100 : 100 - $winning_percentage;

            $ladder_pos = $this->ladder_position($user_id);

        } else {

            $losing_percentage = 0;
            $winning_percentage = 0;
            $ladder_pos = 0;
        };

        $profile_data['pieData'][] = ['label' => 'WIN', 'value' => intval($wins)];

        $profile_data['pieData'][] = ['label' => 'LOSS', 'value' => intval($loss)];

        $profile_data['stats'] = ['winningPct' => intval($winning_percentage), 'losingPct' => intval($losing_percentage), 'points' => intval($points), 'matches' => intval($matches), 'ladder_pos' => intval($ladder_pos)];

        return response()->json($profile_data);
    }

    /**
     * Work out users position on squash ladder
     * @param  int $user_id
     * @return int          ladder position.
     */
    private function ladder_position($user_id)
    {

        $ladder = $this->the_ladder();

        $positions = [];

        foreach ($ladder as $position) {

            $positions[] = $position->player_id;

        }

        return array_search($user_id, $positions) + 1;
    }

    /**
     * Get the squash ladder as an array from database.
     * @return array object
     */
    private function the_ladder()
    {

        return $ladder = Result::groupBy('player_id')->orderBy('points', 'desc')->selectRaw('player_id, sum(points) as points')->get();

    }

    /**
     * Get a list of ladder match results for the user and also works out the game scores for the user and opponent.
     * @param  array $array An array of results from the Results Table.
     * @return array         An array of results, including the related booking information.
     */
    private function list_results($array)
    {

        foreach ($array as $match) {

            $booking = Booking::find($match->match_id);

            $opponent = $booking->player2_id;

            $user = \Auth::user()->id;

            $opponent = ($opponent == $user) ? User::withTrashed()->find($booking->player1_id) : User::withTrashed()->find($booking->player2_id);

            $match['opponent'] = $opponent->first_name;

            $opponent_points = Result::where('match_id', '=', $match->match_id)->where('player_id', '=', $opponent->id)->pluck('points');

            $match['opponent_games'] = $this->game_score($opponent_points, $match->points);

            $match['user_games'] = $this->game_score($match->points, $opponent_points);
        }

        return $array;

    }

    private function calculate_result($u_input)
    {

        $opponent_result = Result::where('match_id', '=', $u_input->input('match_id'))->where('player_id', '!=', \Auth::user()->id);

        $user_result = Result::where('match_id', '=', $u_input->input('match_id'))->where('player_id', '=', \Auth::user()->id);

        if ($u_input->input('win')) {

            $winner_points = $this->winners_result($u_input->input('result_by_default'), $u_input->input('user_score'), $u_input->input('opponent_score'));

            $loser_points = $this->losers_result($u_input->input('result_by_default'), $u_input->input('opponent_score'));

            $user_result->update(['points' => $winner_points, 'winner' => '1']);

            $opponent_result->update(['points' => $loser_points, 'winner' => '0']);

            return;
        }

        $winner_points = $this->winners_result($u_input->input('result_by_default'), $u_input->input('opponent_score'), $u_input->input('user_score'));

        $loser_points = $this->losers_result($u_input->input('result_by_default'), $u_input->input('user_score'));

        $user_result->update(['points' => $loser_points, 'winner' => '0']);

        $opponent_result->update(['points' => $winner_points, 'winner' => '1']);

        return;

    }


    /**
     * Calculate the points for the winner of a ladder match. Best out of 5 games or 3 games.
     * If winner wins in 3 games, collects 20 bonus points, or in 4 games 10 bonus points. No bonus points for best out of 3 games.
     * For playing a match each players collects 10 points.
     * For each match won a player gets 10 points.
     * If win by default, winner collects 50 points. Player defaulting collects no points.
     * @param  bool $win_by_default Check if win was by default
     * @param  int $winners_score Winners match score
     * @param  int $losers_score Loser match score
     * @return int                        wWinners total points for the match
     */
    private function winners_result($win_by_default, $winners_score, $losers_score)
    {
        $bonus_points = 0;

        if ($winners_score > 2) {

            $bonus_points = ($losers_score == 0) ? 20 : 10;

        }

        return $points = ($win_by_default) ? 50 : 10 + ($winners_score * 10) + $bonus_points;

    }

    /**
     * Calculate the point for the losser of a match.
     * @param  bool $loss_by_default Check if loss was by default
     * @param  int $losers_score
     * @return int                    Lossers total points for the match
     */
    private function losers_result($loss_by_default, $losers_score)
    {

        return $points = ($loss_by_default) ? 0 : 10 + ($losers_score * 10);

    }

    /**
     * Get a list of no results from the results table and get the related match information (name of opponent, date, court etc) from the booking and users table.
     * @param  array $array An array of no results from the Results Table
     * @return array        An array of results, including the related booking information.
     */
    private function get_no_results($array)
    {

        foreach ($array as $match) {

            $booking = Booking::find($match->match_id);

            $opponent = $booking->player2_id;

            $user = \Auth::user()->id;

            $opponent = ($opponent == $user) ? User::find($booking->player1_id) : User::find($booking->player2_id);

            $match['opponent'] = $opponent->first_name;
        }

        return $array;

    }

    /**
     * Work out game score based on user and opponent points for a match
     * @param  int $user_score
     * @param  int $opponent_score
     * @return int
     */
    private function game_score($user_points, $opponent_points)
    {

        switch ($user_points) {

            case 60:
                return 3;
                break;

            case 50:

                return ($opponent_points == 0) ? 'DEFAULT' : 3;

                break;

            case 30:

                return 2;

            case 20:

                return 1;

                break;

            case 10:

                return 0;

                break;
        }
    }


}
