<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
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
        if(!Auth::user()){
            return redirect('/')->with(['status' => 'error', 'message' => trans('global.login_user_required')]);
        }

        if(!Auth::user()->can('view_admin_area')){
            return redirect('/')->with(['status' => 'error', 'message' => trans('global.must_be_admin')]);
        }
        
        return $next($request);
    }
}
