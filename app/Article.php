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

    static public function createNew(
        string $article, string $header = '', 
        $image = null, string $subheading = '',
        bool $purify = true
    ) {
        if (Functions::testVar($article)) {
            $tImg = Image::getImageToID($image);
            $tmp = new self;
            $tmp->image_id = Functions::testVar($tImg) ? $tImg : 0;
            $tmp->header = $purify ? Functions::purifyContent($header) : $header;
            $tmp->subheading = $purify ? Functions::purifyContent($subheading) : $subheading;
            $tmp->article = $purify ? Functions::purifyContent($article) : $article;
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
            $array['image']??($array['img']??0), 
            $array['subheading'],
            $array['purify']??true
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

    static public function isContentArray($article)
    {
        if (Functions::testVar($article) && is_array($article)) {
            return array_key_exists('id', $article)
                && array_key_exists('header', $article)
                && array_key_exists('subheading', $article)
                && array_key_exists('img', $article)
                && array_key_exists('article', $article);
        }
        return false;
    }

    static public function getToId($article)
    {
        if (Functions::testVar($article)) {
            if (is_int($article) && self::existsId($article)) {
                return $article;
            } elseif (self::isContentArray($article)) {
                if (Functions::testVar($article['id'])) {
                    // dangerous recursion!
                    return self::getToId($article['id']);
                } else {
                    return self::createNewFrom($article);
                }
            }
        }
        return null;
    }

    static public function getArticle(
        $article, bool $toArray = false, 
        bool $imgAsArr = true
    ) {
        if (Functions::testVar($article)) {
            if ($article instanceof self) {
                return $toArray ? $article->toContentArray($imgAsArr) : $article;
            } elseif (is_int($article)) {
                if (Functions::testVar($a = self::getFromId($article))) {
                    return $toArray ? $a->toContentArray($imgAsArr) :$a;
                }
            } elseif (self::isContentArray($article) && self::existsId($article['id'])) {
                return $toArray ? $article : self::getFromId($article['id']);
            }
        }
        return null;
    }

    public function toContentArray(bool $imgAsArr = true)
    {
        return self::makeContentArray(
            $this->article, $this->header,
            $this->image_id, $this->subheading,
            $imgAsArr, $this->id
        );
    }

    static public function makeContentArray(
        string $article, string $header = '',
        $img = null, string $subheading = '',
        bool $imgAsArr = false, int $id = 0
    ) {
        return [
            'id' => $id,
            'header' => $header,
            'subheading' => $subheading,
            'img' => $imgAsArr ? Image::getImageArray($img) : Image::getImage($img),
            'article' => $article,
        ];
    }

}
