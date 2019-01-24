<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot,
    App\Utilities\Functions\Functions,
    App\Utilities\ImagePivotAPI,
    App\Utilities\ImagePivot,
    App\Product;


class ProductImage extends Pivot implements ImagePivotAPI
{
    use ImagePivot;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_images';
    
    static public function getOtherKey() 
    {
        return 'product_id';
    }

    static public function getIdFromOther($other)
    {
        return Product::getIdFrom($other, false, null);
    }

    static public function getOtherClassName()
    {
        return 'App\Product';
    }

    static public function createNewFrom(Product $product, bool $retObj = false)
    {
        return self::createNew($product, $product->image_id, $retObj);
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
