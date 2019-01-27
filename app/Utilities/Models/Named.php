<?php

namespace App\Utilities\Models;

use App\Utilities\Functions\Functions;

abstract class Named extends Basic
{

    final static public function acceptableOrderingByKey($key)
    {
        return !empty($key) && is_string($key);
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

    public function getParentUrl(string $baseUrl, bool $fullUrl = false)
    {
        return $fullUrl ? url($baseUrl) : $baseUrl;
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

    public function getPubName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return $this->url;
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
        $extraWhereby = null, bool $getAll = false
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
            $extraWhereby = self::getExtraWhereBy($extraWhereby);
            if (Functions::countHas($extraWhereby)) {
                foreach ($extraWhereby as $value) {
                    $where[] = $value;
                    $orWhere[] = $value;
                }
            }
        }
        $query = $withTrashed 
        ? self::withTrashed()->where($where)->orWhere($orWhere)
        : self::where($where)->orWhere($orWhere);
        return $getAll
        ? $query->get()
        : $query->first();
    }

    // this one is not final-ed because some Use-rs may have need to 
    //  overide it with further ways of retrieval..
    static public function getFrom($item, bool $withTrashed = true)
    {
        if (Functions::testVar($item)) {
            if (is_string($item)) {
                return self::getNamed(
                    $item, $withTrashed, null, false
                );
            } else {
                return parent::getFrom($item, $withTrashed);
            }
        } 
        return null;
    }

    static public function getIdFrom(
        $item, bool $usePublic = true, $def = 0
    ) {
        $item_id = $def;
        if (is_string($item)) {
            $t = self::getNamed($item, true, null, false);
            if (Functions::testVar($t)) {
                $item_id = $usePublic 
                ? $t->getPubId()
                : $t->id;
            }
        } else {
            $item_id = parent::getIdFrom($item, $usePublic, $def);
        }
        return $item_id;
    }
}