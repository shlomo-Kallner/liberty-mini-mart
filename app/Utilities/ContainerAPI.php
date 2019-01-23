<?php

namespace App\Utilities;

use App\Utilities\Functions\Functions;

interface ContainerAPI
{
    static public function createNewFrom(array $array, bool $retObj = false);

    static public function genUrlFragment(string $baseUrl, bool $fullUrl = false);

    /// all of these below have defaults defined in the Trait below.
    
    static public function getFromId(int $id, bool $withTrashed = true);

    static public function existsId(int $id, bool $withTrashed = true);

    static public function getFrom($item, bool $withTrashed = true);

    static public function getIdFrom($item, bool $usePublic = true, $def = 0);

    static public function exists($item, bool $withTrashed = true);

    static public function getOrderByKey();

    static public function getNamedByKey();

    static public function getUrlByKey();

    public function getUrl();

    public function getParentUrl(string $baseUrl, bool $fullUrl = false);

    public function getUrlFragment(string $baseUrl, bool $fullUrl = false);

    public function getFullUrl(string $baseUrl, bool $fullUrl = false);

    public function getPubId();

    public function getPubName();
}

trait ContainerID 
{
    /// some trait defined final-ed methods:

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

    final static public function acceptableOrderingByKey($key)
    {
        return !empty($key) && is_string($key);
    }

    final public function getUrlFragment(string $baseUrl, bool $fullUrl = false)
    {
        // {$tmp[0]}/section/{section}/category/{category}/product/{product}
        $surl = $this->getParentUrl($baseUrl, false);
        $url = self::genUrlFragment($surl, false);
        return $fullUrl ? url($url) : $url;
    }

    final public function getFullUrl(string $baseUrl, bool $fullUrl = false)
    {
        $surl = $this->getUrlFragment($baseUrl);
        $url = $surl . $this->getUrl();
        return $fullUrl ? url($url) : $url;
    }

    /**
     * Function getExtraWhereBy() - retrieve the extra Where 
     *                              Parameters from an Array 
     *                              or some other Array-like Object.
     *                            
     * @param array|\Countable $whereBy - It is either [a] an array of 
     *                                    '[<Column>, <op>, <Value>]'
     *                                    arrays that where() accepts.
     *                                  - [b] an array of 'Key => Value' pairs
     *                                    (Key MUST be a string!), where Key is the
     *                                    Column key, to refine the results while 
     *                                    comparing for equality.
     *                                  - or [c] an array of 'Key => [Value, Op]'
     *                                    (Key MUST be a string!), where Key is the
     *                                    Column key, to refine the results while 
     *                                    comparing for Op or equality by default.
     * @return array
     */
    final static public function getExtraWhereBy($whereBy = null)
    {
        $where = [];
        if (Functions::testVar($whereBy) && Functions::countHas($whereBy)) {
            foreach ($whereBy as $key => $value) {
                if (Functions::countHas($value)) {
                    if (count($value) == 2 
                        && self::acceptableOrderingByKey($key)
                    ) {
                        $where[] = [$key, $value[1]??'=', $value[0]];
                    } elseif (count($value) == 3 && is_int($key)) {
                        $where[] = $value;
                    }
                } elseif (self::acceptableOrderingByKey($key)) {
                    $where[] = [$key, '=', $value];
                }
            }
        }
        return $where;
    }

    // end of final-ed methods.


    /// some trait defined defaults, 
    ///  redefine in using class to override
    ///  see PHP Language, Trait docs for details.

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

    static public function getOrderByKey()
    {
        return 'id';
    }

    static public function getNamedByKey()
    {
        return 'name';
    }

    static public function getUrlByKey()
    {
        return 'url';
    }

    public function getPubId()
    {
        return $this->id;
    }

    public function getPubName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return $this->url;
    }
    
    static public function getIdFrom(
        $item, bool $usePublic = true, $def = 0
    ) {
        $item_id = $def;
        if ($item instanceof self) {
            $item_id = $usePublic 
            ? $item->getPubId()
            : $item->id;
        } elseif (Functions::isPropKeyIn($item, 'id')) {
            $item_id = Functions::getPropKey($item, 'id', $def);
        } elseif (is_int($item) && self::existsId($item)) {
            $item_id = $item;
        }
        return $item_id;
    }

    public function getParentUrl(string $baseUrl, bool $fullUrl = false)
    {
        return $fullUrl ? url($baseUrl) : $baseUrl;
    }

    /** 
     * Method getNamed()
     * 
     * @param string $name        - the name or url to search for 
     *                             (uses columns 'name' and 'url' respectively).
     * @param mixed  $withTrashed - pass 'true' to use soft deleted
     *                             items or 'false' to not use them.
     * @param mixed  $extraWhereby  - May be a <value> or an array.
     *                              - If is a <value>, it is added as comparing as 
     *                                equal to refine the result while using 
     *                                self::getOrderByKey() to get the 
     *                                column key.
     *                              - If is an array, it is an array that
     *                                self::getExtraWhereBy() will accept.
     * 
     * @return mixed|null
    */
    static public function getNamed(
        string $name, bool $withTrashed = false, 
        $extraWhereby = null
    ) {
        $where = [
            [self::getUrlByKey(), '=', $name],
        ];
        $orWhere = [
            [self::getNamedByKey(), '=', $name],
        ];
        $key = self::getOrderByKey();
        if (Functions::testVar($extraWhereby) 
            && !Functions::countHas($extraWhereby)
            && self::acceptableOrderingByKey($key)
        ) {
            $where[] = [$key, '=', $extraWhereby];
            $orWhere[] = [$key, '=', $extraWhereby];
        } else {
            $extraWhereby = self::getExtraWhereBy($orderingBy);
            if (Functions::countHas($extraWhereby)) {
                foreach ($extraWhereby as $value) {
                    $where[] = $value;
                    $orWhere[] = $value;
                }
            }
        }
        return $withTrashed 
            ? self::withTrashed()
            ->where($where)
            ->orWhere($orWhere)
            ->first()
            : self::where($where)
            ->orWhere($orWhere)
            ->first();
    }

    /// end of defaults.. 
}