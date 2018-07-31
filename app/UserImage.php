<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\User;

class UserImage extends Pivot
{
    static public function createNewFromUser(User $user)
    {
        $tmp = new self;
        $tmp->user = $user->id;
        $tmp->image = $user->image;
        if ($tmp->save()) {
            return $tmp->id;
        } else {
            return null;
        }
    }
}
