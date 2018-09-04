<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Middleware\StartSession,
    Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
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
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        $us = UserSession::getFromId($request);
        if ($request->hasSession() && ! $request->ajax()) {
            // we came from the call to our parent class
            // on the web routes..
            if (CsrfTokenVerifier::match5($request, $us)) {
                // update the regular Laravel session here..
                $request->session()->put($us->getPayload());
            }
            return $next($request);
        } else {
            // We are on the API routes.. 
            // Its time to get into ACTION!
            if (Functions::testVar($us)) {
                $payload = $us->getPayload();
                $nut = Functions::getVar(Functions::getPropKey($payload, '_nut'), '');
                $token = Functions::getVar(Functions::getPropKey($payload, '_token'), '');
                if (CsrfTokenVerifier::match2($request, $token) 
                    && CsrfTokenVerifier::match3($request, $nut)
                    && CsrfTokenVerifier::match4($request, $us)
                ) {
                    $this->sessionHandled = true;
                    $this->manager->extend(
                        'App\Utilities\DatabaseSession',
                        function ($app) use ($us) {
                            return new DatabaseSessionHandler($us);
                        }
                    );
                    $request->setLaravelSession(
                        $session = $this->startSession($request)
                    );
                    $response = $next($request);
                    // 
                    $driver = $this->manager
                        ->driver('App\Utilities\DatabaseSession');
                    $driver->reflash();
                    $driver->save();
                    return $response;
                } 
            }
        }
        throw new TokenMismatchException;
    }

    /**
     * Get the session implementation from the manager.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Session\Session
     */
    public function getSession(Request $request)
    {
        return tap(
            $this->manager->driver('App\Utilities\DatabaseSession'), 
            function ($session) use ($request) {
                $session->setId($request->cookies->get($session->getName()));
            }
        );
    }

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    public function terminate($request, $response)
    {
        // NO - OP!!
    }
    
    /// A Whole bunch of Overides for Overiding's sake...

    /**
     * Determine if the configured session driver is persistent.
     *
     * @param  array|null  $config
     * @return bool
     */
    protected function sessionIsPersistent(array $config = null)
    {
        // $config = $config ?: $this->manager->getSessionConfig();

        // return ! in_array($config['driver'], [null, 'array']);
        return true;
    }

    /**
     * Determine if a session driver has been configured.
     *
     * @return bool
     */
    protected function sessionConfigured()
    {
        // return ! is_null($this->manager->getSessionConfig()['driver'] ?? null);
        return true;
    }

}
