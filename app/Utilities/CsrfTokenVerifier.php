<?php

namespace App\Utilities;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Session\TokenMismatchException;
// use Illuminate\Encryption\Encrypter;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Exceptions\JsonException;
use App\Utilities\Functions\Functions,
    App\User,
    App\UserSession,
    Closure;

class CsrfTokenVerifier extends VerifyCsrfToken
{
    public function __construct(Application $app, Encrypter $encrypter)
    {
        $app = app();
        // $key = config('app.key');
        // $dec = base64_decode($key, true);
        // $cipher = config('app.cipher');
        //dd($key, base64_decode($key), decrypt($dec, false));
        $encrypter = app('encrypter'); // new Encrypter($dec, $cipher);
        parent::__construct($app, $encrypter);
    }

    /**
     * Function makeVerifier() - Was just stashing this method here 
     *                         for now or maybe not (old version 
     *                         created a instance of the parent 
     *                         class). Now this is just an alias 
     *                         of make().
     *
     * @return self
     */
    static protected function makeVerifier()
    {
        return self::make();
    }

    /**
     * Function make() - A Factory method to create a new CsrfTokenVerifier.
     *
     * @param Application|null $app - the Application instance, 
     *                              if null, will use the global 
     *                              function app() to retrieve it.
     * @param Encrypter|null $encrypter - an Encrypter instance, 
     *                                  if null, will use the global 
     *                                  function app() to retrieve it.
     * @return self
     */
    static public function make(
        Application $app = null, Encrypter $encrypter = null
    ) {
        // $apper = $app ?? app();
        // $key = config('app.key');
        // $cipher = config('app.cipher');
        // $encrypter = new Encrypter($key, $cipher);
        // new Encrypter($dec, $cipher);
        // $encrypt = $encrypter ?? app('encrypter'); 
        // $verifier = new VerifyCsrfToken($apper, $encrypt);
        // $verifier = new self($app ?? app(), $encrypter ?? app('encrypter'));
        return new self($app ?? app(), $encrypter ?? app('encrypter'));
    }

    // the decrypter BARFS on no Session!! 
    public function match(Request $request)
    {
        return $request->hasSession() && $this->tokensMatch($request);
    }

    /// from here on, All methods are essentially static except handle()..

    /**
     * Get the CSRF token from the request. Alternative Method..
     *
     * @param \Illuminate\Http\Request $request - a Request instance.
     * @param string                   $default - a default value to 
     *                                          return if there is no 
     *                                          Token on the Request.
     * 
     * @return string 
     */
    static public function getToken(Request $request, string $default = '')
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
        // dd($token, $request, 'input or header(X-CSRF-TOKEN)');
        // throw new JsonException($request, 'input or header(X-CSRF-TOKEN)', $token);
        $token = $token ?: $request->header('X-XSRF-TOKEN');
        // dd($token, $request, '.. or header(X-XSRF-TOKEN)');
        // throw new JsonException($request, '.. or header(X-XSRF-TOKEN)', $token);
        $token = $token ?: $request->cookies->get('XSRF-TOKEN');
        // dd($token, $request, '.. or cookies->get(XSRF-TOKEN)');
        // throw new JsonException($request, '.. or cookies->get(XSRF-TOKEN)', $token);
        return $token ?: $default;
    }

    static public function do_match(string $key, string $token)
    {
        if (Functions::testVar($key) && Functions::testVar($token)) {
            return hash_equals($key, $token);
        } else {
            return false;
        }
    }

    static public function match2(Request $request, string $token)
    {
        $key = self::getToken($request);
        // dd($key, $token);
        $bol = self::do_match($key, $token);
        $tmp = [
            'token' => $token, 
            'key' => $key,
            'bol' => $bol
        ];
        // throw new JsonException($request, 'in match2()', $tmp);
        return $bol;
    }

    static public function match3(Request $request, string $nut)
    {
        $key = Functions::getVar($request->input('nut'), '');
        // dd($key, $nut);
        $bol = self::do_match($key, $nut);
        $tmp = [
            'nut' => $nut, 
            'key' => $key,
            'bol' => $bol
        ];
        // throw new JsonException($request, 'in match3()', $tmp);
        return $bol;
    }

    static public function match4(Request $request, UserSession $us)
    {
        /*
            // $userData = User::getUserArray($request);
            // $us = UserSession::getFromId($request);
            // $payload = $us->getPayload();
            // $nut = Functions::getPropKey($payload, '_nut');
            // $token = Functions::getPropKey($payload, '_token');
        */
        $sn = $request->hasSession() 
            ? $request->session()->getName() 
            : config('session.cookie');
        $csi = Functions::getVar($request->cookies->get($sn), '');
        $usi =  Functions::testVar($us) ? $us->session_id : '';
        // dd($sn, $csi, $usi);
        $bol = self::do_match($usi, $csi);
        $tmp = [
            'usi' => $usi, 
            'csi' => $csi,
            'sn' => $sn,
            'bol' => $bol
        ];
        // throw new JsonException($request, 'in match4()', $tmp);
        return $bol;
    }

    static public function match5(Request $request, UserSession $us)
    {
        if ($request->hasSession()) {
            $usi =  Functions::testVar($us) ? $us->session_id : '';
            $rsi = Functions::getVar($request->session()->getId(), '');
            if ($usi != '' && $rsi != '') {
                return self::do_match($usi, $rsi);
            }
        }
        return false;
    }

    /**
     * Handle an incoming request. No Longer working on this class/method.
     * All further work is now being done in ApiSessionGuard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        if ($request->hasSession() && ! $request->ajax()) {
            return parent::handle($request, $next);
        } elseif (false) {
            if (Functions::testVar($us = UserSession::getFromId($request))) {
                $payload = $us->getPayload();
                $nut = Functions::getVar(Functions::getPropKey($payload, '_nut'), '');
                $token = Functions::getVar(Functions::getPropKey($payload, '_token'), '');
                if (self::match2($request, $token) 
                    && self::match3($request, $nut)
                    && self::match4($request, $us)
                ) {
                    $response = $next($request);
                    return $response;
                } 
            }
        }
        throw new TokenMismatchException;
    }
}