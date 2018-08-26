<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    App\Article,
    App\Product,
    App\Section,
    App\Categorie,
    App\Page;

class ShopController extends MainController {

    public function __construct($name = '', $titleNameSep = '') {
        parent::__construct($name, $titleNameSep);
    }

    /* public function categories(Request $request) 
    {
        $request->page = 'store/categories';
        return parent::test2($request);
    }

    public function products() 
    {
        return __METHOD__;
    } */

    static public function genProduct()
    {
        $product = [];
        return $product;
    }

    static public function genProductGallery(
        $name, array &$products, 
        //array &$cssClasses = [],
        string $url = 'store', string $sizeClass = 'col-md-12',
        string $owlClass = 'owl-carousel5', 
        string $productClass = 'sale-product'
    ) {
        $res = [
            // the gallery's name..
            'title' => $name,
            // the actual products..
            'products' => serialize($products),
            // some CSS classes ...
            'sizeClass' => $sizeClass, // some (can be multiple) Bootstrap Column Size classes.
            'owlClass' => $owlClass, // a [required] Metronic CSS Class name for items-per-view..
            'productClass' => $productClass, // some extra Metronic CSS class .. can be blank.
            // others?... 
            // 'containerClasses' => '' / ...
        ];

        /* foreach ($cssClasses as $key => $value) {
            if ($key !== 'title' || $key !== 'products') {
                $res[$key] = $value;
            }
        } */
        return $res;
    }

    static public function getNewProducts(
        string $name = 'New Arrivals', string $url = 'store', 
        string $sizeClass = 'col-md-12',
        string $owlClass = 'owl-carousel5', 
        string $productClass = 'sale-product'
    ) {
        $products = [];
        if (!Fuunctions::testVar($products)) {
            foreach (Product::getRandomSample(12) as $np) {
                $products[] = $np->toMini($url);
            }
        }
        // the gallery's name..
        //$name = 'New Arrivals';
        /*  $cssClasses = [
                // the gallery's name..
                'title' => $name,
                // the actual products..
                'products' => serialize($products2),
                // some CSS classes ...
                'sizeClass' => $sizeClass, // some (can be multiple) Bootstrap Column Size classes.
                'owlClass' => $owlClass, // a [required] Metronic CSS Class name for items-per-view..
                'productClass' => $productClass, // some extra Metronic CSS class .. can be blank.
                // others?... 
            ]; 
            return self::genProductGallery($name, $products2, $cssClasses);
        */
        return self::genProductGallery(
            $name, $products, $url, $sizeClass, $owlClass, $productClass
        );
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
        /* $newProducts = [];
        foreach (Product::getRandomSample(12) as $np) {
            $newProducts[] = $np->toMini('store');
        } */
        $bestsellers = [];
        foreach (Product::getRandomSample(3) as $bs) {
            $bestsellers[] = $bs->toSidebar('store');
        }
        $sections = [];
        foreach (Section::getAllModels(false, false) as $sect) {
            $sections[] = $sect->toMini('store');
        }
        $content = [
            'article' => Article::makeContentArray(
                self::getLoremIpsum(),
                'Welcome To Our Store!',
                2,
                'Here you will find a wealth of products that only LIBERTY can PROVIDE!',
                true
            ),
            'newProducts' => $newProducts ?? self::getNewProducts(),
            'bestsellers' => $bestsellers,
            'sections' => $sections,
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
