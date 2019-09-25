<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnBoard extends Model
{
    protected $fillable = [
    	'dj_id',
    	'dj_name',
    	'dj_tag',
    	'dj_avatar'
    ];
}
