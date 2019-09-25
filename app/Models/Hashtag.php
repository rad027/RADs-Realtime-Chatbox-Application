<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Chats;

class Hashtag extends Model
{
    protected $fillable = [
    	'hash'
    ];

    public function getCount(){
    	return Chats::where('message', 'like', '%'.$this->hash.'%')->count();
    }
}
