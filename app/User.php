<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Http\Request,
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

    static public function getUserArray(Request $request = null)
    {
        $user_array = [
            'name' => '',
            'email' => '',
            'id' => '',
            'agent' => '',
            'ip' => '',
            'role' => ['guest']
        ];
        if (Functions::testVar($request)) {
            if ($request->session()->has('user')) {
                $user = session()->get('user');
                if (Functions::testVar($user)) {
                    return $user;
                }
            } else {
                $user_array['ip'] = $request->ip();
                $user_array['agent'] = $request->userAgent();
            }
        } 
        return $user_array;
    }

    public function setUserArray(Request $request) 
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'id' => $this->id,
            'agent' => $request->userAgent(),
            'ip' => $request->ip(),
            'role' => ['guest']
        ];
        $perm = new Basic($this->id);
        if ($perm->isAdmin()) {
            $data['role'][] = 'admin';
        }
        if ($perm->isContentCreator()) {
            $data['role'][] = 'creator';
        }
        if ($perm->isAuthUser()) {
            $data['role'][] = 'user';
        }
        $request->session()->put('user', $data);
        return $data;
    }

    static public function getIsAdmin()
    {
        if (session()->has('user.role')) {
            $tmp = session()->get('user.role');
            return in_array('admin', $tmp, true);
        }
        return false;
    }

    static public function getIsUser()
    {
        if (session()->has('user.role')) {
            $tmp = session()->get('user.role');
            if (Functions::testVar($tmp)) {
                return in_array('user', $tmp, true);
            }
        }
        return false;
    }

    static public function getFromId(int $id) 
    {
        return self::where('id', $id)->first();
    }

    static public function getFromUserArray() 
    {
        if (session()->has('user.id')) {
            $tmp = intval(session()->get('user.id'));
            if (Functions::testVar($tmp) && $tmp !== 0) {
                return self::where('id', $tmp)->first();
            }
        } else {
            return null;
        }
    }

    static public function testIfUser(
        string $email, string $password, 
        Request $request
    ) {
        $ua = self::getUserArray($request);
        if (Functions::testVar($ua['email']) 
            && $ua['email'] !== $email
        ) {
            return false;
        }
        // these two tests should not fail!
        if (Functions::testVar($ua['agent']) 
            && $ua['agent'] !== $request->userAgent()
        ) {
            return false;
        }
        if (Functions::testVar($ua['ip']) 
            && $ua['ip'] !== $request->ip()
        ) {
            return false;
        }
        $tmpCol = self::where('email', $email)->get();
        if (Functions::testVar($tmpCol) && count($tmpCol) == 1) {
            $tmp = $tmpCol[0];
            if (Hash::check($password, $tmp->password)) {
                $tmp->setUserArray($request);
                return true;
            }
        }
        return false;
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

    static public function createNewFrom(array $array) 
    {
        return self::createNew(
            $array['name'], $array['email'], 
            $array['password'], $array['img'], 
            $array['plan']
        );
    }

    
}
