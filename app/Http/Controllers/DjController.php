<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Events\UpdateOnBoard;
use Illuminate\Http\Request;
use App\Models\OnBoard;
use App\Models\Dj;
use App\User;
use Auth;

class DjController extends Controller
{
    public function onboard_view(){
    	return view('ranks.dj.onboard');
    }

    public function dj_list(){
    	return view('ranks.dj.list');
    }

    public function fetch_user(Request $req){
    	if($this->can('dj-add')==false){
    		return response()->json([
    			'status'	=>	0,
    			'text'		=>	'You dont have permission here.'
    		]);
    	}
    	if($req->ajax()){
    		$users = User::where('name','LIKE','%'.$req->input('q').'%')->orderBy('id','desc');
    		if($users->count() > 0){
    			$users = $users->get();
	    		return response()->json([
	    			'status'	=>	1,
	    			'text'		=>	$users
	    		]);
    		}else{
	    		return response()->json([
	    			'status'	=>	0,
	    			'text'		=>	'No results.'
	    		]);
    		}
    	}else{
    		return response()->json([
    			'status'	=>	0,
    			'text'		=>	'You dont have permission here.'
    		]);
    	}
    }

    public function add_dj(Request $req){
    	if($req->ajax()){
    		if($this->can(['dj-add']) == false){
    			return response()->json([
    				'status'	=>	0,
    				'text'		=>	'You dont have permission here.'
    			]);
    		}
    		try {
	    		$c_user = Auth::user();
	    		$user = User::find($req->input('user_id'));
	    		$dj = new Dj();
	    		if($dj->where(['user_id' => $req->input('user_id')])->count()){
	    			return response()->json([
	    				'status'	=>	0,
	    				'text'		=>	'User is already on the list'
	    			]);
	    		}else if($dj->where(['dj_name' => $req->input('dj_name')])->count()){
	    			return response()->json([
	    				'status'	=>	0,
	    				'text'		=>	'DJ name is already taken.'
	    			]);
	    		}else if($dj->where(['user_id' => $req->input('user_id')])->where(['dj_name' => $req->input('dj_name')])->count()){
	    			return response()->json([
	    				'status'	=>	0,
	    				'text'		=>	'This dj is already on the list'
	    			]);
	    		}else{
	    			$x = $user->dj()->create([
	    				'dj_name'	=>	$req->input('dj_name'),
	    				'added_by'	=>	$c_user->name
	    			]);
	    			$r = [
	    				'id'			=>	$x->id,
	    				'dj_name'		=> 	$req->input('dj_name').'&nbsp;('.$user->name.')',
	    				'added_by'		=>	$c_user->name,
	    				'avatar'		=>	$user->info()->first()->avatar,
	    				'created_at'	=>	date('M d, Y h:i a',strtotime($x->created_at))
	    			];
	    			Auth::user()->timeline()->create([
	    				'title'		=>	'You added a DJ',
	    				'content'	=>	'You added <b>'.$req->input('dj_name').'&nbsp;('.$user->name.')'.'</b> as DJ.',
	    				'type'		=>	'dj.add'
	    			]);
	    			return response()->json([
	    				'status'	=>	1,
	    				'text'		=>	$r
	    			]);
	    		}
    		}catch(ModelNotFoundException $e){
    			return response()->json([
    				'status'	=>	0,
    				'text'		=>	'User not found.'
    			]);
    		}
    	}else{
    		return redirect('home')->with('error','Invalid request.');
    	}
    }

    public function djList(Request $req){
    	if($req->ajax()){
    		if($this->can(['dj-list']) == false){
    			return response()->json([
    				'status'	=>	0,
    				'text'		=>	'You dont have permission here.'
    			]);
    		}
    		$array = [];
    		if(DJ::count()){
    			foreach(DJ::orderBy('id','desc')->cursor() as $dj){
    				$array[] = [
    					'dj_name'		=> $dj->dj_name.'&nbsp;('.$dj->user()->first()->name.')',
    					'added_by'		=> $dj->added_by,
    					'avatar'		=> $dj->user()->first()->info()->first()->avatar,
    					'created_at'	=> date('M d, Y h:i a',strtotime($dj->created_at)),
    					'id'			=> $dj->id
    				];
    			}
    			return response()->json([
    				'status'	=>	1,
    				'text'		=>	$array
    			]);
    		}else{
    			return response()->json([
    				'status'	=>	0,
    				'text'		=>	'No DJ on the list yet.'
    			]);
    		}
    	}else{
    		return redirect('home')->with('error','Invalid request.');
    	}
    }

    public function djDelete(Request $req){
    	if($req->ajax()){
    		if($this->can(['dj-delete']) == false){
    			return response()->json([
    				'status'	=>	0,
    				'text'		=>	'You dont have permission here.'
    			]);
    		}
    		try{
    			$dj = DJ::findOrFail($req->input('id'));
    			$name = $dj->dj_name;
    			$dj->delete();
	    		Auth::user()->timeline()->create([
	    			'title'		=>	'You deleted a DJ named <b>'.$name.'</b>',
	    			'content'	=>	'',
	    			'type'		=>	'dj.delete'
	    		]);
    			return response()->json([
    				'status'	=>	1,
    				'text'		=>	'DJ has been deleted.'
    			]);
    		}catch(ModelNotFoundException $e){
    			return response()->json([
    				'status'	=>	0,
    				'text'		=>	'No DJ id found.'
    			]);
    		}
    	}else{
    		return redirect('home')->with('error','Invalid request.');
    	}
    }

