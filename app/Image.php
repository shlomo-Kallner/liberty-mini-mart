<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Utilities\Functions\Functions,
    App\Utilities\ContainerAPI,
    App\Utilities\ContainerID;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;


class Image extends Model implements ContainerAPI
{
    use SoftDeletes, ContainerID;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function createNew(
        string $name, string $path, string $alt, 
        string $caption, bool $retObj = false
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
                return $retObj ? $tmp : $tmp->id;
            }
        } elseif (Functions::testVar($tC) || count($tC) === 1) {
            return $retObj ? $tC[0] : $tC[0]->id;
        }
        return null;
    }

    static public function createNewFromArray(
        array $array, bool $retObj = false
    ) {
        return self::createNew(
            $array['name'], $array['path'], 
            $array['alt'], $array['caption'],
            $retObj
        );
    }

    static public function createNewFrom(
        array $array, bool $retObj = false
    ) {
        return self::createNewFromArray($array, $retObj);
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

    static public function getRandomImage(bool $withTrashed = false)
    {
        $num = $withTrashed 
            ? self::withTrashed()->count()
            : self::count();
        if (Functions::testVar($num)) {
            if ($num > 1) {
                $idx = random_int(1, $num);
                return Functions::getVar($ti = self::getFromId($idx), self::getFromId(1));
            } else {
                return self::getFromId(1);
            }
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

    /**
     * Function makeImageArray() - a factory function for the central
     *                            organized creation of image arrays. 
     *
     * @param integer $id
     * @param string $img
     * @param string $alt
     * @param string $cap
     * @return array
     */
    static public function makeImageArray(
        int $id = 0, string $img = '',
        string $alt = '', string $cap = ''
    ) {
        return [
            'id' => $id,
            'img' => $img,
            'alt' => $alt,
            'cap' => $cap
        ];
    }

    /**
     * Function makeContentArray() - Common API function name 
     *                              (is an alias for makeImageArray().)
     *
     * @param integer $id
     * @param string $img
     * @param string $alt
     * @param string $cap
     * @return array
     */
    static public function makeContentArray(
        int $id = 0, string $img = '',
        string $alt = '', string $cap = ''
    ) {
        return self::makeImageArray($id, $img, $alt, $cap);
    }

    static public function getImageArray($image)
    {
        $tmp = self::getImage($image);
        if (Functions::testVar($tmp)) {
            if ($tmp instanceof self) {
                return $tmp->toImageArray();
            } elseif (self::isImageArray($tmp)) {
                return $tmp;
            } 
        }
        return null;
    }

    public function toContentArray()
    {
        return $this->toImageArray();
    }

    public function toImageArray()
    {
        $imgPath = Functions::testVar($this->path) ? $this->path . '/' : '';
        return self::makeImageArray(
            $this->id, $imgPath . $this->name, 
            $this->alt, $this->caption
        );
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
}
