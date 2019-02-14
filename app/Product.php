<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Database\Eloquent\Collection,
    Illuminate\Support\Facades\Hash,
    App\Utilities\Functions\Functions,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer,
    App\Image,
    App\ProductImage,
    App\Article,
    App\Categorie,
    App\ProductReview;
use DB;

class Product extends Model implements TransformableContainer
{
    use ContainerTransforms;

    static public function createNew(
        string $name, string $url, float $price, 
        float $sale, $category, string $sticker,
        $image, string $description,
        string $title, $article, $payload = null,
        int $availablity = 0, bool $retObj = false
    ) {
        $category_id = Categorie::getIdFrom($category, false, null);
        if (Functions::testVar($category_id)) {
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
            if (!Functions::testVar($tmp) || !Functions::countHas($tmp)) {
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
                    $data->availablity = $availablity;
                    $data->description = Functions::purifyContent($description);
                    $cnTmp = base64_encode(serialize(Functions::getVar($payload, [])));
                    $data->payload = $cnTmp;
                    $data->verihash = Hash::make($cnTmp);
                    if ($data->save()) {
                        $pi = ProductImage::createNewFrom($data, true);
                        if (Functions::testVar($pi)) {
                            return $retObj ? $data : $data->id;
                        }
                    }
                }
            }
        }
        return null;
    }

    public function updateWith(
        string $name, string $url, float $price, 
        float $sale, $category, string $sticker,
        $image, string $description,
        string $title, $article, $payload = null,
        int $availablity = 0, bool $retObj = false
    ) {
        $category_id = Categorie::getIdFrom($category, false, null);
        $whereBy = [
            ['id', '<>', $this->id]
        ];
        $orWhereBy = [
            ['id', '<>', $this->id]
        ];
        if (Functions::testVar($category_id) && $category_id !== $this->category_id) {
            $whereBy[] = ['category_id', '=', $category_id];
            $orWhereBy[] = ['category_id', '=', $category_id];
        } else {
            $whereBy[] = ['category_id', '=', $this->category_id];
            $orWhereBy[] = ['category_id', '=', $this->category_id];
            if (!Functions::testVar($category_id)) {
                $category_id = $this->category_id;
            }
        }
        if ($name !== $this->name) {
            $whereBy[] = ['name', '=', $name];
        } else {
            $whereBy[] = ['name', '=', $this->name];
        }
        if ($url !== $this->url) {
            $orWhereBy[] = ['url', '=', $url];
        } else {
            $orWhereBy[] = ['url', '=', $this->url];
        }
        $tmp = self::withTrashed()->where($whereBy)->orWhere($orWhereBy)->get();
        if (!Functions::testVar($tmp) || !Functions::countHas($tmp)) {
            $tImg = Image::getImageToID($image);
            $tArt = Article::getToId($article);
            if (Functions::testVar($tImg) && Functions::testVar($tArt)) {
                $this->category_id = $category_id;
                $this->name = $name;
                $this->url = $url;
                $this->image_id = $tImg;
                $this->article_id = $tArt;
                $this->title = Functions::purifyContent($title);
                $this->article_id = $tArt;
                $this->price = round($price, 2, PHP_ROUND_HALF_UP);
                $this->sale = round($sale, 2, PHP_ROUND_HALF_UP);
                $this->sticker = $sticker;
                $this->availablity = $availablity;
                $this->description = Functions::purifyContent($description);
                $cnTmp = base64_encode(serialize(Functions::getVar($payload, [])));
                $this->payload = $cnTmp;
                $this->verihash = Hash::make($cnTmp);
                if ($this->save()) {
                    $pi = ProductImage::createNewFrom($this, true);
                    if (Functions::testVar($pi)) {
                        return $retObj ? $this : $this->id;
                    }
                }
            }
        }
        return null;
    }

    static public function createNewFrom(
        array $array, bool $retObj = false
    ) {
        return self::createNew(
            $array['name'], $array['url'], $array['price'], 
            $array['sale'], $array['category_id'], $array['sticker'], 
            $array['image'], $array['description'], 
            $array['title'], $array['article'], $array['payload'],
            $array['availablity'], $retObj
        );
    }

    public function getPayload()
    {
        return unserialize(base64_decode($this->payload));
    }

    /** 
     * Undocumented Function - DO NOT USE! 
     * USE the getAll() method from ContainerTransforms instead!!!
    */
    static public function getAllProducts(
        string $surl, string $curl, bool $toArray = true
    ) {
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

    static public function genProductGallery(
        string $name, array &$products, 
        //array &$cssClasses = [],
        string $baseUrl = 'store', string $sizeClass = 'col-md-12',
        string $owlClass = 'owl-carousel5', 
        string $productClass = 'sale-product',
        bool $serializeProducts = false
    ) {
        $res = [
            // the gallery's name..
            'title' => $name,
            // the actual products..
            'products' => $serializeProducts ? serialize($products) : $products,
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

    /// wishlist item: to be modified to reflect REAL data..
    static public function getNewProducts(
        int $numProducts = 12, string $name = 'New Arrivals', 
        string $baseUrl = 'store', string $sizeClass = 'col-md-12',
        string $owlClass = 'owl-carousel5', 
        string $productClass = 'sale-product', 
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, bool $withTrashed = true, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        $products = self::getRandomSample(
            $numProducts, self::TO_MINI_TRANSFORM, 
            $baseUrl, $useTitle, $fullUrl, $version, 
            $withTrashed, $default, $useBaseMaker,
            $dir
        );
        return self::genProductGallery(
            $name, $products, $baseUrl, $sizeClass, $owlClass, $productClass
        );
    }

    /// wishlist item: to be modified to reflect REAL data..
    static public function getBestsellers(
        int $numProducts = 3, string $baseUrl = 'store', 
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, bool $withTrashed = true, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        return self::getRandomSample(
            $numProducts, self::TO_SIDEBAR_TRANSFORM, 
            $baseUrl, $useTitle, $fullUrl, $version, 
            $withTrashed, $default, $useBaseMaker,
            $dir
        );
    }

    static public function getProductsFor(
        $args, string $baseUrl = 'store', $transform = null, 
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, bool $withTrashed = true, 
        $default = []
    ) {
        return self::getFor(
            $args, $baseUrl, $transform, $useTitle,
            $version, $withTrashed, $fullUrl, $default
        );
    }

    static public function getProductsForCategory(
        $category_id, $transform, string $baseUrl = 'store', 
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, bool $withTrashed = true, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        $products = $withTrashed
        ? self::withTrashed()->where('category_id', $category_id)
        ->orderBy(self::getOrderByKey(), $dir)->get()
        : self::where('category_id', $category_id)
        ->orderBy(self::getOrderByKey(), $dir)->get();
        $done = self::getIfDoneIterating($transform);
        return self::getFor(
            $products, $baseUrl, $transform, $useTitle, $version, 
            $withTrashed, $fullUrl, $default, $useBaseMaker,
            $done, $dir
        );
    }

    static public function genUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        $url = empty($baseUrl) ? 'product/' : $baseUrl . '/product/';
        return $fullUrl ? url($url) : $url;
    }

    public function getParentUrl(string $baseUrl, bool $fullUrl = false)
    {
        return $this->category->getFullUrl($baseUrl, $fullUrl);
    }

    /* 
        public function getPriceOrSale()
        {
            return Functions::testVar($this->sale) || $this->sale != $this->price 
            ? $this->sale 
            : $this->price;
        } 
    */

    public function getPrice()
    {
        return round($this->price, 2, PHP_ROUND_HALF_UP);
    }

    public function getSale()
    {
        return round($this->sale, 2, PHP_ROUND_HALF_UP);
    }

    public function getSticker()
    {
        return $this->sticker;
    }

    public function toFull(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false
    ) {
        $payload = $this->getPayload(); // wishlist item?
        $image = $this->image->toImageArray();
        $reviews = $this->reviews;
        return [
            'productImage' => $image['img'],
            'productImageAlt' => $useTitle ? $this->title : $image['alt'],
            'productOtherImages' => Image::getArraysFor($this->otherImages),// [], // a wishList Item!
            'productTitle' => $this->title,
            'productPrice' => $this->price,
            'productSalePrice' => $this->sale,
            'productAvailability' => $this->availablity, // a wishList Item!
            'productShortDescription' => $this->description,
            'productLongDescription' => Article::getArticle($this->article, true),
            'productRating' => $reviews->avg(
                function ($item) {
                    return $item->rating;
                }
            ), // a wishList Item!
            'productOptions' => Functions::getPropKey($payload, 'options', []), // a wishList Item!
            'productReviews' => ProductReview::getContentArrays($reviews), // a wishList Item!
            'productAdditionalInfo' => Functions::getPropKey($payload, 'additionalInfo', []), // a wishList Item!
            'productSticker' => $this->sticker,
            'productID' => $this->id,
            'productURL' => $this->getFullUrl($baseUrl, $fullUrl),
            'productApiUrl' => $this->getFullUrl('api/' . $baseUrl, $fullUrl),
        ];
    }

    public function toTableArray(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false
    ) {
        $url = $this->getFullUrl($baseUrl, $fullUrl);
        return self::makeTableArray(
            $this->name, $url, $useTitle ? $this->title : $this->image->alt,
            $this->image, $this->description,
            $this->sticker, $this->getDatesArray(),  $this->id,
            $this->getPayload(), $this->price, $this->sale??'no sale',
            $this->category->toUrlListing($baseUrl, $fullUrl, false)
        );
    }

    /// this method WILL require adjustment 
    ///  while covering the WISHLIST!
    static public function makeContentArray(
        string $name, string $url, string $title,
        $img, $article, $price, $sale, 
        string $description, array $otherImages = null,
        string $sticker = '', array $dates = null,
        int $id = 0, string $api = '', array $reviews = null,
        $availablity = '', array $payload = null,
        bool $useBaseMaker = true
    ) {
        if ($useBaseMaker) {
            $content = self::makeBaseContentArray(
                $name, $url, $img, $article, 
                $title, $dates, 
                $otherImages,
                null, false, true, ''
            );
            $content['value']['description'] = $description;
            $content['value']['api'] = $api;
            $content['value']['id'] = $id;
            $content['value']['price'] = $price;
            $content['value']['sale'] = $sale;
            $content['value']['sticker'] = $sticker;
            $content['value']['reviews'] = $reviews ?? [];
            $content['value']['payload'] = $payload ?? [];
            return $content;
        } else {
            return [
                'id' => $id,
                'name' => $name,
                'url' => $url,
                'api' => $api,
                'title' => $title,
                'img' => Image::getImageArray($img),
                'article' => Article::getArticle($article, true),
                'price' => $price,
                'sale' => $sale,
                'sticker' => $sticker,
                'description' => $description,
                'otherImages' => $otherImages ?? [],
                'reviews' => $reviews ?? [],
                'payload' => $payload ?? [],
                'dates' => $dates ?? [],
            ];
        }
    }

    public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        $url = $this->getFullUrl($baseUrl, $fullUrl);
        $api = $this->getFullUrl('api/' . $baseUrl, $fullUrl);
        return self::makeContentArray(
            $this->name, $url, $useTitle ? $this->title : $this->image->alt,
            $this->image, $this->article, $this->price,
            $this->sale??'', $this->description,
            Image::getArraysFor($this->otherImages),
            $this->sticker, [
                'created' => $this->created_at,
                'updated' => $this->updated_at,
                'deleted' => $this->deleted_at,
            ], $this->id, $api, 
            ProductReview::getContentArrays(
                $this->reviews, $baseUrl, [], $version, 
                $useTitle, $withTrashed, $fullUrl, 
                $useBaseMaker, $dir
            ),
            $this->availablity, $this->getPayload(), $useBaseMaker
        );
    }

    public function getChildrenQuery()
    {
        return $this->reviews();
    }

    static public function getChildrenFor(
        $args, string $baseUrl = 'store', $transform = null, 
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, bool $fullUrl = false, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        return ProductReview::getFor(
            $tmp, $baseUrl, $transform, $useTitle,
            $version, $withTrashed, $fullUrl, $default,
            $useBaseMaker, $dir
        );
    }

    static public function getSelf(
        string $baseUrl = 'store', bool $withTrashed = true,
        bool $fullUrl = false, $children = [], 
        $paginator = null, string $pagingFor = ''
    ) {
        $title = $name = 'Products';
        $article = [];
        $img = Image::createImageArray(
            'shopping-1232944_640.jpg', 'Products Listing', 
            'images/site', 'Products Listing'
        );
        $pagingFor = $pagingFor ?: 'productsPanel';
        return self::makeSelf(
            $name, $title, $article,
            $img, $baseUrl, $withTrashed,
            $fullUrl, $children, $paginator,
            $pagingFor, null
        );
    }

    static public function getOrderByKey()
    {
        return 'category_id';
    }

    /// the Eloquent Relationship methods:

    public function article()
    {
        return $this->hasOne('App\Article', 'id', 'article_id');
    }

    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }

    public function reviews()
    {
        return $this->hasMany('App\ProductReview', 'product_id', 'id');
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

    /// end of the Eloquent Relationship methods.
}
