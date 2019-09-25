<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\Auth::check()){
            $user = Auth::user();
            $u = $user->info()->first();
            $u->last_activity = date('Y-m-d H:i:s');
            $u->save();
        }
        return $next($request);
    }
}
