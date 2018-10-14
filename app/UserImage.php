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
        if (!Functions::testVar($user_id = User::getUserId($user))) {
            return null;
        }
        if (!Functions::testVar($image_id = Image::getImageToID($image))) {
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
        
        if (!Functions::testVar($user_id = User::getUserId($user))) {
            return null;
        }
        $tmp = self::where('user_id', $user_id)->get();
        return Image::getAllForPivots($tmp, $toArray);
    }

    static public function getForImage($img)
    {
        if (!Functions::testVar($img_id = Image::getImageToID($img))) {
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
