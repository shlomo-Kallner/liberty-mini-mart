<?php

namespace App\Utilities;

use App\Utilities\Functions\Functions;
use Illuminate\Support\Collection;
use App\Page;

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

    static public function getAllWithPagination(
        $transform, $pageNum, $firstIndex, $lastIndex, int $numShown = 4,
        string $pagingFor = '', string $dir = 'asc', 
        bool $withTrashed = true, string $baseUrl = 'store', 
        bool $useTitle = true, int $version = 1
    ) {
        $tmp = self::getAllWithTransform(
            $transform, $dir, $withTrashed, $baseUrl, 
            $useTitle, $version
        );
        $num = count($tmp);
        return [
            'items' => $tmp,
            'pagination' => Page::genPagination(
                $pageNum, 
                $firstIndex <= $num ? $firstIndex : 0,
                $lastIndex <= $num ? $lastIndex : 0,
                $num,
                Page::genRange(0, $num), $numShown, $pagingFor
            )
        ];
    }

    static public function doTransform(
        $item, $transform = null, string $baseUrl = 'store',
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, $default = null
    ) {
        if ($item instanceof self) {
            if (is_string($transform) && !empty($transform)) {
                switch ($transform) {
                case 'mini':
                    return $item->toMini($baseUrl, $version, $useTitle, $withTrashed);
                case 'full':
                    return $item->toFull($baseUrl, $version, $useTitle, $withTrashed);
                case 'sidebar':
                    return $item->toSidebar($baseUrl, $version, $useTitle, $withTrashed);
                case 'content':
                    return $item->toContentArray($baseUrl, $version, $useTitle, $withTrashed);
                case 'name':
                    return $item->toNameListing();
                }
            } elseif (is_callable($transform)) {
                return $transform($item, $baseUrl, $version, $useTitle, $withTrashed);
            } elseif (is_bool($transform) && $transform) {
                /// allows $transform be a renamed $asArray..
                return $item->toContentArray($baseUrl, $version, $useTitle, $withTrashed);
            } else {
                return $item;
            }
        } else {
            return $default;
        }
    }

    static public function getFor(
        $args, string $baseUrl = 'store', $transform = null, 
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, $default = []
    ) {
        if ((is_array($args) || $args instanceof Collection) 
            && count($args) > 0
        ) {
            if (empty($transform)) {
                return $args instanceof Collection 
                    ? $args->all() 
                    : $args;
            } else {
                $res = [];
                foreach ($args as $item) {
                    if (Functions::testVar(
                        $tmp = self::doTransform(
                            $item, $transform, $baseUrl, $useTitle,
                            $version, $withTrashed, null
                        )
                    )
                    ) {
                        $res[] = $tmp;
                    }
                }
            }
            return $res;
        } else {
            return $default;
        }
    }

    static public function getForWithPagination(
        $args, $transform, $pageNum, $firstIndex, $lastIndex, 
        int $numShown = 4, string $pagingFor = '', string $dir = 'asc', 
        bool $withTrashed = true, string $baseUrl = 'store', 
        bool $useTitle = true, int $version = 1, $default = []
    ) {
        $tmp = self::getFor(
            $args, $baseUrl, $transform, $useTitle, $version,
            $withTrashed, $default
        );
        if (Functions::testVar($tmp)) {
            $num = count($tmp);
            return [
                'items' => $tmp,
                'pagination' => Page::genPagination(
                    $pageNum, 
                    $firstIndex <= $num ? $firstIndex : 0,
                    $lastIndex <= $num ? $lastIndex : 0,
                    $num,
                    Page::genRange(0, $num), $numShown, $pagingFor
                )
            ];
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