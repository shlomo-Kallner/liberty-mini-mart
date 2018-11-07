<?php

namespace App\Utilities;

use App\Utilities\Functions\Functions;

interface ContainerAPI
{
    static public function createNewFrom(array $array, bool $retObj = false);
    
    static public function getFromId(int $id, bool $withTrashed = true);

    static public function existsId(int $id, bool $withTrashed = true);
}

trait ContainerID 
{
    static public function getFromId(int $id, bool $withTrashed = true)
    {
        return $withTrashed 
            ? self::withTrashed()->where('id', $id)->first()
            : self::where('id', $id)->first();
    }

    static public function existsId(int $id, bool $withTrashed = true)
    {
        return Functions::testVar(self::getFromId($id, $withTrashed));
    }
}