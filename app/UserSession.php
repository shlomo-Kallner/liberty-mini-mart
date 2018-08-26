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
        $user_id = Functions::getVar(User::getUserId($user), 0);
        $tmp = self::where(
            [
                ['session_id', '=', $session_id],
                ['user_id', '=', $user_id],
                ['ip_address', '=', $ip],
                ['user_agent', '=', $userAgent]
            ]
        )->get();
        if (!Functions::testVar($tmp) || count($tmp) === 0) {
            $data = new self;
            $data->session_id = $session_id;
            $data->user_id = $user_id;
            $data->ip_address = $ip;
            $data->user_agent = $userAgent;
            $data->payload  = serialize($payload);
            $data->last_activity = $lastActivity;
            if ($data->save()) {
                return $data->id;
            }
        }
        return null;
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
        if ($this->payload != serialize($payload)) {
            $this->payload  = serialize($payload);
        }
        if ($this->last_activity != $lastActivity) {
            $this->last_activity = $lastActivity;
        }
        return $this->save();
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
        } elseif (is_array($id)) {
            return self::where(
                [
                    ['ip_address', '=', $id['ip']],
                    ['user_agent', '=', $id['agent']]
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
