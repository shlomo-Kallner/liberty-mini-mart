<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Database\Eloquent\Collection,
    App\Utilities\Functions\Functions,
    App\Image,
    App\ProductImage,
    App\Article,
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
        string $title, $article
    ) {
        $tmp = self::withTrashed()
            ->where(
                [
                    ['name', '=', $name],
                    ['category_id', '=', $category_id],
                ]
            )->orWhere(
                [
                    ['url', '=', $url],
                    ['category_id', '=', $category_id],
                ]
            )->get();
        if ((!Functions::testVar($tmp) || count($tmp) === 0)
            && Categorie::existsId($category_id)
        ) {
            $tImg = Image::getImageToID($image);
            $tArt = Article::getToId($article);
            if (Functions::testVar($tImg) && Functions::testVar($tArt)) {
                $data = new self;
                $data->name = $name;
                $data->image_id = $tImg;
                $data->title = Functions::purifyContent($title);
                $data->article_id = $tArt;
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

    static public function getFromId(int $id, bool $withTrashed = true)
    {
        return $withTrashed 
            ? self::withTrashed()->where('id', $id)->first()
            : self::where('id', $id)->first();
    }

    static public function existsId(int $id, bool $withTrashed = true)
    {
        return Functions::testVar(self::getFromId($id, $withTrashed));
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

    static public function getAll(
        bool $toArray = false, string $dir = 'asc', bool $withTrashed = true
    ) {
        $tmp = $withTrashed 
            ? self::withTrashed()->orderBy('category_id', $dir)->get()
            : self::orderBy('category_id', $dir)->get();
        if (Functions::testVar($tmp) && count($tmp) > 0) {
            if ($toArray) {
                $res = [];
                foreach($products as $product) {
                    $res[] = $product->toContentArray($withTrashed);
                }
                return $res;
            } else {
                return $tmp->all();
            }
        }
        return null;
    }

    static public function getRandomSample(
        int $numProducts, bool $toArray = false
    ) {
        $numTotal = self::count();
        $res = [];
        if ($numProducts < $numTotal) {
            for ($i = 1; $i <= $numProducts; $i++) {
                $bol = true;
                while ($bol) {
                    $smp = random_int(1, $numTotal);
                    if (!array_key_exists($smp, $res) 
                        && Functions::testVar($t = self::getFromId($smp, false))
                    ) {
                        $res[$smp] = $toArray ? $t->toContentArray(false) : $t;
                        $bol = false;
                    }
                }
            }
        } elseif ($numProducts === $numTotal) {
            $rng = Functions::genRange(1, $numTotal);
            shuffle($rng);
            foreach ($rng as $idx) {
                if (Functions::testVar($t = self::getFromId($idx, false))) {
                    $res[] = $toArray ? $t->toContentArray(false) : $t;
                }
            }
        }
        return $res;
    }

    static public function getProductsForCategory($category_id, $transform, string $baseUrl) 
    {
        $res = [];
        $products = self::where('category_id', $category_id)->get();
        foreach ($products as $product) {
            if (is_string($transform)) {
                switch ($transform) {
                    case 'mini':
                        $res[] = $product->toMini($baseUrl);
                        break;
                    case 'full':
                        $res[] = $product->toFull($baseUrl);
                        break;
                    case 'sidebar':
                        $res[] = $product->toSidebar($baseUrl);
                        break;
                    //$res[] = ($product);
                }
            }
        }
        return $res;
    }

    public function getFullUrl(string $baseUrl)
    {
        $surl = $this->category->getFullUrl($baseUrl);
        return $surl . '/product/' . $this->url;
    }

    public function toSidebar(string $baseUrl, int $version = 1)
    {
        return [
            'url' => $this->getFullUrl($baseUrl),
            'img' => $this->image->toImageArray()['img'],
            'alt' => $this->title,
            'price' => $this->sale != '' || $this->sale != $this->price 
                ? $this->sale 
                : $this->price,
        ];
    }

    public function toMini(string $baseUrl, int $version = 1)
    {
        return [
            'img' => $this->image->toImageArray()['img'],
            'name' => $this->title,
            'id' => $this->id,
            'url' => $this->getFullUrl($baseUrl),
            'price' => $this->sale != '' || $this->sale != $this->price
                ? $this->sale : $this->price,
            'sticker' => $this->sticker,
        ];
    }

    public function toFull(string $baseUrl, int $version = 1)
    {
        return [
            'productImage' => $this->image->toImageArray()['img'],
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
            'productURL' => $this->getFullUrl($baseUrl),
        ];
    }

    ///

    static public function makeContentArray(
        string $name, string $url, string $title,
        $img, $article, $price, $sale, 
        string $description, array $otherImages = null,
        string $sticker = '', array $dates = null,
        int $id = 0
    ) {
        return [
            'id' => $id,
            'name' => $name,
            'url' => $url,
            'title' => $title,
            'img' => Image::getImageArray($img),
            'article' => Article::getArticle($article, true),
            'price' => $price,
            'sale' => $sale,
            'sticker' => $sticker,
            'description' => $description,
            'otherImages' => $otherImages??[],
            'dates' => $dates??[],
        ];
    }

    public function toContentArray(bool $withTrashed = true)
    {
        return self::makeContentArray(
            $this->name, $this->url, $this->title,
            $this->image, $this->article, $this->price,
            $this->sale??'', $this->description,
            Image::getArraysFor($this->otherImages),
            $this->sticker, [
                'created' => $this->created_at,
                'updated' => $this->updated_at,
                'deleted' => $this->deleted_at,
            ], $this->id
        );
    }

    ///

    public function article()
    {
        return $this->hasOne('App\Article', 'id', 'article_id');
    }

    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }

    /**
     * Function otherImages - alias of images()
     *
     * @return null|Collection
     */
    public function otherImages()
    {
        return $this->hasManyThrough(
            'App\Image', 'App\ProductImage',
            'product_id', 'id',
            'id', 'image_id'
        );
    }

    public function images()
    {
        return $this->hasManyThrough(
            'App\Image', 'App\ProductImage',
            'product_id', 'id',
            'id', 'image_id'
        );
    }

    public function category()
    {
        return $this->belongsTo('App\Categorie', 'category_id');
    }

}
