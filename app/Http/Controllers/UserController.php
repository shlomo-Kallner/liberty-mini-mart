<?php

namespace App\Http\Controllers;

use App\User,
    App\Image;
use Illuminate\Http\Request,
    App\Http\Requests\SigninRequest,
    App\Http\Requests\RegisterRequest;
use App\Utilities\Functions\Functions;

class UserController extends MainController 
{
    protected static $redirectVersion = 2; 
    // this has now been 'bumped up' ti switch off showing the user
    // the redirect route..

    public static function getRedVer()
    {
        return self::$redirectVersion;
    }

    public function __construct($name = '', $titleNameSep = '') 
    {
        parent::__construct($name, $titleNameSep);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) 
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) 
    {
        return self::getView('cms.forms.new.user', 'Create a New User');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) 
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) 
    {
        $user = User::getIdFromUserArray();
        if (Functions::testVar($user)) {
            return self::getView('content.user', 'User Profile Page', $user);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request) 
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request) 
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request) 
    {
        //
    }

    /*
     *  USER SIGNIN, SIGNOUT and REGISTRATION:
     */

    public function signup(Request $request) {
        if (User::getIsUser()) {
            return redirect('/');
        } else {
            return parent::getView('forms.register', 'New User Registration Page');
        }
    }

    public function register(RegisterRequest $request) 
    {
        //dd($request);
        $name = $request->lastname . ' , ' . $request->firstname;
        $user = User::createNew(
            $name, $request->email, $request->password, 
            Image::find(1), 1
        );
        //dd($user);
        if (Functions::testVar($user)) {
            $user->setIsAuthUser();
            $user->setUserArray($request);
            parent::addMsg(
                '<h3>New User Registration Successfull!!</h3>'
                . '<p><strong>You Have Successfully Registered Your Account, now just sign in to enter!</strong></p>'
            );
        }
        $request->session()->regenerate();
        return redirect('/');

        /*     
            parent::setAlert(
                'alert-success', 'New User Registration Successfull!!', 
                '<strong>You Have Successfully Registered Your Account, now just sign in to enter!</strong>',
                9000
            );
            return parent::getView('content.index');
        */
    }

    public static function pagePathSplit(string $str)
    {
        $tmp1 = str_replace( '-', '/', $str );
        return str_replace( '_', '-', $tmp1 );
    }

    public static function pagePathJoin(string $str)
    {
        $tmp1 = str_replace('-', '_', $str);
        return str_replace( '/', '-', $tmp1);
    }

    public function signin(SigninRequest $request) 
    {
        //        $email = !empty($request->email) ? $request->email : '[blank]';
        //        $password = !empty($request->password) ? $request->password : '[empty-string]';
        //        $content = [];
        //        $content['header'] = 'Welcome Back!';
        //        $content['article'] = 'Hello ' . $email .
        //                ' !! Your Password is: ' . $password;
        //dd(session()->all());

        // for testing..
        $testing = false;
        $errors = [];
        if ($testing) {
            $ua = User::getUserArray($request);
            $ua['name'] = 'hello';
            $ua['email'] = !empty($request->email) ? $request->email : '';
            $ua['role'][] = 'admin';
            //$ua['role'][] = 'creator';
            $ua['role'][] = 'user';
            $request->session()->put('user', $ua);
        } else {
            /* 
                /// in the REAL PRODUCTION implementation
                ///  will use other User Class methods..
                    $request->session()->put(
                        'user', [
                            'name'=> 'hello',
                            'email' => !empty($request->email) ? $request->email : '[blank]',
                            'is_admin' => 'true',
                        ]
                    );
            */
            if (!User::validateUser($request->email, $request->password, $request)) {
                $errors[] = 'Incorrect User Email & Password Combination!';
            }
        }
        
        
        // redirection stuff..
        //// the Laravel Session appears to HATE being 
        //   assigned ANYTHING other than strings!
        //dd(session()->all());
        //self::$data['user']['loggedin'] = true;
        //return parent::getView('content.tests.test2', 'User Logged In Successfull!!', $content);
        $redirect = '/';
        if ($request->session()->has('redirectPage') && $request->session()->has('redirectToken')) {
            $redirectToken1 = $request->reTok??'';
            //dd(session(), $redirectToken1, $request);
            $redirectToken2 = $request->session()->pull('redirectToken');
            //dd(session()->all(), $redirectToken1, $redirectToken2);
            $redirectPage = $request->session()->pull('redirectPage');
            if ($redirectToken1 === $redirectToken2) {
                $redirect = self::pagePathSplit($redirectPage);
            } 
            /* 
                else {
                //dd($request->session(), $request, $redirectToken1, $redirectToken2);
                /// this was just for some debugging during some testing.. 
                } 
            */
        } 
        $request->session()->regenerate();
        if (Functions::testVar($errors)) {
            return redirect($redirect)->withErrors($errors);
        } else {
            return redirect($redirect);
        }
    }

    public function signinRedirect(Request $request)
    {
        // the old @param string $page ... was REMOVED as 
        //  it IS BEING PASSED THROUGH THE Request @param 
        //  ANYWAYS...
        //dd($request, $request->page);
        // 'redirectPath' is set by the middleware ...
        if ($request->session()->has('redirectPath')) {
            $redirectPage = self::pagePathJoin(
                $request->session()->pull('redirectPath')
            );
            $bol = false;
            if (self::getRedVer() == 1 && !empty($request->page)) {
                if ($request->page == $redirectPage) {
                    $bol = true;
                }
            } else {
                $bol = true;
            }
            if ($bol) {
                
                $request->session()->reflash();

                $redirectData = [
                    'redirectToken' => e(str_random(40)),
                    'redirectPage' => isset($redirectPage)?$redirectPage: '',
                ];
                //$request->session()->pull('redirectPath');

                $request->session()->put($redirectData);

                //$request->session()->regenerate(); /// done by getView()..

                //$request->session()->put('redirectToken', $token );
                //self::$data['page']['redirectToken'] = $token;
                //if(!empty($page) && $page === $request->page){
                //    $request->session()->put('redirectPage', $page );
                    //self::$data['page']['redirectPage'] = $page;
                //}else{
                //    $request->session()->put('redirectPage', $request->page );
                    //self::$data['page']['redirectPage'] = $request->page;
                //}

                // THE redirect view will have to retrieve the $redirect* data 
                //  from the Session directly.. 

                return parent::getView('forms.redirect');
            }
        } 
        /* 
            elseif (!($request->session()->has('redirectPath')) && !empty($request->page)) {
                //dd('hello world..');
            }
         */
        $request->session()->regenerate();
        return redirect('/');
    }

    public function signout(Request $request) 
    {
        //dd(session()->all());
        //$request->session()->forget('user');
        User::resetUserArray($request);
        //dd(session()->all());
        //self::$data['user']['loggedin'] = false;
        //session(['user.loggedin' => false]);
        $request->session()->regenerate();
        return redirect('/');
    }

}
