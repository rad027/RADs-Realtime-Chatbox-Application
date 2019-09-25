<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Chats;
use App\Models\Reply;
use App\Models\Trivia;
use Validator;
use Auth;

class CboxToolsController extends Controller
{
    public function viewit(Request $req){
    	if($req->input('search')){
    		$s = $req->input('search');
    		$chats = Chats::with('user','reply')->where( function($q) use ($s){
    			$q->where('chats.message', 'LIKE', '%'.$s.'%')->orWhereHas('user', function($q) use ($s){
    				$q->where('users.name', 'LIKE', '%'.$s.'%');
    			});
    		})->orderBy('id','desc')->paginate(25);
    	}else{
    		$chats = Chats::with('user','reply')->orderBy('id','desc')->paginate(25);
    	}
    	return view('ranks.developer.tools.cbox.messages', compact('chats'));
    }

    public function cbox_delete_all_message(Request $req){
        $user = Auth::user();
        if($user->hasRole(['Developer', 'Owner', 'Co-Owner'])){
            if($req->ajax()){
                Chats::truncate();
                Reply::truncate();
                return response()->json([
                    'status'    =>  1,
                    'text'      =>  'Chatbox messages has been deleted.'
                ]);
            }
        }else{
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission to proceed with this.'
            ]);
        }
    }

    public function trivia_view(){
        $user = Auth::user();
        if($user->can(['trivia-view'])){
            return view('ranks.tools.trivia.index');
        }else{
            return redirect()->route('home')->with('error','You dont have permission here.');
        }
    }

    public function trivia_add_process(Request $req){
        $user = Auth::user();
        if($user->can(['trivia-add'])){
            $valid = Validator::make($req->all(),[
                'question'  =>  'required|min:5|unique:trivias,question,'.$req->input('question'),
                'answer'    =>  'required|min:1',
                'point'     =>  'required|numeric|min:1'
            ]);
            if($valid->fails()){
                return response()->json([
                    'status'    =>  0,
                    'text'      =>  $valid->errors()
                ]);
            }
            $g = $user->trivia()->create([
                'question'  =>  $req->input('question'),
                'answer'  =>  $req->input('answer'),
                'reward'  =>  $req->input('point'),
            ]);
            $return = [
                'id'    => $g->id,
                'question'  =>  $req->input('question'),
                'answer'  =>  $req->input('answer'),
                'point'  =>  $req->input('point'),
                'created_date'  =>  date('M d, Y h:i a', strtotime($g->created_at)),
            ];
            return response()->json([
                'status'    =>  1,
                'text'      =>  'New trivia has been successfully added.',
                'data'      =>  $return
            ]);
        }else{
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission to proceed with this.'
            ]);
        }
    }

    public function trivia_list(){
        $user = Auth::user();
        if($user->can(['trivia-view'])){
            $c = new Trivia();
            if($c->count() > 0){
                $data = [];
                foreach($c->orderBy('id','desc')->get() as $g){
                    $data[] = [
                        'id'    => $g->id,
                        'question'  =>  $g->question,
                        'answer'  =>  $g->answer,
                        'point'  =>  $g->reward,
                        'created_date'  =>  date('M d, Y h:i a', strtotime($g->created_at)),
                    ];
                }
                return response()->json([
                    'status'    =>  1,
                    'text'      =>  'List fetched successfully.',
                    'data'      =>  $data
                ]);
            }else{
                return response()->json([
                    'status'    =>  0,
                    'text'      =>  'No trivia question has been added yet.'
                ]);
            }
        }else{
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission to proceed with this.'
            ]);
        }
    }

    public function trivia_edit(Request $req){
        $user = Auth::user();
        if($user->can(['trivia-edit'])){
            try{
                $t = Trivia::findOrFail($req->input('id'));
                $valid = Validator::make($req->all(),[
                    'question'  =>  'required|min:5|unique:trivias,question,'.$req->input('id'),
                    'answer'    =>  'required|min:1',
                    'point'     =>  'required|numeric|min:1'
                ]);
                if($valid->fails()){
                    return response()->json([
                        'status'    =>  0,
                        'text'      =>  $valid->errors()
                    ]);
                }
                $t->question = $req->input('question');
                $t->answer = $req->input('answer');
                $t->reward = $req->input('point');
                $t->save();
                return response()->json([
                    'status'    =>  1,
                    'text'      => 'Trivia has been successfully edited.'
                ]);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'status'    =>  0,
                    'text'      =>  'Trivia doesnt exists.'
                ]);
            }
        }else{
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission to proceed with this.'
            ]);
        }
    }

    public function trivia_delete(Request $req){
        $user = Auth::user();
        if($user->can(['trivia-delete'])){
            try{
                $t = Trivia::findOrFail($req->input('id'));
                $t->delete();
                return response()->json([
                    'status'    =>  1,
                    'text'      =>  'Trivia has been deleted.'
                ]);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'status'    =>  0,
                    'text'      =>  'Trivia doesnt exists.'
                ]);
            }
        }else{
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission to proceed with this.'
            ]);
        }
    }

    public function trivia_set(Request $req){
        $user = Auth::user();
        if($user->can(['trivia-edit'])){
            try{
                $t = Trivia::findOrFail($req->input('id'));
                $fp = fopen(storage_path().'/app/trivia.json', 'w');
                $data = [
                    'id'    => $t->id,
                    'question'  =>  $t->question,
                    'answer'  =>  $t->answer,
                    'point'  =>  $t->reward,
                    'status'    => 1,
                    'created_date'  =>  date('M d, Y h:i a', strtotime($t->created_at)),
                ];
                fwrite($fp, json_encode($data,JSON_PRETTY_PRINT));
                fclose($fp);
                return response()->json([
                    'status'    =>  1,
                    'text'      =>  'Trivia has been set to default.'
                ]);
            }catch(ModelNotFoundException $e){
                return response()->json([
                    'status'    =>  0,
                    'text'      =>  'Trivia doesnt exists.'
                ]);
            }
        }else{
            return response()->json([
                'status'    =>  0,
                'text'      =>  'You dont have permission to proceed with this.'
            ]);
        }
    }
    public function trivia_check(){
        $t = (object) json_decode(file_get_contents(storage_path().'/app/trivia.json'),true);
        return response()->json([
            'id'        =>  $t->id,
            'answer'    =>  $this->CryptoJSAesEncrypt('iNewWorks_radpogi',$t->answer)
        ]);
    }

    public function CryptoJSAesEncrypt($passphrase, $plain_text){

        $salt = openssl_random_pseudo_bytes(256);
        $iv = openssl_random_pseudo_bytes(16);

        $iterations = 999;  
        $key = hash_pbkdf2("sha512", $passphrase, $salt, $iterations, 64);

        $encrypted_data = openssl_encrypt($plain_text, 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, $iv);

        $data = array("ciphertext" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "salt" => bin2hex($salt));
        return json_encode($data);
    }
}
