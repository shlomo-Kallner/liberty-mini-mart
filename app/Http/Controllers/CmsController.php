<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController,
    App\Page,
    App\User,
    App\Section,
    App\Categorie,
    App\Product;

class CmsController extends MainController 
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        parent::__construct($name, $titleNameSep);
        /* 
            $this->middleware(
                function ($request, $next) {
                    if (User::getIsAdmin()) {
                        return $next($request);
                    } else {
                        $request->session()->reflash();
                        
                        $request->session()->flash('redirectFullUrl', $request->fullUrl());
                        $request->session()->flash('redirectPath', $request->path());
                        
                        return redirect('signin/' . UserController::pagePathJoin($request->path()));
                    }
                }
            );
        */
    }

    public function index(Request $request)
    {
        //$sections = Section::getAllWithPagination();
        //dd(Page::get()->count());
        $sections = Section::getAllModels();
        foreach ($sections as $section) {
            //$section['categories'] = Categorie::getCategoriesOfSectionWithPagination($section['id'], ... );
            $section['categories'] = Categorie::getCategoriesOfSection($section['id']);
            //dd($section);
        }
        $users = [];
        $pages = [];
        //dd($sections);
        return self::getView(
            'content.cms', 'Admin Dashboard', 
            [
                'article' => [
                    'title' => 'Welcome to OUR DASHBOARD!',
                    'subheading' => 'Here you can add, remove or edit Sections, Categories, Products and Other Content on this site!'
                ],
                'sections' => [
                    'items' => $sections,
                    'pagination' => ''
                ],
                'users'=> [
                    'items' => $users,
                    'pagination' => ''
                ],
                'pages'=> [
                    'items' => $pages,
                    'pagination' => ''
                ],
                /*
                    ''=> [
                        'items' => $->toArray(),
                        'pagination' => ''
                    ],
                */
            ], false, 
            Page::getBreadcrumbs(
                Page::genBreadcrumb('Admin DashBoard', $request->path()),
                Page::genBreadcrumb('Home', '/')
            )
        );
    }



}
