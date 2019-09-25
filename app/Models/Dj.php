<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dj extends Model
{
    protected $fillable = [
    	'dj_name',
    	'added_by'
    ];

	public function user(){
	  return $this->belongsTo('App\User');
	}
}
