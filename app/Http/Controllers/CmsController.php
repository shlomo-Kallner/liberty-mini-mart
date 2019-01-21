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
use HRTime\StopWatch, HRTime\Unit;
use Illuminate\Support\Carbon;

class CmsController extends MainController 
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        parent::__construct($name, $titleNameSep);
    }

    public function index(Request $request)
    {
        $sw = new StopWatch;
        $debug = [false, 1];
        if ($debug[0] && $debug[1] === 0) {
            $sw->start();
        }
        //dd($request->session());
        //dd($request->session()->getId());
        //$sections = Section::getAllWithPagination();
        //dd(Page::get()->count());
        $sectNumShown = 3;
        $sectPageNum = 1;
        $sectPagingFor = 'sectionPanel';
        $sectDir = 'asc';
        $sectBaseUrl = 'admin/store';
        $sectViewNum = 0;
        $sectWithTrashed = true;
        if (false) {
            $sections = Section::getAllWithPagination(
                Section::TO_CONTENT_ARRAY_TRANSFORM, 
                $sectPageNum, $sectNumShown, 
                $sectPagingFor, $sectDir, 
                $sectWithTrashed, $sectBaseUrl,
                $request->path(), $sectViewNum, 
                false, true, 1
            );
        } elseif (false) {
            $sections = Section::getSelfWithPagination(
                $sectPageNum, $sectNumShown, $sectViewNum, 
                $sectBaseUrl, $sectBaseUrl, 
                true, $sectWithTrashed, true, [], $sectDir, 
                $sectPagingFor, true, 1
            );
        } else {
            $sections = [
                'items' => [],
                'pagination' => [],
            ];
        }
        if ($debug[0]) {
            if ($debug[1] === 0) {
                $sw->stop();
                dd($sections, $sw->getLastElapsedTime(Unit::SECOND));
            } elseif ($debug[1] === 1) {
                $sw->start();
            }
        }
        if (Functions::testVar($pv = Page::getPagingVars($request, 'usersPanel'))) {
            $userPn = $pv['pageNum'];
            $userVn = $pv['viewNum'];
        } else {
            $userPn = 1;
            $userVn = 0;
        }
        $userBaseUrl = '';
        if (false) {    
            $users = User::getUsers(
                $userPn, true, true, $userVn, $request->path(), 
                $userBaseUrl, true, false
            );
        } else {
            $users = [
                'items' => [],
                'pagination' => [],
            ];
        }
        if ($debug[0]) {
            if ($debug[1] === 1) {
                $sw->stop();
                dd($users, $sw->getLastElapsedTime(Unit::SECOND)); 
            } elseif ($debug[1] === 2) {
                $sw->start();
            }
        }
        $pagesDir = 'asc';
        $pagesPaging = 'pagesPanel';
        if (Functions::testVar($pv = Page::getPagingVars($request, $pagesPaging))) {
            $pagesPn = $pv['pageNum'];
            $pagesVn = $pv['viewNum'];
        } else {
            $pagesPn = 1;
            $pagesVn = 0;
        }
        $pagesNumShown = 3;
        $usePageGroupings = true;
        if (false) {  
            $pages = Page::getAllPages(
                true, $pagesDir, $usePageGroupings, $request->path(),
                $pagesPaging, $pagesVn, $pagesPn, $pagesNumShown
            );
        } else {
            $pages = [
                'items' => [],
                'pagination' => [],
            ];
        }
        //dd($pages);
        $articles = Article::getSelfWithPagination(
            int $pageNumber, int $numPerPage = 4,  int $numView = 0, 
            string $baseUrl = 'store', string $listUrl = '', 
            bool $fullUrl = false,
            bool $withTrashed = true, bool $useBaseMaker = true,
            $default = [], string $dir = 'asc', 
            string $pagingFor = '', bool $useTitle = true, 
            int $version = 1
        );
        $sidebar = self::getAdminSidebar();
        //dd($sidebar);
        if ($debug[0]) {
            if ($debug[1] === 2) {
                $sw->stop();
            }
            $num = 0;
            foreach ($sections['items'] as $value) {
                $num += count($value['categories']);
            }
            $ticks = $sw->getLastElapsedTime(Unit::SECOND);
            dd($ticks, count($sections['items']), $num, count($users['items']), count($pages['items']));
        }
        $admin_header = 'Admin Dashboard';
        $admin_article = Article::makeArticleArray(
            '', 
            'Welcome to OUR DASHBOARD!',
            null,
            'Here you can add, remove or edit Sections, Categories, Products and Other Content on this site!'
        );
        if (true) {
            $content = [
                'header' => $admin_header,
                'article' => $admin_article,
                'sections' => $sections,
                'users'=> $users,
                'pages'=> $pages,
                /*
                    ''=> [
                        'items' => $->toArray(),
                        'pagination' => ''
                    ],
                */
            ];
        } else {
            $children = [
                $sections, $users, $pages,
            ];
            $dates = [
                'created' => Carbon::now(),
                'updated' => Carbon::now(),
                'deleted' => null,
            ];
            if (Functions::testVar($paging = Page::getPagingVars($request, '', 8))) {
                $pagingPn = $paging['pageNum'];
                $pagingVn = $paging['viewNum'];
                $pagingLimit = $paging['limit'];
            } else {
                $pagingPn = 1;
                $pagingVn = 0;
                $pagingLimit = 8;
            }
            $paginator = Page::genPagination2(
                $pagingPn, $pagingLimit, count($children), 4, '', 
                $pagingVn, $request->path()
            );
            $content = Page::makeBaseContentIterArray(
                $admin_header, $request->path(), $img, $admin_article, 
                $admin_header, $pagingPn, $paginator['totalNumPages'], 
                $pagingLimit, $children, 
                $paginator, $dates, 
                null, Functions::countHas($children),
                false, 0, ''
            );
        }
        return self::getView(
            $request, 'content.cms', $admin_header, 
            $content, false, 
            Page::getBreadcrumbs(
                Page::genBreadcrumb($admin_header, $request->path()),
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
