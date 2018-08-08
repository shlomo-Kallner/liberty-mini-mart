<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions,
    App\Section,
    App\Image,
    App\CategoryImage,
    App\Page;

class Categorie extends Model
{

    static public function createNew(
        string $name, string $url, string $description, 
        string $title, string $article, int $section_id,
        $image, string $sticker
    ) {
        $tmp = self::where(
            [
                ['name', '=', $name],
                ['section_id', '=', $section_id],
                ['url', '=', $url]
            ]
        )->get();
        if ((!Functions::testVar($tmp) || count($tmp) === 0) 
            && Section::existsId($section_id)
        ) {
            if (is_int($img) && Image::existsId($img)) {
                $tImg = $img;
            } elseif (is_array($img)) {
                $tImg = Image::createNewFrom($img);
            } elseif ($img instanceof Image) {
                $tImg = $img->id;
            } else {
                $tImg = null;
            }
            if (Functions::testVar($tImg)) {
                $res = new self;
                $res->name = $name;
                $res->image = $tImg;
                $res->title = Functions::purifyContent($title);
                $res->article = Functions::purifyContent($article);
                $res->url = $url;
                $res->section_id = $section_id;
                $res->sticker = $sticker;
                $res->description = Functions::purifyContent($description);
                if ($res->save()) {
                    $ci = CategoryImage::createNewFrom($res);
                    if (Functions::testVar($ci)) {
                        return $res->id;
                    }
                }
            }
        }
        return null;
    }

    static public function createNewFrom(array $array)
    {
        return self::createNew(
            $array['name'], $array['url'], $array['description'], 
            $array['title'], $array['article'], $array['section_id'], 
            $array['image'], $array['sticker']
        );
    }

    static public function getFromId(int $id)
    {
        return self::where('id', $id)->first();
    }

    static public function existsId(int $id)
    {
        return Functions::testVar(self::getFromId($id));
    }

    static public function getNamed(string $name, $section_id)
    {
        return Functions::dbModel2ViewModel(
            self::where(
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
        $res = [];
        $tmp = self::where('section_id', $section_id)->get()->toArray();
        foreach ($tmp as $category) {
            //dd($category);
            $tCat = [];
            foreach ($category as $key => $val) {
                if (is_string($key) && $key === 'image') {
                    $tCat[$key] = Image::getFromId(intval($val));
                }
            }
            //$res[] = Functions::dbModel2ViewModel($category);
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
