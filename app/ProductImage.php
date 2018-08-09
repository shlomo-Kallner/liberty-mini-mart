<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Utilities\Functions\Functions;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Image;
use App\Product;


class ProductImage extends Pivot
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_images';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function createNew($product, $image)
    {
        if ($product instanceof Product) {
            $product_id = $product->id;
        } elseif (is_int($product) && $product > 0) {
            $product_id = $product;
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
                ['product', '=', $product_id]
            ]
        )->first();
        if (Functions::testVar($t2)) {
            return $t2->id;
        } else {
            $tmp = new self;
            $tmp->product = $product_id;
            $tmp->image = $image_id;
            if ($tmp->save()) {
                return $tmp->id;
            } else {
                return null;
            }
        }
    }

    static public function createNewFrom(Product $product)
    {
        return self::createNew($product->id, $product->image);
    }

    static public function getAllImages($product, bool $toArray = false) 
    {
        
        if ($product instanceof Product) {
            $product_id = $product->id;
        } elseif (is_int($product) && $product > 0) {
            $product_id = $product;
        } else {
            return null;
        }
        $tmp = self::where('product', $product_id)->get();
        return Image::getAllForPivots($tmp, $toArray);
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
        $t = self::where('image', $img_id)->find();
        if (Functions::testVar($t)) {
            return $t->product;
        } else {
            return null;
        }
    }
}
