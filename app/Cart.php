<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Session;

class Cart extends Model
{
    //

    static public function getCurrentCart()
    {
        $cart = [
            'items' => null,
            'currency-icon' => session()->has('currency') ?
                session()->get('currency')  : 'fa-usd',
            'sub-total' => 0,
            'total-items' => 0,
        ];
        if (session()->has('cart')) {
            $cart_info = unserialize(session()->get('cart'));
            $cart['items'] = $cart_info['items'];
            $cart['sub-total'] = $cart_info['sub-total'];
            $cart['total-items'] = $cart_info['total-items'];
        }
        return $cart;
    }
}
