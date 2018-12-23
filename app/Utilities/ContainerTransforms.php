<?php

namespace App\Utilities;

use App\Utilities\Functions\Functions;
use Illuminate\Support\Collection,
    Illuminate\Http\Request;
use App\Page,
    App\Article,
    App\Image;
use Illuminate\Contracts\Support\Arrayable;

interface TransformableContainer
{
    const TO_MINI_TRANSFORM = 'mini';
    const TO_FULL_TRANSFORM = 'full';
    const TO_SIDEBAR_TRANSFORM = 'sidebar';
    const TO_CONTENT_ARRAY_TRANSFORM = 'content';
    const TO_CONTENT_ARRAY_PLUS_TRANSFORM = 'content_plus';
    const TO_NAME_LIST_TRANSFORM = 'name';
    const TO_URL_FRAGMENT_TRANSFORM = 'fragment';
    const TO_URL_LIST_TRANSFORM = 'url';

    static public function getOrderByKey();

    public function getUrlFragment(string $baseUrl, bool $fullUrl = false);

    public function getFullUrl(string $baseUrl, bool $fullUrl = false);

    public function toContentArray(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false
    );

    public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = true,
        bool $done = true, string $dir = 'asc'
    );

    /* public function toContentArrayWithPagination(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false, int $pageNum = 0, 
        int $numItemsPerPage = 4, string $pagingFor = '', 
        int $viewNumber = 0, string $listUrl = '#'
    ); */

    /* public function toTableArray(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true
    ); */

    public function toFull(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false
    );

    public function toMini(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false
    );

    public function toSidebar(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false
    );
}

trait ContainerTransforms
{
    

