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
    protected $request, $storage, $data;

    //protected $session;

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
        return $this->data->has($key);
    }
    
    public function get($key)
    {
        // Functions::testVar($tC = Cart::getFrom($key)
        if ($this->has($key)) {
            //$cart_data = $this->data->get($key);
            return new CartCollection($this->data->get($key));
        }
        return [];
    }
    
    public function put($key, $value)
    {
        $this->data->put($key, $value);
        $this->storage->updateCartFrom($this->request, null, $this->data);

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