<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Fansign;
use Validator;
use Artisan;
use Image;
use Auth;
use File;

class SiteToolsController extends Controller
{
    public function meta_view(){
    	return view('ranks.developer.tools.site.meta');
    }

    public function meta_process(Request $req){
    	$valid = Validator::make($req->all(),[
    		'meta_url'		=>	'required|min:7|url',
    		'meta_title'	=>	'required|min:2',
    		'meta_img'		=>	'required|min:7|url',
    		'meta_desc'		=>	'required|min:10|max:350'
    	]);
    	if($valid->fails()){
    		return back()->withErrors($valid)->withInput();
    	}
    	$data = explode("\n", file_get_contents(base_path('.env')));
    	$data[52] = 'META_URL="'.$req->input('meta_url').'"';
    	$data[53] = 'META_TITLE="'.$req->input('meta_title').'"';
    	$data[54] = 'META_IMG="'.$req->input('meta_img').'"';
    	$data[55] = 'META_DESC="'.$req->input('meta_desc').'"';
    	file_put_contents(base_path('.env'),implode("\n",$data));
    	Artisan::call('config:clear');
    	return back()->with('success','You have successfully updated the Site Meta.');
    }

    public function info_view(){
    	return view('ranks.developer.tools.site.info');
    }

    public function info_process(Request $req){
    	$valid = Validator::make($req->all(),[
    		'site_name'			=>	'required|string|min:4',
    		'site_icon'			=>	'required|string|min:4',
    		'site_logo'			=>	'required|min:7|url',
    		'site_mini_logo'	=>	'required|min:7|url'
    	]);
    	if($valid->fails()){
    		return back()->withErrors($valid)->withInput();
    	}
    	$data = explode("\n", file_get_contents(base_path('.env')));
    	$data[0]  = 'APP_NAME="'.$req->input('site_name').'"';
    	$data[58] = 'APP_ICON="'.$req->input('site_icon').'"';
    	$data[59] = 'APP_LOGO="'.$req->input('site_logo').'"';
    	$data[60] = 'APP_MINI_LOGO="'.$req->input('site_mini_logo').'"';
    	file_put_contents(base_path('.env'),implode("\n",$data));
    	Artisan::call('config:clear');
    	return back()->with('success','You have successfully updated the Site Informations.');
    }

    public function vpromo_view(){
    	return view('ranks.vpromo');
    }

    public function vpromo_process(Request $req){
    	$valid = Validator::make($req->all(),[
    		'embed_link'			=>	'required|min:4|url'
    	]);
    	if($valid->fails()){
    		return back()->withErrors($valid)->withInput();
    	}
    	$data = explode("\n", file_get_contents(base_path('.env')));
    	$data[63]  = 'VIDEO_EMBED_LINK="'.$req->input('embed_link').'"';
    	file_put_contents(base_path('.env'),implode("\n",$data));
    	Artisan::call('config:clear');
    	return back()->with('success','You have successfully updated the Video Promotion link.');
    }

    public function fansigns_view(){
    	return view('ranks.tools.images.fansigns');
    }

    public function fansigns_process(Request $req){
    	$valid = Validator::make($req->all(),[
    		'images'	=>	'required',
    		'images.*'	=>	'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    	]);
    	if($valid->fails()){
    		return response()->json([
    			'status'	=>	0,
    			'text'		=>	'Something is wrong with your input'
    		]);
    	}
    	if($req->hasFile('images') && count($req->File('images')) > 0){
            $images = [];
    		foreach($req->file('images') as $f){
                $name = md5(microtime()).'.'.$f->getClientOriginalExtension();
                $save_path = storage_path().'/fansigns/';
                Image::make($f)->resize(350, 500)->save($save_path.$name);
                $ds = Auth::user()->fs()->create([
                    'image'    =>  $name
                ]);
                $images[] = [
                    'id'    => $ds->id,
                    'image' => $name
                ];
            }
            return response()->json([
                'status'    =>  1,
                'text'      =>  'Images has been successfully uploaded.',
                'images'    => $images
            ]);
    	}else{
            return response()->json([
                'status'    =>  0,
                'text'      =>  'Image file must be in array or has 1 or more counts.'
            ]);
        }
    }

    public function fansigns_fetch(){
        $fs = new Fansign();
        if($fs->count() > 0){
            $images = [];
            foreach($fs->orderBy('id','desc')->cursor() as $f){
                $images[] = [
                    'id'    =>  $f->id,
                    'image' =>  $f->image
                ];
            }
            return response()->json([
                'status'    =>  1,
                'text'      =>  'Images fetched successfully',
                'images'    =>  $images
            ]);
        }else{
            return response()->json([
                'status'    =>  0,
                'text'      =>  'No images uploaded yet.'
            ]);
        }
    }

    public function fansigns_viewer($name){
        return Image::make(storage_path().'/fansigns/'.$name)->response();
    }

    public function fansigns_delete(Request $req){
        if($req->ajax()){
            try{
                $fs = Fansign::findOrFail($req->input('id'));
                $name = $fs->image;
                $fs->delete();
                unlink(storage_path().'/fansigns/'.$name);
                return response()->json([
                    'status'    =>  1,
                    'text'      =>  'Fansign ID '.$req->input('id').' has been deleted.'
                ]);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'status'    =>  0,
                    'text'      =>  'Fansign ID Doesnt exists.'
                ]);
            }
        }
    }

    public function sponsor_view(){
        $user = Auth::user();
        if(!$user->can('disabled-feature')){
            return redirect()->route('home')->with('error','This feature is currently unavailable.');
        }
        return view('ranks.tools.images.sponsor');
    }

    public function sponsor_upload_process(Request $req){
        $user = Auth::user();
        if(!$user->can('disabled-feature')){
            return back()->with('error','This feature is currently unavailable.');
        }
        if($req->ajax()){
            if($req->hasFile('image')){

            }else{
                return response()->json([
                    'status'    =>  0,
                    'text'      =>  'Image is a required field.'
                ]);
            }
        }
    }
}
