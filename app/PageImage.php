<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Utilities\Functions\Functions;
use App\Image;
use App\Page;

class PageImage extends Pivot
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_images';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function createNew($page, $image)
    {
        if ($page instanceof Page) {
            $page_id = $page->id;
        } elseif (is_int($page) && $page > 0) {
            $page_id = $page;
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
                ['image_id', '=', $image_id],
                ['page_id', '=', $page_id]
            ]
        )->first();
        if (Functions::testVar($t2)) {
            return $t2->id;
        } else {
            $tmp = new self;
            $tmp->page_id = $page_id;
            $tmp->image_id = $image_id;
            if ($tmp->save()) {
                return $tmp->id;
            } else {
                return null;
            }
        }
    }

    static public function createNewFrom(Page $page)
    {
        return self::createNew($page->id, $page->image_id);
    }

    static public function getAllImages($page, bool $toArray = false) 
    {
        
        if ($page instanceof Page) {
            $page_id = $page->id;
        } elseif (is_int($page) && $page > 0) {
            $page_id = $page;
        } else {
            return null;
        }
        $tmp = self::where('page_id', $page_id)->get();
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
        $t = self::where('image_id', $img_id)->first();
        if (Functions::testVar($t)) {
            return $t->page;
        } else {
            return null;
        }
    }

    public function page()
    {
        return $this->belongsTo('App\Page', 'page_id');
    }

    public function image()
    {
        return $this->hasOne('App\Image', 'id', 'image_id');
    }
}
