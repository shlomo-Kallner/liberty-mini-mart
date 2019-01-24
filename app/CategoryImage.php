<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot,
    App\Utilities\Functions\Functions,
    App\Utilities\ImagePivotAPI,
    App\Utilities\ImagePivot,
    App\Categorie;

class CategoryImage extends Pivot implements ImagePivotAPI
{
    use ImagePivot;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'category_images';
    
    static public function getOtherKey() 
    {
        return 'category_id';
    }

    static public function getIdFromOther($other)
    {
        return Categorie::getIdFrom($other, false, null);
    }

    static public function getOtherClassName()
    {
        return 'App\Categorie';
    }

    static public function createNewFrom(Categorie $category, bool $retObj = false)
    {
        return self::createNew($category, $category->image_id, $retObj);
    }

    public function category()
    {
        return $this->belongsTo('App\Categorie', 'category_id');
    }
}
