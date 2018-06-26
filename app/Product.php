<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model {

    static public function getAllProducts($curl) 
    {
        $products = DB::table('products')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->select('products.*')
                ->where('categories.url', '=', $curl)
                ->get()
                ->toArray();
        return $products;
    }

    static public function getProductsForCategory($category_id, $transform, $curl) 
    {
        $res = [];
        $products = static::where('category_id', $category_id)->get();
        foreach ($products as $product) {
            if (is_string($transform)) {
                switch ($transform) {
                    case 'mini':
                        $res[] = static::toMini($product, $curl);
                        break;
                    case 'full':
                        $res[] = static::toFull($product, $curl);
                        break;
                    case 'sidebar':
                        $res[] = static::toSidebar($product, $curl);
                        break;
                    //$res[] = ($product);
                }
            }
        }
        return $res;
    }

    static public function toSidebar($product, $curl)
    {
        return [
            'url' => $curl . '/product/' . $product->url,
            'img' => $product->image,
            'alt' => $product->title,
            'price' => $product->sale != ''? $product->sale : $product->price,
        ];
    }

    static public function toMini($product, $curl)
    {
        return [
            'img' => $product->image,
            'name' => $product->title,
            'id' => $product->id,
            'url' => $curl . '/product/' . $product->url,
            'price' => $product->sale != ''? $product->sale : $product->price,
            'sticker' => $product->sticker,
        ];
    }

    static public function toFull($product, $curl)
    {
        return [
            'productImage' => $product->image,
            'productImageAlt' => $product->title,
            'productOtherImages' => [], // a wishList Item!
            'productTitle' => $product->title,
            'productPrice' => $product->price,
            'productSalePrice' => $product->sale,
            'productAvailability' => '', // a wishList Item!
            'productShortDescription' => $product->description,
            'productLongDescription' => '', // a wishList Item!
            'productRating' => '', // a wishList Item!
            'productOptions' => [], // a wishList Item!
            'productReviews' => [], // a wishList Item!
            'productAdditionalInfo' => [], // a wishList Item!
            'productSticker' => $product->sticker,
            'productID' => $product->id,
            'productURL' => $curl . '/product/' . $product->url,
        ];
    }

}
