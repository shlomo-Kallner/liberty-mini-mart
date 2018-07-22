<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions,
    App\Section,
    App\Page;

class Categorie extends Model
{
    static public function getNamed(string $name, $section_id)
    {
        return Functions::dbModel2ViewModel(
            $tmpself::where(
                [
                    'url' => $name,
                    'section_id' => $section_id
                ]
            )->first()
        );
    }

    /**
     * Undocumented function - DO NOT USE THIS FUNCTION!
     * 
     *
     * @param string $url
     * @return void
     */
    static public function getIdFromURL(string $url)
    {
        $tmp = explode('/', $url);
        if ($tmp[0] == 'store' || $tmp[0] == 'admin') {
            // {$tmp[0]}/section/{section}/category/{category}/product/{product}
            $section_id = Section::getNamed($tmp[2])->id;
            return self::getNamed($tmp[4], $section_id)->id;
        } elseif (count($tmp) == 1) {
            return null;
        } else {
            return null;
        }
    }

    static public function getCategoriesOfSection($section_id)
    {
        $tmp = self::where('section_id', $section_id)->get()->toArray();
        $res = [];
        foreach ($tmp as $category) {
            //dd($category);
            $res[] = Functions::dbModel2ViewModel($category);
            //dd($category);
        }
        dd($res);
        return $res;
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
