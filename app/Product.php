<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions,
    App\Image,
    App\ProductImage,
    App\Categorie;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model 
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    static public function createNew(
        string $name, string $url, float $price, 
        float $sale, int $category_id, string $sticker,
        $image, string $description,
        string $title, string $article
    ) {
        $tmp = self::where(
            [
                ['name', '=', $name],
                ['category_id', '=', $category_id],
                ['url', '=', $url]
            ]
        )->get();
        if ((!Functions::testVar($tmp) || count($tmp) === 0)
            && Categorie::existsId($category_id)
        ) {
            if (is_int($img) && Image::existsId($img)) {
                $tImg = $img;
            } elseif (is_array($img)) {
                $tImg = Image::createNewFrom($img);
            } elseif ($img instanceof Image) {
                $tImg = $img->id;
            } else {
                $tImg = null;
            }
            if (Functions::testVar($tImg)) {
                $data = new self;
                $data->name = $data;
                $data->image = $tImg;
                $data->title = Functions::purifyContent($title);
                $data->article = Functions::purifyContent($article);
                $data->url = $url;
                $data->category_id = $category_id;
                $data->price = $price;
                $data->sale = $sale;
                $data->sticker = $sticker;
                $data->description = Functions::purifyContent($description);
                if ($data->save()) {
                    $pi = ProductImage::createNewFrom($data);
                    if (Functions::testVar($pi)) {
                        return $data->id;
                    }
                }
            }
        }
        return null;
    }

    static public function createNewFrom(array $array)
    {
        return self::createNew(
            $array['name'], $array['url'], $array['price'], 
            $array['sale'], $array['category_id'], $array['sticker'], 
            $array['image'], $array['description'], 
            $array['title'], $array['article']
        );
    }

    static public function getFromId(int $id)
    {
        return self::where('id', $id)->first();
    }

    static public function existsId(int $id)
    {
        return Functions::testVar(self::getFromId($id));
    }

    static public function getAllProducts($surl, $curl, bool $toArray = true) 
    {
        $products = self::join(
            'categories', 'categories.id', '=', 'products.category_id'
        )
            ->join('sections', 'sections.id', '=', 'categories.section_id')
            ->select('products.*')
            ->where(
                [
                    ['categories.url', '=', $curl],
                    ['sections.url', '=', $surl]
                ]
            )
            ->get();
        if ($toArray) {
            return $products->toArray(); 
        } 
        return $products;
    }

    static public function getProductsForCategory($category_id, $transform, $curl) 
    {
        $res = [];
        $products = self::where('category_id', $category_id)->get();
        foreach ($products as $product) {
            if (is_string($transform)) {
                switch ($transform) {
                    case 'mini':
                        $res[] = $product->toMini($curl);
                        break;
                    case 'full':
                        $res[] = $product->toFull($curl);
                        break;
                    case 'sidebar':
                        $res[] = $product->toSidebar($curl);
                        break;
                    //$res[] = ($product);
                }
            }
        }
        return $res;
    }

    public function toSidebar($curl)
    {
        return [
            'url' => $curl . '/product/' . $this->url,
            'img' => $this->image,
            'alt' => $this->title,
            'price' => $this->sale != ''? $this->sale : $this->price,
        ];
    }

    public function toMini($curl)
    {
        return [
            'img' => $this->image,
            'name' => $this->title,
            'id' => $this->id,
            'url' => $curl . '/product/' . $this->url,
            'price' => $this->sale != ''? $this->sale : $this->price,
            'sticker' => $this->sticker,
        ];
    }

    public function toFull($curl)
    {
        return [
            'productImage' => $this->image,
            'productImageAlt' => $this->title,
            'productOtherImages' => [], // a wishList Item!
            'productTitle' => $this->title,
            'productPrice' => $this->price,
            'productSalePrice' => $this->sale,
            'productAvailability' => '', // a wishList Item!
            'productShortDescription' => $this->description,
            'productLongDescription' => '', // a wishList Item!
            'productRating' => '', // a wishList Item!
            'productOptions' => [], // a wishList Item!
            'productReviews' => [], // a wishList Item!
            'productAdditionalInfo' => [], // a wishList Item!
            'productSticker' => $this->sticker,
            'productID' => $this->id,
            'productURL' => $curl . '/product/' . $this->url,
        ];
    }

}
