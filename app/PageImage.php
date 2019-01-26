<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot,
    App\Utilities\Functions\Functions,
    App\Utilities\ImagePivotAPI,
    App\Utilities\ImagePivot,
    App\Page;

class PageImage extends Pivot implements ImagePivotAPI
{
    use ImagePivot;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_images';
    
    static public function getOthersKey() 
    {
        return 'page_id';
    }

    static public function getIdFromOther($other)
    {
        return Page::getIdFrom($other, false, null);
    }

    static public function getOtherClassName()
    {
        return 'App\Page';
    }

    static public function createNewFrom(Page $page, bool $retObj = false)
    {
        return self::createNew($page, $page->image_id, $retObj);
    }

    public function page()
    {
        return $this->belongsTo('App\Page', 'page_id');
    }
}
