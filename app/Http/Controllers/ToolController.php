<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Events\UpdateSongRequest;
use App\Models\RequestSong as RS;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use Validator;
use Auth;

class ToolController extends Controller
{
    public function requestSong(Request $req){
        if($this->can('rs-send')==false){
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission here.'
            ]);
        }
    	$valid = Validator::make($req->all(),[
    		'artist'	=>	'required|string|min:2',
    		'song'		=>	'required|string|min:2'
    	]);
    	if($valid->fails()){
    		return response()->json([
    			'status'	=>	0,
    			'text'		=> $valid
    		]);
    	}
    	$rs = new RS();
    	if($rs->where([ 'artist' => $req->input('artist') ])->where([ 'song' => $req->input('song') ])->where([ 'status' => 0 ])->count()){
    		return response()->json([
    			'status'	=>	0,
    			'text'		=>	'This was already requested. Please wait for it to be played.'
    		]);
    	}
    	$d = Auth::user()->rs()->create([
    		'artist'	=>	$req->input('artist'),
    		'song'		=>	$req->input('song'),
    		'status'	=>	0
    	]);
    	$data = [
    		'id'		=>  $d->id,
    		'artist'	=>	$req->input('artist'),
    		'song'		=>	$req->input('song'),
    		'user'		=>	[
    			'name'	=> Auth::user()->info()->first()->display_name
    		]
    	];
    	broadcast(new UpdateSongRequest(1, $data));
        Auth::user()->timeline()->create([
            'title'     =>  'You requested a song.',
            'content'   =>  'You requested a song <b class="text-primary">'.$req->input('artist').' - '.$req->input('song').'</b>.',
            'type'      =>  'request.song'
        ]);
    	return response()->json([
    		'status'	=>	1,
    		'text'		=>	'Request successfully sent.'
    	]);
    }

    public function requestSongList(){
        if($this->can('rs-list')==false){
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission here.'
            ]);
        }
    	if(RS::where(['status'	=>	0])->count()){
    		$data = [];
    		foreach(RS::where(['status' => 0])->orderBy('id','desc')->cursor() as $rs){
    			$data[] = [
    				'id'		=> $rs->id,
    				'artist'	=> $rs->artist,
    				'song'		=> $rs->song,
    				'user'		=> [
    					'name'	=> $rs->user()->first()->info()->first()->display_name
    				]
    			];
    		}
    		return response()->json([
    			'status'	=>	1,
    			'text'		=>	$data
    		]);
    	}else{
    		return response()->json([
    			'status'	=>	0,
    			'text'		=> 'No requested song available.'
    		]);
    	}
    }

    public function removeRequest(Request $req){
        if($this->can('rs-update')==false){
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission here.'
            ]);
        }
    	try{
    		$rs = RS::findOrFail($req->input('id'));
    		$rs->status = 2;
    		$rs->save();
    		$user = Auth::user();
			$msg = [
	    		'created_at'	=>	date('F d, Y h:i a',time()),
	    		'message' => 'Hey @'.$req->input('name').' your song request '.$req->input('title').' is currently unavailable, Sorry.',
	    		'user'	=> [
	    			'info' => $user,
	    			'role' => $user->roles[0],
	    			'extra' => $user->info()->first()
	    		],
			    'replied' => [
					'status'	=>	0 ,
					'to_name'		=>	'',
					'from_name'		=>	'',
					'text'		=>	''
			    ]
			];
			broadcast(new MessageSent($msg));
            Auth::user()->timeline()->create([
                'title'     =>  'You marked a requested song as unavailable.',
                'content'   =>  'You marked a requested song <b class="text-primary">'.$req->input('title').'</b> by <b class="text-primary">'.htmlentities($req->input('name')).'</b> as unavailable.',
                'type'      =>  'request.update.unavailable'
            ]);
    		return response()->json([
    			'status'	=>	1,
    			'text'		=>	'Request deleted successfully.'
    		]);
    	}catch(ModelNotFoundException $e){
    		return response()->json([
    			'status'	=>	0,
    			'text'		=>	'Request ID doesnt exists.'
    		]);
    	}
    }

    public function removeAllRequest(){
        if($this->can('rs-update')==false){
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission here.'
            ]);
        }
    	RS::where(['status'	=> 0])->update([
    		'status'	=>	2
    	]);
        Auth::user()->timeline()->create([
            'title'     =>  'You marked all requested song as unavailable.',
            'content'   =>  '',
            'type'      =>  'request.update.unavailable'
        ]);
    	return response()->json([
    		'status'	=>	1,
    		'text'		=>	'All song request has been marked as unavailable.'
    	]);
    }

    public function playRequest(Request $req){
        if($this->can('rs-update')==false){
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission here.'
            ]);
        }
    	try{
    		$rs = RS::findOrFail($req->input('id'));
    		$rs->status = 1;
    		$rs->save();
    		$user = Auth::user();
			$msg = [
	    		'created_at'	=>	date('F d, Y h:i a',time()),
	    		'message' => 'Hey @'.$req->input('name').' your song request '.$req->input('title').' is now being played. Request more!',
	    		'user'	=> [
	    			'info' => $user,
	    			'role' => $user->roles[0],
	    			'extra' => $user->info()->first()
	    		],
			    'replied' => [
					'status'	=>	0 ,
					'to_name'		=>	'',
					'from_name'		=>	'',
					'text'		=>	''
			    ]
			];
			broadcast(new MessageSent($msg));
            Auth::user()->timeline()->create([
                'title'     =>  'You marked a requested song as unavailable.',
                'content'   =>  'You marked a requested song <b class="text-primary">'.$req->input('title').'</b> by <b class="text-primary">'.htmlentities($req->input('name')).'</b> as played.',
                'type'      =>  'request.update.played'
            ]);
    		return response()->json([
    			'status'	=>	1,
    			'text'		=>	'Request deleted successfully.'
    		]);
    	}catch(ModelNotFoundException $e){
    		return response()->json([
    			'status'	=>	0,
    			'text'		=>	'Request ID doesnt exists.'
    		]);
    	}
    }

    public function historyRequest(){
        if($this->can('rs-list-history')==false){
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission here.'
            ]);
        }
    	if(RS::where('status','!=',0)->count()){
    		$rs = RS::where('status','!=',0)->orderBy('updated_at','desc');
    		$data = [];
    		foreach($rs->take(25)->get() as $r){
    			$data[] = [
    				'artist'	=> $r->artist.' - '.$r->song,
    				'name'		=> $r->user()->first()->info()->first()->display_name,
    				'created_at' => date('M d, Y h:i a',strtotime($r->created_at)),
    				'status'	=> $r->status
    			];
    		}
    		return response()->json([
    			'status'	=>	1,
    			'text'		=>	$data
    		]);
    	}else{
    		return response()->json([
    			'status'	=>	0,
    			'text'		=>	'No history available.'
    		]);
    	}
    }
}
