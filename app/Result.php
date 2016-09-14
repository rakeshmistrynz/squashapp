<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{

    protected $fillable = array('match_id');

    public function player()
    {
        return $this->belongsTo('App\User', 'player_id');
    }

    public function match()
    {
        return $this->belongsTo('App\Booking', 'match_id');
    }

}
