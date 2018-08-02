<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Utilities\Functions\Functions;
use App\Image;
use App\Page;

class PageImage extends Pivot
{
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
                ['image', '=', $image_id],
                ['page', '=', $page_id]
            ]
        );
        if (Functions::testVar($t2)) {
            return $t2->id;
        } else {
            $tmp = new self;
            $tmp->page = $page_id;
            $tmp->image = $image_id;
            if ($tmp->save()) {
                return $tmp->id;
            } else {
                return null;
            }
        }
    }

    static public function createNewFrom(Page $page)
    {
        return self::createNew($page->id, $page->image);
    }

    static public function getAllImages($page) 
    {
        
        if ($page instanceof Page) {
            $page_id = $page->id;
        } elseif (is_int($page) && $page > 0) {
            $page_id = $page;
        } else {
            return null;
        }
        $tmp = self::where('page', $page_id)->get();
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
        $t = self::where('image', $img_id)->find();
        if (Functions::testVar($t)) {
            return $t->page;
        } else {
            return null;
        }
    }
}
