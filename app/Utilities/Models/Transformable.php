<?php

namespace App\Utilities\Models;

use App\Utilities\Functions\Functions,
    Illuminate\Support\Collection,
    Illuminate\Http\Request,
    Illuminate\Support\Carbon,
    App\Page,
    App\Article,
    App\Image,
    Illuminate\Contracts\Support\Arrayable;

abstract class Transformable extends Named 
{
    const TO_MINI_TRANSFORM = 'mini';
    const TO_FULL_TRANSFORM = 'full';
    const TO_SIDEBAR_TRANSFORM = 'sidebar';
    const TO_CONTENT_ARRAY_TRANSFORM = 'content';
    const TO_CONTENT_ARRAY_PLUS_TRANSFORM = 'content_plus';
    const TO_NAME_LIST_TRANSFORM = 'name';
    const TO_URL_FRAGMENT_TRANSFORM = 'fragment';
    const TO_URL_LIST_TRANSFORM = 'url';

    abstract public function toContentArrayPlus(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false, bool $useBaseMaker = true,
        string $dir = 'asc'
    );

    abstract static public function getChildrenFor(
        $args, string $baseUrl = 'store', $transform = null, 
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, bool $fullUrl = false, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    );

    abstract static public function getSelf(
        string $baseUrl = 'store', bool $withTrashed = true,
        bool $fullUrl = false, $children = [], 
        $paginator = null, string $pagingFor = ''
    );

    /**
     * Function getChildrenQuery() - Returns the Eloquent Query Builder
     *                               that returns queries into the 
     *                               _children_ of a _using_ Model.
     *                             - While this method has a default 
     *                               in the trait below, it should be 
     *                               overiden by the _using_ Model if it
     *                               indeed has some _children_ to supply..
     *                             - Should return an Eloquent query-able object..
     *                             - The Default returns _null_..
     *
     * @return \Illuminate\Database\Query\Builder|null
     */
    public function getChildrenQuery(bool $withTrashed = true)
    {
        return null;
    }

    public function getParentQuery(bool $withTrashed = true)
    {
        return null;
    }

    public function numChildren(bool $withTrashed = true)
    {
        $query = $this->getChildrenQuery();
        if (Functions::testVar($query)) {
            return $withTrashed 
                ? $this->getChildrenQuery()->withTrashed()->count()
                : $this->getChildrenQuery()->count();
        } else {
            return 0;
        }
    }

    public function hasChildren(bool $withTrashed = true)
    {
        $num = $this->numChildren($withTrashed);
        return is_int($num)
        ? $num > 0
        : Functions::countHas($num);
    }

    public function getChildren(
        $transform = null, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, $default = [], bool $useBaseMaker = true
    ) {
        if ($this->hasChildren()) {
            $children = self::getOrderedFor(
                $this->getChildrenQuery($withTrashed), $dir, 
                $withTrashed, self::getChildrenOrderByKey()
            );
            return self::getChildrenFor(
                $children, $baseUrl, $transform, $useTitle, 
                $version, $withTrashed, $fullUrl, 
                $default, $useBaseMaker, $dir
            );
        } else {
            return $default;
        }
    }

    public function getChildrenWithPagination(
        $transform = null, bool $withTrashed = true, 
        string $dir = 'asc', string $baseUrl = 'store',
        bool $useBaseMaker = true, int $pageNum = 0, 
        int $numItemsPerPage = 4, string $listUrl = '#', 
        string $pagingFor = '', int $viewNumber = 0, 
        bool $fullUrl = false, bool $useTitle = true,
        int $version = 1, int $totalNum = 0, $default = []
    ) {
        if ($this->hasChildren()) {
            $totalNum = $this->numChildren($withTrashed);
            $pageIdx = self::genFirstAndLastItemsIdxes( 
                $totalNum, $pageNum, $numItemsPerPage
            );
            $query = self::getOrderedByFor(
                $this->getChildrenQuery($withTrashed), $dir, 
                $withTrashed, self::getChildrenOrderByKey()
            );
            if (Functions::testVar($query)) {
                $tmp = $query->offset($pageIdx['begin'])
                    ->limit($numItemsPerPage)
                    ->get();
                if (Functions::countHas($tmp)) {
                    $children = self::getChildrenFor(
                        $tmp, $baseUrl, $transform, $useTitle, 
                        $version, $withTrashed, $fullUrl, 
                        $default, $useBaseMaker, $dir
                    );
                    return self::getPaginatedItemsArray(
                        $children, $pageNum, $numItemsPerPage, 
                        $pagingFor, $listUrl, $viewNumber, 
                        $totalNum
                    );
                }
            }
        } 
        return $default;
    }

