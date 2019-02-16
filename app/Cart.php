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
use Webpatser\Uuid\Uuid;

class Cart extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function getNewCartArray(
        string $currencyIcon = 'fa-usd', array $items = [],
        float $subTotal = 0.0, int $totalItems = 0, 
        int $total = 0, array $conditions = []
    ) {
        /*
            'cart' => [
                'items' => [],
                'currencyIcon' => 'fa-usd',
                'subTotal' => 0,
                'totalItems' => 0,
            ],
        */
        return [
            'items' => $items,
            'currencyIcon' => $currencyIcon,
            'subTotal' => $subTotal,
            'totalItems' => $totalItems,
            'total' => $total,
            'conditions' => $conditions,
        ];
    }

    static public function getSessionCart(bool $retObj = true)
    {
        $cart = DarrylCart::session('cart');
        return $retObj
            ? $cart
            : [
                'cart' => $cart->getContent(),
                'conditions' => $cart->getConditions()
            ];
    }

    static public function makeItemContentArray(
        string $name = '', string $url = '', 
        string $img = '', string $description = '',
        int $quantity = 0, float $price = 0.0, 
        float $priceSum = 0.0, string $api = '',
        array $opts = [], int $id = 0, 
        bool $asUrl = false
    ) {
        return [
            'id' => $id,
            'name' => $name,
            'url' => $asUrl 
                ? url($url)
                : $url,
            'img' => $asUrl 
                ? asset($img)
                : $img,
            'description' => $description,
            'quantity' => $quantity,
            'priceSum' => $priceSum,
            'price' => $price,
            'api' => $asUrl 
                ? url($api)
                : $api,
            'options' => $opts,
        ];
    }

    public function toCartContentArray(
        string $currencyIcon = 'fa-usd', bool $asUrl = false
    ) {
        $content = $this->getCartContent();
        $count = 0;
        $subTotal = 0.0;
        $res = [];
        foreach ($content['cart'] as $item) {
            $opts = $item->attributes->has('options') 
                ? $item->attributes['options']
                : [];
            $count += $item->quantity;
            $subTotal += $item->getPriceSumWithConditions(false);
            $res[] = self::makeItemContentArray(
                $item->name, $asUrl 
                    ? url($item->attributes['url'])
                    : $item->attributes['url'], 
                $asUrl 
                    ? asset($item->attributes['img'])
                    : $item->attributes['img'], 
                $item->attributes['description'],
                $item->quantity, $item->price, 
                $item->getPriceSumWithConditions(false), 
                $asUrl 
                    ? url($item->attributes['api'])
                    : $item->attributes['api'],
                $opts, $item->id, $asUrl
            );
        }
        return self::getNewCartArray(
            $currencyIcon, $res, $subTotal, $count
        );
    }

    static public function condToArray($cond, $target = null, $price = 0.0)
    {
        if (Functions::testVar($cond) && is_object($cond)) {
            if ($cond->getTarget() === $target) {
                $attr = $cond->getAttributes();
                $calcVal = floatval($cond->getCalculatedValue($price));
                return [
                    'name' => $cond->getName(),
                    'value' => $cond->getValue(),
                    'calcValue' => round($calcVal, 2, PHP_ROUND_HALF_UP),
                    'type' => $cond->getType(),
                    'description' => Functions::getPropKey($attr, 'description', ''),
                    'attributes' => $attr,
                ];
            }
        }
        return null;
    }

    static public function itemToArray($item, bool $asUrl = false)
    {
        if (Functions::testVar($item) && is_object($item)) {
            $opts = $item->attributes->has('options') 
                ? $item->attributes['options']
                : [];
            $tConds = $item->getConditions();
            $conds = [];
            foreach ($tConds as $cond) {
                $c = self::condToArray($cond, 'item', $item->price);
                if (Functions::testVar($c)) {
                    $conds[] = $c;
                }
            }
            return [
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
                'priceSum' => round($item->getPriceSumWithConditions(true), 2, PHP_ROUND_HALF_UP),
                'price' => round($item->price, 2, PHP_ROUND_HALF_UP),
                'priceCalc' => round($item->getPriceWithConditions(true), 2, PHP_ROUND_HALF_UP),
                'api' => $asUrl 
                    ? url($item->attributes['api'])
                    : $item->attributes['api'],
                'options' => $opts,
                'conditions' => $conds,
            ];
        }
        return null;
    }


    static public function cartToArray(
        DarrylCartCart $dcart = null, 
        bool $asArray = true, 
        bool $asUrl = false,
        string $currencyIcon = 'fa-usd' 
    ) {
        $darrylCart = Functions::testVar($dcart) ? $dcart : self::getSessionCart();
        $totalItems = $darrylCart->getTotalQuantity();
        $subTotal = round(
            floatval($darrylCart->getSubTotal()), 2, PHP_ROUND_HALF_UP
        );
        $total = round(
            floatval($darrylCart->getTotal()), 2, PHP_ROUND_HALF_UP
        );
        $items = [];
        $cTmp = $darrylCart->getContent();
        if (!$cTmp->isEmpty()) {
            foreach ($cTmp->all() as $item) {
                if ($asArray) {
                    $i = self::itemToArray($item, $asUrl);
                    if (Functions::testVar($i)) {
                        $items[] = $i;
                    }
                } else {
                    $items[] = $item;
                }
            }
        }
        $conds = [];
        $cnTmp = $darrylCart->getConditions();
        if (!$cnTmp->isEmpty()) {
            foreach ($cnTmp->all() as $cond) {
                if ($asArray) {
                    if ($cond->getTarget() === 'total') {
                        $t = 'total';
                        $calVal = $total;
                    } else {
                        $t = 'subTotal';
                        $calVal = $subTotal;
                    }
                    $c = self::condToArray($cond, $t, $calVal);
                    if (Functions::testVar($c)) {
                        $conds[] = $c;
                    }
                } else {
                    $conds[] = $cond;
                }
            }
        }
        return self::getNewCartArray(
            $currencyIcon, $items, $subTotal, 
            $totalItems, $total, $conds
        );
    }

    /**
     * Function getCurrentCart() - Get the current shopping cart in a view friendly 
     *                           array format.
     *
     * @param Request $request
     * @param boolean $asArray
     * @param string $currencyIcon - a default currency Font Awesome icon.
     * @param mixed $dcart
     * 
     * @return array
     */
    static public function getCurrentCart(
        Request $request = null, bool $asArray = true,
        string $currencyIcon = 'fa-usd', $dcart = null
    ) {
        $request = Functions::testVar($request) ? $request : request();
        $sess = $request->hasSession()
                    ? $request->session() 
                    : session();
        $ci = $sess->has('currency') ? $sess->get('currency') : $currencyIcon;
        $cart = self::cartToArray($dcart, $asArray, !$request->ajax(), $ci);
        //dd($cart, $dcart, $dcart->getContent(), DarrylCart::getContent());
        return $cart;
    }

    static public function storeOrCreateCurrentCart(
        Request $request, $user = null, $dcart = null
    ) {
        //dd($request, $user, $dcart);
        if (Functions::testVar($dcart) && $dcart instanceof DarrylCartCart) {
            $content = [ 
                'cart' => $dcart->getContent(),
                'conditions' => $dcart->getConditions()
            ];
        } else {
            $dcart = DarrylCart::session('cart');
            $content = [
                'cart' => $dcart->getContent(),
                'conditions' => $dcart->getConditions()
            ];
        }
        $cart = self::getFrom($request);
        //dd($request, $user, $cart, $content);
        if (!Functions::testVar($cart)) {
            $cart1 = self::createNewFromData($request, $user, $content, false);
            if (Functions::testVar($cart1) && $cart1 instanceof self) {
                return $cart1;
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
        return $this->updateCartFrom(
            $data, $user, self::getSessionCart(false)
        );
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

    static public function getFrom(
        $id, bool $useGet = false, bool $withTrashed = false
    ) {
        $tmp = null;
        if ($id instanceof self) {
            return $id;
        } elseif (is_int($id)) {
            $tmp = $withTrashed
                ? self::withTrashed()->where('id', $id)
                : self::where('id', $id);
        } elseif (is_string($id)) {
            $tmp = $withTrashed
                ? self::withTrashed()->where('session_id', $id)
                : self::where('session_id', $id);
        } elseif ($id instanceof Request) {
            $whereWith = [
                ['ip_address', '=', $id->ip()],
                ['user_agent', '=', $id->userAgent()]
            ];
            if ($id->hasSession()) {
                $whereWith[] = ['session_id', '=', $id->session()->getId()];
            }
            $tmp = $withTrashed
                ? self::withTrashed()->where($whereWith)
                : self::where($whereWith);
        } elseif (is_array($id)) {
            // is a User Array ...
            $whereWith = [
                ['ip_address', '=', $id['ip']],
                ['user_agent', '=', $id['agent']],
                ['user_id', '=', $id['id']]
            ];
            $tmp = $withTrashed
                ? self::withTrashed()->where($whereWith)
                : self::where($whereWith);
        }
        if (Functions::testVar($tmp)) {
            return $useGet
                ? $tmp->get()
                : $tmp->first();
        }
        return null;
    }

    static public function exists(
        $id, bool $useGet = false, bool $withTrashed = false
    ) {
        return Functions::testVar(self::getFrom($id, $useGet, $withTrashed));
    }

    static public function createNew(
        $user, string $session_id, string $ip, 
        string $agent, $content = null, bool $retId = true
    ) {
        $user_id = Functions::getVar(User::getUserId($user), 0);
        /*
         * 
         *      $table->integer('user_id')->unsigned()->nullable();
         *      $table->string('session_id', 255);
         *      $table->string('ip_address', 45)->nullable();
         *      $table->text('user_agent')->nullable();
         *      $table->mediumText('content');
         *      $table->string('verihash', 255);
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
            $tmp->uuid = Uuid::generate(5, '/carts/' . $session_id, Uuid::NS_URL);
            $cnTmp = base64_encode(serialize($content));
            $tmp->content = $cnTmp;
            $tmp->verihash = Hash::make($cnTmp);
            if ($tmp->save()) {
                return $retId ? $tmp->id : $tmp;
            }
        }
        return null;
    }

    static public function createNewFrom(array $array, bool $retObj = false)
    {
        return self::createNewFromData(
            $array['data'], $array['user'] ?? null, 
            $array['content'] ?? null, !$retObj
        );
    }

    static public function createNewFromData(
        $data, $user = null, $content = null, bool $retId = true
    ) {
        if (is_array($data)) {
            return self::createNew(
                $data['user'] ?? $user, $data['session_id'], 
                $data['ip'], $data['agent'], 
                $data['content'] ?? $content, 
                $data['retId'] ?? $retId
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

    static public function getFromOrCreate($data)
    {
        if (Functions::testVar($cart = self::getFrom($data))) {
            return $cart;
        } else {
            return self::createNewFromData($data, null, null, false);
        }
    }
}
