<?php

namespace App\Utilities;

use App\Utilities\Functions\Functions, 
    Illuminate\Database\Eloquent\SoftDeletes,
    Traversable;

interface ContainerAPI
{
    const DELETED_AT = 'deleted_at';

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

    public function getDatesArray();

    public function getPubId();

    public function getPubName();
}

trait ContainerID 
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    ///protected $dates = ['deleted_at'];

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

    final public function getDatesArray()
    {
        $dates = Functions::genDatesArray(
            $this->created_at, $this->updated_at,
            $this->deleted_at
        );
        return $dates;
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
            } elseif (is_string($item)) {
                return self::getNamed(
                    $item, $withTrashed, null, false
                );
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
            $tid = Functions::getPropKey($item, 'id', $def);
            $item_id  = self::getIdFrom($tid, $usePublic, $def);
        } elseif (is_int($item) && self::existsId($item, true)) {
            $item_id = $item;
        } elseif (is_string($item)) {
            $t = self::getNamed($item, true, null, false);
            if (Functions::testVar($t)) {
                $item_id = $usePublic 
                ? $t->getPubId()
                : $t->id;
            }
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
     *                            (uses columns 'name' and 'url' respectively).
     * @param mixed  $withTrashed - pass 'true' to use soft deleted
     *                            items or 'false' to not use them.
     * @param mixed  $extraWhereby  - May be a <value> or an array.
     *                              - If is a <value>, it is added as comparing as 
     *                              equal to refine the result while using 
     *                              self::getOrderByKey() to get the 
     *                              column key.
     *                              - If is an array, it is an array that
     *                              self::getExtraWhereBy() will accept.
     * @param bool $getAll - get all that might use the name or just the first.
     * 
     * @return mixed|null
     */
    static public function getNamed(
        string $name, bool $withTrashed = false, 
        $extraWhereby = null, bool $getAll = false
    ) {
        if (Functions::testVar($name)) {
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
        return null;
    }

    /// end of defaults.. 

    static public function isInListOf($item, $args) 
    {
        $bol = false;
        if ((($args instanceof Traversable) || is_array($args))
            && $item instanceof self
        ) {
            $item_id = $item->id;
            if (Functions::testVar($item_id)) {
                foreach ($args as $arg) {
                    if ($arg instanceof self) {
                        if ($item === $arg) {
                            $bol = true;
                            break;
                        } else {
                            $arg_id = $arg->id;
                            if (Functions::testVar($arg_id) && $item_id === $arg_id) {
                                $bol = true;
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $bol;
    }
}