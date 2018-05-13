<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends MainController {

    public function __construct($name = '', $titleNameSep = '') {
        parent::__construct($name, $titleNameSep);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        //
    }

    /*
     *  USER SIGNIN, SIGNOUT and REGISTRATION:
     */

    public function signup() {
        return parent::getView('content.tests.test2', 'New User Registeration Page');
    }

    public function register(Request $request) {
        return parent::getView('content.tests.test2', 'New User Registeration Successfull!!');
    }

    public static function pagePathSplit(string $str){
        $tmp1 = str_replace( '-', '/', $str );
        return str_replace( '_', '-', $tmp1 );
    }

    public static function pagePathJoin(string $str){
        $tmp1 = str_replace('-', '_', $str);
        return str_replace( '/', '-', $tmp1);
    }

    public function signin(Request $request) {
//        $email = !empty($request->email) ? $request->email : '[blank]';
//        $password = !empty($request->password) ? $request->password : '[empty-string]';
//        $content = [];
//        $content['header'] = 'Welcome Back!';
//        $content['article'] = 'Hello ' . $email .
//                ' !! Your Password is: ' . $password;
        //dd(session()->all());

        // for testing..
        $request->session()->put('user', 'hello');

        // redirection stuff..
        $redirect = '/';
        //// the Laravel Session appears to HATE being 
        //   assigned ANYTHING other than strings!
        //dd(session()->all());
        //self::$data['user']['loggedin'] = true;
        //return parent::getView('content.tests.test2', 'User Logged In Successfull!!', $content);
        if($request->session()->has('redirectPage')){
            $redirect = self::pagePathSplit( $request->session()->pull('redirectPage') ) ;
        }

        return redirect($redirect);
    }

    public function signinRedirect(Request $request){
        // the old @param string $page ... was REMOVED as 
        //  it IS BEING PASSED THROUGH THE Request @param 
        //  ANYWAYS...

        $request->session()->reflash();

        $redirectData = [
            'redirectToken'=> str_random(40),
            'redirectPage' => isset($request->page)?$request->page: '',
        ];

        $request->session()->put($redirectData);

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

        return parent::getView('forms.redirect', self::$data);
    }

    public function signout(Request $request) {
        //dd(session()->all());
        $request->session()->forget('user');
        //dd(session()->all());
        //self::$data['user']['loggedin'] = false;
        //session(['user.loggedin' => false]);
        return redirect('/');
    }

}
