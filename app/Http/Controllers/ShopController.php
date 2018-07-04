<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    App\Page;

class ShopController extends MainController {

    public function __construct($name = '', $titleNameSep = '') {
        parent::__construct($name, $titleNameSep);
    }

    public function categories(Request $request) 
    {
        $request->page = 'store/categories';
        return parent::test2($request);
    }

    public function products() 
    {
        return __METHOD__;
    }

    static public function genProduct()
    {
        $product = [];
        return $product;
    }

    static public function genProductGallery(
        $name, array &$products, 
        array &$cssClasses = [] 
    ) {
        $res = [
            // the gallery's name..
            'title' => $name,
            // the actual products..
            'products' => serialize($products),
        ];

        foreach ($cssClasses as $key => $value) {
            if ($key !== 'title' || $key !== 'products') {
                $res[$key] = $value;
            }
        }
        return $res;
    }

    static public function getNewProducts() 
    {
        $products = [];
        // the gallery's name..
        $name = 'New Arrivals';
        $cssClasses = [
            // some CSS classes ...
            'sizeClass' => 'col-md-12', // some (can be multiple) Bootstrap Column Size classes.
            'owlClass' => 'owl-carousel5', // a [required] Metronic CSS Class name for items-per-view..
            'productClass' => 'sale-product', // some extra Metronic CSS class .. can be blank.
            // others?... 
        ];
        return self::genProductGallery($name, $products, $cssClasses);
    }

    public function index(Request $request)
    {
        //self::$data['sidebar'] = Page::getSidebar($useFakeData);

        //return parent::getView('content.store');
        return self::test($request, false);
    }

    public function test(Request $request, bool $useFakeData = true)
    {
        
        //self::$data['sidebar'] = Page::getSidebar($useFakeData);
        $breadcrumbs = Page::getBreadcrumbs(
            Page::genBreadcrumb('Store','store')
        );
        $title = 'test Store page';
        $content = [
            'article' => [
                'header' => 'Welcome To Our Store!',
                'subheading' => 'Here you will find a wealth of products that only LIBERTY can PROVIDE!',
                //'article' => self::getLoremIpsum(),
            ]
        ];
        return parent::getView('content.store', $title, $content, $useFakeData, $breadcrumbs);
    }

    public function checkout(Request $request) 
    {
        $useFakeData = true;
        self::$data['sidebar'] = Page::getSidebar($useFakeData);
        self::$data['breadcrumbs'] = [
            'links' => [
                [
                    'name' => '',
                    'url' => '',
                ],
            ],
            'current'=> [
                'name' => 'Store',
                'url' => 'store',
            ],
        ];
        $title = 'test Cart page';
        $content = [
            'article' => [
                'header' => 'This is Our CART!',
                'subheading' => 'Here you will find a wealth of products that only LIBERTY can PROVIDE!',
                'article' => serialize($request->json()),
            ]
        ];
        return parent::getView('forms.checkout', $title, $content, $useFakeData);
    }

}
