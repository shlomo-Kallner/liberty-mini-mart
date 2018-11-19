<?php

namespace App\Utilities;

use App\Utilities\Functions\Functions;
use Illuminate\Support\Collection,
    Illuminate\Http\Request;
use App\Page,
    App\Image;

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

    public function toContentArrayWithPagination(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        int $pageNum, int $numItemsPerPage = 4, 
        string $pagingFor = '', int $viewNumber = 0, 
        string $listUrl = '#'
    );

    /* public function toTableArray(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true
    ); */

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
    
    /** 
     * Method getNamed()
     * 
     * @param string $name - the name or url to search for 
     *                     (uses columns 'name' and 'url' respectively).
     * @param mixed $withTrashed - pass 'true' to use soft deleted
     *                           items or 'false' to not use them.
     * @param mixed $orderingBy - May be a <value> or an array.
     *                          If is a <value>:
     *                          - it is added as comparing as 
     *                          equal to refine the result while using 
     *                          self::getOrderByKey() to get the 
     *                          column key.
     *                          If is an array:
     *                          - it is either:
     *                          --[a] an array of '[<Column>, <op>, <Value>]'
     *                          arrays that where() accepts.
     *                          --[b] an array of 'Key => Value' pairs
     *                          (Key MUST be a string!), where Key is the
     *                          Column key, to refine the results while 
     *                          comparing for equality.
     *                          --or [c] an array of 'Key => [Value, Op]'
     *                          (Key MUST be a string!), where Key is the
     *                          Column key, to refine the results while 
     *                          comparing for Op or equality by default.
     * 
     * @return mixed|null
    */
    static public function getNamed(
        string $name, bool $withTrashed = false, 
        $orderingBy = null
    ) {
        $where = [
            ['url', '=', $name],
        ];
        $orWhere = [
            ['name', '=', $name],
        ];
        if (!empty($orderingBy) && !is_array($orderingBy)) {
            $where[] = [self::getOrderByKey(), '=', $orderingBy];
            $orWhere[] = [self::getOrderByKey(), '=', $orderingBy];
        } elseif (!empty($orderingBy) && is_array($orderingBy)) {
            foreach ($orderingBy as $key => $value) {
                if (is_array($value)) {
                    if (count($value) == 2 && is_string($key)) {
                        $where[] = [$key, $value[1]??'=', $value[0]];
                        $orWhere[] = [$key, $value[1]??'=', $value[0]];
                    } elseif (count($value) == 3 && is_int($key)) {
                        $where[] = $value;
                        $orWhere[] = $value;
                    }
                } elseif (is_string($key)) {
                    $where[] = [$key, '=', $value];
                    $orWhere[] = [$key, '=', $value];
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

    static public function getOrderedBy(
        string $dir = 'asc', bool $withTrashed = true,
        $orderingBy = null
    ) {
        if (!empty($orderingBy) && is_string($orderingBy)) {
            $key = $orderingBy;
        } elseif (is_callable($orderingBy)) {
            $key = $orderingBy();
        } else {
            $key = self::getOrderByKey();
        }
        return $withTrashed 
            ? self::withTrashed()
                ->orderBy($key, $dir)
            : self::orderBy($key, $dir);
    }

    static public function getOrdered(
        string $dir = 'asc', bool $withTrashed = true,
        $orderingBy = null
    ) {
        return self::getOrderedBy(
            $dir, $withTrashed, $orderingBy
        )->get();
    }

    static public function getAll(
        string $dir = 'asc', bool $withTrashed = true,
        $orderingBy = null
    ) {
        $tmp = self::getOrdered($dir, $withTrashed, $orderingBy);
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
        $tmp = self::getOrdered($dir, $withTrashed);
        return self::getFor(
            $tmp, $baseUrl, $transform,
            $useTitle, $version, $withTrashed
        );
    }

    static public function genFirstAndLastItemsIdxes( 
        int $numItems, int $pageNum = 0, int $numItemsPerPage = 4
    ) {
        $numPages = Functions::genRowsPerPage(
            $numItems, $numItemsPerPage
        );
        if ($pageNum > 0 && $pageNum > $numPages) {
            $pageNum = $pageNum % $numPages;
        } elseif ($pageNum < 0) {
            $pageNum = -$pageNum;
            if ($pageNum > $numPages) {
                $pageNum = $pageNum % $numPages;
            } 
            $pageNum = $numPages - $pageNum;
        } 
        $first = max(0, $pageNum * $numItemsPerPage);
        if ($first >= 0 && $first < $numItems) {
            $last = $first + $numItemsPerPage;
            if ($last >= $numItems) {
                $last -= ($last - $numItems);  
            } 
        }
        return [
            // 'begin' and 'end' are indexes into a $items2 array..
            'begin' => $first, 
            'end' => $last,
            'index' => $pageNum, // the current page's index
        ];
    }
    
    /**
     * Function genPagination() - Generate Pagination Information as acceptable by
     *                            'lib.themewagon.paginator'...
     * All Numbers passed are indexes starting from zero, 
     * although they are displayed by the component as starting from one,
     * based on the calculations made in 'lib.themewagon.content_list'.
     *
     * @param integer $pageNum               - the current page number.
     * @param integer $firstItemShownOnPage  - the index of the first item being shown
     *                                       (not the First of the total range, unless 
     *                                       this is the first page).
     * @param integer $lastItemShownOnPage   - the index of the last item being shown
     *                                       (not the Last of the total range, unless 
     *                                       this is the last page).
     * @param integer $totalItems            - the total number of items that can be paged through
     * @param array $rangeOfAllItemIndexes   - an array created by Functions::genRange()
     *                                       of all the indexes of all the items..
     * @param integer $numPagesPerPagingView - the number of items..
     * @param string $pagingFor - a selector string for web-pages with more than one 
     *                          paginating view-port, to select the correct view for 
     *                          updating by the backend.
     * @param integer $viewNumber - The current (or to-be Current) Display-Page's index,
     *                            as received via user input (the user having clicked 
     *                            on a pagination link). (May actually have to do with 
     *                            Display-Pages of the PAGINATION's own links!)
     * @param string $baseUrl - The URL to be used in generating pagination links.
     * @return array 
     */
    static public function genPagination1(
        int $pageNum, int $numItemsShownOnPage, int $totalItems, 
        array $rangeOfAllItemIndexes, int $numPageLinksPerPagingView = 4,
        string $pagingFor = '', int $viewNumber = 0, string $baseUrl = ''
    ) {
        return [
            'currentRange' => self::genFirstAndLastItemsIdxes( 
                $totalItems, $pageNum, $numItemsShownOnPage
            ),
            'totalItems' => $totalItems, // the total number of items in the $items2 array
            'ranges' => $rangeOfAllItemIndexes,
            'numPerView' => $numPageLinksPerPagingView,
            'pagingFor' => $pagingFor,
            'viewNumber' => $viewNumber,
            'baseUrl' => $baseUrl
        ];
    }

    /** 
     * Function genPagination2()  
     * The Current OFFICIAL Pagination Generator!
    */
    static public function genPagination2(
        int $pageNum, int $numItemsShownOnPage, int $totalItems, 
        int $numPageLinksPerPagingView = 4, string $pagingFor = '', 
        int $viewNumber = 0, string $baseUrl = ''
    ) {
        $totalNumPages = Functions::genRowsPerPage(
            $totalItems, $numItemsShownOnPage
        );
        $itemIdxs = self::genFirstAndLastItemsIdxes(
            $totalItems, $pageNum, $numItemsShownOnPage
        );
        return [
            'firstItemIndex' => $itemIdxs['begin'],
            'lastItemIndex' => $itemIdxs['end'] - 1,
            'numItemsPerPage' => $numItemsShownOnPage,
            'totalItems' => $totalItems,
            'currentPage' => $pageNum,
            'totalNumPages' => $totalNumPages,
            'numPerView' => $numPageLinksPerPagingView,
            'viewNumber' => $viewNumber,
            'pagingFor' => $pagingFor,
            'baseUrl' => $baseUrl,
        ];
    }
    
    /**
     * Function genPagination() - Generate Pagination Information as acceptable by
     *                            'lib.themewagon.paginator'...
     * All Numbers passed are indexes starting from zero, 
     * although they are displayed by the component as starting from one,
     * based on the calculations made in 'lib.themewagon.content_list'.
     *
     * @param integer $pageNum               - the current page number.
     * @param integer $firstItemShownOnPage  - the index of the first item being shown
     *                                       (not the First of the total range, unless 
     *                                       this is the first page).
     * @param integer $lastItemShownOnPage   - the index of the last item being shown
     *                                       (not the Last of the total range, unless 
     *                                       this is the last page).
     * @param integer $totalItems            - the total number of items that can be paged through
     * @param array $rangeOfAllItemIndexes   - an array created by Functions::genRange()
     *                                       of all the indexes of all the items..
     * @param integer $numPagesPerPagingView - the number of items..
     * @param string $pagingFor - a selector string for web-pages with more than one 
     *                          paginating view-port, to select the correct view for 
     *                          updating by the backend.
     * @param integer $viewNumber - The current (or to-be Current) Display-Page's index,
     *                            as received via user input (the user having clicked 
     *                            on a pagination link). (May actually have to do with 
     *                            Display-Pages of the PAGINATION's own links!)
     * @param string $baseUrl - The URL to be used in generating pagination links.
     * @return array 
     */
    static public function genPagination(
        int $pageNum, int $firstItemShownOnPage, int $lastItemShownOnPage,
        int $totalItems, array $rangeOfAllItemIndexes, int $numPagesPerPagingView = 4,
        string $pagingFor = '', int $viewNumber = 0, string $baseUrl = ''
    ) {
        return [
            'currentRange' => [
                'index' => $pageNum, // the current page's index
                // 'begin' and 'end' are indexes into a $items2 array..
                'begin' => $firstItemShownOnPage, 
                'end' => $lastItemShownOnPage,
            ],
            'totalItems' => $totalItems, // the total number of items in the $items2 array
            'ranges' => $rangeOfAllItemIndexes,
            'numPerView' => $numPagesPerPagingView,
            'pagingFor' => $pagingFor,
            'viewNumber' => $viewNumber,
            'baseUrl' => $baseUrl
        ];
    }

    /**
     * Function genPagingFor() - A helper Function for using genPagination().
     * 
     * "All Numbers passed are indexes starting from zero, 
     * although they are displayed by the component as starting from one,
     * based on the calculations made in 'lib.themewagon.content_list'."
     *
     * @param integer $pageNum - The index of the Display-Page to be 
     *                         displayed.
     * @param integer $totalItems - The total number of items to be 
     *                            displayed across All Display-Pages.
     * @param integer $numItemsPerPage - The number of items to display
     *                                 per Display-Page.
     * @param string $pagingFor - a selector string for web-pages with 
     *                          more than one paginating view-port, to 
     *                          select the correct view for updating by 
     *                          the backend.
     * @param integer $viewNumber - The current (or to-be Current) 
     *                            Display-Page's index, as received via 
     *                            user input (the user having clicked on 
     *                            a pagination link). 
     *                            (May actually have to do with 
     *                            Display-Pages of the PAGINATION's own links!)
     * @param string $baseUrl - The URL to be used in generating pagination links.
     * @return array
     */
    static public function genPagingFor(
        int $pageNum, int $totalItems, int $numItemsPerPage = 4, 
        string $pagingFor = '', int $viewNumber = 0, 
        string $listUrl = '#', int $numPagingIdxsPerPagingView = 0
    ) {
        return self::genPagination2(
            $pageNum, $numItemsPerPage, $totalItems, 
            $numPagingIdxsPerPagingView, $pagingFor, 
            $viewNumber, $listUrl
        );
    }

    static public function getPagingVars(
        Request $request, string $pagingFor
    ) {
        if ($request->has('pageNum') && $request->has('pagingFor') && $request->has('viewNum')) {
            if ($pagingFor == $request->input('pagingFor')) {
                return [
                    'pageNum' => $request->input('pageNum'),
                    'viewNum' => $request->input('viewNum'),
                ];
            }
        }
        return null;
    }

    static public function getPaginatedItemsArray(
        $args, int $pageNum, int $numShown = 4, 
        string $pagingFor = '', string $listUrl = '', 
        int $viewNumber = 0 
    ) {
        $num = count($args);
        if (Functions::testVar($args) && $num > 0) {
            $tp = collect($args)->forPage($pageNum, $numShown);
            return [
                'items' => $tp->all(),
                'pagination' => self::genPagingFor(
                    $pageNum, $num, $numShown, 
                    $pagingFor, $viewNumber, 
                    $listUrl
                ),
            ];
        } else {
            return [];
        }
    }

    static public function getAllWithPagination(
        bool $transform, int $pageNum, int $numShown = 4, 
        string $pagingFor = '', string $dir = 'asc', 
        bool $withTrashed = true, string $baseUrl = 'store', 
        string $listUrl = '', int $viewNumber = 0, 
        bool $useTitle = true, int $version = 1
    ) {
        $paging = collect(Functions::genRange(0, self::count()));
        $page = $paging->forPage($pageNum, $numShown);
        $tmp = self::getOrderedBy($dir, $withTrashed)
            ->offset($page->first())->limit($numShown)
            ->get();
        $tmp1 = self::getFor(
            $tmp, $baseUrl, $transform,
            $useTitle, $version, $withTrashed
        );
        return self::getPaginatedItemsArray(
            $tmp1, $pageNum, $numShown, 
            $pagingFor, $listUrl, 
            $viewNumber
        );
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

    /** 
     * Function getForWithPagination() - 
     * 
     * Usefull for lib.themewagon.content_list, 
     *             lib.themewagon.sidebar,
     *             or lib.themewagon.bestsellers views..
    */
    static public function getForWithPagination(
        $args, $transform, int $pageNum,
        int $numShown = 4, string $pagingFor = '', 
        string $listUrl = '', string $baseUrl = 'store', 
        string $dir = 'asc', int $viewNumber = 0, 
        bool $withTrashed = true, bool $useTitle = true, 
        int $version = 1, $default = []
    ) {
        $cTmp = count($args);
        if ($cTmp <= $numShown) {
            $argTmp = $args;
        } else {
            $paging = collect(Functions::genRange(0, $cTmp));
            $page = $paging->forPage($pageNum, $numShown);
            $argTmp = [];
            foreach ($page as $idx) {
                $argTmp[] = $args[$idx];
            }
        }
        $tmp = self::getFor(
            $argTmp, $baseUrl, $transform, $useTitle, $version,
            $withTrashed, $default
        );
        if (Functions::testVar($tmp)) {
            return self::getPaginatedItemsArray(
                $tmp, $pageNum, $numShown, 
                $pagingFor, $listUrl, 
                $viewNumber
            );
        } else {
            return $default;
        }
    }

    static public function getForWithRecursivePagination(
        $args, int $pageNum, bool $transform = true,
        int $numShown = 4, string $pagingFor = '', 
        string $listUrl = '', string $baseUrl = 'store', 
        string $dir = 'asc', int $viewNumber = 0, 
        bool $withTrashed = true, bool $useTitle = true, 
        int $version = 1, $default = []
    ) {
        if (is_array($args) || $args instanceof Collection) {
            $cTmp = count($args);
            if ($cTmp <= $numShown && $cTmp > 0) {
                $argTmp = $args instanceof Collection 
                    ? $args->all() 
                    : $args;
            } elseif ($cTmp > 0) {
                $paging = collect(Functions::genRange(0, $cTmp));
                $page = $paging->forPage($pageNum, $numShown);
                $argTmp = [];
                foreach ($page as $idx) {
                    $argTmp[] = $args[$idx];
                }
            }
            $tmp = [];
            foreach ($argTmp as $item) {
                if ($item instanceof self) {
                    if ($transform) {
                        if ($res = $item->toContentArrayWithPagination(
                            $baseUrl, $version, $useTitle, $withTrashed,
                            $pageNum, $numShown, $pagingFor, $viewNumber, 
                            $listUrl
                        )) {
                            $tmp[] = $res;
                        }
                    } else {
                        $tmp[] = $item;
                    }
                }
            }
            if (Functions::testVar($tmp) && count($tmp) > 0) {
                return self::getPaginatedItemsArray(
                    $tmp, $pageNum, $numShown, 
                    $pagingFor, $listUrl, 
                    $viewNumber
                );
            } 
        }
        return $default;
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
        $tmp = self::getOrdered($dir, $withTrashed);
        return self::getNameListingOf($tmp);
    }

}