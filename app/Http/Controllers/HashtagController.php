<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\UpdateHashtag;
use App\Models\Hashtag;

class HashtagController extends Controller
{
    public function checkNewHash(){
    	$data = [];
    	$hash = new Hashtag();
    	if($hash->count()){
    		foreach($hash->take(5)->get()->sortByDesc(function($q){
          		return $q->getCount();
        	}) as $h){
    			$data[] = [
    				'hash'	=>	$h->hash,
    				'total'	=>	$h->getCount()
    			];
    		}
    		broadcast(new UpdateHashtag(1,$data));
    		return response()->json([
    			'status'	=>	1,
    			'text'		=> $data
    		]);
    	}else{
    		return response()->json([
    			'status'	=>	0,
    			'text'		=> $data
    		]);
    	}
    }
}
