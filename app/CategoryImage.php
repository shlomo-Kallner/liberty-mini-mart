<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Utilities\Functions\Functions;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Image;
use App\Categorie;

class CategoryImage extends Pivot
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category_images';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function createNew($category, $image)
    {
        if ($category instanceof Categorie) {
            $category_id = $category->id;
        } elseif (is_int($category) && $category > 0) {
            $category_id = $category;
        } else {
            return null;
        }
        if ($image instanceof Image) {
            $image_id = $image->id;
        } elseif (is_int($image) && $image > 0) {
            $image_id = $image;
        } else {
            return null;
        }
        // duplication avoidance..
        $t2 = self::where(
            [
                ['image', '=', $image_id],
                ['category', '=', $category_id]
            ]
        )->first();
        if (Functions::testVar($t2)) {
            return $t2->id;
        } else {
            $tmp = new self;
            $tmp->category = $category_id;
            $tmp->image = $image_id;
            if ($tmp->save()) {
                return $tmp->id;
            } else {
                return null;
            }
        }
    }

    static public function createNewFrom(Categorie $category)
    {
        return self::createNew($category->id, $category->image);
    }

    static public function getAllImages($category) 
    {
        
        if ($category instanceof Categorie) {
            $category_id = $category->id;
        } elseif (is_int($category) && $category > 0) {
            $category_id = $category;
        } else {
            return null;
        }
        $tmp = self::where('category', $category_id)->get();
        return Image::getAllForPivots($tmp);
    }

    static public function getForImage($img)
    {
        if ($img instanceof Image) {
            $img_id = $img->id;
        } elseif (is_int($img) && $img > 0) {
            $img_id = $img;
        } else {
            return null;
        }
        $t = self::where('image', $img_id)->first();
        // redo!! return an array!
        if (Functions::testVar($t)) {
            return $t->category;
        } else {
            return null;
        }
    }
}
