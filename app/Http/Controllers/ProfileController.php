<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\UserInfo as UI;
use Validator;
use Image;
use Auth;
use File;

class ProfileController extends Controller
{
    public function view_profile($username){
    	try{
    		$c = UI::where([ 'display_name' => $username ])->firstOrFail();
    		$profile = (object) [
    			'info'	=>	$c->user()->first(),
    			'extra' => $c,
    			'role'	=> $c->user()->first()->roles()->first(),
    			'timeline' => $c->user()->first()->timeline()
    		];
    		return view('user.profile', compact('username','profile'));
    	}catch(ModelNotFoundException $e){
    		return redirect()->route('home')
    			->with('error','Profile doesnt exists.');
    	}
    }

    public function settings_view(){
    	return view('user.settings');
    }

    public function update_pictures(Request $req){
        if($this->can('profile-update-photo')==false){
            return redirect('home')->with('error','You dont have permission here.');
        }
    	if($req->hasFile('cover_photo') || $req->hasFile('display_photo')){
	    	$valid = Validator::make($req->all(),[
			    'cover_photo'	=>	'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			    'display_photo'	=>	'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
	    	]);
	    	if($valid->fails()){
	    		return back()->withErrors($valid);
	    	}
	    	$currentUser = Auth::user();
	    	if($req->hasFile('cover_photo') && $req->hasFile('display_photo')){
	    		$data = [
	    			$req->file('display_photo'),
	    			$req->file('cover_photo')
	    		];
	    		$i = 0;
	    		$mic = md5(microtime());
	    		foreach($data as $d){
	    			$filename = $i == 0 ? 'avatar'.$d->getClientOriginalExtension() : 'cover'.$d->getClientOriginalExtension();
            		$save_path = $i ==0 ? storage_path().'/users/id/'.$currentUser->id.'/uploads/images/avatar/' : storage_path().'/users/id/'.$currentUser->id.'/uploads/images/cover/';
            		$public_path = $i == 0 ? '/images/profile/'.$currentUser->id.'/avatar/'.$filename : '/images/profile/'.$currentUser->id.'/cover/'.$filename;
            		File::makeDirectory($save_path, $mode = 0755, true, true);
            		Image::make($d)->save($save_path.$filename);
            		//history
	    			$filename2 = $i == 0 ? $mic.'-avatar'.$d->getClientOriginalExtension() : $mic.'-cover'.$d->getClientOriginalExtension();
            		$save_path2 = storage_path().'/users/id/'.$currentUser->id.'/uploads/images/history/';
            		$public_path2 = '/images/profile/'.$currentUser->id.'/history/'.$filename2;
            		File::makeDirectory($save_path2, $mode = 0755, true, true);
            		Image::make($d)->save($save_path2.$filename2);
            		if($i == 0){
		            	$x = $currentUser->info()->first();
		            	$x->avatar = $public_path;
            		}else{
		            	$x = $currentUser->info()->first();
		            	$x->cover_photo = $public_path;
            		}
            		$x->save();
            		$i++;
	    		}
	    		Auth::user()->timeline()->create([
	    			'title'		=>	'You updated your both cover and display photos.',
	    			'content'	=>	'<div class="row"><div class="col-6"><img data-src="/images/profile/'.$currentUser->id.'/history/'.$mic.'-cover'.($req->file('cover_photo')->getClientOriginalExtension()).'" class="img-fluid lazy-load"></div><div class="col-6"><img data-src="/images/profile/'.$currentUser->id.'/history/'.$mic.'-avatar'.($req->file('display_photo')->getClientOriginalExtension()).'" class="img-fluid lazy-load"></div></div>',
	    			'type'		=>	'update.all.photos'
	    		]);
	    		return back()->with('success','Pictures has been updated successfully.');
	    	}else if($req->hasFile('cover_photo')){
	    		$mic = md5(microtime());
	    		$file = $req->file('cover_photo');
	    		$filename = 'cover'.$file->getClientOriginalExtension();
	    		$save_path = storage_path().'/users/id/'.$currentUser->id.'/uploads/images/cover/';
	    		$public_path = '/images/profile/'.$currentUser->id.'/cover/'.$filename;
            	File::makeDirectory($save_path, $mode = 0755, true, true);
            	Image::make($file)->save($save_path.$filename);
            	//history
	    		$filename2 = $mic.'-cover'.$file->getClientOriginalExtension();
	    		$save_path2 = storage_path().'/users/id/'.$currentUser->id.'/uploads/images/history/';
            	File::makeDirectory($save_path2, $mode = 0755, true, true);
            	Image::make($file)->save($save_path2.$filename2);
            	$x = $currentUser->info()->first();
            	$x->cover_photo = $public_path;
            	$x->save();
	    		Auth::user()->timeline()->create([
	    			'title'		=>	'You updated your cover photo.',
	    			'content'	=>	'<div class="row"><div class="col-6"><img data-src="/images/profile/'.$currentUser->id.'/history/'.$mic.'-cover'.($req->file('cover_photo')->getClientOriginalExtension()).'" class="img-fluid lazy-load"></div></div>',
	    			'type'		=>	'update.cover.photos'
	    		]);
	    		return back()->with('success','Cover Photo has been updated successfully.');
	    	}else{
	    		$mic = md5(microtime());
	    		$file = $req->file('display_photo');
	    		$filename = 'avatar'.$file->getClientOriginalExtension();
	    		$save_path = storage_path().'/users/id/'.$currentUser->id.'/uploads/images/avatar/';
	    		$public_path = '/images/profile/'.$currentUser->id.'/avatar/'.$filename;
            	File::makeDirectory($save_path, $mode = 0755, true, true);
            	Image::make($file)->resize(300, 300)->save($save_path.$filename);
            	//history
	    		$filename2 = $mic.'-avatar'.$file->getClientOriginalExtension();
	    		$save_path2 = storage_path().'/users/id/'.$currentUser->id.'/uploads/images/history/';
            	File::makeDirectory($save_path2, $mode = 0755, true, true);
            	Image::make($file)->resize(300, 300)->save($save_path2.$filename2);
            	$x = $currentUser->info()->first();
            	$x->avatar = $public_path;
            	$x->save();
	    		Auth::user()->timeline()->create([
	    			'title'		=>	'You updated your display photo.',
	    			'content'	=>	'<div class="row"><div class="col-6"><img data-src="/images/profile/'.$currentUser->id.'/history/'.$mic.'-avatar'.($req->file('display_photo')->getClientOriginalExtension()).'" class="img-fluid lazy-load"></div></div>',
	    			'type'		=>	'update.display.photos'
	    		]);
	    		return back()->with('success','Display Photo has been updated successfully.');
	    	}
    	}else{
    		return back()->with('error','Invalid input process. Try Again.');
    	}
    }
    
