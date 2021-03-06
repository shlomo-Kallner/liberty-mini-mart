<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Http\Request,
    Session, DB,
    Illuminate\Support\Facades\Hash,
    App\Utilities\Functions\Functions,
    App\Utilities\Permits\Permits,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer;
use App\Utilities\Permits\Basic;
use App\UserImage;
use App\Image, 
    App\Order,
    App\Cart;
use Darryldecode\Cart\Helpers\Helpers, 
    Webpatser\Uuid\Uuid,
    Illuminate\Support\Facades\Log;

class User extends Model implements TransformableContainer
{
    use ContainerTransforms {
        ContainerTransforms::getNamed as private traitGetNamed;
    }
    
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

    public function getRolesArray(
        bool $useBaseMaker = false, bool $p = false
    ) {
        $res = [];
        $version = 2;
        $perm = new Basic($this->id, $p, $version);
        if ($perm->isAdmin()) {
            $res[] = $useBaseMaker
                ? [
                    'value' => 'admin',
                    'children' => null
                ]
                : 'admin';
        }
        if ($perm->isContentCreator()) {
            $res[] = $useBaseMaker
                ? [
                    'value' => 'creator',
                    'children' => null
                ]
                : 'creator';
        }
        if ($perm->isAuthUser()) {
            $res[] = $useBaseMaker 
                ? [
                    'value' => 'user',
                    'children' => null
                ]
                : 'user';
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

    static public function getIdFromUserArray(
        bool $getUser = true, $def = 0, Request $request = null
    ) {
        $session = Functions::testVar($request) && $request->hasSession()
            ? $request->session() : session();
        if ($session->has('user.id')) {
            $tmp = intval($session->get('user.id'));
            if (Functions::testVarOrId($tmp)) {
                return $getUser ? self::getFromId($tmp) : $tmp;
            }
        } 
        return $def;
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
                $data = $tmp->setUserArray($request);
                // Log::info('new user sesssion array: ', $data);
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
        return self::getIdFrom($user, false, 0);
    }

    static public function getNumForVer()
    {
        return 50;
    }

    static public function getAllFromUUID(string $uuid, bool $withTrashed = false)
    {
        return $withTrashed 
            ? self::withTrashed()->where('uuid', $uuid)->get()
            : self::where('uuid', $uuid)->get();
    }

    static public function getFromUUID(string $uuid, bool $withTrashed = false)
    {
        return $withTrashed 
            ? self::withTrashed()->where('uuid', $uuid)->first()
            : self::where('uuid', $uuid)->first();
    }

    static public function genUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        $url = empty($baseUrl) ? 'user/' : $baseUrl . '/user/';
        return $fullUrl ? url($url) : $url;
    }

    public function getUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        return self::genUrlFragment($baseUrl, $fullUrl);
    }

    public function getUrl()
    {
        return $this->uuid;
    }

    /// some ContainerTransforms trait overides..

    static public function getOrderByKey()
    {
        return 'id';
    }

    static public function getNamedByKey()
    {
        return 'email';
    }

    static public function getUrlByKey()
    {
        return 'uuid';
    }

    public function getPubId()
    {
        return $this->uuid;
    }

    /// end of overides..

    

    public function numChildren(bool $withTrashed = true)
    {
        return 2; // to do: add wishlist to this!
    }

    public function getChildren(
        $transform = null, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, $default = [], bool $useBaseMaker = true
    ) {
        $url = $this->getFullUrl($baseUrl, $fullUrl);
        $allOrders = $withTrashed
        ? $this->orders()->withTrashed()
            ->orderBy(Order::getOrderByKey(), $dir)->get()
        : $this->orders()
            ->orderBy(Order::getOrderByKey(), $dir)->get();
        $orders = Order::getSelf(
            $url, $withTrashed, $fullUrl, $allOrders, 
            null, ''
        );
        $allCarts = $withTrashed
        ? $this->carts()->withTrashed()
            ->orderBy(Cart::getOrderByKey(), $dir)->get()
        : $this->carts()
            ->orderBy(Cart::getOrderByKey(), $dir)->get();
        $carts = Cart::getSelf(
            $url, $withTrashed, $fullUrl, $allCarts, 
            null, ''
        );
        // $wishlist = [];   
        return [
            $orders, //'orders' => $this->orders ?? [],
            $carts,//'carts' => $this->carts ?? [],
            // 'wishlist' => [],   
        ];
    }

