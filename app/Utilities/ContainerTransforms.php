<?php

namespace App\Utilities;

use App\Utilities\Functions\Functions;
use Illuminate\Support\Collection;

interface TransformableContainer
{
    const TO_MINI_TRANSFORM = 'mini';
    const TO_FULL_TRANSFORM = 'full';
    const TO_SIDEBAR_TRANSFORM = 'sidebar';
    const TO_CONTENT_ARRAY_TRANSFORM = 'content';
    const TO_NAME_LIST_TRANSFORM = 'name';

    static public function getOrderByKey();

    public function toContentArray(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true
    );

    public function toFull(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true
    );

    public function toMini(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true
    );

    public function toSidebar(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true
    );
}

trait ContainerTransforms
{
    

    static public function makeSidebar(
        string $url, string $img, string $alt,
        string $price = '', int $id = 0
    ) {
        return [
            'url' => $url,
            'img' => $img,
            'alt' => $alt,
            'price' => $price,
            /* 'price' => $this->sale != '' || $this->sale != $this->price 
                ? $this->sale 
                : $this->price, */
        ];
    }

    static public function makeMini(
        string $img, string $name, string $url,
        $price, int $id = 0, string $sticker = ''
    ) {
        return [
            'img' => $img,
            'name' => $name,
            'id' => $id,
            'url' => $url,
            'price' => $price,
            'sticker' => $sticker,
        ];
    }
    
    static public function getNamed(
        string $name, bool $withTrashed = false, $orderingBy = null
    ) {
        $where = [
            ['url', '=', $name],
        ];
        $orWhere = [
            ['name', '=', $name],
        ];
        if (!empty($orderingBy)) {
            $where[] = [self::getOrderByKey(), '=', $orderingBy];
            $orWhere[] = [self::getOrderByKey(), '=', $orderingBy];
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

    static public function getAll(
        string $dir = 'asc', bool $withTrashed = true
    ) {
        $tmp = $withTrashed 
            ? self::withTrashed()
                ->orderBy(self::getOrderByKey(), $dir)
                ->get() 
            : self::orderBy(self::getOrderByKey(), $dir)->get();
        if (Functions::testVar($tmp) && count($tmp) > 0) {
            return $tmp->all();
        }
        return null;
    }

    static public function getAllWithTransform(
        $transform = null, string $dir = 'asc', 
        bool $withTrashed = true, string $baseUrl = 'store', 
        bool $useTitle = true, int $version = 1
    ) {
        $tmp = $withTrashed 
            ? self::withTrashed()
                ->orderBy(self::getOrderByKey(), $dir)
                ->get() 
            : self::orderBy(self::getOrderByKey(), $dir)->get();
        return self::getFor(
            $tmp, $baseUrl, $transform,
            $useTitle, $version, $withTrashed
        );
    }

    static public function getFor(
        $args, string $baseUrl = 'store', $transform = null, 
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, $default = []
    ) {
        if ((is_array($args) || $args instanceof Collection) 
        && count($args) > 0) {
            $res = [];
            foreach ($args as $item) {
                if ($item instanceof self) {
                    if (is_string($transform) && !empty($transform)) {
                        switch ($transform) {
                        case 'mini':
                            $res[] = $item->toMini($baseUrl, $version, $useTitle, $withTrashed);
                            break;
                        case 'full':
                            $res[] = $item->toFull($baseUrl, $version, $useTitle, $withTrashed);
                            break;
                        case 'sidebar':
                            $res[] = $item->toSidebar($baseUrl, $version, $useTitle, $withTrashed);
                            break;
                        case 'content':
                            $res[] = $item->toContentArray($baseUrl, $version, $useTitle, $withTrashed);
                            break;
                        case 'name':
                            $res[] = $item->toNameListing();
                            break;
                        }
                    } elseif (is_callable($transform)) {
                        $res[] = $transform($item, $baseUrl, $version, $useTitle, $withTrashed);
                    } else {
                        $res[] = $item;
                    }
                }
            }
            return $res;
        } else {
            return $default;
        }
    }

    static public function getContentArrays(
        $arrays, string $baseUrl = 'store', $default = [],
        int $version = 1, bool $useTitle = true, 
        bool $withTrashed = true
    ) {
        return self::getFor(
            $arrays, $baseUrl, 
            TransformableContainer::TO_CONTENT_ARRAY_TRANSFORM,
            $useTitle, $version, $withTrashed, $default
        );
    }

    public function toNameListing()
    {
        return [
            'name' => $this->name,
            'url' => $this->url, /// the identifying url fragment,
                                 /// NOT the full URL!!!
        ];
    }

    static public function getNameListingOf($array)
    {
        $res = [];
        if (is_array($array) || $array instanceof Collection) {
            foreach ($tmp as $item) {
                if ($item instanceof self) {
                    $res[] = $item->toNameListing();
                }
            }
        }
        return $res;
    }

    static public function getNameListing(
        bool $withTrashed = false, string $dir = 'asc'
    ) {
        $tmp = $withTrashed 
            ? self::withTrashed()
                ->orderBy(self::getOrderByKey(), $dir)
                ->get() 
            : self::orderBy(self::getOrderByKey(), $dir)->get();
        return self::getNameListingOf($tmp);
    }

}