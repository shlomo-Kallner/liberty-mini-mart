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
