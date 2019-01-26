<?php

namespace App\Utilities;

use App\Utilities\Functions\Functions,
    App\Image,
    Illuminate\Database\Eloquent\SoftDeletes;

interface ImagePivotAPI 
{
    const DELETED_AT = 'deleted_at';

    /// these 3 methods require overides in the implementing class.

    static public function getOthersKey();

    static public function getIdFromOther($other);

    static public function getOtherClassName();

    /// these methods have overides in the trait below.

    static public function createNew($other, $image, bool $retObj = false);

    static public function getAllImages($other, bool $toArray = false);

    static public function getFromImage(
        $image, bool $getAll = false, bool $withTrashed = false, 
        bool $retObj = false
    );

    static public function getForImage(
        $img, bool $getAll = false, bool $withTrashed = false, 
        bool $retObj = false
    );
}

trait ImagePivot
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    /// protected $dates = ['deleted_at'];

    static public function createNew($other, $image, bool $retObj = false)
    {
        $other_id = self::getIdFromOther($other);
        $image_id = Image::getImageToID($image);
        if (Functions::testVar($other_id) && Functions::testVar($image_id)) {
            // duplication avoidance..
            $key = self::getOthersKey();
            $t2 = self::where(
                [
                    ['image_id', '=', $image_id],
                    [$key, '=', $other_id]
                ]
            )->first();
            if (Functions::testVar($t2)) {
                return $retObj ? $t2 : $t2->id;
            } else {
                $tmp = new self;
                $tmp->$key = $other_id;
                $tmp->image_id = $image_id;
                if ($tmp->save()) {
                    return $retObj ? $tmp : $tmp->id;
                }
            }
        } 
        return null;
    }

    static public function getAllImages($other, bool $toArray = false) 
    {
        
        $other_id = self::getIdFromOther($other);
        if (Functions::testVar($other_id)) {
            $tmp = self::where(self::getOthersKey(), $other_id)->get();
            return Image::getAllForPivots($tmp, $toArray);
        }
        return null;
    }

    static public function getFromImage(
        $image, bool $getAll = false, bool $withTrashed = false, 
        bool $retObj = false
    ) {
        return self::getForImage(
            $img, $getAll, $withTrashed, $retObj
        );
    }

    static public function getForImage(
        $img, bool $getAll = false, bool $withTrashed = false, 
        bool $retObj = false
    ) {
        $image_id = Image::getImageToID($img);
        if (Functions::testVar($image_id)) {
            $key = self::getOthersKey();
            if ($getAll) {
                $tmp = $withTrashed
                ? self::withTrashed()->where('image_id', $image_id)->get()
                : self::where('image_id', $image_id)->get();
                if (Functions::testVar($tmp) && Functions::countHas($tmp)) {
                    $res = [];
                    foreach ($tmp as $val) {
                        $res[] = $retObj ? $val->other : $val->$key;
                    }
                    return $res;
                }
            } else {
                $t = $withTrashed
                ? self::withTrashed()->where('image_id', $image_id)->first()
                : self::where('image_id', $image_id)->first();
                if (Functions::testVar($t)) {
                    return $retObj ? $t->other : $t->$key;
                }
            } 
        }
        return null;
    }

    public function other()
    {
        return $this->belongsTo(self::getOtherClassName(), self::getOthersKey());
    }

    public function image()
    {
        return $this->belongsTo('App\Image', 'image_id');
    }
}
