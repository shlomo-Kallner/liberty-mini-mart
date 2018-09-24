<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Darryldecode\Cart\Facades\CartFacade as DarrylCart;
use Darryldecode\Cart\Cart as DarrylCartCart;
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

    static public function getSessionCart()
    {
        return DarrylCart::session('cart');
    }

    /**
     * Function getCurrentCart() - Get the current shopping cart in a view friendly 
     *                           array format.
     *
     * @param Request $request
     * @param boolean $asArray
     * @param string $currencyIcon - a default currency Font Awesome icon.
     * 
     * @return array
     */
    static public function getCurrentCart(
        Request $request = null, bool $asArray = true,
        string $currencyIcon = 'fa-usd'
    ) {
        $sess = Functions::testVar($request) && $request->hasSession()
                    ? $request->session() 
                    : session();
        $cart = self::getNewCartArray(
            $sess->has('currency') ? $sess->get('currency') : $currencyIcon
        );
        $darrylCart = self::getSessionCart();
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
        //dd($cart, $darrylCart, $darrylCart->getContent(), DarrylCart::getContent());
        return $cart;
    }

    static public function storeOrCreateCurrentCart(
        Request $request, $user = null, $dcart = null
    ) {
        if (Functions::testVar($dcart) && $dcart instanceof DarrylCartCart) {
            $content = $dcart->getContent();
        } else {
            $content = DarrylCart::session('cart')->getContent();
        }
        $cart = self::getFrom($request);
        if (!Functions::testVar($cart)) {
            $cart1 = self::createNewFrom($request, $user, $content);
            if (Functions::testVar($cart1)) {
                return self::getFrom($cart1);
            }
        } elseif ($cart instanceof self) {
            if ($cart->updateCartFrom($request, $user, $content)) {
                return $cart;
            }
        }
        return null;
    }

    public function updateCart(
        $content, string $session_id = '', string $ip = '',
        string $agent = '', $user = null
    ) {
        if (Functions::testVar($content)) {
            $tmp1 = $this->getCartContent();
            foreach ($content as $key => $value) {
                if (Functions::isValIn($tmp1, $key, $value) === false) {
                    Functions::setPropKey($tmp1, $key, $value);
                }
            }
            $cnTmp = base64_encode(serialize($tmp1));
            $this->content = $cnTmp;
            $this->verihash = Hash::make($cnTmp);
        }
        if (Functions::testVar($session_id)) {
            $this->session_id = $session_id;
        }
        if (Functions::testVar($ip)) {
            $this->ip_address = $ip;
        }
        if (Functions::testVar($agent)) {
            $this->user_agent = $agent;
        }
        if (Functions::testVar($user)) {
            $user_id = Functions::getVar(User::getUserId($user), 0);
            $this->user_id = $user_id;
        }
        return $this->save();
    }

    public function updateCartWithSession($data, $user = null)
    {
        return $this->updateCartFrom($data, $user, self::getSessionCart());
    }

    public function updateCartFrom($data, $user = null, $content = null)
    {
        if (is_array($data)) {
            return $this->updateCart(
                $data['content'], $data['session_id'], $data['ip'],
                $data['agent'], $data['user']
            );
        } elseif ($data instanceof Request && Functions::testVar($user)
            && $data->hasSession()
        ) {
            return $this->updateCart(
                $content, $data->session()->getId(), $data->ip(),
                $data->userAgent(), $user
            );
        }
        return false;
    }

    public function getCartContent()
    {
        return unserialize(base64_decode($this->content));
    }

    static public function getFrom($id)
    {
        if (is_int($id)) {
            return self::where('id', $id)->first();
        } elseif ($id instanceof self) {
            return $id;
        } elseif ($id instanceof Request) {
            $whereWith = [
                ['ip_address', '=', $id->ip()],
                ['user_agent', '=', $id->userAgent()]
            ];
            if ($id->hasSession()) {
                $whereWith[] = ['session_id', '=', $id->session()->getId()];
            }
            return self::where($whereWith)->get();
        } elseif (is_array($id)) {
            // is a User Array ...
            $whereWith = [
                ['ip_address', '=', $id['ip']],
                ['user_agent', '=', $id['agent']],
                ['user_id', '=', $id['id']]
            ];
            return self::where($whereWith)->get();
        }
        return null;
    }

    static public function exists($id)
    {
        return Functions::testVar(self::getFrom($id));
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

    static public function createNewFrom(
        $data, $user = null, $content = null
    ) {
        if (is_array($data)) {
            return self::createNew(
                $data['user'], $data['session_id'], $data['ip'],
                $data['agent'], $data['content']
            );
        } elseif ($data instanceof Request && Functions::testVar($user)
            && $data->hasSession()
        ) {
            return self::createNew(
                $user, $data->session()->getId(), $data->ip(),
                $data->userAgent(), $content
            );
        }
    }
}
