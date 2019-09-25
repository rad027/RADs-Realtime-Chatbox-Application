<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\Chats;
use App\Models\Reply;
use App\Models\Hashtag;
use App\Models\Trivia;
use App\User;
use Auth;
use Validator;

class ChatController extends Controller
{
	public function deleteAllChats(){
		Chats::truncate();
		Reply::truncate();
		return "done";
	}

	public function getChats(){
		$msg = array();
		$chat = new Chats();
		if($chat->count() > 0){
			foreach($chat->with('user')->orderBy('id','desc')->take(15)->cursor() as $c){
				if($c->reply()->count()){
					$msg[] = [
						'id'	=> $c->id,
						'created_at'	=>	date('F d, Y h:i a', strtotime($c->created_at)),
						'message'	=> $c->message,
						'type'	=>	$c->type != 'normal' ? $c->type : false,
						'user'	=> [
							'info'	=>	$c->user,
							'role'	=> $c->user->roles[0],
							'extra'	=> $c->user->info()->first()
						],
			    		'replied' => [
							'status'	=>	1 ,
							'to_name'	=> $c->reply()->first()->to_name,
							'from_name'		=>	$c->reply()->first()->from_name,
							'text'		=>	$c->reply()->first()->text
			    		]
					];
				}else{
					$msg[] = [
						'id'	=> $c->id,
						'created_at'	=>	date('F d, Y h:i a', strtotime($c->created_at)),
						'message'	=> $c->message,
						'type'	=>	$c->type != 'normal' ? $c->type : false,
						'user'	=> [
							'info'	=>	$c->user,
							'role'	=> $c->user->roles[0],
							'extra'	=> $c->user->info()->first()
						],
			    		'replied' => [
							'status'	=>	0 ,
							'to_name'		=>	'',
							'from_name'		=>	'',
							'text'		=>	''
			    		]
					];
				}
			}
			return response()->json([
				'status'	=>	1,
				'text'	=> $msg
			]);
		}else{
			return response()->json([
				'status'	=>	0,
				'text'		=>	'No messages yet.'
			]);
		}
	}

	public function getMoreChats(Request $req){
		if($req->has('last_id')){
			$chats = Chats::where('id','<',$req->input('last_id'))->orderBy('id','desc');
			if($chats->count()){
				$msg = [];
				foreach($chats->take(15)->cursor() as $c){
					if($c->reply()->count()){
						$msg[] = [
							'id'	=> $c->id,
							'created_at'	=>	date('F d, Y h:i a', strtotime($c->created_at)),
							'message'	=> $c->message,
							'type'	=>	$c->type != 'normal' ? $c->type : false,
							'user'	=> [
								'info'	=>	$c->user,
								'role'	=> $c->user->roles[0],
								'extra'	=> $c->user->info()->first()
							],
				    		'replied' => [
								'status'	=>	1 ,
								'to_name'	=> $c->reply()->first()->to_name,
								'from_name'		=>	$c->reply()->first()->from_name,
								'text'		=>	$c->reply()->first()->text
				    		]
						];
					}else{
						$msg[] = [
							'id'	=> $c->id,
							'created_at'	=>	date('F d, Y h:i a', strtotime($c->created_at)),
							'message'	=> $c->message,
							'type'	=>	$c->type != 'normal' ? $c->type : false,
							'user'	=> [
								'info'	=>	$c->user,
								'role'	=> $c->user->roles[0],
								'extra'	=> $c->user->info()->first()
							],
				    		'replied' => [
								'status'	=>	0 ,
								'to_name'		=>	'',
								'from_name'		=>	'',
								'text'		=>	''
				    		]
						];
					}
				}
				return response()->json([
					'status'	=> 1,
					'text'		=>	$msg
				]);
			}else{
				return response()->json([
					'status'	=> 0,
					'text'		=>	'No more message available.'
				]);
			}
		}else{
			return response()->json([
				'status'	=> 2,
				'text'		=>	'Invalid Parameter.'
			]);
		}
	}

