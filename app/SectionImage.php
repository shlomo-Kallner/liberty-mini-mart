<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Utilities\Functions\Functions;
use App\Section;
use App\Image;

class SectionImage extends Pivot
{
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
        );
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

    static public function getAllImages($section) 
    {
        if ($section instanceof Section) {
            $section_id = $section->id;
        } elseif (is_int($section) && $section > 0) {
            $section_id = $section;
        } else {
            return null;
        }
        $t = self::where('section', $section_id)->get();
        return Image::getAllForPivots($t);
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
        $t = self::where('image', $image_id)->find();
        if (Functions::testVar($t)) {
            return $t->section;
        } else {
            return null;
        }
    }
}
