<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Session, DB,
    Illuminate\Support\Facades\Hash,
    App\Utilities\Functions\Functions,
    App\Utilities\Permits\Permits;
use App\Utilities\Permits\Basic;
use App\UserImage;
use App\Image;

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

    static public function getUserFromId(int $id) 
    {
        return self::where('id', $id)->first();
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

    static public function createNew(
        string $name, string $email, string $password, array $img, 
        int $plan = 1
    ) {
        if (self::where('email', $email)::count() !== 0) {
            return null;
        } else {

            $tmp = new self;
            $tmp->name = $name;
            $tmp->email = $email ;
            $tmp->password = Hash::make($password);
            $tmp->image = Image::createNewFromArray($img);
            $tmp->plan_id = $plan;
            $tmp->save();
            $perm = new Basic($tmp->id);
            $perm->addPermit('***', 9);
            $perm->addPermit('@@@', 8);
            $perm->addPermit('+++', 7);
            $perm->addPermit('&&&', 6);
            $perm->addPermit('!!!', 9);
            $perm->addPermit('###', 8);
            $perm->addPermit('$$$', 7);
            $perm->addPermitRegen('%%%', 6);
            UserImage::createNewFromUser($tmp);
            return $tmp;
        }
    }

    
}
