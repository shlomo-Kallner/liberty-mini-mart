<?php

namespace App\Utilities;

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
    protected $request, $storage, $data;

    //protected $session;

    public function __construct(Request $request = null)
    {
        $request2 = $request ?? request();
        /* 
            if ($request->ajax()) {
                if (Functions::testVar($us = UserSession::getFrom($request))) {
                    $this->session = $us;
                }
            } 
        */
        $this->loadStorage($request);
    }

    protected function loadStorage(Request $request = null)
    {
        $request2 = $request ?? request();
        if (!functions::testVar($this->request)
        || $this->request !== $request2
        ) {
            $this->request = $request2;
        }
        $cart = Cart::getFromOrCreate($this->request);
        if (!Functions::testVar($this->storage) 
        || $cart->id !== $this->storage->id
        ) {
            $this->storage = $cart;
        }
        $tmp = $this->storage->getCartContent();
        $this->data = Collection::make(Functions::getVar($tmp, []));
    }

    public function has($key)
    {
        return $this->data->has($key);
    }
    
    public function get($key)
    {
        $this->loadStorage($this->request);
        // Functions::testVar($tC = Cart::getFrom($key)
        if ($this->has($key)) {
            //$cart_data = $this->data->get($key);
            return new CartCollection($this->data->get($key));
        }
        return [];
    }
    
    public function put($key, $value)
    {
        $this->loadStorage($this->request);
        $this->data->put($key, $value);
        $this->storage->updateCartFrom($this->request, null, $this->data->all());
        /* 
            if ($this->has($key)) {
                $this->data->put($key, $value);
                // update
                //$row->cart_data = $value;
                $this->storage->updateCartFrom($this->request, null, $this->data);
            } else {
                Cart::create(
                    [
                        'id' => $key,
                        'cart_data' => $value
                    ]
                );
            } 
        */
    }
}