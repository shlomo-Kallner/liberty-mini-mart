<?php

namespace App\Utilities;

use App\Utilities\Functions\Functions,
    App\User,
    App\Cart,
    App\UserSession;
use Illuminate\Http\Request,
    Illuminate\Support\Collection,
    Illuminate\Support\Facades\Log;
use Darryldecode\Cart\Cart as DarrylCart, 
    Darryldecode\Cart\CartCollection;


class CartStorage
{
    protected $request, $storage, $data;

    //protected $session;

    public function __construct(Request $request = null)
    {
        $request2 = $request ?? request();
        $this->loadStorage($request2);
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
        // Log::info(
        //     __METHOD__ . 'message', 
        //     ['request' => $request2, 'cart' => $cart, 'storage' => $this->storage]
        // );
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
    }
}