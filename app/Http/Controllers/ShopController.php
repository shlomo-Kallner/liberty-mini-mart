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

}
