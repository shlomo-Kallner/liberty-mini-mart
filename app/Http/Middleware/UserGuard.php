<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\UserController,
    App\User;

class UserGuard
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
        if (User::getIsUser()) {
            return $next($request);
        } else {
            $request->session()->reflash();
            
            $request->session()->flash('redirectFullUrl', $request->fullUrl());
            $request->session()->flash('redirectPath', $request->path());
            $request->session()->regenerate();
            
            return redirect('signin/' . UserController::pagePathJoin($request->path()));
        }
    }
}
