<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Database\Eloquent\SoftDeletes,
    Illuminate\Http\Request;
use App\Utilities\Functions\Functions;
use App\User;
use App\Cart;

class UserSession extends Model
{
    use SoftDeletes;

    const LOCKED_ACTIVITY = 0;
    const CREATED_ACTIVITY = 1;
    const UPDATED_ACTIVITY = 2;
    const DELETED_ACTIVITY = 3;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function createNew(
        string $session_id, $user, string $ip, string $userAgent,
        $payload = null, int $lastActivity = self::CREATED_ACTIVITY
    ) {
        if (Functions::testVar($session_id) 
            && Functions::testVar($ip)
            && Functions::testVar($userAgent)
        ) {
            $user_id = Functions::getVar(User::getUserId($user), 0);
            $whereData = [
                ['ip_address', '=', $ip],
                ['user_agent', '=', $userAgent]
            ];
            if (Functions::testVar($session_id)) {
                $whereData[] = ['session_id', '=', $session_id];
            }
            if (Functions::testVar($user_id) && $user_id > 0) {
                $whereData[] = ['user_id', '=', $user_id];
            }
            $tmp = self::where($whereData)->get();
            if (!Functions::testVar($tmp) || count($tmp) === 0) {
                if (!self::acceptablePayloadType($payload) || !Functions::testVar($payload)) {
                    $tPay = [];
                } else {
                    $tPay = $payload;
                }
                $data = new self;
                $data->session_id = $session_id;
                $data->user_id = $user_id;
                $data->ip_address = $ip;
                $data->user_agent = $userAgent;
                $data->payload  = base64_encode(serialize($tPay??[]));
                $data->last_activity = $lastActivity;
                if ($data->save()) {
                    return $data->id;
                }
            }
        }
        return null;
    }

    static protected function acceptablePayloadType($payload)
    {
        return is_array($payload) || is_object($payload);
    }

    public function updateSession(
        string $session_id, $user, string $ip, string $userAgent,
        $payload = null, int $lastActivity = self::UPDATED_ACTIVITY
    ) {
        $this->session_id = $session_id;
        $user_id = Functions::getVar(User::getUserId($user), 0);
        if ($this->user_id >= 0 && $user_id > 0) {
            //dd($user_id);
            $this->user_id = $user_id;
        }
        $this->ip_address = $ip;
        $this->user_agent = $userAgent;
        $this->updatePayload($payload);
        $this->last_activity = $lastActivity;
        return $this->save();
    }

    protected function updatePayload($payload = null)
    {
        if (Functions::testVar($payload)) {
            $tmp1 = is_string($payload) ? unserialize($payload) : $payload;
            if (self::acceptablePayloadType($tmp1)) {
                $tmp2 = $this->getPayload();
                //dd($tmp2, $payload);
                foreach ($tmp1 as $key => $val) {
                    Functions::setPropKey($tmp2, $key, $val);
                }
                $this->payload = base64_encode(serialize($tmp2));
            }
        }
    }

    public function getPayload(bool $unserialize = true)
    {
        return $unserialize 
            ? unserialize(base64_decode($this->payload)) 
            : base64_decode($this->payload);
    }

    public function tryLock()
    {
        if (!$this->isLocked()) {
            return $this->lock();
        }
        return false;
    }

    public function lock()
    {
        $this->last_activity = self::LOCKED_ACTIVITY;
        return $this->save();
    }

    public function unlock()
    {
        $this->last_activity = self::UPDATED_ACTIVITY;
        return $this->save();
    }

    public function isLocked()
    {
        return $this->last_activity === self::LOCKED_ACTIVITY;
    }

    static public function createNewFrom(Request $request)
    {
        $tmp = User::getUserArray($request);
        return self::createNew(
            $request->hasSession() ? $request->session()->getId() : '',
            intval(Functions::getVar(Functions::getPropKey($tmp, 'id'), 0)),
            $request->ip(),
            $request->userAgent(),
            $request->hasSession() ? $request->session()->all() : []
        );
    }

    static public function createNewOrUpdate(Request $request, int $user_id = 0)
    {
        $session_id = $request->hasSession()
            ? $request->session()->getId()
            : '';
        $session_data = $request->hasSession() 
            ? $request->session()->all() 
            : [];
        if (!Functions::testVar($us = self::getFromId($request))) {
            $tus = UserSession::createNew(
                $session_id, $user_id,
                $request->ip(), $request->userAgent(),
                $session_data
            );
            if (false) {
                if ($tus1 = UserSession::getFromId($tus)) {
                    $tpay = $tus1->getPayload(); 
                    $tses = $tus1->session_id;
                } else {
                    $tpay = ''; 
                    $tses = '';
                }

                $imin = 'tus';
                
                //dd($tus, 'tus');
            }
        } else {
            // $tpay = $us->getPayload(); 
            // $tses = $us->session_id;
            // $imin = 'us';
            $us->updateSession(
                $session_id, $user_id,
                $request->ip(), $request->userAgent(),
                $session_data
            );
            // dd($tpay, $us->getPayload(), 'us');
            // dd($tses, $request->session()->getId(), $us->session_id, 'us');
            // $tpay = $us->getPayload(); 
            // $tses = $us->session_id;
            
        }
        return Functions::testVar($us) 
            ? $us
            : self::getFromId($tus);
        
    }

    public function updateWith(Request $request, int $user_id = 0)
    {
        $session_id = $request->hasSession()
            ? $request->session()->getId()
            : '';
        $session_data = $request->hasSession() 
            ? $request->session()->all() 
            : [];
        return $this->updateSession(
            $session_id, $user_id,
            $request->ip(), $request->userAgent(),
            $session_data
        );
    }

    static public function updateRegenerate(
        Request $request, int $user_id = 0, bool $retObj = false
    ) {
        $cart = Cart::storeOrCreateCurrentCart($request, $user_id);
        $us = UserSession::createNewOrUpdate($request, $user_id);
        $request->session()->regenerate();
        if (Functions::testVar($cart)) {
            $cart->updateCartWithSession($request, $user_id);
        }
        $bol = $us->updateWith($request, $user_id);
        return $retObj ? $us : $bol;
    }

    static public function getFrom($id)
    {
        return self::getFromId($id);
    }

    static public function getFromId($id) 
    {
        if (is_int($id)) {
            return self::where('id', $id)->first();
        } elseif (is_string($id)) {
            return self::where('session_id', $id)->first();
        } else {
            $whereData = null;
            if (is_array($id) && Functions::hasPropKeyIn($id, 'ip')
                && Functions::hasPropKeyIn($id, 'agent')
            ) {
                // assumption: $id is a userArray.
                $whereData = [
                    ['ip_address', '=', $id['ip']],
                    ['user_agent', '=', $id['agent']]
                ];
                if (Functions::hasPropKeyIn($id, 'id')) {
                    $whereData[] = ['user_id', '=', $id['id']];
                }
            } elseif ($id instanceof Request) {
                $whereData = [
                    ['ip_address', '=', $id->ip()],
                    ['user_agent', '=', $id->userAgent()]
                ];
                if ($id->hasSession()) {
                    $whereData[] = ['session_id', '=', $id->session()->getId()];
                }
            } 
            if (Functions::testVar($whereData)) {
                return self::where($whereData)->first();
            }
        }
        return null;
    }

    static public function existsId($id)
    {
        return Functions::testVar(self::getFromId($id));
    }

    static public function exists($id)
    {
        return self::existsId($id);
    }
}
