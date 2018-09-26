<?php

namespace App\Utilities;

use 

use App\Utilities\Functions\Functions,
    App\User,
    App\Cart,
    App\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Darryldecode\Cart\Cart as DarrylCart;
use Darryldecode\Cart\CartCollection;


class CartStorage
{
    protected $request, $session, $storage, $data;

    public function __construct(Request $request = request())
    {
        $this->request = $request;
        /* 
            if ($request->ajax()) {
                if (Functions::testVar($us = UserSession::getFrom($request))) {
                    $this->session = $us;
                }
            } 
        */
        $this->storage = Cart::getFromOrCreate($request);
        $this->data = $this->storage->getCartContent() ?? Collection::make();
    }
    public function has($key)
    {
        return Cart::exists($key);
    }
    
    public function get($key)
    {
        if (Functions::testVar($tC = Cart::getFrom($key)) {
            
            return new CartCollection(Cart::find($key)->cart_data);
        }
        return [];
    }
    
    public function put($key, $value)
    {
        if ($row = Cart::find($key)) {
            // update
            $row->cart_data = $value;
            $row->save();
        } else {
            Cart::create(
                [
                    'id' => $key,
                    'cart_data' => $value
                ]
            );
        }
    }
}