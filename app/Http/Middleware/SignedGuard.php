<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\UserController,
    App\UserSession,
    App\User;

class SignedGuard
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
        if (!User::getIsUser()) {
            return $next($request);
        } else {
            UserSession::updateRegenerate($request);
            return redirect('/');
        }
    }
}