    public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false, bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        $url = $this->getFullUrl($baseUrl, $fullUrl);
        $img = $this->image->toImageArray();
        $otherImages = Image::getArraysFor($this->images);
        $dates = $this->getDatesArray();
        $children = $this->getChildren(
            self::TO_URL_LIST_TRANSFORM, $withTrashed, 
            $dir, $baseUrl, $useTitle, $fullUrl, 
            $version, [], $useBaseMaker
        );
        $hasChildren = Functions::countHas($children);
        if ($useBaseMaker) {
            /* 
                $content = [
                    'value' => [
                        'id' => $this->uuid,
                        'name' => $this->name,
                        'path' => $url,
                        'email' => $this->email,
                        'url' => $url,
                        'img' => $img,
                        'title' => $this->name,
                        'article' => [],
                        'otherImages' => $otherImages,
                        'dates' => $dates,
                        'hasChildren' => $hasChildren,
                        ],
                    'children' => $children,
                ]; 
            */
            $content = self::makeBaseContentArray(
                $this->name, $url, $img, [], 
                $this->name, $dates, 
                $otherImages,
                $children, $hasChildren, 
                true, ''
            );
            $content['value']['id'] = $this->uuid;
            $content['value']['email'] = $this->email;
        } else {
            $content = [
                // 'id' => $this->id,
                // 'uuid' => $this->uuid,
                'id' => $this->uuid,
                'url' => $url,
                'name' => $this->name,
                'path' => $url,
                'email' => $this->email,
                'img' => $this->image->toImageArray(),
                'otherImages' => Image::getArraysFor($this->images),
                'title' => $this->name,
                'article' => '',
                'date' => [
                    'created' => $this->created_at,
                    'modified' => $this->updated_at,
                    'deleted' => $this->deleted_at
                ],
                'orders' => $this->orders??[],
                'carts' => $this->carts??[],
                'wishlist' => [],
            ];
        }
        if (in_array('admin', explode('/', $baseUrl))) {
            $ur = $this->getRolesArray(false);
            if (Functions::countHas($ur)) {
                if ($useBaseMaker) {
                    $content['value']['roles'] = $ur;
                } else {
                    $content['roles'] = $ur;
                }
            }
        }
        return $content;
    }

    public function orders()
    {
        return $this->hasMany('App\Order', 'user_id', 'id');
    }

    public function carts()
    {
        return $this->hasMany('App\Cart', 'user_id', 'id');
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

    public function reviews()
    {
        return $this->hasMany('App\ProductReview', 'user_id', 'id');
    }

    static public function getChildrenFor(
        $args, string $baseUrl = 'store', $transform = null, 
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, bool $fullUrl = false, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        return $defualt; // more to come!! TO DO!!
    }

    static public function getSelf(
        string $baseUrl = 'store', bool $withTrashed = true,
        bool $fullUrl = false, $children = [], 
        $paginator = null, string $pagingFor = ''
    ) {
        $title = $name = 'Users';
        $article = [];
        $img = Image::createImageArray(
            'computer-1331579_640.png', 'Users Listing', 
            'images/site', 'Users Listing'
        );
        $pagingFor = $pagingFor ?: 'usersPanel';
        return self::makeSelf(
            $name, $title, $article,
            $img, $baseUrl, $withTrashed,
            $fullUrl, $children, $paginator,
            $pagingFor, null
        );
    }

    static public function getOurExtraWhereBy()
    {
        return ['id', '>', self::getNumForVer()];
    }

    static public function getNamed(
        string $name, bool $withTrashed = false, 
        $extraWhereby = null
    ) {
        if (Functions::testVar($extraWhereby)) {
            if (Functions::countHas($extraWhereby)) {
                $where = Functions::arrayableToArray($extraWhereby, []);
            } else {
                $where = [
                    [self::getOrderByKey(), '=', $extraWhereby]
                ];
            }
        } else {
            $where = [];
        }
        $where[] = self::getOurExtraWhereBy();
        return self::traitGetNamed($name, $withTrashed, $where);
    }

    static public function getOrdered(
        string $dir = 'asc', bool $withTrashed = true,
        $orderingBy = null
    ) {
        return self::getOrderedBy(
            $dir, $withTrashed, $orderingBy
        )->where(self::getOurExtraWhereBy())->get();
    }

    static public function getCount(bool $withTrashed = false)
    {
        return $withTrashed 
        ? self::withTrashed()->where(self::getOurExtraWhereBy())->count()
        : self::where(self::getOurExtraWhereBy())->count();
    }

    static public function getUsers(
        int $pageNumber = 1, bool $toArray = true, 
        bool $forA = false, int $paginatorViewNum = 0,
        string $paginatorBaseUrl = '', string $baseUrl = 'user',
        bool $withTrashed = true, bool $fullUrl = false, 
        bool $useBaseMaker = true, int $numPerPage = 3,
        bool $usePagingFor = false
    ) {
        //$numPerPage = 3; // 5;
        $tmp = $withTrashed 
            ? self::withTrashed()->where(self::getOurExtraWhereBy())->get()
            : self::where(self::getOurExtraWhereBy())->get();
        //dd($tmp);
        $res = [];
        if (Functions::testVar($tmp) && Functions::countHas($tmp)) {
            $users = [];
            $numPages = Functions::genRowsPerPage(count($tmp), $numPerPage);
            if ($pageNumber >= 0 && $pageNumber <= $numPages) {
                $pn = $pageNumber > 0 && $pageNumber <= $numPages ? $pageNumber : 1;
                $tu = $tmp->forPage($pn, $numPerPage);
                
                //dd($tmp, $tu);
                foreach ($tu as $user) {
                    // dd($user);
                    if (Functions::testVar($ur = $user->getRolesArray(false))
                        && count($ur) > 0
                    ) {
                        if ($toArray) {
                            $ua = $user->toContentArray(
                                $baseUrl, 1, false, $withTrashed,
                                $fullUrl, $useBaseMaker
                            );
                            if ($forA) {
                                if ($useBaseMaker) {
                                    $ua['value']['roles'] = $ur;
                                } else {
                                    $ua['roles'] = $ur;
                                }
                            }
                            $users[] = $ua;
                            // dd($ua, $ur);
                        } else {
                            if ($forA) {
                                $user->roles = $ur;
                            }
                            $users[] = $user;
                        }
                    }
                    // dd($ur);
                }
                // dd($users);
                $paginator = self::genPagingFor(
                    $pn, count($tmp), $numPerPage,
                    'usersPanel', $paginatorViewNum, 
                    $paginatorBaseUrl
                );
                if ($useBaseMaker) {
                    $img = []; //Image::getImageArray($img)
                    $res = self::makeBaseContentIterArray(
                        'Users', self::genUrlFragment($baseUrl, $fullUrl),
                        $img, [], 'Users', $pageNumber, $numPages, $numPerPage,
                        $users, $paginator, [], [], true, $usePagingFor, 
                        $paginatorViewNum, 'usersPanel'
                    );
                } else {
                    $res['pagination'] = $paginator;
                    $res['items'] = $users;
                }
                //dd($users);
            } elseif ($useBaseMaker) {
                $res['done'] = true;
                $res['value'] = null;
            }
        } elseif ($useBaseMaker) {
            $res['done'] = true;
            $res['value'] = null;
        }
        return $res;
    }

    static public function createNew(
        string $name, string $email, string $password, $img, 
        int $plan = 1, bool $setRememberToken = false, 
        bool $retObj = false
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
            $tmp->uuid = Uuid::generate(5, '/users/' . $email, Uuid::NS_URL);
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
                        return $retObj ? $tmp : $tmp->id;
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
        while ($bl) {
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

    static public function createNewFrom(
        array $array, bool $retObj = false
    ) {
        return self::createNew(
            $array['name'], $array['email'], 
            $array['password'], $array['img'], 
            $array['plan'], 
            isset($array['setRememberToken']) 
                ? $array['setRememberToken']
                : false, 
            $retObj
        );
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
