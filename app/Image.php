<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Utilities\Functions\Functions;
use Illuminate\Database\Eloquent\Relations\Pivot;


class Image extends Model
{
    static public function createNew(
        string $name, string $path, string $alt, 
        string $caption
    ) {
        $tC = self::where(
            [
                ['name', '=', $name],
                ['path', '=', $path]
            ]
        )->get();
        if (!Functions::testVar($tC) || count() === 0) {       
            $tmp = new self;
            $tmp->name = $name;
            $tmp->path = $path;
            $tmp->alt = $alt;
            $tmp->caption = $caption;
            if ($tmp->save()) {
                return $tmp->id;
            }
        } 
        return null;
    }

    static public function createNewFromArray(array $array)
    {
        return self::createNew(
            $array['name'], $array['path'], 
            $array['alt'], $array['caption']
        );
    }

    static public function createNewFrom(array $array)
    {
        return self::createNewFromArray($array);
    }

    static public function getAllForPivots($pivots)
    {
        if (Functions::testVar($pivots)) {
            $res = [];
            foreach ($pivots as $pivot) {
                if ($pivot instanceof Pivot) {
                    $t = self::getFromId($pivot->image);
                    if (Functions::testVar($t)) {
                        $res[] = $t;
                    }
                }
            }
            return $res;
        } else {
            return null;
        }
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
