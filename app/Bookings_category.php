<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookings_category extends Model
{

    public $timestamps = false;

    protected $fillable = [

        'category_description'

    ];

    public function bookings()
    {

        return $this->hasMany('App\Booking');
    }

}
