<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $fillable = [
    	'display_name',
    	'avatar',
    	'neon_color',
    	'tuitored',
    	'rewards',
        'status',
        'last_activity',
        'cover_photo',
        'location',
        'skill',
        'bio',
        'chat_points',
        'special_points'
    ];

	public function user(){
	  return $this->belongsTo('App\User');
	}
}
