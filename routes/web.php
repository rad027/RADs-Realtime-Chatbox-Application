<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* Public Routes */
Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

//Social Routes
Route::get('/social/{provider}/redirect/', 'SocialController@redirect');
Route::get('/social/{provider}/callback/', 'SocialController@callback');

//For Image View Route
Route::get('{path}/{name}/image/view','\App\RADs@view_image');

//Fetch Message (logged or not)
Route::get('chat/messages','ChatController@getChats');

//Hashtag Update
Route::post('hashtag/fetch','HashtagController@checkNewHash');
//Online Update
Route::post('online/users','UserController@getOnline');

//View User Images
Route::get('images/profile/{id}/{path}/{image}','ProfileController@userProfileAvatar');

//View Fansign Images
Route::get('images/{name}/fansigns', 'SiteToolsController@fansigns_viewer');

//Fansign listing
Route::post('site/images/fansigns/get', 'SiteToolsController@fansigns_fetch');

//Get DJ on board
Route::post('/onboard/current', 'DjController@onboard_now');
/* Public Routes End */

/* Auth Routes */
Route::group(['middleware' => ['auth']], function() {
    Route::resource('tools/roles','RoleController');
    Route::resource('tools/users','UserController');
    Route::resource('tools/permissions','PermissionController');

    Route::post('chat/send','ChatController@sendChat');
    Route::post('chat/messages/more','ChatController@getMoreChats');

    //Request song
    Route::post('request/song','ToolController@requestSong');
    Route::post('request/song/list','ToolController@requestSongList');
    Route::post('request/song/remove','ToolController@removeRequest');
    Route::post('request/song/clear','ToolController@removeAllRequest');
    Route::post('request/song/play','ToolController@playRequest');
    Route::post('request/song/history','ToolController@historyRequest');

    //Profile
    Route::get('profile',function(){
    	return redirect()->route('profile',strtolower(str_replace(' ', '.', \Auth::user()->name)));
    });
    Route::get('profile/{username}','ProfileController@view_profile')->name('profile')->middleware('permission:profile-view');

    //Settings
    Route::get('settings','ProfileController@settings_view')->middleware('permission:setting-view');
    	//Update Pictures
    	Route::post('settings/update_pictures','ProfileController@update_pictures')->name('update.pictures');
    	//Update Informatin
    	Route::post('settings/update_informations','ProfileController@update_informations')->name('update.informations');

    //Onboard and DJ stuffs
    Route::get('dj/onboard','DjController@onboard_view')->middleware('permission:onboard-view');
        //DJ List
        Route::get('dj/list','DjController@dj_list')->middleware('permission:dj-list');
            //fetch dj
            Route::post('dj/list', 'DjController@djList');
            //Dj Delte
            Route::post('dj/delete','DjController@djDelete');
            //Dj fetch user
            Route::post('dj/fetch/user','DjController@fetch_user');
            //DJ Add
            Route::post('dj/list/add', 'DjController@add_dj');
            //DJ Edit info
            Route::post('dj/info', 'DjController@djInfo');
            //DJ Update
            Route::post('dj/update', 'DjController@djUpdate');
        //Dj Onboard
        Route::post('onboard/update','DjController@OnBoardUpdate');


    Route::get('chat/all/delete','ChatController@deleteAllChats');

    //Get User Info
    Route::post('user/info','UserController@user_info');
    /* Create Developer Account here */
	Route::get('/create/admin',function(){
		$user = \App\User::find(1);
		$role = \Spatie\Permission\Models\Role::find(1);
		$role->syncPermissions(\Spatie\Permission\Models\Permission::pluck('id','id')->all());
		$user->assignRole(1);
		return $user;
	});

    //Trivia
        //Bot Trivia Check Default
        Route::post('cbox/trivia/default/check', 'CboxToolsController@trivia_check');

    Route::get('check_me',function(){
        return \App\Models\Trivia::all()->random(1)->first();
    });
});
Route::group(['middleware' => ['auth','role:Developer']], function() { 
    //Site Tools
        //Site Meta View
        Route::get('site/meta', 'SiteToolsController@meta_view');
        //Site Meta Process
        Route::post('site/meta', 'SiteToolsController@meta_process');
        //Site Information View
        Route::get('site/info', 'SiteToolsController@info_view');
        //Site Information Process
        Route::post('site/info', 'SiteToolsController@info_process');
});
Route::group(['middleware' => ['auth','role:Developer|Owner|Co-Owner']], function() { 
    //Chatox tool
        //Messages search
        Route::post('cbox/messages', 'CboxToolsController@viewit');
        //Messages view
        Route::get('cbox/messages', 'CboxToolsController@viewit');
    //Chatbox Delete All Messages
    Route::post('cbox/delete/all/messages', 'CboxToolsController@cbox_delete_all_message');
});
Route::group(['middleware' => ['auth','role:Developer|Owner|Co-Owner|Administrator']], function() { 
    //Video promotion edit view
    Route::get('site/vpromotion', 'SiteToolsController@vpromo_view');
    //Video promotion edit promotion
    Route::post('site/vpromotion', 'SiteToolsController@vpromo_process');
    //Images Tools
        //Fansigns view
        Route::get('site/images/fansigns', 'SiteToolsController@fansigns_view');
        //Fansigns view
        Route::post('site/images/fansigns', 'SiteToolsController@fansigns_process');
        //Fansign Delete
        Route::post('site/images/fansigns/delete', 'SiteToolsController@fansigns_delete');

        //Sponsor View
        Route::get('site/images/sponsor', 'SiteToolsController@sponsor_view');
        //Sponsor Upload Process
        Route::post('site/images/sponsor', 'SiteToolsController@sponsor_upload_process');

    //Bot Trivia view
    Route::get('cbox/trivia', 'CboxToolsController@trivia_view');
        //Bot Trivia add process
        Route::post('cbox/trivia', 'CboxToolsController@trivia_add_process');
        //Bot Trivia list
        Route::post('cbox/trivia/list', 'CboxToolsController@trivia_list');
        //Bot Trivia Edit
        Route::post('cbox/trivia/edit', 'CboxToolsController@trivia_edit');
        //Bot Trivia Delete
        Route::post('cbox/trivia/delete', 'CboxToolsController@trivia_delete');
        //Bot Trivia Set Default
        Route::post('cbox/tivia/default', 'CboxToolsController@trivia_set');
});    
/* Auth Routes End */

/* Test Routes*/
Route::get('/replied',function(){
	return \App\User::find(1)->assignRole(1);
});
Route::get('/info','UserController@testF');
/* Test Routes End */