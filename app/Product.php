<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model {

    static public function getAllProducts($curl) {
        $products = DB::table('products')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->select('products.*')
                ->where('categories.url', '=', $curl)
                ->get()
                ->toArray();
        return $products;
    }

}
