<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Utilities\Functions\Functions;

class ProductReview extends Model
{
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
            if($tmp->save()) {
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
        if (is_int($id)) {
            return $withTrashed
                ? self::withTrashed()->where('id', $id)->first()
                : self::where('id', $id)->first();
        } elseif (is_array($id)) {
            $where = [];
            if (Functions::hasPropKeyIn($id, 'user')) {
                $where[] = [
                    'user_id', '=', Functions::getPropKey($id, 'user')
                ];
            }
            if (Functions::hasPropKeyIn($id, 'rating')) {
                $where[] = [
                    'rating', '=', Functions::getPropKey($id, 'user')
                ];
            }
            return $withTrashed
                ? self::withTrashed()->where($where)->get()
                : self::where($where)->get();
        } else {
            return null;
        }
    }

    static public function getFromId($id, bool $withTrashed = true) 
    {
        return self::getFrom($id, $withTrashed);
    }

    static public function exists($id, bool $withTrashed = true)
    {
        return Functions::testVar(self::getFrom($id, $withTrashed));
    }

    static public function existsId($id, bool $withTrashed = true)
    {
        return self::exists($id, $withTrashed);
    }

    static public function makeContentArray(
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
    
    public function toContentArray(bool $useFormattedDate = true)
    {
        $date = $this->created_at >= $this->updated_at 
        ? $this->created_at : $this->updated_at;
        $name = User::existsId($this->user_id) 
        ? User::getFromId($this->user_id)->name
        : '';
        return self::makeContentArray(
            $this->content, $this->rating, 
            $date, $name, $this->id, $useFormattedDate
        );
    }

    static function getContentArrays(
        $arrays, bool $useFormattedDate = true, $default = []
    ) {
        $res = $default;
        if (Functions::testVar($arrays)
            && (is_array($arrays) || $arrays instanceof Collection)
        ) {
            foreach ($arrays as $val) {
                if (Functions::testVar($val) && $val instanceof self) {
                    $res[] = $val->toContentArray($useFormattedDate);
                }
            }
        }
        return $res;
    }
}
