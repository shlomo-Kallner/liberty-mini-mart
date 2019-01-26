<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot, 
    App\Utilities\Functions\Functions,
    App\Utilities\ImagePivotAPI,
    App\Utilities\ImagePivot,
    App\User;

class UserImage extends Pivot implements ImagePivotAPI
{
    
    use ImagePivot;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_images';

    static public function getOthersKey() 
    {
        return 'user_id';
    }

    static public function getIdFromOther($other)
    {
        return User::getIdFrom($other, false, null);
    }

    static public function getOtherClassName()
    {
        return 'App\User';
    }

    static public function createNewFrom(User $user, bool $retObj = false)
    {
        return self::createNew($user, $user->image_id, $retObj);
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
