<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
