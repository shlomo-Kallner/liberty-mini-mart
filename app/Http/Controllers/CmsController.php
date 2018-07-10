<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController;

class CmsController extends MainController 
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        parent::__construct($name, $titleNameSep);
        $this->middleware(
            function ($request, $next) {
                if ($request->session()->has('user.is_admin')) {
                    return $next($request);
                } else {
                    $request->session()->reflash();
                    
                    $request->session()->flash('redirectFullUrl', $request->fullUrl());
                    $request->session()->flash('redirectPath', $request->path());
                    
                    return redirect('signin/' . UserController::pagePathJoin($request->path()));
                }
            }
        );
    }

    public function index(Request $request)
    {
        return self::getView('content.cms');
    }


}
