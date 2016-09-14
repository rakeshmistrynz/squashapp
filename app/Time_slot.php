<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Time_slot extends Model
{

    protected $fillable = array('time_slot');

    public $timestamps = false;

    public function time()
    {

        return $this->hasMany('App\Booking', 'time_slot_id');
    }

}
