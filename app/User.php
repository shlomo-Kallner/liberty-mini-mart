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
use Darryldecode\Cart\Helpers\Helpers;

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
            'name' => Functions::getVar($name, ''),
            'email' => Functions::getVar($email, ''),
            'id' => Functions::getVar($id, ''),
            'agent' => Functions::getVar($agent, ''),
            'ip' => Functions::getVar($ip, ''),
            'role' => ['guest']
        ];
    }

    static public function getUserArray(Request $request = null)
    {
        if (Functions::testVar($request)) {
            if ($request->hasSession() && $request->session()->has('user')) {
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
        if ($request->hasSession()) {
            $request->session()->put('user', $data);
        }
        //dd($data);
        return $data;
    }

    static public function resetUserArray(Request $request)
    {
        /// first lets create a new user array, 
        ///  while keeping the userAgent and ip..
        $new_data = self::getNewUserArray( 
            $request->userAgent(), $request->ip()
        );
        
        if ($request->hasSession()) {
            //$request->session()->get('user');
            /// remove the old data..
            $request->session()->forget('user');
            
            /// reseting the user array.. by setting the new data..
            $request->session()->put('user', $new_data);
        }
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
            'img' => $this->image->toImageArray(),
            'otherImages' => Image::getArraysFor($this->images),
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

    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }

    public function images()
    {
        return $this->hasManyThrough(
            'App\Image', 'App\UserImage',
            'user_id', 'id',
            'id', 'image_id'
        );
    }

    static public function getUsers(
        int $pageNumber = 1, bool $toArray = true, 
        bool $forA = false, int $paginatorViewNum = 0,
        string $paginatorBaseUrl = ''
    ) {
        $numPerPage = 3; //5;
        $tmp = self::where('id', '>', self::getNumForVer())->get();
        //dd($tmp);
        $res = [];
        if (Functions::testVar($tmp) && count($tmp) > 0) {
            $users = [];
            $numPages = Functions::genRowsPerPage(count($tmp), $numPerPage);
            $pn = $pageNumber > 0 && $pageNumber <= $numPages ? $pageNumber : 1;
            $tu = $tmp->forPage($pn, $numPerPage);
            foreach ($tu as $user) {
                $p = false ? true : false;
                //$perm = new Basic($user->id, $p);
                //dd($perm);
                // $perm->isAdmin() || $perm->isContentCreator() || $perm->isAuthUser() 
                if (Functions::testVar($ur = $user->getRolesArray())
                    && count($ur) > 0
                ) {
                    if ($toArray) {
                        $tu = $user->toContentArray();
                        if ($forA) {
                            $tu['roles'] = $ur;
                        }
                        $users[] = $tu;
                    } else {
                        if ($forA) {
                            $user->roles = $ur;
                        }
                        $users[] = $user;
                    }
                }
                //dd($perm);
            }
            $res[] = $users;
            $res[] = Page::genPagingFor(
                $pn - 1, count($tmp), $numPerPage,
                'usersPanel', $paginatorViewNum, 
                $paginatorBaseUrl
            );
            //dd($users);
        } 
        return $res;
    }

    static public function createNew(
        string $name, string $email, string $password, $img, 
        int $plan = 1, bool $setRememberToken = false
    ) {
        $tu = self::where('email', $email)->get();
        if (!Functions::testVar($tu) || count($tu) === 0) {
            $tImg = Image::getImageToID($img);
            $tmp = new self;
            $tmp->name = $name;
            $tmp->email = $email;
            $tmp->password = Hash::make($password);
            $tmp->image_id = $tImg;
            $tmp->plan_id = $plan;
            if ($setRememberToken) {
                $tmp->remember_token = self::genRememberToken();
            } else {
                $tmp->remember_token = '';
            }
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

    static public function rememberToken(
        string $token, bool $getUser = true,
        bool $withTrashed = false
    ) {
        $tmp = $withTrashed
            ? self::withTrashed()->where('remember_token', $token)->get()
            : self::where('remember_token', $token)->get();
        if (!$getUser) {
            // checking availability of a given token..
            // (not accounting for the value of $withTrashed...)
            return !Functions::testVar($tmp) || count($tmp) ===  0;
        } else {
            if (!Functions::testVar($tmp) || count($tmp) ===  0) {
                // no user found, so return null.
                return null;
            } elseif (count($tmp) === 1) {
                return $tmp[0];
            } elseif (!$withTrashed) {
                throw new \InvalidArgumentException(
                    'Multiple Users With The Same Remember_Token!'
                );
            } else {
                // $withTrashed is true so 
                // (just a admin looking in the db)
                // return all..
                return $tmp;
            }
        }
    }

    static public function genRememberToken()
    {
        $bl = true;
        while ($bl){
            $trt = str_random(100);
            if (self::rememberToken($trt, false)) {
                $bl = false;
            }
        }
        return $trt;
    }

    public function updateUser(array $args = null) 
    {
        if (Functions::testVar($args)) {
            $didSave = false;
            if (Functions::isPropKeyIn($args, 'name') 
            && is_string($args['name'])
            ) {
                $this->name = $args['name'];
            }
            if (Functions::isPropKeyIn($args, 'email') 
                && is_string($args['email'])
            ) {
                $this->email = $args['email'];
            }
            if (Functions::isPropKeyIn($args, 'password') 
                && is_string($args['password'])
            ) {
                $this->password = Hash::make($args['password']);
            }
            if (Functions::isPropKeyIn($args, 'plan') 
                && is_int($args['plan'])
            ) {
                $this->plan_id = $args['plan'];
            }
            if (Functions::isPropKeyIn($args, 'rememberToken')) {
                if ((is_bool($args['rememberToken'])
                && $args['rememberToken']) 
                || (is_string($args['rememberToken'])
                && $args['rememberToken'] === 'reset')
                ) {
                    $this->remember_token = self::genRememberToken();
                } elseif (is_string($args['rememberToken'])
                && $args['rememberToken'] === 'unset'
                ) {
                    $this->remember_token = '';
                } 
            } 
            if (Functions::isPropKeyIn($args, 'img')) {
                if (is_array($args['img']) 
                && Helpers::isMultiArray($args['img'], true)
                ) {
                    foreach ($args['img'] as $img) {
                        // update otherImages...
                        $ui = UserImage::createNew($this, $img);
                        //return $tmp; return false?
                        // break out of the loop? 
                        // throw an error? silently fail?
                        // ignore?... ignore!
                        if (Functions::testVar($ui) && !$didSave) {
                            $didSave = true;
                        }
                    }
                } else {
                    $this->image_id = Image::getImageToID($args['img']);
                }
                if (Functions::isPropKeyIn($args, 'otherImages')) {
                    if (is_array($args['otherImages'])) {
                        foreach ($args['otherImages'] as $idx => $img) {
                            // update otherImages...
                            if (is_int($idx)) {
                                $ui = UserImage::createNew($this, $img);
                                //return $tmp; return false?
                                // break out of the loop? 
                                // throw an error? silently fail?
                                // ignore?... ignore!
                                if (Functions::testVar($ui) && !$didSave) {
                                    $didSave = true;
                                }
                            } elseif (is_string($idx)) {
                                $ui = UserImage::createNew($this, $args['otherImages']);
                                //return $tmp; return false?
                                // break out of the loop? 
                                // throw an error? silently fail?
                                // ignore?... ignore!
                                if (Functions::testVar($ui) && !$didSave) {
                                    $didSave = true;
                                }
                                break;
                            }
                        }
                    } elseif (is_int($args['otherImages'])) {
                        $ui = UserImage::createNew($this, $args['otherImages']);
                        //return $tmp; return false?
                        // break out of the loop? 
                        // throw an error? silently fail?
                        // ignore?... ignore!
                        if (Functions::testVar($ui) && !$didSave) {
                            $didSave = true;
                        }
                    }
                }
            }
            // update imgs and ..
            return $tmp->save() || $didSave;
        } 
        return null;
    }

    static public function createNewFrom(array $array) 
    {
        return self::createNew(
            $array['name'], $array['email'], 
            $array['password'], $array['img'], 
            $array['plan'], 
            isset($array['setRememberToken']) 
                ? $array['setRememberToken']
                : false
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
