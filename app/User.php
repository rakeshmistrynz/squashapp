<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'first_name',
        'last_name',
        'user_photo_file',
        'user_type',
        'email',
        'password'

    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['deleted_at'];

    public function setFirstNameAttribute($first_name)
    {
        $this->attributes['first_name'] = trim($first_name);
    }

    public function setLastNameAttribute($last_name)
    {
        $this->attributes['last_name'] = trim($last_name);
    }

    public function player()
    {

        return $this->hasMany('App\Booking', 'player1_id');
    }

    public function opponent()
    {

        return $this->hasMany('App\Booking', 'player2_id');
    }

    public function results()
    {

        return $this->hasMany('App\Result', 'player_id');
    }

    public function author()
    {

        return $this->hasMany('App\Notices', 'author_id');
    }
}
