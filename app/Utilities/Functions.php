<?php

namespace App\Utilities\Functions;

use \Illuminate\Support\Collection;

class Functions{

    static public function testVar($var)
    {
        return isset($var) && !empty($var);
    }

    static public function testBladedVar(string $var)
    {
        if (self::testVar($var)) {
            $tmp = unserialize(html_entity_decode($var));
            return !empty($tmp);
        }
        return false;
    }

    static public function getBladedContent(string $var, $default = null)
    {
        if (self::testVar($var)) {
            $tmp = unserialize(html_entity_decode($var));
            return !empty($tmp) ? $tmp : $default ;
        } else {
            return $default;
        }
    }

    static public function getBladedString(string $str, $default = '')
    {
        if (self::testVar($var)) {
            $tmp = html_entity_decode($var);
            return !empty($tmp) ? $tmp : $default ;
        } else {
            return $default;
        }
    }

    static public function getContent(string $var, $default = null)
    {
        if (self::testVar($var)) {
            return !empty($var) ? $var : $default ;
        } else {
            return $default;
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
        $col = collect($range);
        $numTotal = self::genRowsPerPage($col->count(), $numPerPage);
        for ($i = 0; $i < $numTotal; $i++ ) {
            $res[] = $col->forPage($i, $numPerPage)->all();
        }
        return $res;
    }


    /**
     *  function genPagesIndexes
     * 
     * @param integer $ppp - productsPerPage
     * @param integer $ppr - productsPerRow
     * @param int     $tp  - totalProducts (total number OF products)
     * @param int     $pn  - pageNumber ; -1 for generate a single-page
     * 
     * @return array - a array of arrays of indices of rows..
     */
    static public function genPagesIndexes(int $ppp, int $ppr, int $tp, int $pn = -1)
    {
        $res = [];
        if ($ppp <= $tp) {
            $rpp = self::genRowsPerPage($ppp, $ppr); // => $rowsPerPage
        } else {
            $rpp = self::genRowsPerPage($tp, $ppr); // => $rowsPerPage
        }

        $rip = self::genPageArray(self::genRange(0, $tp), $ppr); // => $rowsIdxPages
        $pip = self::genPageArray(self::genRange(0, count($rip)), $rpp); // => $pagesIdxPages

        if ($pn > -1) { // => $pageNumber2
            $res = [];
            $page = $pip[$pn];
            foreach ($page as $row) {
                $res[] = $rip[$row];
            }
        } else {
            // by default 
            $res = &$rip;
        }
        return $res;
    }

}

/**
 * My Original Declarations...
 */
/* 
    function testVar($var)
    {
        return isset($var) && !empty($var);
    }

    function testBladedVar(string $var)
    {
        if (testVar($var)) {
            $tmp = unserialize(html_entity_decode($var));
            return !empty($tmp);
        }
        return false;
    }

    function getBladedContent(string $var, $default = null)
    {
        if (testVar($var)) {
            $tmp = unserialize(html_entity_decode($var));
            return !empty($tmp) ? $tmp : $default ;
        } else {
            return $default;
        }
    }

    function getBladedString(string $str, $default = '')
    {
        if (testVar($var)) {
            $tmp = html_entity_decode($var);
            return !empty($tmp) ? $tmp : $default ;
        } else {
            return $default;
        }
    }

    function getContent(string $var, $default = null)
    {
        if (testVar($var)) {
            return !empty($var) ? $var : $default ;
        } else {
            return $default;
        }
    }

    function genRange(int $beg, int $end, int $step = 1)
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
*/
/*
    /?
        @param integer $ppp - productsPerPage
        @param integer $ppr - productsPerRow
        @return integer - rowsPerPage
    ?/

    function genRowsPerPage(int $ppp, int $ppr) 
    {
        $res = $ppp / $ppr;
        if ($ppp % $ppr > 0) {
            $res++;
        }
        return $res;
    }
*/

/*
   /?
    * function genPageArray
    *
    * @param array $range - must be a Range from genRange() or similar array!
    * @param integer $numPerPage
    * @return array
   ?/
    function genPageArray(array $range, int $numPerPage)
    {
        $res = [];
        $col = collect($range);
        $numTotal = genRowsPerPage($col->count(), $numPerPage);
        for ($i = 0; $i < $numTotal; $i++ ) {
            $res[] = $col->forPage($i, $numPerPage)->all();
        }
        return $res;
    }

 */

/*
  /?
    @param integer $ppp - productsPerPage
    @param integer $ppr - productsPerRow
    @return array - a array of arrays of indices of rows..
  ?/
    function genPagesIndexes(int $ppp, int $ppr, int $tp, int $pn = -1)
    {
        $res = [];
        if ($ppp <= $tp) {
            $rpp = genRowsPerPage($ppp, $ppr); // => $rowsPerPage
        } else {
            $rpp = genRowsPerPage($tp, $ppr); // => $rowsPerPage
        }

        $rip = genPageArray(genRange(0, $tp), $ppr); // => $rowsIdxPages
        $pip = genPageArray(genRange(0, count($rip)), $rpp); // => $pagesIdxPages

        if ($pn > -1) { // => $pageNumber2
            $res = [];
            $page = $pip[$pn];
            foreach ($page as $row) {
                $res[] = $rip[$row];
            }
        } else {
            // by default 
            $res = &$rip;
        }
        return $res;
    }

*/            
     
