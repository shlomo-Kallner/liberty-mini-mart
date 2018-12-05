<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer,
    App\Utilities\ContainerAPI,
    App\Utilities\ContainerID,
    App\Section,
    App\Product,
    App\Image,
    App\CategoryImage,
    App\Page;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model implements TransformableContainer, ContainerAPI
{

    use SoftDeletes, ContainerTransforms, ContainerID;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function createNew(
        string $name, string $url, string $description, 
        string $title, $article, int $section_id,
        $image, string $sticker, bool $retObj = false
    ) {
        $tmp = self::withTrashed()
            ->where(
                [
                    ['name', '=', $name],
                    ['section_id', '=', $section_id],
                ]
            )->orWhere(
                [
                    ['url', '=', $url],
                    ['section_id', '=', $section_id],
                ]
            )->get();
        if ((!Functions::testVar($tmp) || count($tmp) === 0) 
            && Section::existsId($section_id)
        ) {
            $tImg = Image::getImageToID($image);
            $tArt = Article::getToId($article);
            if (Functions::testVar($tImg) && Functions::testVar($tArt)) {
                $res = new self;
                $res->name = $name;
                $res->image_id = $tImg;
                $res->title = Functions::purifyContent($title);
                $res->article_id = $tArt;
                $res->url = $url;
                $res->section_id = $section_id;
                $res->sticker = $sticker;
                $res->description = Functions::purifyContent($description);
                if ($res->save()) {
                    $ci = CategoryImage::createNewFrom($res);
                    if (Functions::testVar($ci)) {
                        return $retObj ? $res : $res->id;
                    }
                }
            }
        }
        return null;
    }

    static public function createNewFrom(array $array, bool $retObj = false)
    {
        return self::createNew(
            $array['name'], $array['url'], $array['description'], 
            $array['title'], $array['article'], $array['section_id'], 
            $array['image'], $array['sticker'], $retObj
        );
    }

    public function getUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        // {$tmp[0]}/section/{section}/category/{category}/product/{product}
        $surl = $this->section->getFullUrl($baseUrl, false);
        $url = $surl . '/category/';
        return $fullUrl ? url($url) : $url;
    }

    public function toSidebar(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false
    ) {
        $img = $this->image->toImageArray();
        return self::makeSidebar(
            $this->getFullUrl($baseUrl, $fullUrl), $img['img'],
            $useTitle ? $this->title : $img['alt'],
            '', $this->id
        );
    }

    public function toMini(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false
    ) {
        $img = $this->image->toImageArray();
        return self::makeMini(
            $img['img'], $useTitle ? $this->title : $img['alt'],
            $this->getFullUrl($baseUrl, $fullUrl), '', $this->id, 
            $this->sticker
        );
        /* 
            'price' => $this->sale != '' 
            || $this->sale != $this->price
                ? $this->sale : $this->price, 
        */
    }

    static public function getOrderByKey()
    {
        return 'section_id';
    }

    static public function makeTableArray(
        string $name, string $url, string $title,
        $img, string $description,
        string $sticker = '', array $dates = [], int $id = 0
    ) {
        return [
            'id' => $id,
            'name' => $name,
            'img' => Image::getImageArray($img),
            'title' => $title,
            'url' => $url,
            'description' => $description,
            'dates' => $dates??[],
        ];
    }

    static public function makeContentArray(
        string $name, string $url, string $title,
        $img, $article, string $description,
        array $products = null, array $otherImages = null,
        string $sticker = '', array $dates = [], int $id = 0
    ) {
        return [
            'id' => $id,
            'name' => $name,
            'img' => Image::getImageArray($img),
            'otherImages' => $otherImages??[],
            'title' => $title,
            'article' => Article::getArticle($article, true),
            'url' => $url,
            'sticker' => $sticker,
            'description' => $description,
            'products' => $products??[],
            'dates' => $dates??[],
        ];
    }

    static public function makeCmcContentArray(
        $item, string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false
    ) {
        return $item->toCmsContentArray();
    }

    public function toCmsContentArray(
        int $i = 0
    ) {

    }

    // TO BE IMPLEMENTED!!!
    public function toContentArrayWithPagination(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false, int $pageNum = 0, 
        int $numItemsPerPage = 4, string $pagingFor = '', 
        int $viewNumber = 0, string $listUrl = '#'
    ) {
        return $this->toContentArray(
            $baseUrl, $version, $useTitle, $withTrashed,
            $fullUrl
        );
    }

    public function toContentArray(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false
    ) {
        return self::makeContentArray(
            $this->name, $this->getFullUrl($baseUrl, $fullUrl), 
            $useTitle ? $this->title : $this->image->alt,
            $this->image, $this->article, $this->description,
            $this->getProducts(
                Product::TO_URL_LIST_TRANSFORM, 
                $withTrashed, 'asc', $baseUrl, $useTitle,
                $fullUrl, $version, []
            ),
            Image::getArraysFor($this->otherImages),
            $this->sticker, [
                'created' => $this->created_at,
                'updated' => $this->updated_at,
                'deleted' => $this->deleted_at,
            ], $this->id
        );
    }

    public function toFull(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false
    ) {
        return $this->toContentArray(
            $baseUrl, $version, $useTitle, $withTrashed,
            $fullUrl
        );
    }

    /**
     * Undocumented function - DO NOT USE THIS FUNCTION!
     * 
     *
     * @param string $url
     * @return void
     */
    static public function getIdFromURL(string $url)
    {
        $tmp = explode('/', $url);
        if ($tmp[0] == 'store' || $tmp[0] == 'admin') {
            // {$tmp[0]}/section/{section}/category/{category}/product/{product}
            $section_id = Section::getNamed($tmp[2])->id;
            return self::getNamed($tmp[4], $section_id)->id;
        } elseif (count($tmp) == 1) {
            return self::where('url', $url)
                ->orderBy('section_id', 'asc')
                ->get();
        } else {
            return null;
        }
    }

    public function getProduct(string $url, bool $withTrashed = true)
    {
        return $withTrashed 
            ? $this->products()->withTrashed()->where('url', $url)->first()
            : $this->products()->where('url', $url)->first();
    }

    public function getProducts(
        $transform = null, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, $default = []
    ) {
        $tmp = $withTrashed 
            ? $this->products()->withTrashed()
                ->orderBy('name', $dir)->get()
            : $this->products()->orderBy('name', $dir)->get();
        return Product::getFor(
            $tmp, $baseUrl, $transform, $useTitle,
            $version, $withTrashed, $fullUrl, $default
        );
    }

    public function getProductsWithPagination(
        $pageNum, $firstIndex, $lastIndex, 
        int $numShown = 4, string $pagingFor = '',
        $transform = null, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        bool $useTitle = true, int $version = 1, 
        $default = []
    ) {
        $tmp = $this->getProducts(
            $transform, $withTrashed, $dir,
            $baseUrl, $useTitle, $version, $default
        );
        $num = count($tmp);
        return [
            'items' => $tmp,
            'pagination' => Page::genPagination(
                $pageNum, 
                $firstIndex <= $num ? $firstIndex : 0,
                $lastIndex <= $num ? $lastIndex : $num,
                $num,
                Page::genRange(0, $num), $numShown, $pagingFor
            )
        ];
    }

    static public function getCategoriesOfSection(
        int $section_id, $transform = null, 
        bool $withTrashed = false, string $baseUrl = 'store',
        bool $useTitle = true, int $version = 1, 
        $default = []
    ) {
        $tmp = $withTrashed 
            ? self::withTrashed()->where('section_id', $section_id)->get()
            : self::where('section_id', $section_id)->get();
        return self::getFor(
            $tmp, $baseUrl, $transform, $useTitle,
            $version, $withTrashed, $default
        );
    }

    static public function getCategoriesOfSectionWithPagination(
        int $section_id, $pageNum, $firstIndex, $lastIndex, 
        int $numShown = 4, string $pagingFor = ''
    ) {
        $tmp = self::getCategoriesOfSection($section_id);
        $num = count($tmp);
        return [
            'items' => $tmp,
            'pagination' => Page::genPagination(
                $pageNum, 
                $firstIndex <= $num ? $firstIndex : 0,
                $lastIndex <= $num ? $lastIndex : 0,
                $num,
                Page::genRange(0, $num), $numShown, $pagingFor
            )
        ];
    }

    /// the Eloquent Relationship methods:

    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }

    public function otherImages()
    {
        return $this->hasManyThrough(
            'App\Image', 'App\CategoryImage',
            'category_id', 'id',
            'id', 'image_id'
        );
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'category_id', 'id');
    }

    public function article()
    {
        return $this->hasOne('App\Article', 'id', 'article_id');
    }

    public function section()
    {
        return $this->belongsTo('App\Section', 'section_id');
    }
    
}
