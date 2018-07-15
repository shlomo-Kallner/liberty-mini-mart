<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Page;

class Section extends Model
{
    static public function getNamed(string $name)
    {
        return self::where('url', $name)->first();
    }

    static public function getAllWithPagination(
        $pageNum, $firstIndex, $lastIndex, int $numShown = 4,
        string $pagingFor = ''
    ) {
        $tmp = self::all();
        $num = count($tmp);
        return [
            'sections' => $tmp,
            'pagination' => Page::genPagination(
                $pageNum, 
                $firstIndex <= $num ? $firstIndex : 0,
                $lastIndex <= $num ? $lastIndex : 0,
                $num,
                Page::genRange(0, $num), $numShown, $pagingFor
            )
        ];
    }
}
