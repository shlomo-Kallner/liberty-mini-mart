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
        if (User::getIsAdmin()) {
            return $next($request);
        } else {
            //dd($request->session()->all(), $request->reTok);
            $request->session()->reflash();
            
            //$request->session()->flash('redirectFullUrl', $request->fullUrl());
            $request->session()->flash('redirectPath', $request->path());
            $request->session()->regenerate();
            //dd($request->session()->all(), $request->reTok);
            return redirect('signin/' . UserController::pagePathJoin($request->path()));
        }
    }
}
