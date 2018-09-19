<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Darryldecode\Cart\Facades\CartFacade as DarrylCart;
use App\Utilities\Functions\Functions;
use Illuminate\Support\Facades\Hash;
use App\User;
use Illuminate\Http\Request;

class Cart extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    static public function getCurrentCart(Request $request = null, bool $useCart = false)
    {
        $sess = Functions::testVar($request) && $request->hasSession()
                    ? $request->session() 
                    : session();
        /*
            'cart' => [
                'items' => [],
                'currencyIcon' => 'fa-usd',
                'subTotal' => 0,
                'totalItems' => 0,
            ],
        */
        $cart = [
                'items' => [],
                'currencyIcon' => $sess->has('currency') 
                    ? $sess->get('currency')  
                    : 'fa-usd',
                'subTotal' => 0,
                'totalItems' => 0,
        ];
        if (!$useCart && $sess->has('cart')) {
                $cart_info = unserialize($sess->get('cart'));
                $cart['items'] = $cart_info['items'];
                $cart['subTotal'] = $cart_info['subTotal'];
                $cart['totalItems'] = $cart_info['totalItems'];
            
        } else {
            $darrylCart = DarrylCart::session('cart');
            if (!$darrylCart->isEmpty()) {
                $cart['items'] = $darrylCart->getContent()->all();
                $cart['subTotal'] = $darrylCart->getSubTotal();
                $cart['totalItems'] = $darrylCart->getTotalQuantity();

            }
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

    static public function createNew(
        $user, string $session_id, $content = null
    ) {
        $user_id = Functions::getVar(User::getUserId($user), 0);
        //
        /**
         * 
                $table->integer('user_id')->unsigned()->nullable();
                $table->string('session_id', 255);
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->mediumText('content');
                $table->string('verihash', 255);
         */
    }

    static public function createNewFrom(array $array)
    {
        return self::createNew();//
    }
}
