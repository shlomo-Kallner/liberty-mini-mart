<?php

namespace App\Utilities\Functions;

use Illuminate\Support\Collection,
    Illuminate\Support\HtmlString,
    Illuminate\Contracts\Support\Htmlable,
    HTMLPurifier, DB, \Countable,
    Illuminate\Support\Facades\Log, 
    Composer\Semver\Comparator,
    Illuminate\Http\Request,
    Illuminate\Http\Response,
    Illuminate\Http\JsonResponse,
    Symfony\Component\VarDumper\Cloner\VarCloner,
    Symfony\Component\VarDumper\Dumper\HtmlDumper,
    Symfony\Component\VarDumper\Dumper\CliDumper,
    App\Exceptions\JsonException,
    Illuminate\Contracts\Support\Arrayable,
    Illuminate\Support\Carbon;


class Functions
{

    static public function testVar($var)
    {
        return isset($var) && !empty($var);
    }

    static public function getVar($var, $default = null)
    {
        return self::testVar($var) ? $var : $default;
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

    /**
     * Function getBladedContent -  Get the content of a 
     *                              Blade Escaped Serialized
     *                              variable..
     *
     * @param mixed|string $var
     * @param mixed $default
     * @return void
     */
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

    static public function toBladableContent($val)
    {
        if ($val instanceof Htmlable) {
            return $val->toHtml();
        } elseif (is_array($val) || is_object($val)) { 
            return serialize($val);
        } else { 
            return $val;
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

    static public function getURLRegexStr()
    {
        return '/^[:lower:]{3,}(-[:lower:]{3,})*$/';
    }

    static public function getImageFileMIMETypeStr()
    {
        return 'image/jpeg,image/gif,image/png';
    }

    static public function getDateTimeStr(
        string $sep = '', string $dateSep = '/',
        string $timeSep = ':'
    ) {
        $date = 'd' . $dateSep . 'm' . $dateSep . 'Y';
        $time = 'h' . $timeSep . 'i' . $timeSep . 's';
        return date('D' . $sep . $date . $sep . $time);
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

    static public function int2url_encode(int $num, bool $enc = false)
    {
        $s = pack('V', $num);
        $h = bin2hex($s);
        return $enc ? rawurlencode($h) : $h;
    }

    static public function url2int_decode(string $url, bool $enc = false)
    {
        $h = $enc ? rawurldecode($url) : $url;
        $s = hex2bin($h);
        $n = unpack('V', $s);
        //dd($n);
        return $n[1];
    }

    static public function testVersions(
        string $ver1, string $ver2, string $op = '=='
    )
    {
        $res = false;
        switch ($op) {
            case '==':
                $res = Comparator::equalTo($ver1, $ver2);
                break;

            case '!=':
                $res = Comparator::notEqualTo($ver1, $ver2);
                break;
            
            case '>':
                $res = Comparator::greaterThan($ver1, $ver2);
                break;
            
            case '<':
                $res = Comparator::lessThan($ver1, $ver2);
                break;

            case '>=':
                $res = Comparator::greaterThanOrEqualTo($ver1, $ver2);
                break;

            case '<=':
                $res = Comparator::lessThanOrEqualTo($ver1, $ver2);
                break;

            default:
                $res = false;
                break;
        }
        return $res;
    }

    /**
     * Function dbModel2ViewModel() - OBSOLETE - returns an empty array.
     * 
     * @deprecated 
     *
     * @param  array   $dbModel   - the content array of a compatable model.
     * @param  boolean $useTitle  - if true, use the 'title' key for 'alt'.
     *                            - if false, use the 'img' key_s 'alt' key 
     *                            for 'alt'.
     * 
     * @return array|null
     */
     static public function dbModel2ViewModel(
        array &$dbModel, bool $useTitle = false
    ) {
        /* 
            if (array_key_exists('image', $dbModel) && is_int($dbModel['image'])) {
                $img = DB::table('images')->where('id', $dbModel['image'])->first();
                $dbModel['img'] = $img->path . '/' . $img->name;
                $dbModel['imgAlt'] = $img->alt;
            } elseif (array_key_exists('image', $dbModel) && is_string($dbModel['image'])) {
                if (!array_key_exists('img', $dbModel) && array_key_exists('image', $dbModel)) {
                    $dbModel['img'] = $dbModel['image'];
                }
                if (!array_key_exists('imgAlt', $dbModel) && array_key_exists('title', $dbModel)) {
                    $dbModel['imgAlt'] = $dbModel['title'];
                }
            } else {
            }
            
            return $dbModel; // just as a convenience as we received the param by reference..
        */
        $res = [];
        if (false) {
            foreach ($dbModel as $key => $val) {
                if ($key == 'image' && is_int($val)) {
                    if (!array_key_exists('img', $dbModel) && !array_key_exists('imgAlt', $dbModel)) {
                        $img = DB::table('images')->where('id', $val)->first();
                        if ($img) {
                            $res['img'] = $img->path . '/' . $img->name;
                            $res['imgAlt'] = $img->alt;
                        } elseif ($useTitle) {
                            $res['img'] = $dbModel['image'];
                            $res['imgAlt'] = $dbModel['title'];
                        }
                    } 
                } else {
                    $res[$key] = $val;
                }
            }
        }
        return $res;
    }
 
    static public function jsonRetOrDump(
        Request $request, $id = null, ...$data
    ) {
        if (! $request->ajax()) {
            dd($request, $id ?? __METHOD__, ...$data);
        } else {
            throw new JsonException($request, $id ?? __METHOD__, ...$data);
        }
    }

    static public function genDumpResponse(Request $request, ...$data)
    {
        if ($request->ajax()) {
            $tmp = [];
            $dumper = new CliDumper;
            $tmp[] = $dumper->dump((new VarCloner)->cloneVar($request), true);
            foreach ($data as $val) {
                $tmp[] = $dumper->dump((new VarCloner)->cloneVar($val), true);
            }
            return new JsonResponse($tmp);
        } else {
            $dumper = new HtmlDumper;
            $data[] = $request;
            $tmp = $dumper->dump((new VarCloner)->cloneVar($data), true);
            return new Response($tmp);
        }
    }

    static public function genDatesArray(
        $created_at, $updated_at, $deleted_at
    ) {
        return [
            'created' => $created_at,
            'modified' => $updated_at,
            'deleted' => $deleted_at
        ];
    }

    static public function genDefaultDates(bool $setUpdated = false) 
    {
        $now = Carbon::now();
        return self::genDatesArray(
            $now, $setUpdated ? $now : null, null
        );
    }

    /**
     * Function compValues
     *
     * @param mixed $var1
     * @param mixed $var2
     * @return bool|int  -1 for smaller than, 0 for equal to and 1 for greater than
     *                   false for incomparable. 
     *                   Can only return 0 or false for Objects or Arrays..
     *                   Compares $var2 to $var1 and if they are arrays or Objects
     *                   _ALL_ of $var2's keys/property_names and values _MUST_ Exist 
     *                   in $var1 and be equal to the keys/properties and values 
     *                   in $var1 for the method to return 0.
     */
    static public function compValues($var1, $var2) 
    {
        $res = false;
        if (self::testVar($var1) && self::testVar($var2)) {
            /* 
                if (is_array($var1) && is_array($var2)) {
                    $aTmp1 = array_diff_assoc($var1, $var2);
                    $aTmp2 = array_diff_assoc($var2, $var1);
                } elseif (is_object($var1) && is_object($var2) 
                    && $var1 instanceof $var2) {

                } else 
            */
            if ((is_array($var1) && is_array($var2)) 
                || (is_object($var1) && is_object($var2) && $var1 instanceof $var2)
            ) {
                $res = 0;
                foreach ($var1 as $key => $val) {
                    if (self::isPropKeyIn($var2, $key)) {
                        $iTmp = self::getPropKey($var2, $key);
                        $cTmp = self::compValues($val, $iTmp);
                        if ($cTmp !== 0) {
                            $res = false;
                        }
                    }
                }
            } elseif (is_int($var1) && is_int($var2)) {
                if ($var1 > $var2) {
                    $res = 1;
                } elseif ($var1 < $var2) {
                    $res = -1;
                } else {
                    $res = 0;
                }
            } elseif (is_float($var1) && is_float($var2)) {
                if ($var1 > $var2) {
                    $res = 1;
                } elseif ($var1 < $var2) {
                    $res = -1;
                } else {
                    $res = 0;
                }
            } elseif (is_string($var1) && is_string($var2)) {
                $res = strcmp($var1, $var2);
            }
        }
        return $res;
    }

    static public function isAdminPath($path)
    {
        if (is_string($path)) {
            return in_array('admin', explode('/', $path));
        } elseif ($path instanceof Request) {
            return self::isAdminPath($path->path());
        } else {
            return false;
        }
    }

    static public function is_countable($value)
    {
        if (is_array($value) || $value instanceof Collection
            || is_subclass_of($value, '\Countable')
        ) {
            return true;
        }
        return false;
    }

    static public function countHas($value)
    {
        return self::is_countable($value) && count($value) > 0;
    }

    static public function arrayableToArray($arr, $def = null)
    {
        if (self::testVar($arr)) {
            if ($arr instanceof Collection) {
                return $arr->all();
            } elseif ($arr instanceof Arrayable) {
                return $arr->toArray();
            } elseif (is_array($arr)) {
                return $arr;
            }
        }
        return $def;
    }

    static public function isPropKeyIn($data, $name) 
    {
        $bol = null;
        if (isset($data) && self::testVar($name)) {
            if (is_array($data) && (is_int($name) || is_string($name))) {
                $bol = array_key_exists($name, $data);
            } elseif ($data instanceof Collection) {
                $bol = $data->offsetExists($name); 
            } elseif (is_object($data)) {
                $bol = property_exists($data, $name) || isset($data->$name) 
                    || !empty($data->$name); 
            }
        }
        return $bol;
    }

    static public function hasPropKeyIn($data, $name)
    {
        $bol = null;
        if (isset($data) && self::testVar($name)) {
            $bol = self::isPropKeyIn($data, $name) 
                && self::testVar(self::getPropKey($data, $name));
        }
        return $bol;
    }

    static public function getPropKey($data, $name, $default = null) 
    {
        $res = $default; // null;
        if (isset($data) && self::testVar($name)) {
            if (self::isPropKeyIn($data, $name)) {
                if (is_array($data)) {
                    $res = $data[$name];
                } elseif ($data instanceof Collection) {
                    $res = $data->offsetGet($name); 
                } elseif (is_object($data)) {
                    $res = $data->$name;
                }
            }
        } 
        return $res;
    }

    static public function setPropKey(&$data, $name, $val = null)
    {
        $res = self::isPropKeyIn($data, $name);
        if (isset($data) && self::testVar($name)) {
            if (is_array($data)) {
                $data[$name] = $val;
            } elseif ($data instanceof Collection) {
                $data->offsetSet($name, $val); 
            } elseif (is_object($data)) {
                $data->$name = $val;
            }
        } 
        //dd($res, $data, $name, $val, is_array($data));
        return $res;
    }

    static public function isValIn($data, $key, $val = null)
    {
        $bol = null;
        if (self::testVar($data)) {
            $bol = false;
            if (self::isPropKeyIn($data, $key) && self::testVar($val)) {
                $nTmp = self::getPropKey($data, $key) ?? null;
                if (self::testVar($nTmp)) {
                    $cTmp = self::compValues($nTmp, $val);
                    if ($cTmp === 0) {
                        $bol = true;
                    } 
                }
            } elseif (self::testVar($key) && !self::testVar($val)) {
                foreach ($data as $kTmp => $vTmp) {
                    $cTmp = self::compValues($vTmp, $key);
                    if ($cTmp === 0) {
                        $bol = true;
                        break;
                    } 
                }
            }
        }
        return $bol;
    }

    static public function genMultipleFromArray(array $arr, int $num)
    {
        $res = [];
        if (self::testVar($arr) && self::testVar($num) && $num > 0) {
            for ($i = 0; $i < $num; $i++) {
                foreach ($arr as $val) {
                    $res[] = $val;
                }
            }
        }
        return $res;
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
        } else {
            $tmp[] = $beg;
            $tmp[] = $end;
        }
        return $tmp;
    }

    /**
     * Function genRowsPerPage
     * 
     * Should have been called getNumRowsPerPage ..
     * or getNumItemsPerUnit ..
     * TOO LATE!
     * 
     * Can also be used for determining the number 
     * of Pages of content, using the total
     * number of content items as $ppp (productsPerPage)
     * and the number of Items per Page as $ppr (productsPerRow).
     * 
     * @param integer $ppp - productsPerPage
     * @param integer $ppr - productsPerRow
     *
     * @return integer      - numRowsPerPage
     */
    static public function genRowsPerPage(int $ppp, int $ppr) 
    {
        if ($ppp <= $ppr) {
            return 1;
        } else {
            $res = intdiv($ppp, $ppr);
            if ($ppp % $ppr > 0) {
                $res++;
            }
            return $res;
        }
    }


    /**
     * Function genPageArray
     *
     * @param  array   $range      - must be a Range of Indexes from 
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
        if ($rngLen <= $numPerPage) {
            $res[] = $range;
        } elseif ($rngLen > $numPerPage) {
            $col = collect($range);
            $numTotal = self::genRowsPerPage($rngLen, $numPerPage);
            for ($i = 0; $i < $numTotal; $i++ ) {
                // Collection::forPage() cannot really handle a page
                //  numbered '0' -- if '0' is passed in it will 
                //  result in a duplicate page on passing in 
                //  page number '1'..
                $tmp = $col->forPage($i+1, $numPerPage)->all();
                $tmp1 = [];
                foreach ($tmp as $val) {
                    $tmp1[] = $val;
                }
                $res[] = $tmp1;
            }
        }
        return $res;
    }

    static public function getLastIndex(array $arr)
    {
        return self::testVar($arr) ? count($arr) - 1 : 0;
    }

    static public function getIndexOf(array $arr, $val)
    {
        $res = null;
        if (self::testVar($arr) && self::testVar($val)) {
            foreach ($arr as $key => $value) {
                if ($value === $val) {
                    $res = $key;
                    break;
                }
            }
        } 
        return $res;
    }


    /**
     *  Function genPagesIndexes
     * 
     * @param integer $ppp      - productsPerPage
     * @param integer $ppr      - productsPerRow
     * @param int     $tp       - totalProducts (total number OF products)
     * @param int     $pn       - pageNumber ; valid page numbers are from 
     *                          0 and up;
     *                          - -1 for generate and return all as a 
     *                          single-page;
     *                          - -2 for generate and return all pages;
     * @param int     $numPages - the number of pages of products as 
     *                          calculated by this function and returned 
     *                          to the caller by reference. 
     *                          - This parameter is passed by reference.
     * 
     * @return array - a array of arrays of indices of rows..
     */
    static public function genPagesIndexes(
        int $ppp, int $ppr, int $tp, int $pn = -1,
        int &$numPages = 0
    ) {
        $res = [];
        Log::info('$numPages before: ', ['$numPages' => $numPages]);
        if ($numPages < 1) {
            $numPages = self::genRowsPerPage($tp, $ppp);
        }
        //dd($ppp, $ppr, $tp, $pn, $numPages);
        /* Log::info(
            'all parameter in '. __METHOD__ .': ', 
            [
                'itemsPerPage' => $ppp, 
                'itemsPerRow' => $ppr, 
                'totalItems' => $tp, 
                'pageNumber' => $pn,
                'numPages' => $numPages
            ]
        ); */
        if ($pn > -3 && $pn < $numPages) {

            /// step 0] if not given a valid page index, 
            ///         goto step 4.
            /// step 1] generate a 'table/array' of 
            ///         indexes into the ProductArray Per Content Page. 
            $pnValid = $pn > -1 && $pn < $numPages;
            /// step 2] split each indexesPerContentPage Array into
            ///         rows of indexesPerContentPage per Page
            /// step 3] return the page's rowArray. exit function.
            /// step 4] execute step 1, 2 and 3 with the total number of
            ///         Products as the number of Products Per Content
            ///         Page and a Page Number of 0. 
            ///         (aka Generate a PageTable with only 1 entry,
            ///          and return the entry's rowArray.)
            /* Log::info(
                'all parameter and vars in '. __METHOD__ .': ', 
                [
                    'itemsPerPage' => $ppp, 
                    'itemsPerRow' => $ppr, 
                    'totalItems' => $tp, 
                    'pageNumber' => $pn,
                    'numPages' => $numPages,
                    'isPageNumberValid' => $pnValid
                ]
            ); */
            if ($pnValid || $pn == -2) {
                // generate all pages_of_indexes ...
                // for multiple/single_selected display_page..
                $pageIndexRanges = self::genPageArray(
                    self::genRange(0, $tp - 1), $ppp
                );
                /* Log::info(
                    'all parameter and vars in '. __METHOD__ .': ', 
                    [
                        'itemsPerPage' => $ppp, 
                        'itemsPerRow' => $ppr, 
                        'totalItems' => $tp, 
                        'pageNumber' => $pn,
                        'numPages' => $numPages,
                        'isPageNumberValid' => $pnValid,
                        'pageIndexRanges' => $pageIndexRanges,
                    ]
                ); */
            } else {
                // generate a single page_of_indexes with all products..
                // for a single display_page of all products..
                $pageIndexRanges = self::genPageArray(
                    self::genRange(0, $tp - 1), $tp
                );
            }
            // now create the Pages_Of_Rows ...
            if ($pnValid || $pn == -1) {
                // if generating for a single_selected_display_page
                // or for a single_display_page_of_all_products ..
                $tmpArray = $pageIndexRanges[$pnValid?$pn:0];
                $tmpRange = self::genRange(
                    $tmpArray[0], 
                    $tmpArray[self::getLastIndex($tmpArray)]
                );
                $res[] = self::genPageArray($tmpRange, $ppr);
                /* Log::info(
                    'all parameter and vars in '. __METHOD__ .': ', 
                    [
                        'itemsPerPage' => $ppp, 
                        'itemsPerRow' => $ppr, 
                        'totalItems' => $tp, 
                        'pageNumber' => $pn,
                        'numPages' => $numPages,
                        'isPageNumberValid' => $pnValid,
                        'pageIndexRanges' => $pageIndexRanges,
                        'tmpIsOf1' => [
                            $pageIndexRanges[$pnValid?$pn:0][0], 
                            count($pageIndexRanges[$pnValid?$pn:0]) -1
                        ],
                        'tmpIsOf12' => [
                            $tmpArray[0], 
                            $tmpArray[self::getLastIndex($tmpArray)]
                        ],
                        'tmpRange' => $tmpRange,
                        'res' => $res,
                    ]
                ); */
                //dd("res", $res, $tmpRange);
            } else {
                // if generating all display_pages ..
                foreach ($pageIndexRanges as $page) {
                    $res[] = self::genPageArray(self::genRange(0, count($page)), $ppr);
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

