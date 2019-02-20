<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    Illuminate\Support\Facades\Session,
    Illuminate\Support\Carbon, 
    App\Http\Controllers\UserController,
    App\Utilities\Functions\Functions,
    App\Page,
    App\User,
    App\Section,
    App\Categorie,
    App\Article,
    App\Product,
    App\Order,
    App\PageGroup,
    HRTime\StopWatch, 
    HRTime\Unit;

class CmsController extends MainController 
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
    {
        parent::__construct($name, $titleNameSep);
    }

    public function index(Request $request)
    {
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
        $productData = [
            'PageNum' => 1,
            'NumShown' => 12,
            'PagingFor' => 'productsPanel',
            'Dir' => 'asc',
            'WithTrashed' => true,
            'BaseUrl' => 'admin/store',
            'ViewNum' => 0,
            'UseBaseMaker' => true,
            'Default' => [],
            'FullUrl' => !$useVue,
            'ListUrl' => $request->path(),
        ];
        $ordersData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'ordersPanel',
            'Dir' => 'asc',
            'WithTrashed' => true,// Functions::isAdminPath($request->path()),
            'BaseUrl' => 'admin',// Functions::isAdminPath($request->path()) ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => true,// $request->ajax(),
            'Default' => [],
            'UseTitle' => true,
            'FullUrl' => !$useVue,// !$request->ajax(),
            'ListUrl' => $request->path(),
        ];
        $catData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'categoriesPanel',
            'Dir' => 'asc',
            'WithTrashed' => true,// Functions::isAdminPath($request->path()),
            'BaseUrl' => 'admin/store',// Functions::isAdminPath($request->path()) ? 'admin' : '',
            'ViewNum' => 0,
            'UseBaseMaker' => true,// $request->ajax(),
            'Default' => [],
            'UseTitle' => true,
            'FullUrl' => !$useVue,// !$request->ajax(),
            'ListUrl' => $request->path(),
        ];

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
        $products = Product::getSelf(
            $productData['BaseUrl'], $productData['WithTrashed'],
            $productData['FullUrl'], [], 
            null, $usePagingFor ? $productData['PagingFor'] : ''
        );
        $orders = Order::getSelf(
            $ordersData['BaseUrl'], $ordersData['WithTrashed'],
            $ordersData['FullUrl'], [], 
            null, $usePagingFor ? $ordersData['PagingFor'] : ''
        );
        $categories = Categorie::getSelf(
            $catData['BaseUrl'], $catData['WithTrashed'],
            $catData['FullUrl'] = false, [], 
            null, $usePagingFor ? $catData['PagingFor'] : ''
        );
        $sidebar = self::getAdminSidebar();
        $admin_header = self::getAdminHeader();
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
                'categories' => $categories,
                /*
                    ''=> [
                        'items' => $->toArray(),
                        'pagination' => ''
                    ],
                */
            ];
        } elseif ($useVue) {
            $children = [
                $sections, $users, $pages, $categories,
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
                            string $url ['value']['url'], 
                            '', 0, ''
                        ),
                    */
                    Section::makeMini(
                        $sections['value']['img']['img'], 
                        $sections['value']['name'], 
                        $sections['value']['url'], 
                        '', 0, ''
                    ),
                    // User::makeMini(
                    //     $users['value']['img']['img'], 
                    //     $users['value']['name'], 
                    //     $users['value']['url'], 
                    //     '', 0, ''
                    // ),
                    Page::makeMini(
                        $pages['value']['img']['img'], 
                        $pages['value']['name'], 
                        $pages['value']['url'], 
                        '', 0, ''
                    ),
                    Article::makeMini(
                        $articles['value']['img']['img'], 
                        $articles['value']['name'], 
                        $articles['value']['url'], 
                        '', 0, ''
                    ),
                    PageGroup::makeMini(
                        $menus['value']['img']['img'], 
                        $menus['value']['name'], 
                        $menus['value']['url'], 
                        '', 0, ''
                    ),
                    Product::makeMini(
                        $products['value']['img']['img'], 
                        $products['value']['name'], 
                        $products['value']['url'], 
                        '', 0, ''
                    ),
                    Order::makeMini(
                        $orders['value']['img']['img'], 
                        $orders['value']['name'], 
                        $orders['value']['url'], 
                        '', 0, ''
                    ),
                    Categorie::makeMini(
                        $categories['value']['img']['img'], 
                        $categories['value']['name'], 
                        $categories['value']['url'], 
                        '', 0, ''
                    ),
                ]
            ];
        }
        return self::getView(
            $request, 'content.cms', $admin_header, 
            $content, false, 
            Page::getBreadcrumbs(
                self::getAdminBreadcrumb($request->path()),
                self::getHomeBreadcumb()
            ), null,
            $sidebar
        );
    }

    static public function getAdminHeader()
    {
        return 'Admin Dashboard';
    }

    static public function getAdminBreadcrumb(string $path = '')
    {
        return Page::genBreadcrumb(
            self::getAdminHeader(), 
            !empty($path) ? $path : 'admin'
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
        $iconAfter = false ? 'fa-plus' : '';
        $useIcons = false;
        $sidebar[] = Page::genURLMenuItem(
            'admin/store/section/create', 'Create a New Section', 
            $useIcons ? 'fa-shopping-cart' : '', 
            '', '', $iconAfter, 'button'
        );  
        $sidebar[] = Page::genURLMenuItem(
            'admin/store/category/create', 'Create a New Category', 
            $useIcons ? 'fa-shopping-basket' : '', 
            '', '', $iconAfter, 'button'
        );  
        $sidebar[] = Page::genURLMenuItem(
            'admin/store/product/create', 'Create a New Product', 
            $useIcons ? 'fa-shopping-bag' : '', 
            '', '', $iconAfter, 'button'
        );  
        // $sidebar[] = Page::genURLMenuItem(
        //     'admin/user/create', 'Create a New User', 
        //     $useIcons ? 'fa-address-book' : '', 
        //     '', '', $iconAfter, 'button'
        // );  
        $sidebar[] = Page::genURLMenuItem(
            'admin/page/create', 'Create a New Content Page', 
            $useIcons ? 'fa-newspaper-o' : '', 
            '', '', $iconAfter, 'button'
        );  
        $sidebar[] = Page::genURLMenuItem(
            'admin/menus/create', 'Create a New Navigation Menu', 
            $useIcons ? 'fa-bars' : '', 
            '', '', $iconAfter, 'button'
        );  
        return $sidebar;
    }


}
