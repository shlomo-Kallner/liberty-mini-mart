<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    App\Utilities\Functions\Functions,
    App\Page,
    App\Image,
    App\SectionImage;

class Section extends Model
{
    static public function getNamed(string $name)
    {
        return self::where('url', $name)->first();
    }
    static public function getAllModels() 
    {
        $tmp = self::all()->toArray();
        /* foreach ($tmp as $section) {
            $section = Functions::dbModel2ViewModel($section);
        } */
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

    static public function createNew(
        string $name, string $url, string $title, string $article,
        string $description, $img, string $sub_title = ''
    ) {
        $tmp = self::where('name', $name)
            ->orWhere('url', $url)
            ->get();
        if (!Functions::testVar($tmp) || count($tmp) === 0) {
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
                $data = new self;
                $data->name = $name;
                $data->image = $tImg;
                $data->sub_title = $sub_title;
                $data->article = $article;
                $data->url = $url;
                $data->description = $description;
                if ($data->save()) {
                    if (Functions::testVar(SectionImage::createNewFrom($data))) {
                        return $data->id;
                    }
                }
            }
             
        }
        return null;
    }

    static public function createNewFrom(Array $array)
    {
        return self::createNew(
            $array['name'], $array['url'], $array['title'], 
            $array['article'], $array['description'], 
            $array['img'], $array['sub_title']
        );
    }

    static public function getFromId(int $id)
    {
        return self::where('id', $id)->find();
    }

    static public function existsId(int $id)
    {
        return Functions::testVar(self::getFromId($id));
    }
}