    public function djInfo(Request $req){
    	if($req->ajax()){
    		if($this->can(['dj-update']) == false){
    			return response()->json([
    				'status'	=>	0,
    				'text'		=>	'You dont have permission here.'
    			]);
    		}
    		try{
    			$dj = DJ::findOrFail($req->input('id'));
    			return response()->json([
    				'status'	=>	1,
    				'text'		=>	$dj->dj_name
    			]);
    		}catch(ModelNotFoundException $e){
    			return response()->json([
    				'status'	=>	0,
    				'text'		=>	'No DJ id found.'
    			]);
    		}
    	}else{
    		return redirect('home')->with('error','Invalid request.');
    	}
    }

    public function djUpdate(Request $req){
    	if($req->ajax()){
    		if($this->can(['dj-update']) == false){
    			return response()->json([
    				'status'	=>	0,
    				'text'		=>	'You dont have permission here.'
    			]);
    		}
    		try{
    			$dj = DJ::findOrFail($req->input('id'));
    			$old = $dj->dj_name;
    			$dj->dj_name = $req->input('dj_name');
    			$dj->save();
	    		Auth::user()->timeline()->create([
	    			'title'		=>	'You updated a DJ',
	    			'content'	=>	'You have successfuly updated <b>'.$old.'</b>`s name into <b>'.$req->input('dj_name').'</b>.',
	    			'type'		=>	'dj.update'
	    		]);
    			return response()->json([
    				'status'	=>	1,
    				'text'		=>	'DJ has been updated.'
    			]);
    		}catch(ModelNotFoundException $e){
    			return response()->json([
    				'status'	=>	0,
    				'text'		=>	'No DJ id found.'
    			]);
    		}
    	}else{
    		return redirect('home')->with('error','Invalid request.');
    	}
    }

    public function OnBoardUpdate(Request $req){
    	if($req->ajax()){
    		try{
    			$uwu = [];
    			if($req->input('dj') == 0){
	    			OnBoard::create([
				    	'dj_id'			=>	0,
				    	'dj_name'		=>	'Auto Tune',
				    	'dj_tag'		=>	'No dj is onlne',
				    	'dj_avatar'		=>	'/images/icon-user.png'
	    			]);
	    			$uwu = [
				    	'dj_id'			=>	0,
				    	'dj_name'		=>	'Auto Tune',
				    	'dj_tag'		=>	'No dj is onlne',
				    	'dj_avatar'		=>	'/images/icon-user.png'
	    			];
    			}else{
	    			if(empty($req->input('dj'))){
	    				return response()->json([
	    					'status'	=>	0,
	    					'text'		=>	'Disc Jockey must be filled up.'
	    				]);
	    			}
    				$dj = DJ::findOrFail($req->input('dj'));
	    			if(empty($req->input('tagline'))){
	    				return response()->json([
	    					'status'	=>	0,
	    					'text'		=>	'Tagline must be filled up.'
	    				]);
	    			}
	    			OnBoard::create([
				    	'dj_id'			=>	$dj->id,
				    	'dj_name'		=>	$dj->dj_name,
				    	'dj_tag'		=>	$req->input('tagline'),
				    	'dj_avatar'		=>	$dj->user()->first()->info()->first()->avatar ? $dj->user()->first()->info()->first()->avatar : '/images/icon-user.png'
	    			]);
	    			$uwu = [
				    	'dj_id'			=>	$dj->id,
				    	'dj_name'		=>	$dj->dj_name,
				    	'dj_tag'		=>	$req->input('tagline'),
				    	'dj_avatar'		=>	$dj->user()->first()->info()->first()->avatar ? $dj->user()->first()->info()->first()->avatar : '/images/icon-user.png'
	    			];
    			}
    			broadcast(new UpdateOnBoard($uwu));
	    		Auth::user()->timeline()->create([
	    			'title'		=>	'You updated the On Board',
	    			'content'	=>	'You have successfuly updated Onboard for <b>'.$uwu['dj_name'].'</b>.',
	    			'type'		=>	'dj.update'
	    		]);
    			return response()->json([
    				'status'	=>	1,
    				'text'		=>	$uwu
    			]);
    		}catch(ModelNotFoundException $e){
    			return response()->json([
    				'status'	=>	0,
    				'text'		=>	'No DJ id found.'
    			]);
    		}
    	}else{
    		return redirect('home')->with('error','Invalid request.');
    	}
    }

    public function onboard_now(Request $req){
    	if($req->ajax()){
    		$ob = OnBoard::orderBy('id','desc');
    		if($ob->count()){
    			return response()->json([
    				'status'	=>	1,
    				'text'		=>	[
				    	'dj_id'			=>	$ob->first()->id,
				    	'dj_name'		=>	$ob->first()->dj_name,
				    	'dj_tag'		=>	$ob->first()->dj_tag,
				    	'dj_avatar'		=>	$ob->first()->dj_avatar
    				]
    			]);
    		}else{

    		}
    	}else{
    		return redirect('home')->with('error','Invalid request.');
    	}
    }
}
