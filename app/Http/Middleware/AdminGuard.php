<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\UserController,
    App\User;


class AdminGuard
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
        //dd($request->session());
        if (User::getIsAdmin()) {
            return $next($request);
        } else {
            //dd($request->session()->all(), $request->reTok);
            $request->session()->reflash();
            
            //$request->session()->flash('redirectFullUrl', $request->fullUrl());
            $request->session()->flash('redirectPath', $request->path());
            $request->session()->regenerate();
            //dd($request->session()->all(), $request->reTok);
            $ver = UserController::getRedVer();
            if ($ver === 1) {
                // the old version .. (reveals contained request path..)
                $new_path = UserController::pagePathJoin($request->path());
                return redirect('signin/' . $new_path);
            } else {
                // the new version -> depends on us having flashed to the session
                //  the redirect route in the first place... 
                //  so why show the user the path?
                return redirect('signin');
            }
        }
    }
}
