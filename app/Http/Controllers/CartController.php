<?php

namespace App\Http\Controllers;

use App\Cart,
    App\User,
    App\Section,
    App\Categorie,
    App\Product,
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

    public function doCartAction(Request $request, string $action = '')
    {
        $section = Section::getSection($request->section, false);
        $category = $section->getCategory($request->category);
        $product = $category->getProduct($request->product);
        $productSB = $product->toSidebar('store');
        $cart = Cart::getSessionCart();
        $numProducts = $request->input('info.numProducts') ?? 1;
        if ($action == 'addToCart') {
            if ($cart->has($product->id)) {
                $cart->update($product->id, ['quantity' => $numProducts]);
            } else {
                $cart->add(
                    $product->id, $product->name, $productSB['price'],
                    $numProducts,
                    [
                        'url' => url($productSB['url']),
                        'img' => asset($productSB['img']),
                        'description' => $product->description,
                        'api' => url($product->getFullUrl('api/store')),
                    ]
                );
            }
        } elseif ($action == 'delFromCart') {
            if ($cart->has($product->id)) {
                $cart->remove($product->id);
            }
        } elseif ($acton == 'remFromCart') {
            if ($cart->has($product->id)) {
                $cart->update(
                    $product->id, 
                    ['quantity' => $numProducts > 0 ? -1 * $numProducts : $numProducts ]
                );
            }
        }
        $user = $request->session()->has('user') 
            ? $request->session()->get('user.id')
            : null;
        //dd($request, $user, $cart);
        /* 
            $cart1 = Cart::storeOrCreateCurrentCart(
                $request, $user, $cart
            ); 
        */
        if (!true) {
            return Functions::genDumpResponse($request, $user, $cart);
        } elseif (true) {
            return Cart::getCurrentCart(
                $request, true, 'fa-usd', $cart
            );
        } else {
            return Functions::genDumpResponse(
                $request, Cart::getCurrentCart($request), 
                $cart
            );
        }
    }

    public function addToCart(Request $request) 
    {
        if (false) {
            return $this->dataTester($request);
        } else {
            return $this->doCartAction($request, 'addToCart');
        }
    }

    public function delFromCart(Request $request)
    {
        if (false) {
            return $this->dataTester($request);
        } else {
            return $this->doCartAction($request, 'delFromCart');
        }
    }

    public function remFromCart(Request $request)
    {
        if (false) {
            return $this->dataTester($request);
        } else {
            return $this->doCartAction($request, 'remFromCart');
        }
    }

    public function dataTester(Request $request)
    {
        //dd($request);
        $verifier = Verifier::make();
        $userData = User::getUserArray($request);
        $us = UserSession::getFromId($userData);
        if ($request->hasSession()) {
            
            $sn = $request->session()->getName();    
            $rsi = $request->session()->getId();
            $sData = $request->session();

        } else {
            // if ($request->ajax()) 
            $sn = config('session.cookie');
            $rsi = '';
            $sData = session();
        }
        $payload = Functions::testVar($us) ? $us->getPayload() : null;
        $nut = Functions::getVar(Functions::getPropKey($payload, '_nut'), '');
        $token = Functions::getVar(Functions::getPropKey($payload, '_token'), '');
        
        $rd = self::getRequestData($request);
        $csi = $request->cookies->get($sn);
        $usi = $us->session_id;
        
        $m1 = $request->hasSession() ? $verifier->match($request) : null;
        $m2 = $verifier->match2($request, $token);
        $m3 = $verifier->match3($request, $nut);
        $m4 = $verifier->match4($request, $us);

        /* $sd = $sData->driver();
        $sd->setId($m4 ? $csi : $usi);
        $sd->start(); */

        // dd($m4 ? $csi : $usi, $sd, $sData, $token, $nut);
        

        $tmp = [
            'status' => $m2 && $m3 ? 'success' : 'failure',
            'cookie-SID' => $csi,
            'old_si' => $usi,
            'request_si' => $rsi,
            'old_token' => $token,
            'old_nut' => $nut,
            'match1' => $m1,
            'match2' => $m2,
            'match3' => $m3,
            'match4' => $m4, //Verifier::do_match($usi, $csi),
            'session_data' =>$sData,
            '_token' => $request->input('_token'),
            'request' => $rd,
            'csrf' => $request->header('X-CSRF-TOKEN'),
            'xsrf' => $request->header('X-XSRF-TOKEN'),
            //'decoded' => decrypt($request->header('X-XSRF-TOKEN')),
            'nut' => $request->input('nut'),
            'session_token' => $request->hasSession() 
                ? $request->session()->token()
                : $sData->token(),
                //: '<no-token>',
        ];
        return Functions::genDumpResponse($request, $tmp);
    }
}
