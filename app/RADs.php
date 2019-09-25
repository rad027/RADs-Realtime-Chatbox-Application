<?php

namespace App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Image;

class RADs extends Controller
{
    public function view_image($path,$name){
    	return Image::make(storage_path().'/app/public/'.$path.'/'.$name)->response();
    }
}
