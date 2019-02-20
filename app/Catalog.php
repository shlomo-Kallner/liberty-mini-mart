<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// WISHLIST ITEM!!
class Catalog extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function getIdFrom(
        $item, bool $usePublic = true, $def = 0
    ) {
        return $def; // ON IMPELENTING THIS CLASS USE THE TRAITS!
    }

    static public function getFromId(int $id)
    {
        return self::where('id', $id)->first();
    }

    static public function createNew()
    {
        //
    }

    static public function createNewFrom(array $array)
    {
        return self::createNew();//
    }
}
