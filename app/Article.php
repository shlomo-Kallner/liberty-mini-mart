<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions;
use App\Image;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    //

    static public function getFromId(int $id)
    {
        return self::where('id', $id)->first();
    }

    static public function existsId(int $id)
    {
        return Functions::testVar(self::getFromId($id));
    }

    static public function createNew(
        string $article, string $header = '', 
        $image = null, string $subheading = ''
    ) {
        if (Functions::testVar($article)) {
            $tImg = Image::getImageToID($image);
            $tmp = new self;
            $tmp->image_id = Functions::testVar($tImg) ? $tImg : 0;
            $tmp->header = $header;
            $tmp->subheading = $subheading;
            $tmp->article = $article;
            if ($tmp->save()) {
                return $tmp->id;
            }
        }
        return null;
    }

    static public function createNewFrom(array $array)
    {
        return self::createNew(
            $array['article'], $array['header'],
            $array['image'], $array['subheading']
        );
    }

    public function toContentArray()
    {
        return self::makeContentArray(
            $this->article, $this->header,
            $this->image_id, $this->subheading
        );
    }

    static public function makeContentArray(
        string $article, string $header = '',
        $img = null, string $subheading = ''
    ) {
        return [
            'header' => $header,
            'subheading' => $subheading,
            'img' => Image::getImage($img),
            'article' => $article,
        ];
    }

}
