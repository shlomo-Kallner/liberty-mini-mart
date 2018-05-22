<?php

namespace App\Utilities\Functions;

use \Illuminate\Support\Collection;

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

/**
    @param integer $ppp - productsPerPage
    @param integer $ppr - productsPerRow
    @return integer - rowsPerPage
*/
function genRowsPerPage(int $ppp, int $ppr) 
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
 * @param array $range - must be a Range from genRange() or similar array!
 * @param integer $numPerPage
 * @return array
 * 
 */
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


/**
    @param integer $ppp - productsPerPage
    @param integer $ppr - productsPerRow
    @return array - a array of arrays of indices of rows..
*/
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
            
     
