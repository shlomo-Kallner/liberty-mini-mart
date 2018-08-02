<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Utilities\Functions\Functions;
use App\Image;
use App\User;

class UserImage extends Pivot
{
    static public function createNew($user, $image)
    {
        if ($user instanceof User) {
            $user_id = $user->id;
        } elseif (is_int($user) && $user > 0) {
            $user_id = $user;
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
                ['user', '=', $user_id]
            ]
        );
        if (Functions::testVar($t2)) {
            return $t2->id;
        } else {
            $tmp = new self;
            $tmp->user = $user_id;
            $tmp->image = $image_id;
            if ($tmp->save()) {
                return $tmp->id;
            } else {
                return null;
            }
        }
    }

    static public function createNewFrom(User $user)
    {
        return self::createNew($user->id, $user->image);
    }

    static public function getAllImages($user) 
    {
        
        if ($user instanceof User) {
            $user_id = $user->id;
        } elseif (is_int($user) && $user > 0) {
            $user_id = $user;
        } else {
            return null;
        }
        $tmp = self::where('user', $user_id)->get();
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
            return $t->user;
        } else {
            return null;
        }
    }
}
