<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Session,
    App\Utilities\Functions\Functions;

class User extends Model
{
    //

    static public function getUserArray()
    {
        $user_array = [];
        if (session()->has('user')) {
            $user = session()->get('user');
            if (Functions::testVar($user)) {
                if (is_array($user)) {
                    foreach ($user as $key => $value) {
                        $user_array[$key] = Functions::purifyContent($value);
                    }
                }
            }
            
        } else {
            $user_array = [
                'name' => '',
                'email' => '',
                'id' => '',
                'agent' => '',
                'ip' => '',
            ];
        }
        return $user_array;
    }

    static public function getUserIsAdmin()
    {
        return session()->has('is_admin') ? true : false;
    }
}
