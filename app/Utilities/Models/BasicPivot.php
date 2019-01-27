<?php

namespace App\Utilities\Models;

use App\Utilities\Functions\Functions, 
    Illuminate\Database\Eloquent\SoftDeletes,
    Illuminate\Database\Eloquent\Relations\Pivot;

abstract class BasicPivot extends Pivot
{
    use SoftDeletes;

    const DELETED_AT = 'deleted_at';
    
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
            $otherClassName = self::getOtherClassName();
            if ($item instanceof self) {
                return $item;
            } elseif (is_int($item)) {
                return self::getFromId($item, $withTrashed);
            } elseif ($item instanceof $otherClassName) {
                $id = self::getIdFromOther($item);
                return $withTrashed
                ? self::withTrashed()->where(self::getOthersKey(), $id)->first()
                : self::where(self::getOthersKey(), $id)->first();
            }
        } 
        return null;
    }

    static public function getIdFrom($item, bool $usePublic = true, $def = 0) 
    {
        $item_id = $def;
        $otherClassName = self::getOtherClassName();
        if ($item instanceof self) {
            $item_id = $usePublic 
            ? $item->getPubId()
            : $item->id;
        } elseif (is_int($item) && self::existsId($item, true)) {
            $item_id = $item;
        } elseif ($item instanceof $otherClassName) {
            $item_id = self::getIdFrom(self::getFrom($item, true), $usePublic, $def);
        } elseif (Functions::isPropKeyIn($item, 'id')) {
            $item_id = Functions::getPropKey($item, 'id', $def);
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

    public function other()
    {
        return $this->belongsTo(self::getOtherClassName(), self::getOthersKey());
    }

    /// these 3 methods require overides in the implementing class.

    abstract static public function getOthersKey();

    abstract static public function getIdFromOther($other);

    abstract static public function getOtherClassName();
}

