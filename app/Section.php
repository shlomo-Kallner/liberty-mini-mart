<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions,
    App\Page;

class Section extends Model
{
    static public function getNamed(string $name)
    {
        return self::where('url', $name)->first();
    }
    static public function getAllModels() 
    {
        $tmp = self::all()->toArray();
        foreach ($tmp as $section) {
            $section = Functions::dbModel2ViewModel($section);
        }
        return $tmp;
    }

    static public function getAllWithPagination(
        $pageNum, $firstIndex, $lastIndex, int $numShown = 4,
        string $pagingFor = ''
    ) {
        $tmp = self::getAllModels();
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
