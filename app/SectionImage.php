<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Utilities\Functions\Functions;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Section;
use App\Image;

class SectionImage extends Pivot
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'section_images';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function createNew($section, $image) 
    {
        if ($section instanceof Section) {
            $section_id = $section->id;
        } elseif (is_int($section) && $section > 0) {
            $section_id = $section;
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
                ['section', '=', $section_id]
            ]
        )->first();
        if (Functions::testVar($t2)) {
            return $t2->id;
        } else {
            $tmp = new self;
            $tmp->section = $section_id;
            $tmp->image = $image_id;
            if ($tmp->save()) {
                return $tmp->id;
            } else {
                return null;
            }
        }
    }

    static public function createNewFrom(Section $section) 
    {
        return self::createNew($section->id, $section->image);
    }

    static public function getAllImages($section, bool $toArray = false) 
    {
        if ($section instanceof Section) {
            $section_id = $section->id;
        } elseif (is_int($section) && $section > 0) {
            $section_id = $section;
        } else {
            return null;
        }
        $t = self::where('section', $section_id)->get();
        return Image::getAllForPivots($t, $toArray);
    }

    static public function getFromImage($image) 
    {
        if ($image instanceof Image) {
            $image_id = $image->id;
        } elseif (is_int($image) && $image > 0) {
            $image_id = $image;
        } else {
            return null;
        }
        $t = self::where('image', $image_id)->first();
        if (Functions::testVar($t)) {
            return $t->section;
        } else {
            return null;
        }
    }
}
