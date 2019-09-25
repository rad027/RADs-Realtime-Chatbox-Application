<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestSong extends Model
{
    protected $fillable = [
    	'artist',
    	'song',
    	'status'
    ];

	public function user(){
	  return $this->belongsTo('App\User');
	}
}
