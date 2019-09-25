<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator,Redirect,Response,File;
use Socialite;
use App\User;
use App\Events\UpdateMembers as UM;

class SocialController extends Controller
{
    public function redirect($provider){
    	return Socialite::driver($provider)->redirect();
    }

    public function callback($provider){
		$getInfo = Socialite::driver($provider)->user(); 
		$user = $this->createUser($getInfo,$provider); 
		auth()->login($user); 
		return redirect()->to('/');
    }

    public function createUser($getInfo,$provider){
		$user = User::where('provider_id', $getInfo->id)->first();
		if (!$user) {
		    $user = User::create([
		        'name'     => $getInfo->name,
		        'email'    => $getInfo->email,
		        'provider' => $provider,
		        'provider_id' => $getInfo->id,
		        'password'	=> bcrypt('Poging_Web_Developer_From_Philippines')
		    ]);
		    $user->assignRole(12);
		    $user->info()->create([
		    	'display_name'		=>	strtolower(str_replace(' ', '.', $getInfo->name)),
		    	'avatar'			=>	'/images/icon-user.png',
		    	'neon_color'		=>	0,
		    	'tuitored'			=>	0,
		    	'rewards'			=>	'',
		    	'status'			=>	1,
		    	'last_activity'		=>	date('Y-m-d H:i:s'),
		        'location'			=>	'',
		        'skill'				=>	'',
		        'bio'				=>	'',
		        'chat_points'		=>	0,
		        'special_points'	=>	0
		    ]);
		    broadcast(new UM(strtolower(str_replace(' ', '.', $getInfo->name))));
		}
		return $user;
    }
}