    static public function makeSidebar(
        string $url, string $img, string $alt,
        string $price = ''
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
        $price, $id = 0, string $sticker = ''
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

    static public function makeDefaultBaseContentIterArray(
        array $value = null, array $children = null, bool $done = true
    ) {
        return [
            'value' => $value,
            'children' => $children,
            'done' => $done,
        ];
    }

    static public function makeBaseContentArray(
        string $name, string $url, $img, $article, 
        string $title, array $dates = [], 
        array $otherImages = null,
        $children = [], $hasChildren = null, 
        bool $done = true
    ) {
        return self::makeDefaultBaseContentIterArray(
            [
                'name' => $name,
                'path' => $url,
                'url' => $url,
                'img' => Image::getImageArray($img),
                'title' => $title,
                'article' => Article::getArticle($article, true),
                'otherImages' => $otherImages??[],
                'dates' => $dates??[],
                'hasChildren' => is_null($hasChildren)
                    ? Functions::countHas($children)
                    : (is_bool($hasChildren) ? $hasChildren : false)
            ],
            Functions::testVar($children) 
                ? Functions::arrayableToArray($children)
                : null,
            $done
        );
    }

    static public function makeBaseContentIterArray(
        string $name, string $url, $img, $article, 
        string $title, int $pageNumber, int $numPages, 
        int $numPerPage, $children = [], 
        $paginator = null, array $dates = [], 
        array $otherImages = null, $hasChildren = null,
        bool $usePagingFor = false, int $numView = 0, 
        string $pagingFor = ''
    ) {
        $nPN = $pageNumber > 0 ? $pageNumber + 1 : 1;
        $done = $nPN > $numPages;
        $res = self::makeBaseContentArray(
            $name, $url, $img, $article, $title, $dates, 
            $otherImages, $children, $hasChildren, $done
        );
        if ($nPN > 0 && $nPN <= $numPages) {
            $res['value']['next'] = $url . '?' . http_build_query(
                $usePagingFor 
                ? [
                    'viewNum' => $numView, 
                    'pageNum'=> $nPN,
                    'pagingFor' => $pagingFor,
                    'limit' => $numPerPage,
                ]
                : [
                    'page' => $nPN,
                    'limit' => $numPerPage,
                ]
            );
            $res['done'] = false;
            $res['value']['done'] = false;
        } elseif ($done) {
            $res['done'] = true;
            $res['value']['done'] = true;
        }
        return $res;
    }

    public function getFullUrl(string $baseUrl, bool $fullUrl = false)
    {
        $surl = $this->getUrlFragment($baseUrl);
        $url = $surl . $this->url;
        return $fullUrl ? url($url) : $url;
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
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1
    ) {
        $tmp = self::getOrdered($dir, $withTrashed);
        return self::getFor(
            $tmp, $baseUrl, $transform,
            $useTitle, $version, $withTrashed, 
            $fullUrl
        );
    }

    static public function genFirstAndLastItemsIdxes( 
        int $numItems, int $pageNum = 0, int $numItemsPerPage = 4
    ) {
        $numPages = Functions::genRowsPerPage(
            $numItems, $numItemsPerPage
        );
        if ($pageNum > 0 && $pageNum >= $numPages) {
            $pageNum = $pageNum % $numPages;
        } elseif ($pageNum < 0) {
            $pageNum = -$pageNum;
            if ($pageNum >= $numPages) {
                $pageNum = $pageNum % $numPages;
            } 
            $pageNum = $numPages - $pageNum;
        } 
        $first = max(0, $pageNum * $numItemsPerPage);
        if ($first > $numItems) {
            $first -= $numItems;
        }
        $last = $first + $numItemsPerPage;
        if ($last >= $numItems) {
            $last -= ($last - $numItems);  
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
     * OBSOLETE!!! Use genPagination2() instead!
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
     * The Current OFFICIAL Pagination Information 
     * Generator as the API of the 
     * lib.themewagon.paginator Component
     * has CHANGED!
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
            //'itemsPerPage' => $numItemsShownOnPage,
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
     * OBSOLETE!!! Use genPagination2() instead!
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
        Request $request, string $pagingFor, int $limit = 4,
        string $dir = 'asc'
    ) {
        if ($request->has('pageNum') && $request->has('pagingFor') && $request->has('viewNum')) {
            if ($pagingFor == $request->input('pagingFor')) {
                $res = [
                    'pageNum' => $request->input('pageNum'),
                    'viewNum' => $request->input('viewNum'),
                ];
                if ($request->has('limit')) {
                    $res['limit'] = $request->input('limit');
                } else {
                    $res['limit'] = $limit;
                }
                if ($request->has('order')) {
                    $res['order'] = $request->input('order');
                } else {
                    $res['order'] = $dir;
                }
                return $res;
            }
        } elseif ($request->has('page')) {
            $res = [
                'pageNum' => $request->input('page'),
                'viewNum' => 0,
            ];
            if ($request->has('limit')) {
                $res['limit'] = $request->input('limit');
            } else {
                $res['limit'] = $limit;
            }
            if ($request->has('order')) {
                $res['order'] = $request->input('order');
            } else {
                $res['order'] = $dir;
            }
            return $res;
        }
        return null;
    }

    static public function getPaginatedItemsArray(
        $args, int $pageNum, int $numShown = 4, 
        string $pagingFor = '', string $listUrl = '', 
        int $viewNumber = 0, int $totalNum = 0 
    ) {
        $num = $totalNum > 0 ? $totalNum : count($args);
        $pageNum = $pageNum ?: 1;
        if (Functions::testVar($args) && $num > 0) {
            if ($totalNum > 0) {
                if (is_array($args)) {
                    $items = $args;
                } elseif ($args instanceof Collection) {
                    $items = $args->all();
                } elseif ($args instanceof Arrayable) {
                    $items = $args->toArray();
                } else {
                    $items = [];
                }
            } else {
                $tp = collect($args)->forPage($pageNum, $numShown);
                $items = $tp->all();
            }
            if (count($items) > 0) {
                return [
                    'items' => $items,
                    'pagination' => self::genPagingFor(
                        $pageNum, $num, $numShown, 
                        $pagingFor, $viewNumber, 
                        $listUrl
                    )
                ];
            }
        }
        return [];
    }

    static public function getAllWithPagination(
        $transform, int $pageNum, int $numShown = 4, 
        string $pagingFor = '', string $dir = 'asc', 
        bool $withTrashed = true, string $baseUrl = 'store', 
        string $listUrl = '', int $viewNumber = 0, 
        bool $fullUrl = false, bool $useTitle = true, 
        int $version = 1
    ) {
        $totalNum = self::count();
        $pageIdx = self::genFirstAndLastItemsIdxes( 
            $totalNum, $pageNum, $numShown
        );
        $tmp = self::getOrderedBy($dir, $withTrashed)
            ->offset($pageIdx['begin'])->limit($numShown)
            ->get();
        $tmp1 = self::getFor(
            $tmp, $baseUrl, $transform,
            $useTitle, $version, $withTrashed, $fullUrl
        );
        return self::getPaginatedItemsArray(
            $tmp1, $pageNum, $numShown, 
            $pagingFor, $listUrl, 
            $viewNumber, $totalNum
        );
    }

    static public function doTransform(
        $item, $transform = null, string $baseUrl = 'store',
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, bool $fullUrl = false, 
        $default = null, bool $useBaseMaker = true,
        bool $done = true, string $dir = 'asc'
    ) {
        if ($item instanceof self) {
            if (is_string($transform) && !empty($transform)) {
                switch ($transform) {
                case 'mini':
                    return $item->toMini($baseUrl, $version, $useTitle, $withTrashed, $fullUrl);
                case 'full':
                    return $item->toFull($baseUrl, $version, $useTitle, $withTrashed, $fullUrl);
                case 'sidebar':
                    return $item->toSidebar($baseUrl, $version, $useTitle, $withTrashed, $fullUrl);
                case 'content':
                    return $item->toContentArray($baseUrl, $version, $useTitle, $withTrashed, $fullUrl);
                case 'content_plus':
                    return $item->toContentArrayPlus(
                        $baseUrl, $version, $useTitle, $withTrashed, 
                        $fullUrl, $useBaseMaker, $done, $dir
                    );
                case 'name':
                    return $item->toNameListing();
                case 'fragment':
                    return $item->toUrlFragrment($baseUrl, $fullUrl);
                case 'url':
                return $item->toUrlListing($baseUrl, $fullUrl);
                }
            } elseif (is_callable($transform)) {
                return $transform($item, $baseUrl, $version, $useTitle, $withTrashed, $fullUrl);
            } elseif (is_bool($transform) && $transform) {
                /// allows $transform be a renamed $asArray..
                return $item->toContentArray($baseUrl, $version, $useTitle, $withTrashed, $fullUrl);
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
        bool $withTrashed = true, bool $fullUrl = false, 
        $default = [], bool $useBaseMaker = true,
        bool $done = true, string $dir = 'asc'
    ) {
        if (Functions::countHas($args)) {
            if (empty($transform)) {
                return Functions::arrayableToArray($args, $args);
            } else {
                $res = [];
                foreach ($args as $item) {
                    if (Functions::testVar(
                        $tmp = self::doTransform(
                            $item, $transform, $baseUrl, $useTitle,
                            $version, $withTrashed, $fullUrl, null,
                            $useBaseMaker, $done, $dir
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
     *  and Generating the Children Array for a 
     *  BaseMakerContentArray..
    */
    static public function getForWithPagination(
        $args, $transform, int $pageNum,
        int $numShown = 4, string $pagingFor = '', 
        string $listUrl = '', string $baseUrl = 'store', 
        string $dir = 'asc', int $viewNumber = 0, 
        bool $withTrashed = true, bool $useTitle = true, 
        bool $fullUrl = false, int $version = 1, $default = [], 
        bool $useBaseMaker = true, bool $done = false
    ) {
        $cTmp = count($args);
        if ($cTmp <= $numShown) {
            $argTmp = $args;
        } else {
            $pageIdx = self::genFirstAndLastItemsIdxes( 
                $cTmp, $pageNum, $numShown
            );
            $paging = Functions::genRange(
                $pageIdx['begin'], ($pageIdx['end'] - 1)
            );
            $argTmp = [];
            foreach ($paging as $idx) {
                $argTmp[] = $args[$idx];
            }
        }
        $tmp = self::getFor(
            $argTmp, $baseUrl, $transform, $useTitle, $version,
            $withTrashed, $fullUrl, $default, $useBaseMaker, $done,
            $dir
        );
        if (Functions::testVar($tmp) && Functions::countHas($tmp)) {
            return self::getPaginatedItemsArray(
                $tmp, $pageNum, $numShown, 
                $pagingFor, $listUrl, 
                $viewNumber
            );///
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
        bool $fullUrl = false, int $version = 1, $default = []
    ) {
        if (is_array($args) || $args instanceof Collection) {
            $cTmp = count($args);
            if ($cTmp <= $numShown && $cTmp > 0) {
                $argTmp = $args instanceof Collection 
                    ? $args->all() 
                    : $args;
            } elseif ($cTmp > 0) {
                $pageIdx = self::genFirstAndLastItemsIdxes( 
                    $cTmp, $pageNum, $numShown
                );
                $paging = Functions::genRange(
                    $pageIdx['begin'], ($pageIdx['end'] - 1)
                );
                $argTmp = [];
                foreach ($paging as $idx) {
                    $argTmp[] = $args[$idx];
                }
            }
            $tmp = [];
            foreach ($argTmp as $item) {
                if ($item instanceof self) {
                    if ($transform) {
                        if ($res = $item->toContentArrayWithPagination(
                            $baseUrl, $version, $useTitle, $withTrashed,
                            $fullUrl, $pageNum, $numShown, $pagingFor, 
                            $viewNumber, $listUrl
                        )
                        ) {
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
        bool $withTrashed = true, bool $fullUrl = false
    ) {
        return self::getFor(
            $arrays, $baseUrl, 
            TransformableContainer::TO_CONTENT_ARRAY_TRANSFORM,
            $useTitle, $version, $withTrashed, $fullUrl, $default
        );
    }

    public function toUrlListing(string $baseUrl, bool $fullUrl = false)
    {
        $url = $this->getFullUrl($baseUrl, false);
        return [
            'name' => $this->name,
            'url' =>  $fullUrl ? url($url) : $url, 
        ];
    }

    public function toUrlFragrment(string $baseUrl, bool $fullUrl = false)
    {
        $url = $this->getUrlFragment($baseUrl);
        return [
            'name' => $this->name,
            'url' =>  $fullUrl ? url($url) : $url, 
        ];
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