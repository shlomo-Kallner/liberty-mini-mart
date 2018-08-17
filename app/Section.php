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

    static public function getNamed(string $name)
    {
        return self::where('url', $name)->first();
    }

    public function getCategory(string $url)
    {
        return $this->categories()->where('url', $url)->first();
    }

    static public function makeContentArray(
        string $name, string $url, $img,
        array $otherImages = null,
        array $cats = null, int $id = 0
    ) {
        return [
            'name' => $name,
            'url' => $url,
            'img' => Image::getImageArray($img),
            'otherImages' => $otherImages??[],
            'categories' => $cats,
        ];
    }

    public function toContentArray()
    {
        $tCats = $this->categories;
        $cats = [];
        foreach($tCats as $cat) {
            $cats[] = $cat->toContentArray();
        }
        return self::makeContentArray(
            $this->name, $this->url, $this->image,
            //SectionImage::getAllImages($this->id),
            Image::getArraysFor($this->otherImages),
            $cats, $this->id
        );
    }

    static public function getAllModels(bool $withCats = true, bool $toArray = true) 
    {
        $tmp = self::all();
        /* foreach ($tmp as $section) {
            $section = Functions::dbModel2ViewModel($section);
        } */
        if ($toArray) {
            $res = [];
            foreach ($tmp as $sect) {
                $res[] = $sect->toContentArray($withCats);
            }
            return $res;
        } else {
            return $tmp;
        }
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

    static public function getAllWithPagination(
        bool $withCats = true, bool $toArray = true,
        $pageNum, $firstIndex, $lastIndex, int $numShown = 4,
        string $pagingFor = ''
    ) {
        $tmp = self::getAllModels($withCats, $toArray);
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
        string $description, $img, string $sub_title = ''
    ) {
        $tmp = self::where('name', $name)
            ->orWhere('url', $url)
            ->get();
        if (!Functions::testVar($tmp) || count($tmp) === 0) {
            $tImg = Image::getImageToID($img);
            $tArt = Article::getToId($article);
            if (Functions::testVar($tImg) && Functions::testVar($tArt)) {
                $data = new self;
                $data->name = $name;
                $data->image_id = $tImg;
                $data->sub_title = $sub_title;
                $data->article_id = $tArt;
                $data->url = $url;
                $data->description = $description;
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
            $array['img'], $array['sub_title']
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
}
