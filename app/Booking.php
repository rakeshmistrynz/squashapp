<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{

    protected $fillable = [

        'booking_date',
        'time_slot_id',
        'player1_id',
        'player2_id',
        'court_id',
        'booking_cat_id'
    ];

    public function player()
    {
        return $this->belongsTo('App\User', 'player1_id');
    }

    public function opponent()
    {
        return $this->belongsTo('App\User', 'player2_id');
    }

    public function booking_cat()
    {
        return $this->belongsTo('App\Bookings_category', 'booking_cat_id');
    }

    public function time()
    {
        return $this->belongsTo('App\Time_slot', 'time_slot_id');
    }

    public function match()
    {
        return $this->hasMany('App\Result', 'match_id');
    }


}
