<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = [
    	'to_name',
    	'from_name',
    	'text'
    ];

	public function chats(){
	  return $this->belongsTo('App\Models\Chats','id','message_id');
	}
}
