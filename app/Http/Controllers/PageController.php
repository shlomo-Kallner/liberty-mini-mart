<?php

namespace App\Http\Controllers;

use App\Page;
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
    public function index() 
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() 
    {
        return self::getView('cms.forms.new.page', 'Create a New Content Page');
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
                'content.content', $page_info['title'], $page_info['content'],
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

    public function home(Request $request) 
    {
        // for now...
        return $this->test3($request);
    }

    /// UTILITY METHODS:
    /// Testing Functions Start:

    public function index1(Request $request) {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        $title = 'test ' . $requestedPage . ' page';
        $content = [
            'header' => "<b>$requestedPage</b>",
            'article' => "<p><i>HEllloo WORLD!!</i></p>"
        ];

        return self::getView('content.tests.index2', $title, $content);
    }

    public function test(Request $request) {
        $requestedPage = !empty($request->page) ? $request->page : 'index';
        $title = 'test ' . $requestedPage . ' page';
        $content = [
            'header' => "<b>$requestedPage</b>",
            'subheading' => "<p>'Ring It Again/Buy U.S. Gov&apos;t Bonds/Third Liberty Loan'</p>",
            'article'=> "<p>" . e('World War I-era poster depicts colonial-era celebratory crowd in front of Independence Hall in Philadelphia, PA. Large Liberty Bell used as decorative element. Published by Sackett & Wilhelms Corp, N.Y., ca. 1917- ca. 1919') . "</p>",
            'img' => "images/site/ring_it_liberty_bell.jpg",
            'imgAlt' => 'Ring It Again/Buy U.S. Gov&apos;t Bonds/Third Liberty Loan',
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
        return self::getView('content.tests.test2', $title, $content);
    }

}
