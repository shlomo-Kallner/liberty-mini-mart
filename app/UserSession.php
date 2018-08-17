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
        $payload = null
    ) {
        if (Functions::testVar($user_id = User::getUserId($user))) {
            $tmp = self::where(
                [
                    ['session_id', '=', $session_id],
                    ['user_id', '=', $user_id],
                    ['ip_address', '=', $ip],
                    ['user_agent', '=', $userAgent]
                ]
            )->get();
            if (!Functions::testVar($tmp) || count($tmp) === 0) {
                
            }
        }
        return null;
    }

    static public function createNewFrom(Request $request)
    {
        $tmp = User::getUserArray($request);
        return self::createNew(
            $request->session()->getId(),
            Functions::testVar($tmp['id']??'') ? $tmp['id'] :'',
            $request->ip(),
            $request->userAgent(),
            $request->session()->all()
        );
    }
}
