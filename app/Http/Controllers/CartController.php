<?php

namespace App\Http\Controllers;

use App\Cart,
    App\User,
    App\Utilities\Functions\Functions,
    App\UserSession;
use Illuminate\Http\Request;
use Darryldecode\Cart as DarrylCart;
use App\Utilities\CsrfTokenVerifier as Verifier;

class CartController extends MainController
{

    public function __construct($name = '', $titleNameSep = '') {
        parent::__construct($name, $titleNameSep);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return parent::getView($request, 'content.cart');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

    public function showDelete(Request $request)
    {
        // display 'ARE YOU SURE' PAGE...
    }

    public function addToCart(Request $request) 
    {
        //dd($request);
        $verifier = new Verifier;
        $userData = User::getUserArray($request);
        $us = UserSession::getFromId($userData);
        $payload = $us->getPayload();
        $nut = Functions::getPropKey(
            Functions::getPropKey($payload, 'site'), 
            'nut'
        );
        $token = Functions::getPropKey($payload, '_token');
        if ($verifier->match2($request, $token) 
            && $verifier->match3($request, $nut)
        ) {
            return self::getRequestData($request);
        }
        return [
            'status' => 'failure',
            'old_nut' => $nut,
            'old_token' => $token,
            '_token' => $request->input('_token'),
            'csrf' => $request->header('X-CSRF-TOKEN'),
            'xsrf' => $request->header('X-XSRF-TOKEN'),
            //'decoded' => decrypt($request->header('X-XSRF-TOKEN')),
            'nut' => $request->input('nut'),
            'session_token' => $request->session()->token()
        ];
    }
}
