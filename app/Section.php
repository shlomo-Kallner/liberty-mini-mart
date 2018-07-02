<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    static public function getNamed(string $name)
    {
        return self::where('url', $name)->first();
    }
}
