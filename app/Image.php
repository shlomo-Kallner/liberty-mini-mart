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

    static public function getImage($image)
    {
        if (is_int($image)) {
            return self::getFromId($image);
        } elseif ($image instanceof self) {
            return $image;
        } else {
            return null;
        }
    }

    static public function getImageArray($image)
    {
        $tmp = self::getImage($image);
        if (Functions::testVar($tmp)) {
            $imgPath = Functions::testVar($tmp->path) ? $tmp->path . '/' : '';
            $img = $imgPath . $tmp->name;
            $alt = $tmp->alt;
            $cap = $tmp->caption;
        } else {
            $img = '';
            $alt = '';
            $cap = '';
        }
        return [
            'img' => $img,
            'alt' => $alt,
            'cap' => $cap
        ];
    }

    static public function getAllForPivots($pivots)
    {
        if (Functions::testVar($pivots)) {
            $res = [];
            foreach ($pivots as $pivot) {
                if ($pivot instanceof Pivot) {
                    $t = self::getFromId($pivot->image);
                    if (Functions::testVar($t)) {
                        $res[] = $t;
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
