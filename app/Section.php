<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions,
    App\Categorie,
    App\Page,
    App\Image,
    App\SectionImage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function getNamed(string $name, bool $withTrashed = false)
    {
        return $withTrashed 
        ? self::withTrashed()->where('url', $name)->first()
        : self::where('url', $name)->first();
    }

    static public function getSection(
        $section, bool $toArray = false, bool $withTrashed = false
    ) {
        if (Functions::testVar($section)) {
            if (is_string($section)) {
                $tmp = self::getNamed($section, $withTrashed);
                return $toArray && Functions::testVar($tmp) 
                    ? $tmp->toContentArray($withTrashed) : $tmp;
            } elseif (is_int($section) 
                && Functions::testVar($ts = self::getFromId($section))
            ) {
                return $toArray ? $ts->toContentArray($withTrashed) : $ts;
            } elseif ($section instanceof self) {
                return $toArray ? $section->toContentArray($withTrashed) : $section;
            }
        }
        return null;
    }

    public function getCategory(string $url, bool $withTrashed = false)
    {
        return $withTrashed 
            ? $this->categories()->withTrashed()->where('url', $url)->first()
            : $this->categories()->where('url', $url)->first();
    }

    public function getCategories(bool $withTrashed = true)
    {
        $tCats = $withTrashed 
            ? $this->categories()->withTrashed()->get() 
            : $this->categories;
        $cats = [];
        foreach ($tCats as $cat) {
            $cats[] = $cat->toContentArray($withTrashed);
        }
        return $cats;
    }

    static public function makeContentArray(
        string $name, string $url, $img,
        string $title, $article, string $description,
        array $otherImages = null,
        array $cats = null, array $dates = [], int $id = 0
    ) {
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

    public function toContentArray(bool $withTrashed = true)
    {
        return self::makeContentArray(
            $this->name, $this->url, $this->image,
            $this->title, $this->article, 
            $this->description,
            //SectionImage::getAllImages($this->id),
            Image::getArraysFor($this->otherImages),
            $this->getCategories(), 
            [
                'created' => $this->created_at,
                'updated' => $this->updated_at,
                'deleted' => $this->deleted_at,
            ],
            $this->id
        );
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
            //'sticker' => $this->sticker,
        ];
    }

    static public function getAll(
        bool $toArray = true, bool $withTrashed = true
    ) {
        return self::getAllModels($toArray, $withTrashed);
    }

    static public function getAllModels(
        bool $toArray = true, bool $withTrashed = true
    ) {
        $tmp = $withTrashed ? self::withTrashed()->get() : self::all();
        /* foreach ($tmp as $section) {
            $section = Functions::dbModel2ViewModel($section);
        } */
        $res = [];
        if ($toArray && count($tmp) > 0) {
            foreach ($tmp as $sect) {
                $res[] = $sect->toContentArray();
            }
        } elseif (count($tmp) > 0) {
            return $tmp; 
        }
        return $res;
    }

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

    public function getFullUrl(string $baseUrl)
    {
        // {$tmp[0]}/section/{section}/category/{category}/product/{product}
        // $surl = $this->catalog->getFullUrl($baseUrl);
        return $baseUrl . '/section/' . $this->url;
    }

    static public function getAllWithPagination(
        bool $toArray = true,
        $pageNum, $firstIndex, $lastIndex, int $numShown = 4,
        string $pagingFor = ''
    ) {
        $tmp = self::getAllModels($toArray);
        $num = count($tmp);
        return [
            'sections' => $tmp,
            'pagination' => Page::genPagination(
                $pageNum, 
                $firstIndex <= $num ? $firstIndex : 0,
                $lastIndex <= $num ? $lastIndex : 0,
                $num,
                Page::genRange(0, $num), $numShown, $pagingFor
            )
        ];
    }

    static public function createNew(
        string $name, string $url, string $title, $article,
        string $description, $img, int $catalog_id = 1
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
                        return $data->id;
                    }
                }
            }
        }
        return null;
    }

    static public function createNewFrom(Array $array)
    {
        return self::createNew(
            $array['name'], $array['url'], $array['title'], 
            $array['article'], $array['description'], 
            $array['img'], $array['catalog_id']
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
}