    public function sendChat(Request $req){
    	$user = Auth::user();
    	if(!$user->can('chat-send')){
    		return response()->json([
    			'status'	=>	0,
    			'text'		=>	'Sorry but you dont have a permission to send a chat yet.'
    		]);
    	}
    	$msg = [
    		'created_at'	=>	date('F d, Y h:i a',time()),
    		'message' => $req->input('message'),
    		'user'	=> [
    			'info' => $user,
    			'role' => $user->roles[0],
    			'extra' => $user->info()->first()
    		],
    		'replied' => [
				'status'	=>	$req->input('replied')['status'] == 1 ? $req->input('replied')['status'] : 0 ,
				'to_name'		=>	$req->input('replied')['status'] == 1 ? $req->input('replied')['to_name'] : '',
				'from_name'		=>	$req->input('replied')['status'] == 1 ? $req->input('replied')['from_name'] : '',
				'text'		=>	$req->input('replied')['status'] == 1 ? $req->input('replied')['text'] : ''
    		]
    	];
    	$user->chats()->create([
    		'message'	=>	$req->input('message'),
    		'type'		=>	'normal'
    	]);
    	if($req->input('replied')['status'] == 1):
	    	$user->chats()->orderBy('chats.id','desc')->first()->reply()->create([
	    		'to_name'	=>	$req->input('replied')['to_name'],
	    		'from_name'	=>	$req->input('replied')['from_name'],
	    		'text'	=>	$req->input('replied')['text']
	    	]);
	    endif;
    	broadcast(new MessageSent($msg))->toOthers();
    	//check trivia answer
    	$t = (object) json_decode(file_get_contents(storage_path().'/app/trivia.json'),true);
    	if(stripos(strtolower($req->input('message')),strtolower($t->answer)) !== false && $t->status==1){
    		$bot_msg = [
    			'created_at'	=>	date('F d, Y h:i a',time()),
    			'message' 		=> 	'<b>@'.$user->info()->first()->display_name.'</b> got the answer for our Trivia and got <b>'.$t->point.' chat points.</b>',
    			'type'			=>	'trivia_answered',
	    		'user'	=> [
	    			'info' => [
	    				'id'	=>	0
	    			]
	    		]
    		];
    		User::find(1)->chats()->create([
	    		'message'	=>	'<b>@'.$user->info()->first()->display_name.'</b> got the answer for our Trivia and got <b>'.$t->point.' chat points.</b>',
	    		'type'		=>	'trivia_answered'
    		]);
    		$user->info()->first()->increment('chat_points',$t->point);
    		$data = [
                'id'    => $t->id,
                'question'  =>  $t->question,
                'answer'  =>  $t->answer,
                'point'  =>  $t->point,
                'status'    => 2,
                'created_date'  =>  date('M d, Y h:i a', strtotime($t->created_date)),
    		];
    		$fp = fopen(storage_path().'/app/trivia.json', 'w');
    		fwrite($fp, json_encode($data,JSON_PRETTY_PRINT));
    		fclose($fp);
    		broadcast(new MessageSent($bot_msg));
    	}
    	//set or announce trivia
    	$this->triviaBot();
    	//fetch hastagged words
    	preg_match_all('/(#\w+)/i', $req->input('message'), $hash);
    	foreach($hash[0] as $h){
    		$c = Hashtag::where(['hash' => $h]);
    		if($c->count() <= 0){
    			Hashtag::create([
    				'hash'	=>	$h
    			]);
    		}
    	}
    	return response()->json([
    		'status' => 1,
    		'text' => 'message sent.'
    	]);
    }

    private function getLastID(){
    	return Chats::orderBy('id','desc')->first()->id;
    }

    private function triviaBot(){
    	if($this->getLastID()%5==0){
    		$t = (object) json_decode(file_get_contents(storage_path().'/app/trivia.json'),true);
    		if($t->status == 1){
    			$data = (object)[
	                'id'    => $t->id,
	                'question'  =>  $t->question,
	                'answer'  =>  $t->answer,
	                'point'  =>  $t->point,
	                'status'    => 2,
	                'created_date'  =>  date('M d, Y h:i a', strtotime($t->created_date)),
    			];
    		}
    		else{
    			$t = Trivia::all()->random(1)->first();
    			$data = (object)[
	                'id'    => $t->id,
	                'question'  =>  $t->question,
	                'answer'  =>  $t->answer,
	                'point'  =>  $t->reward,
	                'status'    => 1,
	                'created_date'  =>  date('M d, Y h:i a', strtotime($t->created_at)),
    			];
	    		$fp = fopen(storage_path().'/app/trivia.json', 'w');
	    		fwrite($fp, json_encode($data,JSON_PRETTY_PRINT));
	    		fclose($fp);
    		}
    		$bot_msg = [
    			'created_at'	=>	date('F d, Y h:i a',time()),
    			'message' 		=> 	'
    									<p class="h4 mb-1 text-muted">Trivia Question!</p>
    									<p class="mb-0 text-muted">
    										'.$data->question.'
    									</p>
    									<p class="mb-0 text-muted"><b>Reward Chat Points : '.$data->point.'</b></p>
    								',
    			'type'			=>	'trivia_question',
	    		'user'	=> [
	    			'info' => [
	    				'id'	=>	0
	    			]
	    		]
    		];
    		User::find(1)->chats()->create([
	    		'message'	=>	'
    								<p class="h4 mb-1 text-muted">Trivia Question!</p>
    								<p class="mb-0 text-muted">
    									'.$data->question.'
    								</p>
    								<p class="mb-0 text-muted"><b>Reward Chat Points : '.$data->point.'</b></p>
    							',
	    		'type'		=>	'trivia_question'
    		]);
    		broadcast(new MessageSent($bot_msg));
    	}
    }
}
