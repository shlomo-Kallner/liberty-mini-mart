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

    static public function getNewCartArray(string $currencyIcon = 'fa-usd')
    {
        /*
            'cart' => [
                'items' => [],
                'currencyIcon' => 'fa-usd',
                'subTotal' => 0,
                'totalItems' => 0,
            ],
        */
        return [
            'items' => [],
            'currencyIcon' => $currencyIcon,
            'subTotal' => 0,
            'totalItems' => 0,
        ];
    }

    static public function getCurrentCart(
        Request $request = null, bool $asArray = true, bool $useCart = false
    ) {
        $sess = Functions::testVar($request) && $request->hasSession()
                    ? $request->session() 
                    : session();
        $cart = self::getNewCartArray(
            $sess->has('currency') ? $sess->get('currency') : 'fa-usd'
        );
        if (false && $useCart && $sess->has('cart')) {
            $cart_info = unserialize($sess->get('cart'));
            $cart['items'] = $cart_info['items'];
            $cart['subTotal'] = $cart_info['subTotal'];
            $cart['totalItems'] = $cart_info['totalItems'];
            //dd($cart);
        } else {
            $darrylCart = DarrylCart::session('cart');
            if (!$darrylCart->isEmpty()) {
                $cTmp = $darrylCart->getContent()->all();
                foreach ($cTmp as $item) {
                    if ($asArray) {
                        $cart['items'][] = [
                            'id' => $item->id,
                            'name' => $item->name,
                            'url' => $request->ajax() 
                                ? url($item->attributes['url'])
                                : $item->attributes['url'],
                            'img' => $request->ajax() 
                                ? asset($item->attributes['img'])
                                : $item->attributes['img'],
                            'description' => $item->attributes['description'],
                            'quantity' => $item->quantity,
                            'priceSum' => $item->getPriceSumWithConditions(),
                        ];
                    } else {
                        $cart['items'][] = $item;
                    }
                }
                $cart['subTotal'] = $darrylCart->getSubTotal();
                $cart['totalItems'] = $darrylCart->getTotalQuantity();
            }
            //dd($cart, $darrylCart);
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
        $user, string $session_id, string $ip, string $agent, $content = null
    ) {
        $user_id = Functions::getVar(User::getUserId($user), 0);
        /**
         * 
                $table->integer('user_id')->unsigned()->nullable();
                $table->string('session_id', 255);
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->mediumText('content');
                $table->string('verihash', 255);
         */
        if (empty($content)) {
            $content = [];
        }
        $cTmp = self::where(
            [
                ['user_id', '=', $user_id],
                ['session_id', '=', $session_id],
                ['ip_address', '=', $ip],
                ['user_agent', '=', $agent]
            ]
        )->get();
        if (!Functions::testVar($cTmp) && count($cTmp) === 0) {
            $tmp = new self;
            $tmp->user_id = $user_id;
            $tmp->session_id = $session_id;
            $tmp->ip_address = $ip;
            $tmp->user_agent = $agent;
            $cnTmp = base64_encode(serialize($content));
            $tmp->content = $cnTmp;
            $tmp->verihash = Hash::make($cnTmp);
            if ($tmp->save()) {
                return $tmp->id;
            }
        }
        return null;
    }

    static public function createNewFrom(array $array)
    {
        return self::createNew(
            $array['user'], $array['session_id'], $array['ip'],
            $array['agent'], $array['content']
        );
    }
}
