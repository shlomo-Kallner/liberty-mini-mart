<?php

namespace App\Http\Controllers;

use App\Page,
    App\Product,
    App\Article;
use Illuminate\Http\Request,
    App\Utilities\Functions\Functions;

class PageController extends MainController 
{
    
    public function __construct($name = '', $titleNameSep = ' | ') 
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
        
        $pages = Page::getAllWithPagination();
        if ($request->ajax()) {
            
            $pagesDir = 'asc';
            $pagesPaging = 'pagesPanel';
            if (Functions::testVar($pv = Page::getPagingVars($request, $pagesPaging))) {
                $pagesPn = $pv['pageNum'];
                $pagesVn = $pv['viewNum'];
                if (Functions::hasPropKeyIn($pv, 'limit')) {
                    $pagesNumShown = $pv['limit'];
                }
            } else {
                $pagesPn = 1;
                $pagesVn = 0;
                $pagesNumShown = 3;
            }
            $usePageGroupings = true;
            $pages = Page::getAllPages(
                true, $pagesDir, $usePageGroupings, $request->path(),
                $pagesPaging, $pagesVn, $pagesPn, $pagesNumShown
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) 
    {
        return self::getView($request, 'cms.forms.new.page', 'Create a New Content Page');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $page) 
    {
        $page_info = Page::getNamedPage($page, $request->path());
        if (Functions::testVar($page_info) && $page_info['visible'] > 0) {
            // WISHLIST ITEM: a more sophisticated user role based
            //  Visibility / Access Control Method..
            return self::getView(
                $request, 'content.content', $page_info['title'], $page_info['content'],
                false, $page_info['breadcrumbs']
            );
        } else {
            abort(404);
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $page) 
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $pages
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $pages) 
    {
        //
    }

    public function showDelete(Request $request) 
    {
        $page = Page::getNamedPage($request->page, $request->path, true);
        if (Functions::testVar($page)) {
            return self::getView(
                $request, 'cms.forms.delete.page', 
                'Are You Sure That You Want To Delete This Page?',
                ['data' => $page],
                false,
                Page::getBreadcrumbs(
                    Page::genBreadcrumb('Delete Page', $request->path()),
                    Page::genBreadcrumb('Admin Dashboard', 'admin')
                ) 
            );
        } else {
            abort(404);
        }
    }

    public function home(Request $request) 
    {
        //dd('hello');
        // for now...
        if (true) {
            //dd('hello');
        
            //$title = 'test ' . $requestedPage . ' page';
            $content = [
                'article'=> Article::makeContentArray(
                    e("<p> World War I-era poster depicts colonial-era celebratory crowd in front of Independence Hall in Philadelphia, PA. Large Liberty Bell used as decorative element. Published by Sackett & Wilhelms Corp, N.Y., ca. 1917- ca. 1919 </p>"),
                    e("<b>Home</b>"), (2),
                    e("<p>Ring It Again/Buy U.S. Gov&apos;t Bonds/Third Liberty Loan</p>"),
                    true
                ),
                'newProducts' => Product::getNewProducts(12, 'New Arrivals'),
                'sampleProducts' => Product::getNewProducts(3, 'Three Sample Items'),
                'bestsellers' => Product::getBestsellers(),
                'filters' => [], // search filters are a WISH-LIST ITEM!!!
                'pricings' => [], // membership plan pricings are a WISH-LIST ITEM!!
            ];
            //dd('hello', 3);
        
            // $useFakeData = false;
            //self::$data['sidebar'] = Page::getSidebar($useFakeData);
            //dd($request->path());
            $breadcrumbs = Page::getBreadcrumbs(
                Page::genBreadcrumb('Home', '/')
            );

            //return $this->getTemplateView($title, $content);
            //dd($request->session()->all());
            //dd(session()->all());
            return self::getView(
                $request, 'content.index', 'Home', 
                $content, false, $breadcrumbs
            );
        } else {
            return $this->test3($request);
        }

    }

    /// UTILITY METHODS:
    /// Testing Functions Start:

    public function index1(Request $request) 
    {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        $title = 'test ' . $requestedPage . ' page';
        $content = [
            'header' => "<b>$requestedPage</b>",
            'article' => [
                'header' => "<p><i>HEllloo WORLD!!</i></p>"
                ]
        ];

        return self::getView($request, 'content.tests.index2', $title, $content);
    }

    public function test(Request $request) 
    {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        $title = 'test ' . $requestedPage . ' page';
        $content = [
            'header' => "<b>$requestedPage</b>",
            'article' => Article::makeContentArray(
                "<p>" . e('World War I-era poster depicts colonial-era celebratory crowd in front of Independence Hall in Philadelphia, PA. Large Liberty Bell used as decorative element. Published by Sackett & Wilhelms Corp, N.Y., ca. 1917- ca. 1919') . "</p>",
                '', 2, "<p>'Ring It Again/Buy U.S. Gov&apos;t Bonds/Third Liberty Loan'</p>"
            ),
            'breadcrumbs' => ''
        ];

        //return $this->getTemplateView($title, $content);
        //dd($request->session()->all());
        //dd(session()->all());
        self::$data['new_products'] = [
            'products' => [],
            'currency' => 'fa-usd',
        ];
        self::$data['pricing'] = [];
        self::$data['sidebar'] = [
            'sidebar' => [],
            'filters' => [
                'name' => '', // <= this the title/heading text of the filter!
                'filter' => '', // <= this is the HTML of the filter!
            ],
            'bestsellers' => [
                [
                    'url' => '',
                    'img' => '',
                    'alt' => '',
                ],
            ],
        ];
        return self::getView($request, 'content.tests.test2', $title, $content);
    }

}
