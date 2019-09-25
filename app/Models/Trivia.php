<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trivia extends Model
{
    protected $fillable = [
    	'question',
    	'answer',
    	'reward'
    ];

	public function user(){
	  return $this->belongsTo('App\User');
	}
}
