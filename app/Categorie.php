<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer,
    App\Section,
    App\Product,
    App\Image,
    App\CategoryImage,
    App\Page,
    App\User;

class Categorie extends Model implements TransformableContainer
{

    use ContainerTransforms;

    static public function createNew(
        string $name, string $url, string $description, 
        string $title, $article, $section,
        $image, string $sticker, bool $retObj = false
    ) {
        $section_id = Section::getIdFrom($section, false, 0);
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
        if ((!Functions::testVar($tmp) || !Functions::countHas($tmp)) 
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

    public function updateWith(
        string $name, string $url, string $description, 
        string $title, $article, $section,
        $image, string $sticker, bool $retObj = false
    ) {
        $section_id = Section::getIdFrom($section, false, 0);
        $whereBy = [
            ['id', '<>', $this->id]
        ];
        $orWhereBy = [
            ['id', '<>', $this->id]
        ];
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
        if ((!Functions::testVar($tmp) || !Functions::countHas($tmp)) 
            && Section::existsId($section_id)
        ) {
            $tImg = Image::getImageToID($image);
            $tArt = Article::getToId($article);
            if (Functions::testVar($tImg) && Functions::testVar($tArt)) {
                $this->name = $name;
                $this->image_id = $tImg;
                $this->title = Functions::purifyContent($title);
                $this->article_id = $tArt;
                $this->url = $url;
                $this->section_id = $section_id;
                $this->sticker = $sticker;
                $this->description = Functions::purifyContent($description);
                if ($this->save()) {
                    $ci = CategoryImage::createNewFrom($this);
                    if (Functions::testVar($ci)) {
                        return $retObj ? $this : $this->id;
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

    static public function genUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        $url = empty($baseUrl) ? 'category/' : $baseUrl . '/category/';
        return $fullUrl ? url($url) : $url;
    }

    public function getParentUrl(string $baseUrl, bool $fullUrl = false)
    {
        $p = User::getIsAdmin() ? $this->section()->withTrashed()->first() : $this->section;
        if (Functions::testVar($p)) {
            return $p->getFullUrl($baseUrl, $fullUrl);
        }
        return $fullUrl ? url($baseUrl) : $baseUrl;
    }

    public function getSticker()
    {
        return $this->sticker;
    }

    static public function getOrderByKey()
    {
        return 'section_id';
    }

    static public function getChildrenOrderByKey()
    {
        return Product::getOrderByKey();
    }

    public function getChildrenQuery()
    {
        return $this->products();
    }

    static public function makeContentArray(
        string $name, string $url, string $title,
        $img, $article, string $description,
        array $products = null, array $otherImages = null,
        string $sticker = '', array $dates = [], int $id = 0,
        bool $useBaseMaker = true 
    ) {
        if ($useBaseMaker) {
            $content = self::makeBaseContentArray(
                $name, $url, $img, $article, 
                $title, $dates, 
                $otherImages, $products, 
                Functions::countHas($products), true
            );
            return $content;
        } else {
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
    }

    static public function makeContentArrayWithPagination(
        string $name, string $url, string $title,
        $img, $article, string $description,
        array $products = null, $paginator = null, 
        array $otherImages = null, string $sticker = '', 
        array $dates = [], int $id = 0,
        bool $useBaseMaker = true 
    ) {
        if (Functions::testVar($paginator) && $useBaseMaker) {
            $content = self::makeBaseContentIterArray(
                $name, $url, $img, $article, $title, 
                $paginator['currentPage'], $paginator['totalNumPages'], 
                $paginator['numItemsPerPage'], $products, 
                $paginator, $dates, 
                $otherImages, Functions::countHas($products),
                !empty($paginator['pagingFor']), 
                $paginator['viewNumber'], 
                $paginator['pagingFor']
            );
        } else {
            $content = self::makeContentArray(
                $name, $url, $title, $img, $article, 
                $description, $products, $otherImages,
                $sticker, $dates, $id, $useBaseMaker 
            );
            if (Functions::testVar($paginator) && !$useBaseMaker) {
                $content['pagination'] = $paginator;
            }
        }
        return $content;
    }

    static public function getSelf(
        string $baseUrl = 'store', bool $withTrashed = true,
        bool $fullUrl = false, $children = [], 
        $paginator = null, string $pagingFor = ''
    ) {
        $title = $name = 'Categories';
        $article = [];
        $img = Image::createImageArray(
            'compare-643305_640.png', 'Categories Listing', 
            'images/site', 'Categories Listing'
        );
        $pagingFor = $pagingFor ?: 'categoriesPanel';
        return self::makeSelf(
            $name, $title, $article,
            $img, $baseUrl, $withTrashed,
            $fullUrl, $children, $paginator,
            $pagingFor, null
        );
    }

    public function toContentArrayWithPagination(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false, int $pageNum = 0, 
        int $numItemsPerPage = 4, string $pagingFor = '', 
        int $viewNumber = 0, string $listUrl = '#', 
        bool $useBaseMaker = true
    ) {
        $products = $this->getProductsWithPagination(
            Product::TO_URL_LIST_TRANSFORM, $pageNum,
            $numItemsPerPage, $withTrashed, 
            'asc', $baseUrl, $listUrl, $fullUrl, 
            $viewNumber, $useBaseMaker, [], $version, 
            $useTitle, $pagingFor
        );
        $content = self::makeContentArrayWithPagination(
            $this->name, $this->getFullUrl($baseUrl, $fullUrl), 
            $useTitle ? $this->title : $this->image->alt,
            $this->image, $this->article, $this->description,
            $products['items'] ?? [], $products['pagination'] ?? [],
            Image::getArraysFor($this->otherImages),
            $this->sticker, [
                'created' => $this->created_at,
                'updated' => $this->updated_at,
                'deleted' => $this->deleted_at,
            ], $this->id, $useBaseMaker
        );
        return $content;
    }
    
    public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        return self::makeContentArray(
            $this->name, $this->getFullUrl($baseUrl, $fullUrl), 
            $useTitle ? $this->title : $this->image->alt,
            $this->image, $this->article, $this->description,
            $this->getProducts(
                Product::TO_URL_LIST_TRANSFORM, 
                $withTrashed, $dir, $baseUrl, $useTitle,
                $fullUrl, $version, [], $useBaseMaker
            ),
            Image::getArraysFor($this->otherImages),
            $this->sticker, [
                'created' => $this->created_at,
                'updated' => $this->updated_at,
                'deleted' => $this->deleted_at,
            ], $this->id, $useBaseMaker
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
        } elseif (count($tmp) === 1) {
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
        int $version = 1, $default = [], bool $useBaseMaker = true
    ) {
        $tmp = self::getOrderedFor(
            $this->products(), $dir, 
            $withTrashed, 'name'
        ); 
        return Product::getFor(
            $tmp, $baseUrl, $transform, $useTitle,
            $version, $withTrashed, $fullUrl, $default,
            $useBaseMaker, $dir
        );
    }

    public function hasChildren(bool $withTrashed = true)
    {
        return true;
    }

    static public function getChildrenFor(
        $args, string $baseUrl = 'store', $transform = null, 
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, bool $fullUrl = false, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        return Product::getFor(
            $args, $baseUrl, $transform, $useTitle,
            $version, $withTrashed, $fullUrl, $default,
            $useBaseMaker, $dir
        );
    }

    public function numChildren(bool $withTrashed = true) 
    {
        return $withTrashed 
            ? $this->products()->withTrashed()->count()
            : $this->products()->count();
    }

    public function getProductsWithPagination(
        $transform = null, int $pageNum = 1,
        int $numShown = 4, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        string $listUrl = '', bool $fullUrl = false, 
        int $viewNumber = 0, bool $useBaseMaker = true, 
        $default = [], int $version = 1, bool $useTitle = true,
        string $pagingFor = ''
    ) {
        $tmp = self::getOrderedFor(
            $this->products(), $dir, 
            $withTrashed, 'name'
        ); 
        if (Functions::testVar($tmp) && Functions::countHas($tmp)) {
            $listUrl = !empty($listUrl) || $listUrl !== '#' 
                ? $listUrl
                : $this->getFullUrl($baseUrl, $fullUrl);
            return Product::getForWithPagination(
                $tmp, $transform, $pageNum, $numShown, 
                $pagingFor,  $listUrl, $baseUrl, $dir, 
                $viewNumber, $withTrashed, $useTitle, 
                $fullUrl, $version, $default, 
                $useBaseMaker
            );
        }
        return $default;
    }

    static public function getCategoriesOfSection(
        int $section_id, $transform = null, 
        bool $withTrashed = false, string $baseUrl = 'store',
        bool $useTitle = true, int $version = 1, 
        $default = [], string $dir = 'asc'
    ) {
        $tmp = $withTrashed 
            ? self::withTrashed()->where('section_id', $section_id)
                ->orderBy('name', $dir)->get()
            : self::where('section_id', $section_id)
                ->orderBy('name', $dir)->get();
        return self::getFor(
            $tmp, $baseUrl, $transform, $useTitle,
            $version, $withTrashed, $default
        );
    }

    static public function getCategoriesOfSectionWithPagination(
        int $section_id, $transform = null, int $pageNum = 1,
        int $numShown = 4, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        string $listUrl = '', bool $fullUrl = false, 
        int $viewNumber = 0, bool $useBaseMaker = true, 
        $default = [], int $version = 1, bool $useTitle = true,
        string $pagingFor = '', bool $done = false
    ) {
        $tmp = $withTrashed 
            ? self::withTrashed()->where('section_id', $section_id)
                ->orderBy('name', $dir)->get()
            : self::where('section_id', $section_id)
                ->orderBy('name', $dir)->get();
        if (Functions::testVar($tmp) && Functions::countHas($tmp)) {
            return self::getForWithPagination(
                $tmp, $transform, $pageNum,
                $numShown, $pagingFor, 
                $listUrl, $baseUrl, 
                $dir, $viewNumber = 0, 
                $withTrashed, $useTitle, 
                $fullUrl, $version, $default, 
                $useBaseMaker, $done
            );
        }
        return $default;
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
        //           hasMany (  other-model, f-Key-onOtherMOdel, p-Key-onThisModel )
        return $this->hasMany('App\Product', 'category_id', 'id');
    }

    public function article()
    {
        //          hasOne ( other-model, f-Key-onOtherMOdel, p-Key-onThisModel )
        return $this->hasOne('App\Article', 'id', 'article_id');
    }

    public function section()
    {
        //         belongsTo ( other-model, f-Key-onThisModel, p-Key-onOtherMOdel );
        return $this->belongsTo('App\Section', 'section_id', 'id');
    }

    /// end of the Eloquent Relationship methods.
}
