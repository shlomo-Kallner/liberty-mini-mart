<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer;
use App\Image;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model implements TransformableContainer
{
    use SoftDeletes, ContainerTransforms;

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
        bool $retObj = false
    ) {
        if (Functions::testVar($article)) {
            $tImg = Image::getImageToID($image);
            $tmp = new self;
            $tmp->image_id = Functions::testVar($tImg) ? $tImg : 0;
            $tmp->header = Functions::purifyContent($header);
            $tmp->subheading = Functions::purifyContent($subheading);
            $tmp->article = Functions::purifyContent($article);
            if ($tmp->save()) {
                return $retObj ? $tmp : $tmp->id;
            }
        }
        return null;
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
                if (Functions::isPropKeyIn($article, 'id')) {
                    // dangerous recursion!
                    return self::getToId(Functions::getPropKey($article, 'id'));
                } else {
                    return self::createNewFrom($article);
                }
            }
        }
        return null;
    }

    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }

    static public function getArticle(
        $article, bool $toArray = false
    ) {
        if (Functions::testVar($article)) {
            if ($article instanceof self) {
                return $toArray ? $article->toArticleArray() : $article;
            } elseif (is_int($article)) {
                if (Functions::testVar($a = self::getFromId($article))) {
                    return $toArray ? $a->toArticleArray() : $a;
                }
            } elseif (self::isContentArray($article)) {
                if (Functions::testVar($b = self::getFromId($article['id']))) {
                    return $toArray ? $article : $b;
                } else {
                    $c = self::createNewFrom($article, true);
                    return $toArray ? $c->toArticleArray() : $c;
                }
            }
        }
        return null;
    }

    public function toArticleArray()
    {
        return self::makeArticleArray(
            $this->article, $this->header,
            $this->image, $this->subheading,
            $this->id
        );
    }

    static public function makeArticleArray(
        string $article, string $header = '',
        $img = null, string $subheading = '',
        int $id = 0
    ) {
        return [
            'id' => $id,
            'header' => $header,
            'subheading' => $subheading,
            'img' => Image::getImageArray($img),
            'article' => $article,
        ];
    }

    /// interface implementations..

    static public function createNewFrom(
        array $array, bool $retObj = false
    ) {
        return self::createNew(
            $array['article'], $array['header'],
            $array['image']??($array['img']??0), 
            $array['subheading'],
            $retObj
        );
    }

    static public function genUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        $url = empty($baseUrl) ? 'article/' : $baseUrl . '/article/';
        return $fullUrl ? url($url) : $url;
    }

    public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = true,
        bool $done = true, string $dir = 'asc'
    ) {
        if ($useBaseMaker) {
            $content = self::makeBaseContentArray(
                $this->id, $this->getFullUrl($baseUrl, $fullUrl), 
                $this->image, null, $this->header, 
                $this->getDatesArray(), 
                null, [], false, true, ''
            );
            $content['value']['article'] = $this->article;
            $content['value']['header'] = $this->header;
            $content['value']['subheading'] = $this->subheading;
        } else {
            $content = self::makeArticleArray(
                $this->article, $this->header,
                $this->image, $this->subheading,
                $this->id
            );
        }
        return $content;
    }

    public function numChildren(bool $withTrashed = true)
    {
        return 0;
    }

    public function getChildren(
        $transform = null, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, $default = [], bool $useBaseMaker = true,
        bool $done = true
    ) {
        return $default;
    }

    static public function getSelf(
        string $baseUrl = 'store', bool $withTrashed = true,
        bool $fullUrl = false, $children = [], 
        $paginator = null, string $pagingFor = ''
    ) {
        $title = $name = 'Articles';
        $article = null;
        $img = Image::createImageArray(
            'newspaper-2253408_640.jpg', 'Articles Listing', 
            'images/site', 'Articles Listing'
        );
        $pagingFor = $pagingFor ?: 'articlesPanel';
        return self::makeSelf(
            $name, $title, $article,
            $img, $baseUrl, $withTrashed,
            $fullUrl, $children, $paginator,
            $pagingFor, null
        );
    }

    /// trait overides..

    static public function getNamedByKey()
    {
        return 'id';
    }

    static public function getUrlByKey()
    {
        return 'id';
    }

    public function getUrl()
    {
        return $this->id;
    }

    public function getPubName()
    {
        return $this->id;
    }

}
