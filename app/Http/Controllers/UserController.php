<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use App\Models\UserInfo as UI;
use DB;
use Hash;
use SimpleXMLElement;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(10);
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return back();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();


        return view('users.edit',compact('user','roles','userRole'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return $request->input();
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password'
        ]);


        $input = $request->all();


        $user = User::find($id);
        $user->update($input);
        $x = $user->info()->first();
        $x->location = $request->input('location');
        $x->display_name = $request->input('display_name');
        $x->skill = $request->input('skill');
        $x->neon_color = $request->input('neon_color');
        $x->save();

        if($request->has('roles')){
            $roles = $request->input('roles');
        }else{
            $roles = $user->roles->pluck('name','name')->all();
        }
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($roles);
        Auth::user()->timeline()->create([
            'title' =>  'You`ve successfully updated user <span class="text-link">'.$user->name.'</span>',
            'content'   =>  '',
            'type'  =>  'update.user'
        ]);

        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

    public function getOnline(){
        $file = json_decode(file_get_contents(storage_path().'/app/online.json'),true);
        if(count($file)){
            return response()->json([
                'status'    =>  1,
                'onlines'   =>  $file
            ]);
        }else{
            return response()->json([
                'status'    =>  0,
                'onlines'   => []
            ]);
        }
    }

    public function testF(){
        $search_phrase = 'terror twilight';
        $xml_request_url = 'http://www.freecovers.net/api/search/'.urlencode($search_phrase);
        $xml = new SimpleXMLElement($xml_request_url, null, true);
        foreach ($xml as $title) {
          echo 'Title: '.$title->name;
          echo 'Category: '.$title->category;
          echo 'Upload date: '.$title->added;
          echo 'Image: '.$title->image;
          foreach ($title->covers->cover as $cover) {
            echo 'Cover type:'.$cover->type;
            echo 'Resolution:'.$cover->width.'x'.$cover->height;
            echo 'Filesize:'.$cover->filesize;
            echo 'Download page:'.$cover->url;
            echo 'Thumbnail:'.$cover->thumbnail;
          }
        }        
    }

    public function user_info(Request $req){
        if($req->ajax()){
            try{
                $u = UI::where(['display_name' => $req->input('username')])->firstOrFail();
                $data = [
                    'info'      =>  $u->user()->first(),
                    'extra'     =>  $u,
                    'roles'     =>  $u->user()->first()->roles()->first(),
                    'c_count'   =>  $u->user()->first()->chats()->count(),
                    'c_post'    => 0
                ];
                return response()->json([
                    'status'    =>  1,
                    'text'      =>  $data
                ]);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'status'    =>  0,
                    'text'      =>  'User doesnt exists.'
                ]);
            }
        }else{
            return back()->with(['error','Invalid Request']);
        }
    }
}
