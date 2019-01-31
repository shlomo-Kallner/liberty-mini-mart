<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request,
    App\Article,
    App\Product,
    App\Section,
    App\Categorie,
    App\Page;
use \App\Utilities\Functions\Functions;

class ShopController extends MainController {

    public function __construct($name = '', $titleNameSep = '') {
        parent::__construct($name, $titleNameSep);
    }

    /* 
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
    */

    public function index(Request $request)
    {
        //self::$data['sidebar'] = Page::getSidebar($useFakeData);

        //return parent::getView('content.store');
        if (false) {

        } else {
            return self::test($request, false);
        }
    }

    public function getStore(Request $request) 
    {
        return __METHOD__;
    }

    public function test(Request $request, bool $useFakeData = true)
    {
        
        //self::$data['sidebar'] = Page::getSidebar($useFakeData);
        $breadcrumbs = Page::getBreadcrumbs(
            Page::genBreadcrumb('Store', 'store')
        );
        $title = 'test Store page';
        /* 
            $newProducts = [];
            foreach (Product::getRandomSample(12) as $np) {
                $newProducts[] = $np->toMini('store');
            } 
            $bestsellers = [];
            foreach (Product::getRandomSample(3) as $bs) {
                $bestsellers[] = $bs->toSidebar('store');
            } 
            $sections = [];
            foreach (Section::getAllModels(false, false) as $sect) {
                $sections[] = $sect->toMini('store');
            }
        */
        $tmpData = [
            'PageNum' => 0,
            'NumShown' => 12,
            'PagingFor' => 'productsPanel',
            'Dir' => 'asc',
            'WithTrashed' => Functions::isAdminPath($request->path()),
            'BaseUrl' => Functions::isAdminPath($request->path()) ? 'admin/store' : 'store',
            'ViewNum' => 0,
            'UseBaseMaker' => $request->ajax(),
            'Default' => [],
            'UseTitle' => true,
            'FullUrl' => !$request->ajax(),
            'ListUrl' => $request->path(),
            'UseGetSelf' => false,
            'Transform' => Section::TO_MINI_TRANSFORM,
            'Version' => 1,
        ];
        $content = [
            'article' => Article::makeArticleArray(
                self::getLoremIpsum(),
                'Welcome To Our Store!',
                2,
                'Here you will find a wealth of products that only LIBERTY can PROVIDE!',
                true
            ),
            'newProducts' => Product::getNewProducts(),
            'bestsellers' => Product::getBestsellers(),
            'sections' => Section::getAllWithTransform(
                $tmpData['Transform'], $tmpData['Dir'], 
                $tmpData['WithTrashed'], $tmpData['BaseUrl'], 
                $tmpData['UseTitle'], $tmpData['FullUrl'], 
                $tmpData['Version'], $tmpData['Default'], 
                $tmpData['UseBaseMaker']
            ),
        ];
        return parent::getView($request, 'content.store', $title, $content, $useFakeData, $breadcrumbs);
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
        return parent::getView($request, 'forms.checkout', $title, $content, $useFakeData);
    }

}
