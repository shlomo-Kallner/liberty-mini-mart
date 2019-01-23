<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User,
    App\Product,
    App\Image;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Utilities\Functions\Functions,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer,
    Illuminate\Database\Eloquent\SoftDeletes;

class ProductReview extends Model implements TransformableContainer
{
    use SoftDeletes, ContainerTransforms {
        ContainerTransforms::getFrom as private traitGetFrom;
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function createNew(
        $user, int $product_id, int $rating, string $content,
        bool $retObj = false
    ) {
        if ($user_id = User::getUserId($user)) {
            //$datetime = Carbon::make($date);
            $tmp = new self;
            $tmp->product_id = $product_id;
            $tmp->user_id = $user_id;
            $tmp->content = Functions::purifyContent($content);
            $tmp->rating = $rating;
            if ($tmp->save()) {
                return $retObj ? $tmp : $tmp->id;
            }
        }
        return null;
    }

    static public function createNewFrom(
        array $args, bool $retObj = false
    ) {
        return self::createNew(
            $args['user'], $args['product_id'], $args['rating'],
            $args['content'], $retObj
        );
    }

    static public function getFrom($id, bool $withTrashed = true)
    {
        if (is_array($id) && Functions::countHas($id)) {
            $where = [];
            if (Functions::hasPropKeyIn($id, 'user')) {
                $where[] = [
                    'user_id', '=', Functions::getPropKey($id, 'user')
                ];
            }
            if (Functions::hasPropKeyIn($id, 'rating')) {
                $where[] = [
                    'rating', '=', Functions::getPropKey($id, 'rating')
                ];
            }
            if (Functions::hasPropKeyIn($id, 'product')) {
                $where[] = [
                    'product_id', '=', Functions::getPropKey($id, 'product')
                ];
            }
            if (Functions::countHas($where)) {
                return $withTrashed
                ? self::withTrashed()->where($where)->get()
                : self::where($where)->get();
            }
        } 
        return self::traitGetFrom($id, $withTrashed);
    }

    static public function makeOldContentArray(
        string $content, int $rating, $date = null,
        string $author = '', int $id = 0, bool $useFormattedDate = true
    ) {
        if ($date instanceof Carbon) {
            $datetime = $useFormattedDate 
                ? $date->toArray()['formatted']
                : $date;
        } elseif (empty($date)) {
            $date = Carbon::now();
            $datetime =$useFormattedDate 
                ?  $date->toArray()['formatted']
                : $date;
        } elseif (is_array($date)) {
            $datetime = $useFormattedDate && Functions::hasPropKeyIn($date, 'formatted') 
                ? Functions::getPropKey($date, 'formatted')
                : $date;
        } else {
            $datetime = $date;
        }
        return [
            'id' => $id,
            'author' => $author,
            'date' => $datetime,
                //'30/12/2013 - 07:37',
            'rating' => $rating,
            'content' =>  e($content),
        ];
    }
    
    public function toOldContentArray(bool $useFormattedDate = true)
    {
        $date = $this->created_at >= $this->updated_at 
        ? $this->created_at : $this->updated_at;
        $name = $this->user->name;
        return self::makeOldContentArray(
            $this->content, $this->rating, 
            $date, $name, $this->id, $useFormattedDate
        );
    }

    static public function getContentArrays(
        $arrays, bool $useFormattedDate = true, $default = []
    ) {
        $res = $default;
        if (Functions::testVar($arrays)
            && (is_array($arrays) || $arrays instanceof Collection)
        ) {
            foreach ($arrays as $val) {
                if (Functions::testVar($val) && $val instanceof self) {
                    $res[] = $val->toOldContentArray($useFormattedDate);
                }
            }
        }
        return $res;
    }

    static public function genUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        $url = empty($baseUrl) ? 'review/' : $baseUrl . '/review/';
        return $fullUrl ? url($url) : $url;
    }

    public function getParentUrl(string $baseUrl, bool $fullUrl = false)
    {
        return $this->product->getFullUrl($baseUrl, $fullUrl);
    }

    public function getImageArray()
    {
        return $this->user->getImageArray();
    }

    public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        if ($useBaseMaker) {
            $name = $this->product->name;
            $content = self::makeBaseContentArray(
                $name, $this->getFullUrl($baseUrl, $fullUrl), 
                $this->getImageArray(), null, 
                'Review of ' . $name . ' by ' . $this->user->name, 
                $this->getDatesArray(), 
                null, [], false, true, ''
            );
            $content['value']['id'] = $this->id;
            $content['value']['rating'] = $this->rating;
            $content['value']['content'] = $this->content;
            return $content;
        } else {
            return $this->toOldContentArray();
        }
    }

    static public function getChildrenFor(
        $args, string $baseUrl = 'store', $transform = null, 
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, bool $fullUrl = false, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    );

    static public function getSelf(
        string $baseUrl = 'store', bool $withTrashed = true,
        bool $fullUrl = false, $children = [], 
        $paginator = null, string $pagingFor = ''
    ) {
        $str = 'A Product Review';
        return Image::createImageArray(
            'experience-3239623_640.jpg', $str, 
            'images/site', $str, 0
        );
    }

    /// Eloquent relationships..

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id')->withDefault();
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id')->withDefault();
    }

    /// end of the Eloquent Relationship methods.
}
