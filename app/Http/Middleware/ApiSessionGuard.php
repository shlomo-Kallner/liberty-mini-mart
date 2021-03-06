<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Middleware\StartSession,
    Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\SessionManager;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Exceptions\JsonException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Cookie;
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
     * @throws \Illuminate\Session\TokenMismatchException | \App\Exceptions\JsonException
     */
    public function handle($request, Closure $next)
    {
        $us = UserSession::getFromId($request);
        if ($request->hasSession() && ! $request->ajax()) {
            // we came from the call to our parent class
            // on the web routes..
            //$old_session = $request->session()->all();
            if (Functions::testVar($us) && $us->tryLock()
                && CsrfTokenVerifier::match5($request, $us)
            ) {
                // update the regular Laravel session here..
                $request->session()->put($us->getPayload());
                $us->unlock();
                //$new_session = $request->session()->all();
                //dd($old_session, $new_session);
            }
            return $next($request);
        } elseif (Functions::testVar($us)) {
            // We are on the API routes.. 
            // Its time to get into ACTION!
            // dd($us, $request, $us->unlock());
            // throw new JsonException($request, 'before tryLock()', $us, $us->unlock());
            $payload = $us->getPayload(true);
            if (is_string($payload)) {
                $payload = unserialize($payload);
            }
            $nut = Functions::getVar(Functions::getPropKey($payload, '_nut'), '');
            $token = Functions::getVar(Functions::getPropKey($payload, '_token'), '');
            // dd($us, $payload, $nut, $token, $request, $us->unlock(), 'before match()');
            // throw new JsonException($request, 'before match()', $us, $payload, $nut, $token, $us->unlock());
            $match2 = CsrfTokenVerifier::match2($request, $token);
            $match3 = CsrfTokenVerifier::match3($request, $nut);
            $match4 = CsrfTokenVerifier::match4($request, $us);
            if ($match2 && $match3 && $match4) {
                // dd($us, $payload, $nut, $token, $us->unlock(), 'after match()');
                // throw new JsonException($request, 'after match()', $us, $us->getPayload(false), $payload, $this->getSession($request), $nut, $token, $us->unlock());
                $this->sessionHandled = true;
                /* 
                    $this->manager->extend(
                        'App_Utilities_DatabaseSession',
                        function ($app) {
                            $us = UserSession::getFromId(request());
                            return new DatabaseSessionHandler($us);
                        }
                    ); 
                */
                try {
                    $session = $this->getSession($request);
                    // throw new JsonException($request, 'dumping $session 1', $session, $us->unlock());
                    $session->setRequestOnHandler($request);
                    // throw new JsonException($request, 'dumping $session 2', $session, $us->unlock());
                    $session->start();
                    // throw new JsonException($request, 'dumping $session 3', $session, $us->unlock());
                    $request->setLaravelSession($session);
                    // throw new JsonException($request, 'dumping $session 4', $session, $us->unlock());
                }
                catch (\Exception $e) {
                    throw new JsonException($request, 'dumping $session 5', $session, $us->unlock(), $e);
                }
                catch (\Error $e) {
                    throw new JsonException($request, 'dumping $session 5', $session, $us->unlock(), $e);
                }
                // dd($session);
                try {
                    $response = $next($request);
                    /* 
                        $driver = $this->manager
                        ->driver('App_Utilities_DatabaseSession'); 
                    */
                    $session->reflash();
                    $config = $this->manager->getSessionConfig();
                    $response->headers->setCookie(
                        new Cookie(
                            $session->getName(), $session->getId(), 
                            $this->getCookieExpirationDate(),
                            $config['path'], $config['domain'], 
                            $config['secure'] ?? false,
                            $config['http_only'] ?? true, false, 
                            $config['same_site'] ?? null
                        )
                    );
                    $session->put(
                        [
                            '_token' => $token,
                            '_nut' => $nut,
                        ]
                    );                    
                    $session->save();
                }
                catch (\Exception $e) {
                    throw new JsonException($request, 'dumping $session 6a', $session, $response, $us->unlock(), $e);
                }
                catch (\Error $e) {
                    throw new JsonException($request, 'dumping $session 6a', $session, $response, $us->unlock(), $e);
                }
                /* 
                    finally {
                        throw new JsonException($request, 'dumping $session 6b', $session, $response, $us->unlock());
                    } 
                    $us->unlock();
                */
                return $response;
            } 
            // dd($us, $request, $us->unlock(), 'after if statement');
            if (false) {
                return Functions::jsonRetOrDump(
                    $request, __METHOD__, [
                        'us' => $us,
                        //'us_is_unlocked' => $us->unlock(),
                        'us_payload' => $payload,
                        'us_payload:is_string' => is_string($payload),
                        'us_payload:is_object|is_array' => is_object($payload) || is_array($payload),
                        'us_nut' => $nut,
                        'us_token' => $token,
                        'match2' => $match2,
                        'match3' => $match3,
                        'match4' => $match4,
                    ], 'after if statement'
                );
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
            $this->manager->driver('App_Utilities_DatabaseSession'), 
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
