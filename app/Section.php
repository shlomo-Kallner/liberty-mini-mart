<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer,
    App\Utilities\ContainerAPI,
    App\Utilities\ContainerID,
    App\Categorie,
    App\Page,
    App\Image,
    App\SectionImage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Section extends Model implements TransformableContainer, ContainerAPI
{
    use SoftDeletes, ContainerTransforms, ContainerID;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function genUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        // {$tmp[0]}/section/{section}/category/{category}/product/{product}
        // $surl = $this->catalog->getFullUrl($baseUrl);
        $url = empty($baseUrl) ? 'section/' : $baseUrl . '/section/';
        return $fullUrl ? url($url) : $url;
    }

    static public function getSection(
        $section, $transform = null, bool $withTrashed = false,
        string $baseUrl = 'store', bool $useTitle = true, 
        bool $fullUrl = false, int $version = 1, $default = null
    ) {
        if (Functions::testVar($section)) {
            if (is_string($section)) {
                $tmp = self::getNamed($section, $withTrashed, null);          
            } elseif (is_int($section) && $section > 0) {
                $tmp = self::getFromId($section, $withTrashed);
            } elseif ($section instanceof self) {
                $tmp = $section;
            }
            if (Functions::testVar($tmp)) {
                return self::doTransform(
                    $tmp, $transform, $baseUrl,
                    $useTitle, $version, 
                    $withTrashed, $fullUrl, $default
                );
            }
        }
        return $default;
    }

    public function getCategory(string $url, bool $withTrashed = false)
    {
        return $withTrashed 
            ? $this->categories()->withTrashed()
                ->where('url', $url)->first()
            : $this->categories()->where('url', $url)->first();
    }

    public function getCategories(
        $transform = null, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, $default = [], bool $useBaseMaker = true,
        bool $done = true
    ) {
        $tmp = $withTrashed 
            ? $this->categories()->withTrashed()
                ->orderBy('name', $dir)->get() 
            : $this->categories()->orderBy('name', $dir)->get();
        return Categorie::getFor(
            $tmp, $baseUrl, $transform, $useTitle,
            $version, $withTrashed, $fullUrl, $default, 
            $useBaseMaker, $done, $dir
        );
    }

    public function hasChildren(bool $withTrashed = true)
    {
        return true;
    }

    public function numChildren(bool $withTrashed = true)
    {
        return $withTrashed 
            ? $this->categories()->withTrashed()->count()
            : $this->categories()->count();
    }

    public function getChildren(
        $transform = null, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, $default = [], bool $useBaseMaker = true,
        bool $done = true
    ) {
        return $this->getCategories(
            $transform, $withTrashed, $dir,
            $baseUrl, $useTitle, $fullUrl, 
            $version, $default, $useBaseMaker, 
            $done
        );
    }

    public function getChildrenWithPagination(
        $transform = null, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        bool $useBaseMaker = true, int $pageNum = 0, 
        int $numItemsPerPage = 4, string $listUrl = '#', 
        string $pagingFor = '', int $viewNumber = 0, 
        bool $fullUrl = false, bool $useTitle = true,
        int $version = 1, $default = []
    ) {
        return $this->getCategoriesWithPagination(
            $transform, $pageNum,
            $numItemsPerPage, $withTrashed, 
            $dir, $baseUrl, $listUrl, $fullUrl, 
            $viewNumber, $useBaseMaker, 
            $default, $version, $useTitle,
            $pagingFor, false
        );
    }

    public function getCategoriesWithPagination(
        $transform = null, int $pageNum = 1,
        int $numShown = 4, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        string $listUrl = '', bool $fullUrl = false, 
        int $viewNumber = 0, bool $useBaseMaker = true, 
        $default = [], int $version = 1, bool $useTitle = true,
        string $pagingFor = '', bool $done = false
    ) {
        $tmp = $withTrashed 
            ? $this->categories()->withTrashed()
                ->orderBy('name', $dir)->get() 
            : $this->categories()->orderBy('name', $dir)->get();
        $res = [];
        if (Functions::testVar($tmp) && Functions::countHas($tmp)) {
            return Categorie::getForWithPagination(
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

    static public function makeContentArray(
        string $name, string $url, $img,
        string $title, $article, string $description,
        array $otherImages = null,
        array $cats = null, array $dates = [], int $id = 0,
        bool $useBaseMaker = true, bool $done = true
    ) {
        if ($useBaseMaker) {
            $content = self::makeBaseContentArray(
                $name, $url, $img, $article, $title,
                $id, $dates, $otherImages,
                $cats, Functions::countHas($cats), $done
            );
            $content['value']['description'] = $description;
            $content['value']['id'] = $id;
            return $content;
        } else {
            return [
                'id' => $id,
                'name' => $name,
                'url' => $url,
                'img' => Image::getImageArray($img),
                'title' => $title,
                'article' => Article::getArticle($article, true),
                'description' => $description,
                'otherImages' => $otherImages??[],
                'categories' => $cats??[],
                'dates' => $dates??[],
            ];
        }
    }

    public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = true,
        bool $done = true, string $dir = 'asc'
    ) {
        return self::makeContentArray(
            $this->name, $this->getFullUrl($baseUrl, $fullUrl), 
            $this->image, 
            $useTitle ? $this->title : $this->image->alt, 
            $this->article, $this->description,
            Image::getArraysFor($this->otherImages),
            $this->getCategories(
                Categorie::TO_URL_LIST_TRANSFORM, 
                $withTrashed, $dir, $baseUrl,
                $useTitle, $fullUrl, $version, [],
                $useBaseMaker, false
            ), 
            [
                'created' => $this->created_at,
                'updated' => $this->updated_at,
                'deleted' => $this->deleted_at,
            ],
            $this->id, $useBaseMaker, $done
        );
    }

    public function toContentArrayWithPagination(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false, int $pageNum = 0, 
        int $numItemsPerPage = 4, string $pagingFor = '', 
        int $viewNumber = 0, string $listUrl = '#', 
        bool $useBaseMaker = true, bool $done = true
    ) {
        $cats = $this->getCategoriesWithPagination(
            Categorie::TO_URL_LIST_TRANSFORM, 
            $pageNum, $numItemsPerPage, $withTrashed, 
            'asc', $baseUrl, $listUrl, $fullUrl, 
            $viewNumber, $useBaseMaker, 
            [], $version, $useTitle,
            $pagingFor, false
        );
        $content = self::makeContentArray(
            $this->name, $this->getFullUrl($baseUrl, $fullUrl), 
            $this->image, 
            $useTitle ? $this->title : $this->image->alt, 
            $this->article, $this->description,
            Image::getArraysFor($this->otherImages),
            $cats['items'] ?? [], 
            [
                'created' => $this->created_at,
                'updated' => $this->updated_at,
                'deleted' => $this->deleted_at,
            ],
            $this->id, $useBaseMaker, $done
        );
        $content['value']['pagination'] = $cats['pagination'] ?? [];
        return $content;
    }
    
    static public function getOrderByKey()
    {
        return 'catalog_id';
    }

    static public function getAllModels(
        $transform = null, string $dir = 'asc', 
        bool $withTrashed = true, string $baseUrl = 'store', 
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1
    ) {
        return self::getAllWithTransform(
            $transform, $dir, $withTrashed, $baseUrl, 
            $useTitle, $fullUrl, $version
        );
    }

    /// Eloquent methods..

    public function article()
    {
        return $this->hasOne('App\Article', 'id', 'article_id');
    }

    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }

    public function categories()
    {
        return $this->hasMany('App\Categorie', 'section_id');
    }

    /**
     *  Function otherImages() Is Now An Eloquent Relationship!
     *  Pass it's result through the getArraysFor() on the Image
     *  Model!
     */
    public function otherImages()
    {
        return $this->hasManyThrough(
            'App\Image', 'App\SectionImage', 
            'section_id', 'id', 
            'id', 'image_id'
        );
        //return SectionImage::getAllImages($this->id);
    }

    /// end of Eloquent methods.

    static public function createNew(
        string $name, string $url, string $title, $article,
        string $description, $img, int $catalog_id = 1,
        bool $retObj = false
    ) {
        $tmp = self::withTrashed()
            ->where(
                [
                    ['name', '=', $name],
                    ['catalog_id', '=', $catalog_id],
                ]
            )->orWhere(
                [
                    ['url', '=', $url],
                    ['catalog_id', '=', $catalog_id],
                ]
            )->get();
        if (!Functions::testVar($tmp) || count($tmp) === 0) {
            $tImg = Image::getImageToID($img);
            $tArt = Article::getToId($article);
            if (Functions::testVar($tImg) && Functions::testVar($tArt)) {
                $data = new self;
                $data->name = $name;
                $data->url = $url;
                $data->image_id = $tImg;
                $data->title = Functions::purifyContent($title);
                $data->article_id = $tArt;
                $data->description = Functions::purifyContent($description);
                $data->catalog_id = $catalog_id;
                if ($data->save()) {
                    if (Functions::testVar(SectionImage::createNewFrom($data))) {
                        return $retObj ? $data : $data->id;
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
            $array['name'], $array['url'], $array['title'], 
            $array['article'], $array['description'], 
            $array['img'], $array['catalog_id'], $retObj
        );
    }
}
