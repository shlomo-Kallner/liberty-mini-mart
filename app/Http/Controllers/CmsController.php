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
    App\Product,
    App\PageGroup;
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
        $debug = [
            false, // do debug..
            1, // which to debug.. [0 -> Section, 1 -> User, 2 -> ]
        ];
        if ($debug[0] && $debug[1] === 0) {
            $sw->start();
        }
        $useGetSelf = false;
        $useGetAll = false;
        $useOld = false;
        $useVue = false;
        $usePagingFor = false;
        $sectData = [
            'PageNum' => 1,
            'NumShown' => 3,
            'PagingFor' => 'sectionPanel',
            'Dir' => 'asc',
            'WithTrashed' => true,
            'BaseUrl' => 'admin/store',
            'ViewNum' => 0,
            'UseBaseMaker' => true,
            'Default' => [],
            'FullUrl' => !$useVue,
            'ListUrl' => $request->path(),
        ];
        $userData = [
            'PageNum' => 1,
            'NumShown' => 3,
            'PagingFor' => 'usersPanel',
            'Dir' => 'asc',
            'WithTrashed' => true,
            'BaseUrl' => 'admin',
            'ViewNum' => 0,
            'UseBaseMaker' => true,
            'Default' => [],
            'FullUrl' => !$useVue,
            'ListUrl' => $request->path(),
        ];
        $pagesData = [
            'PageNum' => 1,
            'NumShown' => 3,
            'PagingFor' => 'pagesPanel',
            'Dir' => 'asc',
            'WithTrashed' => true,
            'BaseUrl' => 'admin',
            'ViewNum' => 0,
            'UseBaseMaker' => true,
            'Default' => [],
            'FullUrl' => !$useVue,
            'ListUrl' => $request->path(),
        ];
        $articlesData = [
            'PageNum' => 1,
            'NumShown' => 4,
            'PagingFor' => 'articlesPanel',
            'Dir' => 'asc',
            'WithTrashed' => true,
            'BaseUrl' => 'admin',
            'ViewNum' => 0,
            'UseBaseMaker' => true,
            'Default' => [],
            'FullUrl' => !$useVue,
            'ListUrl' => $request->path(),
        ];
        $menusData = [
            'PageNum' => 1,
            'NumShown' => 4,
            'PagingFor' => 'menusPanel',
            'Dir' => 'asc',
            'WithTrashed' => true,
            'BaseUrl' => 'admin',
            'ViewNum' => 0,
            'UseBaseMaker' => true,
            'Default' => [],
            'FullUrl' => !$useVue,
            'ListUrl' => $request->path(),
        ];
        if ($useOld) {
            if ($useGetAll) {
                $sections = Section::getAllWithPagination(
                    Section::TO_CONTENT_ARRAY_TRANSFORM, 
                    $sectData['PageNum'], $sectData['NumShown'], 
                    $sectData['PagingFor'], $sectData['Dir'], 
                    $sectData['WithTrashed'], $sectData['BaseUrl'],
                    $request->path(), $sectData['ViewNum'], 
                    true, true, 1, [], true
                );
            } elseif ($useGetSelf) {
                $sections = Section::getSelfWithPagination(
                    $sectData['PageNum'], $sectData['NumShown'], 
                    $sectData['ViewNum'], $sectData['BaseUrl'], 
                    $sectData['BaseUrl'], true, 
                    $sectData['WithTrashed'], true, [], 
                    $sectData['Dir'], $sectData['PagingFor'], 
                    true, 1
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
            $pv = User::getPagingVars(
                $request, $userData['PagingFor'], $userData['NumShown'],
                $userData['Dir']
            );
            if (Functions::testVar($pv)) {
                $userData['PageNum'] = $pv['pageNum'];
                $userData['ViewNum'] = $pv['viewNum'];
                $userData['NumShown'] = $pv['limit'];
                $userData['Dir'] = $pv['order'];
            }
            if ($useGetAll) {   
                $users = User::getUsers(
                    $userData['PageNum'], true, true, 
                    $userData['ViewNum'], $request->path(), 
                    $userData['BaseUrl'], $userData['WithTrashed'], 
                    false, $userData['UseBaseMaker'], 
                    $userData['NumShown'], !empty($userData['PagingFor'])
                );
            } elseif ($useGetSelf) {
                $users = User::getSelfWithPagination(
                    $userData['PageNum'], $userData['NumShown'],  
                    $userData['ViewNum'], 
                    $userData['BaseUrl'], $request->path(), 
                    true, $userData['WithTrashed'], 
                    $userData['UseBaseMaker'], $userData['Default'], 
                    $userData['Dir'], $userData['PagingFor'], 
                    true, 1
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
            $pv = Page::getPagingVars(
                $request, $pagesData['PagingFor'], $pagesData['NumShown'],
                $pagesData['Dir']
            );
            if (Functions::testVar($pv)) {
                $pagesData['PageNum'] = $pv['pageNum'];
                $pagesData['ViewNum'] = $pv['viewNum'];
            } 
            $usePageGroupings = true;
            if ($useGetAll) {  
                $pages = Page::getAllPages(
                    true, $pagesData['Dir'], $usePageGroupings, 
                    $request->path(), $pagesData['PagingFor'], 
                    $pagesData['ViewNum'], $pagesData['PageNum'], 
                    $pagesData['NumShown']
                );
            } else {
                $pages = [
                    'items' => [],
                    'pagination' => [],
                ];
            }
            //dd($pages);
            if ($debug[0]) {
                if ($debug[1] === 2) {
                    $sw->stop();
                    dd($pages, $sw->getLastElapsedTime(Unit::SECOND)); 
                } elseif ($debug[1] === 3) {
                    $sw->start();
                }
            }
            if ($useGetSelf) {
                $articles = Article::getSelfWithPagination(
                    $articlesData['PageNum'], $articlesData['NumShown'],  
                    $articlesData['ViewNum'], $articlesData['BaseUrl'], 
                    $articlesData['ListUrl'], $articlesData['FullUrl'],
                    $articlesData['WithTrashed'], $articlesData['UseBaseMaker'],
                    $articlesData['Default'], $articlesData['Dir'], 
                    $articlesData['PagingFor'], true, 1
                );
            } else {
                $articles = [
                    'items' => [],
                    'pagination' => [],
                ];
            }
            //dd($sidebar);
            if ($debug[0]) {
                if ($debug[1] === 3) {
                    $sw->stop();
                }
                $num = 0;
                foreach ($sections['items'] as $value) {
                    $num += count($value['categories']);
                }
                $ticks = $sw->getLastElapsedTime(Unit::SECOND);
                dd($ticks, count($sections['items']), $num, count($users['items']), count($pages['items']));
            }
        } else {
            /*
                ::getSelf(
                    string $baseUrl = 'store', bool $withTrashed = true,
                    bool $fullUrl = false, $children = [], 
                    $paginator = null, string $pagingFor = ''
                );
            */
            $sections = Section::getSelf(
                $sectData['BaseUrl'], $sectData['WithTrashed'],
                $sectData['FullUrl'], [], 
                null, $usePagingFor ? $sectData['PagingFor'] : ''
            );
            $users = User::getSelf(
                $userData['BaseUrl'], $userData['WithTrashed'],
                $userData['FullUrl'], [], 
                null, $usePagingFor ? $userData['PagingFor'] : ''
            );
            $pages = Page::getSelf(
                $pagesData['BaseUrl'], $pagesData['WithTrashed'],
                $pagesData['FullUrl'], [], 
                null, $pagesData ? $pagesData['PagingFor'] : ''
            );
            $articles = Article::getSelf(
                $articlesData['BaseUrl'], $articlesData['WithTrashed'],
                $articlesData['FullUrl'], [], 
                null, $usePagingFor ? $articlesData['PagingFor'] : ''
            );
            $menus = PageGroup::getSelf(
                $menusData['BaseUrl'], $menusData['WithTrashed'],
                $menusData['FullUrl'], [], 
                null, $usePagingFor ? $menusData['PagingFor'] : ''
            );
        }
        $sidebar = self::getAdminSidebar();
        $admin_header = 'Admin Dashboard';
        $admin_article = Article::makeArticleArray(
            '', 
            'Welcome to OUR DASHBOARD!',
            null,
            'Here you can add, remove or edit Sections, Categories, Products and Other Content on this site!'
        );
        if ($useOld) {
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
        } elseif ($useVue) {
            $children = [
                $sections, $users, $pages,
            ];
            $dates = [
                'created' => Carbon::now(),
                'updated' => Carbon::now(),
                'deleted' => null,
            ];
            $pagingLimit = 8;
            $pagingDir = 'asc';
            $pagingPn = 1;
            $pagingVn = 0;
            $paging = Page::getPagingVars($request, '', $pagingLimit, $pagingDir);
            if (Functions::testVar($paging)) {
                $pagingPn = $paging['pageNum'];
                $pagingVn = $paging['viewNum'];
                $pagingLimit = $paging['limit'];
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
        } else {
            $content = [
                'items' => [
                    /*
                        ::makeMini(
                            string $img ['value']['img']['img'], 
                            string $name ['value']['name'], 
                            string $url ['value']['url'], '', 0, ''
                        ),
                    */
                    Section::makeMini(
                        $sections['value']['img']['img'], $sections['value']['name'], 
                        $sections['value']['url'], '', 0, ''
                    ),
                    User::makeMini(
                        $users['value']['img']['img'], $users['value']['name'], 
                        $users['value']['url'], '', 0, ''
                    ),
                    Page::makeMini(
                        $pages['value']['img']['img'], $pages['value']['name'], 
                        $pages['value']['url'], '', 0, ''
                    ),
                    Article::makeMini(
                        $articles['value']['img']['img'], $articles['value']['name'], 
                        $articles['value']['url'], '', 0, ''
                    ),
                    PageGroup::makeMini(
                        $menus['value']['img']['img'], $menus['value']['name'], 
                        $menus['value']['url'], '', 0, ''
                    ),
                ]
            ];
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
            'admin/store/section/create', 'Create a New Section', 'fa-shopping-cart', 
            '', '', 'fa-plus', 'button'
        );  
        $sidebar[] = Page::genURLMenuItem(
            'admin/store/category/create', 'Create a New Category', 'fa-shopping-basket', 
            '', '', 'fa-plus', 'button'
        );  
        $sidebar[] = Page::genURLMenuItem(
            'admin/store/product/create', 'Create a New Product', 'fa-shopping-bag', 
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
        $sidebar[] = Page::genURLMenuItem(
            'admin/menus/create', 'Create a New Navigation Menu', 'fa-bars', 
            '', '', 'fa-plus', 'button'
        );  
        return $sidebar;
    }


}
