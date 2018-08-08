<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Session;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


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

    static public function getFromId(int $id)
    {
        return self::where('id', $id)->first();
    }

    static public function existsId(int $id)
    {
        return Functions::testVar(self::getFromId($id));
    }

    static public function createNew()
    {
        //
    }

    static public function createNewFrom(array $array)
    {
        return self::createNew();//
    }
}