    public function userProfileAvatar($id, $path , $image)
    {
        return Image::make(storage_path().'/users/id/'.$id.'/uploads/images/'.$path.'/'.$image)->response();
    }

    public function update_informations(Request $req){
        if($this->can('profile-update-info')==false){
            return redirect('home')->with('error','You dont have permission here.');
        }
    	$user = Auth::user();
    	$c = $user->can('update-name');
    	if($c){
    		$valid = Validator::make($req->all(),[
    			'main_name'		=>	'required|string|min:4|max:24|unique:users,name,'.$user->id,
    			'display_name'	=>	'required|string|min:4|max:24|unique:user_infos,display_name,'.$user->info()->first()->id,
    			'bio'	=>	'max:500'
    		]);
    		if($valid->fails()){
    			return redirect('settings#v-pills-info')->withErrors($valid)->withInput();
    		}
    		$user->name = $req->input('main_name');
    		$user->save();
    		$x = $user->info()->first();
    		$x->display_name = $req->input('display_name');
    		$x->location = $req->input('location');
    		$x->skill = $req->input('skills');
    		$x->bio = $req->input('bio');
    		$x->save();
    	}else{
    		$valid = Validator::make($req->all(),[
    			'bio'	=>	'max:500'
    		]);
    		if($valid->fails()){
    			return redirect('settings#v-pills-info')->withErrors($valid)->withInput();
    		}
    		$x = $user->info()->first();
    		$x->location = $req->input('location');
    		$x->skill = $req->input('skills');
    		$x->bio = $req->input('bio');
    		$x->save();
    	}
    	$user->timeline()->create([
    		'title'		=>	'You`ve updated your information.',
    		'content'	=> '',
    		'type'		=>	'update.informations'
    	]);
    	return redirect('settings#v-pills-info')->with('success','Information has been successfully updated.');
    }
}
