<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions,
    App\Section,
    App\Product,
    App\Image,
    App\CategoryImage,
    App\Page;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model
{

    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function createNew(
        string $name, string $url, string $description, 
        string $title, $article, int $section_id,
        $image, string $sticker
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
                        return $res->id;
                    }
                }
            }
        }
        return null;
    }

    static public function createNewFrom(array $array)
    {
        return self::createNew(
            $array['name'], $array['url'], $array['description'], 
            $array['title'], $array['article'], $array['section_id'], 
            $array['image'], $array['sticker']
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

    public function toContentArray(bool $withTrashed = true)
    {
        return self::makeContentArray(
            $this->name, $this->url, $this->title,
            $this->image, $this->article, $this->description,
            $this->getProducts($withTrashed), 
            Image::getArraysFor($this->otherImages),
            $this->sticker, [
                'created' => $this->created_at,
                'updated' => $this->updated_at,
                'deleted' => $this->deleted_at,
            ], $this->id
        );
    }

    static public function getNamed(string $name, $section_id)
    {
        return self::where(
            [
                'url' => $name,
                'section_id' => $section_id
            ]
        )->first();
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

    static public function getAll(
        bool $toArray = false, string $dir = 'asc', bool $withTrashed = true
    ) {
        $tmp = $withTrashed 
        ? self::withTrashed()->orderBy('section_id', $dir)->get()
        : self::orderBy('section_id', $dir)->get();
        if (Functions::testVar($tmp) && count($tmp) > 0) {
            if ($toArray) {
                $res = [];
                foreach ($tmp as $cat) {
                    $res[] = $cat->toContentArray($withTrashed);
                }
                return $res;
            } else {
                return $tmp->all();
            }
        }
        return null;
    }

    public function getProduct(string $url)
    {
        return $this->products()->where('url', $url)->first();
    }

    public function getProducts(bool $withTrashed = true, string $dir = 'asc')
    {
        $tmp = $withTrashed 
            ? $this->products()->withTrashed()->orderBy('name', $dir)->get()
            : $this->products()->orderBy('name', $dir)->get();
        $res = [];
        if (Functions::testVar($tmp)) {
            foreach ($tmp as $product) {
                $res[] = $product->toContentArray();
            }
        }
        return $res;
    }

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

    public function getFullUrl(string $baseUrl)
    {
        // {$tmp[0]}/section/{section}/category/{category}/product/{product}
        $surl = $this->section->getFullUrl($baseUrl);
        return $surl . '/category/' . $this->url;
    }

    static public function getCategoriesOfSection(
        $section_id, bool $toArray = true, bool $withTrashed = false
    ) {
        $res = [];
        $tmp = $withTrashed 
            ? self::withTrashed()->where('section_id', $section_id)->get()
            : self::where('section_id', $section_id)->get();
        if (Functions::testVar($tmp) && count($tmp) > 0) {
            foreach ($tmp as $category) {
                //dd($category);
                $res[] = $category->toContentArray();
            }
        }
        //dd($res);
        return $res;
    }

    static public function getCategoriesOfSectionWithPagination(
        $section_id, $pageNum, $firstIndex, $lastIndex, int $numShown = 4,
        string $pagingFor = ''
    ) {
        $tmp = self::getCategoriesOfSection($section_id);
        $num = count($tmp);
        return [
            'categories' => $tmp,
            'pagination' => Page::genPagination(
                $pageNum, 
                $firstIndex <= $num ? $firstIndex : 0,
                $lastIndex <= $num ? $lastIndex : 0,
                $num,
                Page::genRange(0, $num), $numShown, $pagingFor
            )
        ];
    }

    public function toSidebar(string $baseUrl, int $version = 1)
    {
        return [
            'url' => $this->getFullUrl($baseUrl),
            'img' => $this->image->toImageArray()['img'],
            'alt' => $this->title,
            /* 'price' => $this->sale != '' || $this->sale != $this->price 
                ? $this->sale 
                : $this->price, */
        ];
    }

    public function toMini(string $baseUrl, int $version = 1)
    {
        return [
            'img' => $this->image->toImageArray()['img'],
            'name' => $this->title,
            'id' => $this->id,
            'url' => $this->getFullUrl($baseUrl),
            /* 'price' => $this->sale != '' || $this->sale != $this->price
                ? $this->sale : $this->price, */
            'sticker' => $this->sticker,
        ];
    }
}
