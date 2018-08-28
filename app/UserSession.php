<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Http\Request;
use App\Utilities\Functions\Functions;
use App\User;

class UserSession extends Model
{
    static public function createNew(
        string $session_id, $user, string $ip, string $userAgent,
        $payload = null, int $lastActivity = 1
    ) {
        if (Functions::testVar($session_id) 
            && Functions::testVar($ip)
            && Functions::testVar($userAgent)
        ) {
            $user_id = Functions::getVar(User::getUserId($user), 0);
            $tmp = self::where(
                [
                    ['session_id', '=', $session_id],
                    ['user_id', '=', $user_id],
                    ['ip_address', '=', $ip],
                    ['user_agent', '=', $userAgent]
                ]
            )->orWhere(
                [
                    ['user_id', '=', $user_id],
                    ['ip_address', '=', $ip],
                    ['user_agent', '=', $userAgent]
                ]
            )->orWhere(
                [
                    ['ip_address', '=', $ip],
                    ['user_agent', '=', $userAgent]
                ]
            )->get();
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
        $payload = null, int $lastActivity = 2
    ) {
        if ($this->session_id != $session_id) {
            $this->session_id = $session_id;
        }
        $user_id = Functions::getVar(User::getUserId($user), 0);
        //dd($user_id);
        if ($this->user_id != $user_id) {
            $this->user_id = $user_id;
        }
        if ($this->ip_address != $ip) {
            $this->ip_address = $ip;
        }
        if ($this->user_agent != $userAgent) {
            $this->user_agent = $userAgent;
        }
        $this->updatePayload($payload);
        if ($this->last_activity != $lastActivity) {
            $this->last_activity = $lastActivity;
        }
        return $this->save();
    }

    protected function updatePayload($payload = null)
    {
        if (Functions::testVar($payload)) {
            $tmp1 = is_string($payload) ? unserialize($payload) : $payload;
            if (self::acceptablePayloadType($tmp1)) {
                $tmp2 = unserialize(base64_decode($this->payload));
                foreach ($tmp1 as $key => $val) {
                    Functions::setPropKey($tmp2, $key, $val);
                }
                $this->payload = base64_encode(serialize($tmp2));
            }
        }
    }

    public function getPayload()
    {
        return unserialize(base64_decode($this->payload));
    }

    static public function createNewFrom(Request $request)
    {
        $tmp = User::getUserArray($request);
        return self::createNew(
            $request->session()->getId(),
            Functions::getVar($tmp['id']??'', 0),
            $request->ip(),
            $request->userAgent(),
            $request->session()->all()
        );
    }

    static public function getFromId($id) 
    {
        if (is_int($id)) {
            return self::where('id', $id)->first();
        } elseif (is_string($id)) {
            return self::where('session_id', $id)
                ->first();
        } elseif (is_array($id) && Functions::testVar($id['ip'])
            && Functions::testVar($id['agent'])
        ) {
            return self::where(
                [
                    ['ip_address', '=', $id['ip']],
                    ['user_agent', '=', $id['agent']]
                ]
            )->first();
        } elseif ($id instanceof Request) {
            return self::where(
                [
                    ['ip_address', '=', $id->ip()],
                    ['user_agent', '=', $id->userAgent()]
                ]
            )->orWhere(
                [
                    ['session_id', '=', $id->session()->getId()],
                    ['ip_address', '=', $id->ip()],
                    ['user_agent', '=', $id->userAgent()]
                ]
            )->first();
        } else {
            return null;
        }
    }

    static public function existsId($id)
    {
        return Functions::testVar(self::getFromId($id));
    }
}
