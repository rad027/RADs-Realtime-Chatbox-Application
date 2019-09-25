<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'provider', 'provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function chats(){
      return $this->hasMany('App\Models\Chats');
    }

    public function info(){
      return $this->hasMany('App\Models\UserInfo');
    }

    public function rs(){
      return $this->hasMany('App\Models\RequestSong');
    }

    public function timeline(){
      return $this->hasMany('App\Models\Timeline');
    }

    public function dj(){
      return $this->hasMany('App\Models\Dj');
    }

    public function fs(){
      return $this->hasMany('App\Models\Fansign');
    }

    public function trivia(){
      return $this->hasMany('App\Models\Trivia');
    }

    public function getAllPermissionsAttribute() {
      $permissions = [];
        foreach (Permission::all() as $permission) {
          if (Auth::user()->can($permission->name)) {
            $permissions[] = $permission->name;
          }
        }
        return $permissions;
    }    

}
