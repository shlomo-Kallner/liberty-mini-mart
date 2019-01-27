<?php

namespace App\Utilities\Models;

use App\Utilities\Functions\Functions, 
    Illuminate\Database\Eloquent\SoftDeletes,
    Illuminate\Database\Eloquent\Model;

abstract class Basic extends Model 
{
    use SoftDeletes;

    const DELETED_AT = 'deleted_at';

    abstract static public function createNewFrom(array $array, bool $retObj = false);
    
    final static public function getFromId(int $id, bool $withTrashed = true)
    {
        return $withTrashed 
            ? self::withTrashed()->where('id', $id)->first()
            : self::where('id', $id)->first();
    }

    final static public function existsId(int $id, bool $withTrashed = true)
    {
        return Functions::testVar(self::getFromId($id, $withTrashed));
    }

    // final-ed because it just uses getFrom()..
    final static public function exists($item, bool $withTrashed = true)
    {
        return Functions::testVar(self::getFrom($item, $withTrashed));
    }

    // this one is not final-ed because some Use-rs may have need to 
    //  overide it with further ways of retrieval..
    static public function getFrom($item, bool $withTrashed = true)
    {
        if (Functions::testVar($item)) {
            if ($item instanceof self) {
                return $item;
            } elseif (is_int($item)) {
                return self::getFromId($item, $withTrashed);
            } 
        } 
        return null;
    }

    static public function getIdFrom($item, bool $usePublic = true, $def = 0) 
    {
        $item_id = $def;
        if ($item instanceof self) {
            $item_id = $usePublic 
            ? $item->getPubId()
            : $item->id;
        } elseif (Functions::isPropKeyIn($item, 'id')) {
            $item_id = Functions::getPropKey($item, 'id', $def);
        } elseif (is_int($item) && self::existsId($item, true)) {
            $item_id = $item;
        } 
        return $item_id;
    }

    final public function getDatesArray()
    {
        $dates = Functions::genDatesArray(
            $this->created_at, $this->updated_at,
            $this->deleted_at
        );
        return $dates;
    }

    public function getPubId()
    {
        return $this->id;
    }

}