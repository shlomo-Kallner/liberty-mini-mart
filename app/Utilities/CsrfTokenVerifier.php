<?php

namespace App\Utilities;

use App\Http\Middleware\VerifyCsrfToken as Verifier;
use Illuminate\Contracts\Encryption\Encrypter;
// use Illuminate\Encryption\Encrypter;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Utilities\Functions\Functions,
    App\User,
    App\UserSession;

class CsrfTokenVerifier extends Verifier
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

    /// Just stashing this method here for now..
    /// maybe not.. see make()..
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
     * @return void
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
        // $verifier = new Verifier($apper, $encrypt);
        // $verifier = new self($app ?? app(), $encrypter ?? app('encrypter'));
        return new self($app ?? app(), $encrypter ?? app('encrypter'));
    }

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

        $token = $token ?: $request->header('X-XSRF-TOKEN');

        return $token ?: $default;
    }

    // the decrypter BARFS!!
    public function match(Request $request)
    {
        return $this->tokensMatch($request);
    }

    static public function do_match(string $key, string $token)
    {
        if (Functions::testVar($key) && Functions::testVar($token)) {
            return hash_equals($key, $token);
        } else {
            return false;
        }
    }

    // this WORKS!!
    static public function match2(Request $request, string $token)
    {
        $key = self::getToken($request);
        return self::do_match($key, $token);
    }

    static public function match3(Request $request, string $nut)
    {
        $key = Functions::getVar($request->input('nut'), '');
        return self::do_match($key, $nut);
    }

    static public function match4(Request $request)
    {
        $userData = User::getUserArray($request);
        $us = UserSession::getFromId($userData);
        //$payload = $us->getPayload();
        //$nut = Functions::getPropKey($payload, '_nut');
        //$token = Functions::getPropKey($payload, '_token');
        $sn = $request->hasSession() 
            ? $request->session()->getName() 
            : config('session.cookie');
        $csi = Functions::getVar($request->cookies->get($sn), '');
        $usi =  Functions::testVar($us) ? $us->session_id : '';

        return self::do_match($usi, $csi);
    }
}