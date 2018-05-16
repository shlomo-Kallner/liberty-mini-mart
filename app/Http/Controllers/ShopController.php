<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopController extends MainController {

    public function __construct($name = '', $titleNameSep = '') {
        parent::__construct($name, $titleNameSep);
    }

    public function categories(Request $request) {
        $request->page = 'store/categories';
        return parent::test2($request);
    }

    public function products() {
        return __METHOD__;
    }

    static public function genProduct(){
        $product = [];
        return $product;
    }

    static public function genProductGallery($name, array &$products, 
        array &$cssClasses = [] )
    {
        $res = [
            // the gallery's name..
            'title' => $name,
            // the actual products..
            'products' => serialize($products),
        ];

        foreach($cssClasses as $key => $value){
            if($key !== 'title' || $key !== 'products')
            {
                $res[$key] = $value;
            }
        }
        return $res;
    }

    static public function getNewProducts() {
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
        return self::genProductGallery($name,$products,$cssClasses);
    }

    public function index(Request $request){

        return parent::getView('content.store');
    }

}
