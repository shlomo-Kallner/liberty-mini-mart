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
        $sectNumShown = 4;
        $sectPageNum = 1;
        $sectPagingFor = 'sectionPanel';
        $sectDir = 'asc';
        $sectBaseUrl = 'store';
        $sectViewNum = 0;
        $sections = Section::getAllWithPagination(
            true, $sectPageNum, $sectNumShown, 
            $sectPagingFor, $sectDir, true, $sectBaseUrl, 
            $request->path(), $sectViewNum, 
            true, 1
        );
        //dd($sections);
        if (Functions::testVar($pv = Page::getPagingVars($request, 'usersPanel'))) {
            $userPn = $pv['pageNum'];
            $userVn = $pv['viewNum'];
        } else {
            $userPn = 1;
            $userVn = 0;
        }
        $users = User::getUsers($userPn, true, true, $userVn, $request->path());
        //dd($users); 

        $pagesDir = 'asc';
        $pagesPn = 1;
        $pagesVn = 0;
        $pagesNumShown = 4;
        $usePageGroupings = true;
        $pages = Page::getAllPages(
            true, $pagesDir, $usePageGroupings, $request->path(),
            'pagesPanel', $pagesVn, $pagesPn, $pagesNumShown
        );
        //dd($pages);
        $articles = []; // Article::getAll();
        $sidebar = self::getAdminSidebar();
        //dd($sidebar);
        return self::getView(
            $request, 'content.cms', 'Admin Dashboard', 
            [
                'header' => 'Admin Dashboard',
                'article' => Article::makeContentArray(
                    '', 
                    'Welcome to OUR DASHBOARD!',
                    null,
                    'Here you can add, remove or edit Sections, Categories, Products and Other Content on this site!'
                ),
                'sections' => $sections,
                'users'=> $users,
                'pages'=> $pages,
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
