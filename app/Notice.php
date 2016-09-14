<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'headline',
        'body',
        'image_name',
        'file_name'

    ];

    protected $dates = ['deleted_at'];

    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }

}