    static public function getChildrenOrderByKey()
    {
        return 'url'; // or 'id' ...?
    }

    /// transforms...

    public function toUrlListing(
        string $baseUrl, bool $fullUrl = false, 
        bool $useBaseMaker = false, bool $withTrashed = true
    ) {
        $url = $this->getFullUrl($baseUrl, false, $withTrashed);
        $value = [
            'name' => $this->getPubName(),
            'url' =>  $fullUrl ? url($url) : $url, 
        ];
        return $useBaseMaker
            ? self::makeDefaultBaseContentIterArray($value, null, false)
            : $value;
    }

    public function toUrlFragrment(
        string $baseUrl, bool $fullUrl = false, 
        bool $useBaseMaker = false, bool $withTrashed = true
    ) {
        $url = $this->getUrlFragment($baseUrl, false, $withTrashed);
        $value = [
            'name' => $this->name,
            'url' =>  $fullUrl ? url($url) : $url, 
        ];
        return $useBaseMaker
            ? self::makeDefaultBaseContentIterArray($value, null, false)
            : $value;
    }

    public function toNameListing(bool $useBaseMaker = false)
    {
        $value = [
            'name' => $this->getPubName(),
            'url' => $this->getUrl(), /// the identifying url fragment,
                                 /// NOT the full URL!!!
        ];
        return $useBaseMaker
            ? self::makeDefaultBaseContentIterArray($value, null, false)
            : $value;
    }

    public function toSidebar(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false
    ) {
        $img = $this->getImageArray();
        return self::makeSidebar(
            $this->getFullUrl($baseUrl, $fullUrl), $img['img'], 
            $useTitle ? $this->title : $img['alt'], 
            $this->getPriceOrSale()
        ); 
    }

    public function toMini(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false
    ) {
        $img = $this->getImageArray();
        return self::makeMini(
            $img['img'], $useTitle ? $this->title : $img['alt'], 
            $this->getFullUrl($baseUrl, $fullUrl, $withTrashed),
            $this->getPriceOrSale(), 
            $this->getPubId(), $this->getSticker()
        ); 
    }

    public function toContentArrayWithPagination(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false, int $pageNum = 0, 
        int $numItemsPerPage = 4, string $pagingFor = '', 
        int $viewNumber = 0, string $listUrl = '#', 
        bool $useBaseMaker = true
    ) {
        return $this->toContentArrayPlus(
            $baseUrl, $version, $useTitle, $withTrashed, 
            $fullUrl, $useBaseMaker, 'asc'
        );
    }

    public function toFull(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true,
        bool $fullUrl = false
    ) {
        return $this->toContentArray(
            $baseUrl, $version, 
            $useTitle, $withTrashed,
            $fullUrl
        );
    }

    public function toContentArray(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true, 
        bool $fullUrl = false
    ) {
        return $this->toContentArrayPlus(
            $baseUrl, $version, $useTitle, $withTrashed,
            $fullUrl, true, 'asc'
        );
    }

    /// end of transforms.

    /// other getters..
    public function getPriceOrSale()
    {
        return '';
    }

    public function getSticker()
    {
        return '';
    }

    public function getImageArray()
    {
        return $this->image->toImageArray();
    }

    static public function getCount(bool $withTrashed = false)
    {
        return $withTrashed 
        ? self::withTrashed()->count()
        : self::count();
    }

    /// end of other getters.

    /// this method (toTableArray) is deprecated!
    /// table views will not make use of a special 
    ///  data transform.
    /* public function toTableArray(
        string $baseUrl = 'store', int $version = 1, 
        bool $useTitle = true, bool $withTrashed = true
    ); */

