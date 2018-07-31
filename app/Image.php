<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    static public function createNew(
        string $name, string $path, string $alt, 
        string $caption
    ) {
        $tmp = new self;
        $tmp->name = $name;
        $tmp->path = $path;
        $tmp->alt = $alt;
        $tmp->caption = $caption;
        if ($tmp->save()) {
            return $tmp->id;
        } else {
            return null;
        }
    }

    static public function createNewFromArray(array $array)
    {
        return self::createNew(
            $array['name'], $array['path'], 
            $array['alt'], $array['caption']
        );
    }
}
