<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    protected $fillable = [
    	'title',
    	'content',
    	'type'
    ];

	public function user(){
	  return $this->belongsTo('App\User');
	}
}