    static public function getContentArrays(
        $arrays, string $baseUrl = 'store', $default = [],
        int $version = 1, bool $useTitle = true, 
        bool $withTrashed = true, bool $fullUrl = false, 
        bool $useBaseMaker = false, string $dir = 'asc'
    ) {
        return self::getFor(
            $arrays, $baseUrl, 
            TransformableContainer::TO_CONTENT_ARRAY_TRANSFORM,
            $useTitle, $version, $withTrashed, $fullUrl, $default,
            $useBaseMaker, $dir
        );
    }

    static public function getNameListingOf($array, bool $useBaseMaker = false)
    {
        $res = [];
        if (is_array($array) || $array instanceof Collection) {
            foreach ($tmp as $item) {
                if (self::isTransformable($item)) {
                    $res[] = $item->toNameListing($useBaseMaker);
                }
            }
        }
        return $res;
    }

    static public function getNameListing(
        bool $withTrashed = false, string $dir = 'asc',
        bool $useBaseMaker = false
    ) {
        $tmp = self::getOrdered($dir, $withTrashed);
        return self::getNameListingOf($tmp, $useBaseMaker);
    }

    static public function getRandomSample(
        int $numItems, $transform = null, 
        string $baseUrl = 'store', 
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, bool $withTrashed = true, 
        $default = [], bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        $numTotal = self::getCount($withTrashed);
        $rng = [];
        if ($numItems < $numTotal) {
            for ($i = 1; $i <= $numItems; $i++) {
                $bol = true;
                while ($bol) {
                    $smp = random_int(1, $numTotal);
                    if (!array_key_exists($smp, $rng)) {
                        $rng[$smp] = $smp;
                        $bol = false;
                    }
                }
            }
        } elseif ($numItems >= $numTotal) {
            $rng = Functions::genRange(1, $numTotal);
            shuffle($rng);
        }
        if (Functions::countHas($rng)) {
            $res = [];
            foreach ($rng as $idx) {
                $t = self::getFromId($idx, $withTrashed);
                if (Functions::testVar($t)) {
                    $res[] = $t;
                }
            }
            return self::getFor(
                $res, $baseUrl, $transform, 
                $useTitle, $version, $withTrashed, $fullUrl, 
                $default, $useBaseMaker, $dir
            );
        }
        return $default;
    }

    /// generic make_() methods..
    
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

    /**
     * Function makeTableArray() - OBSOLETE and DEPRECATED method! 
     *                             DO NOT USE!
     * 
     * @deprecated any
     *
     * @param string $name
     * @param string $url
     * @param string $title
     * @param [type] $img
     * @param string $description
     * @param string $sticker
     * @param array $dates
     * @param integer $id
     * @return array
     */
    static public function makeTableArray(
        string $name, string $url, string $title,
        $img, string $description,
        string $sticker = '', array $dates = [], int $id = 0
    ) {
        return [
            'id' => $id,
            'name' => $name,
            'img' => Image::getImageArray($img),
            'title' => $title,
            'url' => $url,
            'description' => $description,
            'dates' => $dates,
            'sticker' => $sticker,
        ];
    }

