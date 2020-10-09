<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'rank',
        'api_id',
    ];

    function user(){
        return $this->belongsTo('App\Models\User');
    }

    /**
     * The attributes that should be hidden for arrays.
     * all of these are not needed for now
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
        'updated_at',
        'created_at',
        'rank'
    ];
}
