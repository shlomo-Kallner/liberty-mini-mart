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

    public function signin(Request $request) {
//        $email = !empty($request->email) ? $request->email : '[blank]';
//        $password = !empty($request->password) ? $request->password : '[empty-string]';
//        $content = [];
//        $content['header'] = 'Welcome Back!';
//        $content['article'] = 'Hello ' . $email .
//                ' !! Your Password is: ' . $password;
        //dd(session()->all());
        $request->session()->put('user', 'hello');
        //// the Laravel Session appears to HATE being 
        //   assigned ANYTHING other than strings!
        //dd(session()->all());
        //self::$data['user']['loggedin'] = true;
        //return parent::getView('content.tests.test2', 'User Logged In Successfull!!', $content);
        return redirect('/');
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
