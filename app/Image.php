<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Utilities\Functions\Functions;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;


class Image extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function createNew(
        string $name, string $path, string $alt, 
        string $caption
    ) {
        $tC = self::where(
            [
                ['name', '=', $name],
                ['path', '=', $path]
            ]
        )->get();
        if (!Functions::testVar($tC) || count($tC) === 0) {       
            $tmp = new self;
            $tmp->name = $name;
            $tmp->path = $path;
            $tmp->alt = $alt;
            $tmp->caption = $caption;
            if ($tmp->save()) {
                return $tmp->id;
            }
        } elseif (Functions::testVar($tC) || count($tC) === 1) {
            return $tC[0]->id;
        }
        return null;
    }

    static public function createNewFromArray(array $array)
    {
        return self::createNew(
            $array['name'], $array['path'], 
            $array['alt'], $array['caption']
        );
    }

    static public function createNewFrom(array $array)
    {
        return self::createNewFromArray($array);
    }

    static public function getImageToID($image)
    {
        if (is_array($image) && !self::isImageArray($image)) {
            return self::createNewFrom($image);
        } elseif (is_array($image) && self::isImageArray($image)) {
            return self::existsId($image['id']) ? $image['id'] : null;
        } elseif (is_int($image) && self::existsId($image)) {
            return $image;
        } elseif ($image instanceof self && self::existsId($image->id)) {
            return $image->id;
        } else {
            return null;
        }
    }

    static public function isImageArray($image)
    {
        if (Functions::testVar($image) && is_array($image)) {
            return array_key_exists('id', $image)
                && array_key_exists('img', $image)
                && array_key_exists('alt', $image)
                && array_key_exists('cap', $image);
        } else {
            return false;
        }
    }

    static public function getImage($image)
    {
        if (is_int($image) && self::existsId($image)) {
            return self::getFromId($image);
        } elseif ($image instanceof self) {
            return $image;
        } elseif (self::isImageArray($image) ) {
            return $image;
        } else {
            return null;
        }
    }

    static public function getImageArray($image)
    {
        $tmp = self::getImage($image);
        if (Functions::testVar($tmp)) {
            if ($tmp instanceof self) {
                $imgPath = Functions::testVar($tmp->path) ? $tmp->path . '/' : '';
                $img = $imgPath . $tmp->name;
                $alt = $tmp->alt;
                $cap = $tmp->caption;
                $id = $tmp->id;
            } elseif (self::isImageArray($tmp)) {
                return $tmp;
            } else {
                $img = '';
                $alt = '';
                $cap = '';
                $id = '';
            }
        } else {
            $img = '';
            $alt = '';
            $cap = '';
            $id = '';
        }
        return [
            'id' => $id,
            'img' => $img,
            'alt' => $alt,
            'cap' => $cap
        ];
    }

    public function toImageArray()
    {
        return self::getImageArray($this);
    }

    static public function getArraysFor($images)
    {
        $res = [];
        if (Functions::testVar($images) && count($images) > 0) {
            foreach ($images as $img) {
                if ($img instanceof self) {
                    $res[] = $img->toImageArray();
                }
            }
        }
        return $res;
    }

    static public function getAllForPivots($pivots, bool $toArray = false)
    {
        if (Functions::testVar($pivots)) {
            $res = [];
            foreach ($pivots as $pivot) {
                if ($pivot instanceof Pivot) {
                    $t = self::getFromId($pivot->image_id);
                    if (Functions::testVar($t)) {
                        if ($toArray) {
                            $res[] = self::getImageArray($t);
                        } else {
                            $res[] = $t;
                        }
                        
                    }
                }
            }
            return $res;
        } else {
            return null;
        }
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
