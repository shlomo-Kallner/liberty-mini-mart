<?php

namespace App\Utilities\Functions;

use Illuminate\Support\Collection,
    Illuminate\Support\HtmlString,
    HTMLPurifier;

class Functions{

    static public function testVar($var)
    {
        return isset($var) && !empty($var);
    }

    static public function testBladedVar($var)
    {
        if (self::testVar($var)) {
            if (is_string($var)) {
                $tmp = unserialize(html_entity_decode((string)$var));
                return !empty($tmp);
            } elseif ($var instanceof HtmlString) {
                $tmp = unserialize(html_entity_decode($var->toHtml()));
                return !empty($tmp);
            }
            return true;
        }
        return false;
    }

    static public function getBladedContent($var, $default = null)
    {
        if (self::testVar($var)) {
            if (is_string($var) ) {
                $tmp = unserialize(html_entity_decode((string)$var));
                return !empty($tmp) ? $tmp : $default ;
            } elseif ($var instanceof HtmlString) {
                $tmp = unserialize(html_entity_decode($var->toHtml()));
                return !empty($tmp) ? $tmp : $default ;
            } else {
                return !empty($var) ? $var : $default ;
            }

        } else {
            return $default;
        }
    }

    static public function getUnBladedContent($var, $default = null)
    {
        if (self::testVar($var)) {
            if (is_string($var) ) {
                $tmp = unserialize((string)$var);
                return !empty($tmp) ? $tmp : $default ;
            } elseif ($var instanceof HtmlString) {
                $tmp = unserialize($var->toHtml());
                return !empty($tmp) ? $tmp : $default ;
            } else {
                return !empty($var) ? $var : $default ;
            }

        } else {
            return $default;
        }
    }

    static public function getBladedString($str, $default = '')
    {
        if (self::testVar($str)) {
            $tmp = null;
            if (is_string($str)) {
                $tmp = html_entity_decode((string)$str);
            } elseif ($str instanceof HtmlString) {
                $tmp = html_entity_decode($str->toHtml());
            } else {
                return $default;
            }
            return !empty($tmp) ? $tmp : $default ;
        } else {
            return $default;
        }
    }

    static public function getContent($var, $default = null)
    {
        if (self::testVar($var)) {
            return !empty($var) ? $var : $default ;
        } else {
            return $default;
        }
    }

    static public function purifyContent($content)
    {
        if (static::testVar($content)) {
            $purify = new HTMLPurifier();
            if (is_string($content)) {
                return $purify->purify($content);
            } elseif ($content instanceof HtmlString) {
                return $purify->purify($content->toHtml());
            } elseif (is_object($content)) {
                foreach ($content as $key => $value) {
                    $content->$key = self::purifyContent($value);
                }
                return $content;
            } elseif (is_array($content)) {
                foreach ($content as $key => $value) {
                    $content[$key] = self::purifyContent($value);
                }
                return $content;
            } else {
                return $purify->purify((string)$content);
            }
        } else {
            return null;
        }

    }

    static public function genRange(int $beg, int $end, int $step = 1)
    {
        $tmp = [];
        if ($step > 0) {
            for ($i = $beg; $i <= $end; $i += $step) {
                $tmp[] = $i;
            }
        } elseif ($step < 0) {
            for ($i = $beg; $i >= $end; $i += $step) {
                $tmp[] = $i;
            }
        }
        return $tmp;
    }

    /**
     * function genRowsPerPage
     * 
     * Should have been called getNumRowsPerPage ..
     * or getNumItemsPerUnit ..
     * TOO LATE!
     * 
     * Can also be used for determining the number 
     * of Pages of content, using the total
     * number of content items as $ppp
     * and the number of Items per Page as $ppr.
     * 
     * @param  integer $ppp - productsPerPage
     * @param  integer $ppr - productsPerRow
     *
     * @return integer      - rowsPerPage
    */
    static public function genRowsPerPage(int $ppp, int $ppr) 
    {
        $res = $ppp / $ppr;
        if ($ppp % $ppr > 0) {
            $res++;
        }
        return $res;
    }


