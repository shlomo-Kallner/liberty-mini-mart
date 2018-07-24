<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Session, DB,
    Illuminate\Support\Facades\Hash,
    App\Utilities\Functions\Functions,
    App\Utilities\Permits\Permits;

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

    static public function getUserFromId($id) 
    {

    }

    static public function testIfUser($email, $password, array $extra = null)
    {

    }

    
    static public function getUserId($user)
    {
        $user_id = null;
        if ($user instanceof self) {
            $user_id = $user->id;
        } elseif (is_array($user)) {
            $user_id = $user['id'];
        } 
        return $user_id;
    }

    
}
