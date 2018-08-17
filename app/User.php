<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Http\Request,
    Session, DB,
    Illuminate\Support\Facades\Hash,
    App\Utilities\Functions\Functions,
    App\Utilities\Permits\Permits;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Utilities\Permits\Basic;
use App\UserImage;
use App\Image;

class User extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    static public function getNewUserArray(
        string $agent, string $ip,
        string $name = '', string $email = '', string $id = ''
    ) {
        return [
            'name' => Functions::testVar($name) ? $name : '',
            'email' => Functions::testVar($email) ? $email : '',
            'id' => Functions::testVar($id) ? $id : '',
            'agent' => Functions::testVar($agent) ? $agent : '',
            'ip' => Functions::testVar($ip) ? $ip : '',
            'role' => ['guest']
        ];
    }

    static public function getUserArray(Request $request = null)
    {
        if (Functions::testVar($request)) {
            if ($request->session()->has('user')) {
                $user = $request->session()->get('user');
                if (Functions::testVar($user)) {
                    return $user;
                }
            } else {
                return self::getNewUserArray(
                    $request->userAgent(), $request->ip()
                );
            }
        } elseif (session()->has('user')) {
            $user = session()->get('user');
            if (Functions::testVar($user)) {
                return $user;
            }
        } else {
            return self::getNewUserArray('', '');
        }
    }

    public function getRolesArray(Basic $permit = null, bool $p = false)
    {
        $res = [];
        if (Functions::testVar($permit)) {
            $perm = $permit;
        } else {
            $perm = new Basic($this->id, $p);
        }
        if ($perm->isAdmin()) {
            $res[] = 'admin';
        }
        if ($perm->isContentCreator()) {
            $res[] = 'creator';
        }
        if ($perm->isAuthUser()) {
            $res[] = 'user';
        }
        return $res;
    }

    public function setUserArray(Request $request) 
    {
        $data = self::getNewUserArray( 
            $request->userAgent(), $request->ip(),
            $this->name, $this->email, (string)$this->id
        );
        $data['role'] = $this->getRolesArray();
        $request->session()->put('user', $data);
        //dd($data);
        return $data;
    }

    static public function resetUserArray(Request $request)
    {
        //$request->session()->get('user');
        /// remove the old data..
        $request->session()->forget('user');
        /// create a new user array, 
        ///  while keeping the userAgent and ip..
        $new_data = self::getNewUserArray( 
            $request->userAgent(), $request->ip()
        );
        /// reseting the user array..
        $request->session()->put('user', $new_data);
        /// and returning the new array..
        return $new_data;
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

    static public function getIdFromUserArray(bool $getUser = true) 
    {
        if (session()->has('user.id')) {
            $tmp = intval(session()->get('user.id'));
            if (Functions::testVar($tmp) && $tmp !== 0) {
                if ($getUser) {
                    return self::getFromId($tmp);
                } else {
                    return $tmp;
                }
            }
        } else {
            return null;
        }
    }

    static public function validateUser(
        string $email, string $password, 
        Request $request
    ) {
        $tmpCol = self::where('email', $email)->get();
        if (Functions::testVar($tmpCol) && count($tmpCol) === 1) {
            $tmp = $tmpCol[0];
            if (Hash::check($password, $tmp->password)) {
                //dd($tmp);
                $tmp->setUserArray($request);
                return true;
            }
        }
        return false;
    }

    /**
     * Function testIfUser - TO BE USED FOR CHECKOUT PAGE USER VALIDATION!
     *
     * @param string $email
     * @param string $password
     * @param Request $request
     * @return void
     */
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
        return self::validateUser($email, $password, $request);
    }

    
    static public function getUserId($user)
    {
        $user_id = null;
        if ($user instanceof self) {
            $user_id = $user->id;
        } elseif (is_array($user)) {
            $user_id = $user['id'];
        } elseif (is_int($user) && self::existsId($user)) {
            $user_id = $user;
        }
        return $user_id;
    }

    static public function getNumForVer()
    {
        return 50;
    }

    public function toContentArray() 
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'img' => Image::getImageArray($this->image_id),
            'otherImages' => UserImage::getAllImages($this->id, true),
            'date' => [
                'created' => $this->created_at,
                'modified' => $this->updated_at,
                'deleted' => $this->deleted_at
            ],
            'orders' => [],
            'carts' => [],
            'wishlist' => [],
        ];
    }

    public function orders()
    {
        return $this->hasMany('App\Order', 'user_id');
    }

    public function carts()
    {
        //return $this->hasMany('App\Cart', 'user_id');
    }

    static public function getAllUsers(
        bool $toArray = true, bool $forA = false, 
        bool $paginate = false, int $num_pages = 0
    ) {
        $tmp = self::where('id', '>', self::getNumForVer())->get();
        //dd($tmp);
        $users = [];
        if (Functions::testVar($tmp) && count($tmp) > 0) {
            foreach ($tmp as $user) {
                $p = false ? true : false;
                $perm = new Basic($user->id, $p);
                //dd($perm);
                if ($perm->isAdmin() 
                    || $perm->isContentCreator()
                    || $perm->isAuthUser() 
                ) {
                    $ur = $user->getRolesArray($perm, $p);
                    if ($toArray) {
                        $tu = $user->toContentArray();
                        $tu['roles'] = $ur;
                        $users[] = $tu;
                    } else {
                        $user->roles = $ur;
                        $users[] = $user;
                    }

                }
                //dd($perm);
            }
            //dd($users);
        } 
        return $users;
    }

    static public function createNew(
        string $name, string $email, string $password, $img, 
        int $plan = 1
    ) {
        $tu = self::where('email', $email)->get();
        if (count($tu) === 0) {
            $tImg = Image::getImageToID($img);
            $tmp = new self;
            $tmp->name = $name;
            $tmp->email = $email;
            $tmp->password = Hash::make($password);
            $tmp->image_id = $tImg;
            $tmp->plan_id = $plan;
            if ($tmp->save()) {
                $perm = new Basic($tmp->id);
                if (Functions::testVar($perm)) {
                    $perm->makeFakes(1, false, 1);
                    $ui = UserImage::createNewFrom($tmp);
                    if (Functions::testVar($ui)) {
                        return $tmp;
                    }
                }
            }
        }
        return null;
    }

    static public function createNewFrom(array $array) 
    {
        return self::createNew(
            $array['name'], $array['email'], 
            $array['password'], $array['img'], 
            $array['plan']
        );
    }

    static public function getFromId(int $id) 
    {
        return self::where('id', $id)->first();
    }

    static public function existsId(int $id)
    {
        return Functions::testVar(self::getFromId($id));
    }

    public function setIsAdmin(bool $regen = false)
    {
        $perm = new Basic($this->id);
        $of1 = random_int(1, 9);
        $perm->makeFakes(false ? $of1 : 1, false, 1);
        $perm->setAdmin();
        $of2 = random_int(1, 9);
        $perm->makeFakes(false ? $of2 : 1, $regen, 1);
    }

    public function setIsAuthUser(bool $regen = false)
    {
        $perm = new Basic($this->id);
        $of1 = random_int(1, 9);
        $perm->makeFakes(false ? $of1 : 1, false, 1);
        $perm->setAuthUser();
        $of2 = random_int(1, 9);
        $perm->makeFakes(false ? $of2 : 1, $regen, 1);
    }
    
}
