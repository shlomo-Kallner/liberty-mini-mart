<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Utilities\Functions\Functions;
use App\Image;
use App\User;

class UserImage extends Pivot
{
    
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_images';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


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
                ['image_id', '=', $image_id],
                ['user_id', '=', $user_id]
            ]
        )->first();
        if (Functions::testVar($t2)) {
            //dd('$t2');
            return $t2->id;
        } else {
            $tmp = new self;
            $tmp->user_id = $user_id;
            $tmp->image_id = $image_id;
            if ($tmp->save()) {
                //dd('$tmp');
                return $tmp->id;
            } else {
                return null;
            }
        }
    }

    static public function createNewFrom(User $user)
    {
        return self::createNew($user->id, $user->image_id);
    }

    static public function getAllImages($user, bool $toArray = false) 
    {
        
        if ($user instanceof User) {
            $user_id = $user->id;
        } elseif (is_int($user) && $user > 0) {
            $user_id = $user;
        } else {
            return null;
        }
        $tmp = self::where('user_id', $user_id)->get();
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
            return $t->user;
        } else {
            return null;
        }
    }
}
