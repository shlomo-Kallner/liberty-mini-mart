<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    Illuminate\Support\Facades\Session;
use App\Http\Controllers\UserController,
    App\Utilities\Functions\Functions,
    App\Page,
    App\User,
    App\Section,
    App\Categorie,
    App\Article,
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
        //dd($request->session());
        //dd($request->session()->getId());
        //$sections = Section::getAllWithPagination();
        //dd(Page::get()->count());
        $sections = Section::getAllModels(true);
        foreach ($sections as $section) {
            //$section['categories'] = Categorie::getCategoriesOfSectionWithPagination($section['id'], ... );
            $section['categories'] = Categorie::getCategoriesOfSection($section['id']);
            //dd($section);
        }
        //dd($sections);
        
        if ($request->has('pageNum') && $request->has('pagingFor')) {
            $pf = $request->input('pagingFor');
            if ($pf === 'usersPanel') {
                $userPn = $request->input('pageNum');
            } else {
                $userPn = 1;
            }
        } else {
            $userPn = 1;
        }
        
        
        $users = User::getUsers($userPn, true, true);
        //dd($users); // []; // 
        $pages = Page::getAllPages();
        //dd($pages);
        $articles = []; // Article::getAll();
        $sidebar = self::getAdminSidebar();
        //dd($sidebar);
        return self::getView(
            'content.cms', 'Admin Dashboard', 
            [
                'header' => 'Admin Dashboard',
                'article' => Article::makeContentArray(
                    '', 
                    'Welcome to OUR DASHBOARD!',
                    null,
                    'Here you can add, remove or edit Sections, Categories, Products and Other Content on this site!'
                    )
                /* [
                    'header' => 'Welcome to OUR DASHBOARD!',
                    'subheading' => 'Here you can add, remove or edit Sections, Categories, Products and Other Content on this site!'
                ] */
                ,
                'sections' => [
                    'items' => $sections,
                    'pagination' => ''
                ],
                'users'=> [
                    'items' => $users[0],
                    'pagination' => $users[1]
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
            ), null,
            $sidebar
        );
    }

    static public function getAdminSidebar()
    {
        $sidebar = [];
        /*
            //TODO: use this
            $sidebar[] = Page::genURLMenuItem(
                string $url, string $name, string $icon = '', 
                string $textTransform = '', string $cssExtraClasses = '', 
                string $iconAfter = '', string $role = ''
            ); 

            /// for each of the "create" Links below..

        */

        $sidebar[] = Page::genURLMenuItem(
            'admin/section/create', 'Create a New Section', 'fa-shopping-cart', 
            '', '', 'fa-plus', 'button'
        );  
        $sidebar[] = Page::genURLMenuItem(
            'admin/category/create', 'Create a New Category', 'fa-shopping-basket', 
            '', '', 'fa-plus', 'button'
        );  
        $sidebar[] = Page::genURLMenuItem(
            'admin/product/create', 'Create a New Product', 'fa-shopping-bag', 
            '', '', 'fa-plus', 'button'
        );  
        $sidebar[] = Page::genURLMenuItem(
            'admin/user/create', 'Create a New User', 'fa-address-book', 
            '', '', 'fa-plus', 'button'
        );  
        $sidebar[] = Page::genURLMenuItem(
            'admin/page/create', 'Create a New Content Page', 'fa-newspaper-o', 
            '', '', 'fa-plus', 'button'
        );  
        return $sidebar;
    }


}
