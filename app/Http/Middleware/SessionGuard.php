<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Http\Request;

use App\Utilities\CsrfTokenVerifier,
    App\User,
    App\Utilities\Functions\Functions,
    App\UserSession;

class SessionGuard extends StartSession
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
        $response = $next($request);
        return $response;
    }

    public function getData(Request $request)
    {
        $userData = User::getUserArray($request);
        $us = UserSession::getFromId($userData);
        $payload = $us->getPayload();
        $nut = Functions::getPropKey($payload, '_nut');
        $token = Functions::getPropKey($payload, '_token');
    }
}
