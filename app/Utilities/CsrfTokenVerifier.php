<?php

namespace App\Utilities;

use App\Http\Middleware\VerifyCsrfToken as Verifier;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use App\Utilities\Functions\Functions;

class CsrfTokenVerifier extends Verifier
{
    public function __construct()
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
    static protected function makeVerifier()
    {
        $app = app();
        // $key = config('app.key');
        // $cipher = config('app.cipher');
        // $encrypter = new Encrypter($key, $cipher);
        $encrypter = app('encrypter'); // new Encrypter($dec, $cipher);
        $verifier = new Verifier($app, $encrypter);
        return $verifier;
    }

    /**
     * Get the CSRF token from the request. Alternative Method..
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getToken(Request $request)
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        $token = $token ?: $request->header('X-XSRF-TOKEN');

        return $token ?: null;
    }

    // the decrypter BARFS!!
    public function match(Request $request)
    {
        return $this->tokensMatch($request);
    }

    // this WORKS!!
    public function match2(Request $request, string $token)
    {
        $key = $this->getToken($request);
        if (Functions::testVar($key) && Functions::testVar($token)) {
            return hash_equals($key, $token);
        } else {
            return false;
        }
    }

    public function match3(Request $request, string $nut)
    {
        $key = $request->input('nut');
        if (Functions::testVar($key) && Functions::testVar($nut)) {
            return hash_equals($key, $nut);
        } else {
            return false;
        }
    }
}