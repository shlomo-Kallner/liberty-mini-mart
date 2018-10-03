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

    static public function cartToArray(
        $dcart, array $acart = null,
        bool $asUrl = true, bool $asArray = true,
        string $currencyIcon = 'fa-usd'
    ) {
        if (Functions::testVar($dcart) 
        && $dcart instanceof DarrylCartCart
        ) {
            $darrylCart = &$dcart;
        } else {
            $darrylCart = self::getSessionCart();
        }
        if (Functions::testVar($acart)) {
            $cart = &$acart;
        } else {
            $cart = self::getNewCartArray($currencyIcon);
        }
        if (!$darrylCart->isEmpty()) {
            $cTmp = $darrylCart->getContent()->all();
            foreach ($cTmp as $item) {
                if ($asArray) {
                    $cart['items'][] = [
                        'id' => $item->id,
                        'name' => $item->name,
                        'url' => $asUrl 
                            ? url($item->attributes['url'])
                            : $item->attributes['url'],
                        'img' => $asUrl 
                            ? asset($item->attributes['img'])
                            : $item->attributes['img'],
                        'description' => $item->attributes['description'],
                        'quantity' => $item->quantity,
                        'priceSum' => $item->getPriceSumWithConditions(),
                        'api' => $asUrl 
                        ? url($item->attributes['api'])
                        : $item->attributes['api'],
                    ];
                } else {
                    $cart['items'][] = $item;
                }
            }
            $cart['subTotal'] = $darrylCart->getSubTotal();
            $cart['totalItems'] = $darrylCart->getTotalQuantity();
        }
        return $cart;
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
        string $currencyIcon = 'fa-usd', $dcart = null
    ) {
        $sess = Functions::testVar($request) && $request->hasSession()
                    ? $request->session() 
                    : session();
        $cart = self::getNewCartArray(
            $sess->has('currency') ? $sess->get('currency') : $currencyIcon
        );
        if (Functions::testVar($dcart) 
        && $dcart instanceof DarrylCartCart
        ) {
            $darrylCart = &$dcart;
        } else {
            $darrylCart = self::getSessionCart();
        }
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
                        'api' => $request->ajax() 
                        ? url($item->attributes['api'])
                        : $item->attributes['api'],
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
        //dd($request, $user, $dcart);
        if (Functions::testVar($dcart) && $dcart instanceof DarrylCartCart) {
            $content = $dcart->getContent();
        } else {
            $content = DarrylCart::session('cart')->getContent();
        }
        $cart = self::getFrom($request);
        //dd($request, $user, $cart, $content);
        if (!Functions::testVar($cart)) {
            $cart1 = self::createNewFrom($request, $user, $content);
            if (Functions::testVar($cart1)) {
                return self::getFrom($cart1);
            }
            //dd($cart, $cart1, $request, $user, $content);
        } elseif ($cart instanceof self) {
            if ($cart->updateCartFrom($request, $user, $content)) {
                //dd($cart, $request, $user, $content, __METHOD__, 0);
                return $cart;
            }
            //dd($cart, $request, $user, $content, __METHOD__, 1);
        } elseif (Functions::testVar($cart) && count($cart) > 0) {
            if ($cart[0]->updateCartFrom($request, $user, $content)) {
                return $cart[0];
            }
        }
        //dd($cart, $request, $user, $content, __METHOD__);
        return null;
    }

    public function updateCart(
        $content, string $session_id = '', string $ip = '',
        string $agent = '', $user = null
    ) {
        //dd($content)
        if (Functions::testVar($content)) {
            $tmp1 = $this->getCartContent();
            //dd($content, $tmp1);
            foreach ($content as $key => $value) {
                /* 
                    if (Functions::isValIn($tmp1, $key, $value) === false) {
                        dd(true);
                        Functions::setPropKey($tmp1, $key, $value);
                    } 
                */
                Functions::setPropKey($tmp1, $key, $value);
                //dd(false);
            }
            //dd($content, $tmp1);
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
        } elseif ($data instanceof Request && $data->hasSession()
        ) {
            //dd($data, $data->hasSession());
            return $this->updateCart(
                $content, $data->session()->getId(), $data->ip(),
                $data->userAgent(), $user
            );
        }
        //dd($data, $data->hasSession());
        return false;
    }

    public function getCartContent()
    {
        return unserialize(base64_decode($this->content));
    }

    static public function getFrom($id, bool $useGet = false)
    {
        if (is_int($id)) {
            return $useGet 
                ? self::where('id', $id)->get()
                : self::where('id', $id)->first();
        } elseif ($id instanceof self) {
            return $id;
        } elseif (is_string($id)) {
            return $useGet 
                ? self::where('session_id', $id)->get()
                : self::where('session_id', $id)->first();
        } elseif ($id instanceof Request) {
            $whereWith = [
                ['ip_address', '=', $id->ip()],
                ['user_agent', '=', $id->userAgent()]
            ];
            if ($id->hasSession()) {
                $whereWith[] = ['session_id', '=', $id->session()->getId()];
            }
            return $useGet 
                ? self::where($whereWith)->get()
                : self::where($whereWith)->first();
        } elseif (is_array($id)) {
            // is a User Array ...
            $whereWith = [
                ['ip_address', '=', $id['ip']],
                ['user_agent', '=', $id['agent']],
                ['user_id', '=', $id['id']]
            ];
            return $useGet 
                ? self::where($whereWith)->get()
                : self::where($whereWith)->first();
        }
        return null;
    }

    static public function exists($id, bool $useGet = false)
    {
        return Functions::testVar(self::getFrom($id, $useGet));
    }

    static public function createNew(
        $user, string $session_id, string $ip, 
        string $agent, $content = null, bool $retId = true
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
        //dd($cTmp, !Functions::testVar($cTmp));
        if (!Functions::testVar($cTmp) || count($cTmp) === 0) {
            $tmp = new self;
            $tmp->user_id = $user_id;
            $tmp->session_id = $session_id;
            $tmp->ip_address = $ip;
            $tmp->user_agent = $agent;
            $cnTmp = base64_encode(serialize($content));
            $tmp->content = $cnTmp;
            $tmp->verihash = Hash::make($cnTmp);
            if ($tmp->save()) {
                return $retId ? $tmp->id : $tmp;
            }
        }
        return null;
    }

    static public function createNewFrom(
        $data, $user = null, $content = null, bool $retId = true
    ) {
        if (is_array($data)) {
            return self::createNew(
                $data['user'], $data['session_id'], $data['ip'],
                $data['agent'], $data['content'], $retId
            );
        } elseif ($data instanceof Request && $data->hasSession()) {
            //dd($data, $user, $content, __METHOD__);
            return self::createNew(
                $user, $data->session()->getId(), $data->ip(),
                $data->userAgent(), $content, $retId
            );
        }
        return null;
    } 

    static public function getFromOrCreate($data, bool $retId = true)
    {
        if (Functions::testVar($cart = self::getFrom($data))) {
            return $cart;
        } else {
            return self::createNewFrom($data, null, null, false);
        }
    }
}