    static public function makeDefaultBaseContentIterArray(
        array $value = null, array $children = null, 
        bool $done = false
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
        bool $done = true, string $next = ''
    ) {
        $dates = Functions::countHas($dates)
            ? $dates
            : Functions::genDefaultDates(true);
        $value = [
            'name' => $name,
            'path' => $url,
            'url' => $url,
            'img' => Image::getImageArray($img),
            'title' => $title,
            'article' => Article::getArticle($article, true),
            'otherImages' => $otherImages??[],
            'dates' => $dates,
            'hasChildren' => is_null($hasChildren)
                ? Functions::countHas($children)
                : (is_bool($hasChildren) ? $hasChildren : false),
            'done' => $done,
        ];
        if (!empty($next)) {
            $value['next'] = $next;
        }
        return self::makeDefaultBaseContentIterArray(
            $value,
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
        if ($nPN > 0 && $nPN <= $numPages) {
            $next = $url . '?' . http_build_query(
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
            $done = false;
        } else {
            $done = true;
            $next = '';
        }
        $content = self::makeBaseContentArray(
            $name, $url, $img, $article, $title, $dates, 
            $otherImages, $children, $hasChildren, $done,
            $next
        );
        if (Functions::testVar($paginator)) {
            $content['value']['pagination'] = $paginator;
        }
        return $content;
    }

    static public function makeSelf(
        string $name, string $title, $article = [],
        $img = [], string $baseUrl = 'store', 
        bool $withTrashed = true,
        bool $fullUrl = false, $children = [], 
        $paginator = null, string $pagingFor = '',
        array $otherImages = null
    ) {
        $url = self::genUrlFragment($baseUrl, $fullUrl, $withTrashed);
        $usePagingFor = !empty($pagingFor) ? true : false;
        $dates = Functions::genDefaultDates(true);
        if (Functions::countHas($children) 
            && Functions::countHas($paginator)
        ) {
            return self::makeBaseContentIterArray(
                $name, $url, $img, $article, 
                $title, $paginator['currentPage'], 
                $paginator['totalNumPages'], 
                $paginator['numItemsPerPage'], $children, 
                $paginator, $dates, 
                $otherImages, true, $usePagingFor, 
                $paginator['viewNumber'], $pagingFor
            );
        } else {
            return self::makeBaseContentArray(
                $name, $url, $img, $article, 
                $title, $dates, $otherImages, [], false, 
                true, ''
            );
        }
    }

    /// end of generic make_() methods.

    static public function getIfDoneIterating($transform)
    {
        return $transform === self::TO_FULL_TRANSFORM ||
            $transform === self::TO_CONTENT_ARRAY_TRANSFORM ||
            $transform === self::TO_CONTENT_ARRAY_PLUS_TRANSFORM ||
            (is_bool($transform) && $transform);
    }

    static public function getSelfWithPagination(
        int $pageNumber, int $numPerPage = 4,  int $numView = 0, 
        string $baseUrl = 'store', string $listUrl = '', 
        bool $fullUrl = false,bool $withTrashed = true, 
        bool $useBaseMaker = true, $default = [], 
        string $dir = 'asc', string $pagingFor = '', 
        bool $useTitle = true, int $version = 1
    ) {
        $itemsArray = self::getAllWithPagination(
            self::TO_URL_LIST_TRANSFORM, $pageNumber, 
            $numPerPage, $pagingFor, $dir, $withTrashed, 
            $baseUrl, $listUrl, $numView, $fullUrl, 
            $useTitle, $version, $default, $useBaseMaker
        );
        $children = Functions::countHas($itemsArray) 
            ? $itemsArray['items'] : null;
        $paginator = Functions::countHas($itemsArray) 
            ? $itemsArray['pagination'] : null;
        return self::getSelf(
            $baseUrl, $withTrashed,
            $fullUrl, $children, 
            $paginator, $pagingFor
        );
    }

    /// query helpers..

    static public function getOrderedByFor(
        $arg, string $dir = 'asc', 
        bool $withTrashed = true,
        $orderingBy = null
    ) {
        if (Functions::testVar($arg) && is_object($arg)) {
            if (self::acceptableOrderingByKey($orderingBy)) {
                $key = $orderingBy;
            } elseif (is_callable($orderingBy)) {
                $key = $orderingBy();
                if (!self::acceptableOrderingByKey($key)) {
                    $key = self::getOrderByKey();    
                }
            } else {
                $key = self::getOrderByKey();
            }
            return $withTrashed 
                ? $arg->withTrashed()
                    ->orderBy($key, $dir)
                : $arg->orderBy($key, $dir);
        } 
        return null;
    }

    static public function getOrderedBy(
        string $dir = 'asc', bool $withTrashed = true,
        $orderingBy = null
    ) {
        if (self::acceptableOrderingByKey($orderingBy)) {
            $key = $orderingBy;
        } elseif (is_callable($orderingBy)) {
            $key = $orderingBy();
            if (!self::acceptableOrderingByKey($key)) {
                $key = self::getOrderByKey();    
            }
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

    static public function getOrderedFor(
        $arg, string $dir = 'asc', 
        bool $withTrashed = true,
        $orderingBy = null
    ) {
        if (Functions::testVar($arg) && is_object($arg)) {
            return self::getOrderedByFor(
                $arg, $dir, $withTrashed,
                $orderingBy
            )->get();
        }
        return null;
    }

    /// end of query helpers.

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

    static public function getAllFor(
        $arg, string $dir = 'asc', 
        bool $withTrashed = true,
        $orderingBy = null
    ) {
        $tmp = self::getOrderedByFor(
            $arg, $dir, $withTrashed,
            $orderingBy
        );
        if (Functions::testVar($tmp) && count($tmp) > 0) {
            return $tmp->all();
        }
        return null;
    }

    static public function getAllWithTransform(
        $transform = null, string $dir = 'asc', 
        bool $withTrashed = true, string $baseUrl = 'store', 
        bool $useTitle = true, bool $fullUrl = false, 
        int $version = 1, $default = [], 
        bool $useBaseMaker = true
    ) {
        $tmp = self::getOrdered($dir, $withTrashed);
        return self::getFor(
            $tmp, $baseUrl, $transform,
            $useTitle, $version, $withTrashed, 
            $fullUrl, $default, $useBaseMaker, $dir
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
        if (Functions::countHas($args) && $num > 0) {
            if ($totalNum > 0) {
                $items = Functions::arrayableToArray($args, []);
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
        int $version = 1, $default = [], bool $useBaseMaker = true
    ) {
        $totalNum = self::getCount($withTrashed);
        $pageIdx = self::genFirstAndLastItemsIdxes( 
            $totalNum, $pageNum, $numShown
        );
        $tmp = self::getOrderedBy($dir, $withTrashed)
            ->offset($pageIdx['begin'])->limit($numShown)
            ->get();
        if (Functions::testVar($tmp) && Functions::countHas($tmp)) {
            $tmp1 = self::getFor(
                $tmp, $baseUrl, $transform,
                $useTitle, $version, $withTrashed, $fullUrl,
                $default, $useBaseMaker, $dir
            );
            if (Functions::testVar($tmp1) && Functions::countHas($tmp1)) {
                return self::getPaginatedItemsArray(
                    $tmp1, $pageNum, $numShown, 
                    $pagingFor, $listUrl, 
                    $viewNumber, $totalNum
                );
            }
        }
        return $default;
    }

    static public function isTransformable($item)
    {
        return Functions::testVar($item) 
            && ($item instanceof self
            || is_subclass_of($item, Transformable::class));
    }

    static public function doTransform(
        $item, $transform = null, string $baseUrl = 'store',
        bool $useTitle = true, int $version = 1, 
        bool $withTrashed = true, bool $fullUrl = false, 
        $default = null, bool $useBaseMaker = true,
        string $dir = 'asc'
    ) {
        if (self::isTransformable($item)) {
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
                        $baseUrl, $version, $useTitle, 
                        $withTrashed, $fullUrl, $useBaseMaker, 
                        $dir
                    );
                case 'name':
                    return $item->toNameListing($useBaseMaker);
                case 'fragment':
                    return $item->toUrlFragrment($baseUrl, $fullUrl, $useBaseMaker);
                case 'url':
                    return $item->toUrlListing($baseUrl, $fullUrl, $useBaseMaker);
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
        string $dir = 'asc'
    ) {
        if (Functions::countHas($args)) {
            if (empty($transform)) {
                return Functions::arrayableToArray($args, $args);
            } else {
                $res = [];
                foreach ($args as $item) {
                    $tmp = self::doTransform(
                        $item, $transform, $baseUrl, $useTitle,
                        $version, $withTrashed, $fullUrl, null,
                        $useBaseMaker, $dir
                    );
                    if (Functions::testVar($tmp)) {
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
        bool $useBaseMaker = true
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
            $withTrashed, $fullUrl, $default, $useBaseMaker, 
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

    /// currently the only user of toContentArrayWithPagination()!
    ///  otherwise a work in progress, UNIMPLEMENTED!!
    static public function getForWithRecursivePagination(
        $args, int $pageNum, bool $transform = true,
        int $numShown = 4, string $pagingFor = '', 
        string $listUrl = '', string $baseUrl = 'store', 
        string $dir = 'asc', int $viewNumber = 0, 
        bool $withTrashed = true, bool $useTitle = true, 
        bool $fullUrl = false, int $version = 1, $default = []
    ) {
        if (Functions::countHas($args)) {
            $cTmp = count($args);
            if ($cTmp <= $numShown && $cTmp > 0) {
                $argTmp = Functions::arrayableToArray($args, []);
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
                if (self::isTransformable($item)) {
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
}
