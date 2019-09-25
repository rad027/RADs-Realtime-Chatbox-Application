<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
	protected $fillable = [
		'message',
		'type'
	];

	public function user(){
	  return $this->belongsTo('App\User');
	}

    public function reply(){
      return $this->hasMany('App\Models\Reply','message_id','id');
    }
}
