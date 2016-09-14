<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Notice;

use Illuminate\Http\Request;

class NoticesController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Notices Controller
    |--------------------------------------------------------------------------
    |
    | This controller contains the view rendered for viewing club notices, posted by the club administrator.
    |
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a list of club notices.
     *
     * @return Response
     */
    public function index()
    {
        $notices = Notice::orderBy('created_at','DESC')->get();

        return view('notices.index', compact('notices'));
    }

    public function test()
    {
        $configvalue = config('squash.user_types');

        return $configvalue;
    }

}
