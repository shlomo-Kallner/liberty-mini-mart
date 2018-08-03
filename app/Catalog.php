<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    static public function getFromId(int $id)
    {
        return self::where('id', $id)->find();
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
