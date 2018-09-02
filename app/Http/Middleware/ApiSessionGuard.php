<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Middleware\StartSession,
    Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\User,
    App\Utilities\Functions\Functions,
    App\Utilities\CsrfTokenVerifier,
    App\UserSession;

class ApiSessionGuard extends StartSession
{
    /**
     * Create a new session middleware.
     *
     * @param  \Illuminate\Session\SessionManager  $manager
     * @return void
     */
    public function __construct(SessionManager $manager)
    {
        parent::__construct($manager);
    }

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
}