    /**
     * function genPageArray
     *
     * @param  array   $range      - must be a Range from 
     *                             genRange() or similar array!
     * @param  integer $numPerPage
     * 
     * @return array
     * 
     */
    static public function genPageArray(array $range, int $numPerPage)
    {
        $res = [];
        $rngLen = count($range);
        if ($rngLen == $numPerPage) {
            $res[] = $range;
        } elseif ($rngLen > $numPerPage) {
            $col = collect($range);
            $numTotal = self::genRowsPerPage($col->count(), $numPerPage);
            for ($i = 0; $i < $numTotal; $i++ ) {
                $res[] = $col->forPage($i, $numPerPage)->all();
            }
        }
        return $res;
    }


    /**
     *  function genPagesIndexes
     * 
     * @param integer $ppp - productsPerPage
     * @param integer $ppr - productsPerRow
     * @param int     $tp  - totalProducts (total number OF products)
     * @param int     $pn  - pageNumber ; valid page numbers are from 0 and up;
     *                     - -1 for generate and return all as a single-page;
     *                     - -2 for generate and return all pages;
     * 
     * @return array - a array of arrays of indices of rows..
     */
    static public function genPagesIndexes(int $ppp, int $ppr, int $tp, int $pn = -1)
    {
        $res = [];
        $numPages = self::genRowsPerPage($tp, $ppp);
        if ($pn > -3 && $pn < $numPages) {

            /// step 0] if not given a valid page index, 
            ///         goto step 4.
            /// step 1] generate a 'table/array' of 
            ///         indexes into the ProductArray Per Content Page. 
            $pnValid = $pn > 0 && $pn < $numPages;
            /// step 2] split each indexesPerContentPage Array into
            ///         rows of indexesPerContentPage per Page
            /// step 3] return the page's rowArray. exit function.
            /// step 4] execute step 1, 2 and 3 with the total number of
            ///         Products as the number of Products Per Content
            ///         Page and a Page Number of 0. 
            ///         (aka Generate a PageTable with only 1 entry,
            ///          and return the entry's rowArray.)
            if ($pnValid || $pn == -2) {
                $pageIndexRanges = self::genPageArray(self::genRange(0, $tp), $ppp);
            } else {
                $pageIndexRanges = self::genPageArray(self::genRange(0, $tp), $tp);
            }
            if ($pnValid || $pn == -1) {
                $res[] = self::genPageArray(count($pageIndexRanges[$pnValid?$pn:0]), $ppr);
            } else {
                foreach ($pageIndexRanges as $page) {
                    $res[] = self::genPageArray(count($page), $ppr);
                }
            }

            /* // the OLD AND WRONG CODE - here for working reference during fix...
                if ($ppp <= $tp) {
                    /// usually productsPerPage is smaller and so use it..
                    $rpp = self::genRowsPerPage($ppp, $ppr); // => $rowsPerPage
                } else {
                    /// otherwise use totalProducts
                    $rpp = self::genRowsPerPage($tp, $ppr); // => $rowsPerPage
                }
        
                /// generate the 'pages' of indices (into the product array) for ALL rows..
                $rip = self::genPageArray(self::genRange(0, $tp), $ppr); // => $rowsIdxPages
                /// generate the 'pages' of indices (into $rip) for ALL 'content-pages'
                $pip = self::genPageArray(self::genRange(0, count($rip)), $rpp); // => $pagesIdxPages
        
                if ($pn > -1 && $pn < count($pip) ) { // => $pageNumber2
                    /// if a 'content-pages-number'
                    ///  is set AND it's valid, 
                    ///  return just that 'content-page's'
                    ///  row-index-pages from $rip..
                    $res = [];
                    $page = $pip[$pn];
                    foreach ($page as $row) {
                        $res[] = $rip[$row];
                    }
                } else {
                    /// else by default ..
                    /// return ALL row-index-pages..
                    $res = &$rip;

            */

        }
        return $res;
    }

}

