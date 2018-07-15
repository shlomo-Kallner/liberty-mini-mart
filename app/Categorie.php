<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Page;

class Categorie extends Model
{
    static public function getNamed(string $name, $section_id)
    {
        return self::where([
            'url' => $name,
            'section_id' => $section_id
        ])->first();
    }

    static public function getCategoriesOfSection($section_id)
    {
        return self::where('section_id', $section_id)->get();
    }

    static public function getCategoriesOfSectionWithPagination(
        $section_id, $pageNum, $firstIndex, $lastIndex, int $numShown = 4,
        string $pagingFor = ''
    ) {
        $tmp = self::getCategoriesOfSection($section_id);
        $num = count($tmp);
        return [
            'categories' => $tmp,
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
