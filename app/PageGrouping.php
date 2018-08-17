<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Database\Eloquent\SoftDeletes,
    App\Utilities\Functions\Functions;


class PageGrouping extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_groupings';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    static public function orderAround(int $order)
    {
        $tmp = self::where('order', '>=', $order)->get();
        if (Functions::testVar($tmp) && count($tmp) > 0) {
            foreach ($tmp as $t) {
                if ($t->order >= $order) {
                    $t->order += 1;
                    $t->save();
                }
            }
        }
    }

    static public function createNew(
        string $name, int $order = -1
    ) {
        $tmp = self::where('name', $name)->get();
        if (!Functions::testVar($tmp) || count($tmp) === 0) {
            if ($order < 1) {
                $order = count(self::all()) + 1;
            } else {
                self::orderAround($order);
            }
            $data = new self;
            $data->name = $name;
            $data->order = $order;
            if ($data->save()) {
                return $data->id;
            }
        }
        return null;
    }

    static public function createNewFrom(array $array)
    {
        return self::createNew($array['name'], $array['order']??-1);
    }

    static public function getFrom($pg) 
    {
        if (is_string($pg) && Functions::testVar($pg)) {
            $tmp = self::where('name', $pg)->first();
        } elseif (is_int($pg) && Functions::testVar($pg)) {
            $tmp = self::where('id', $pg)->first();
        } else {
            $tmp = null;
        }
        return $tmp;
    }

    static public function exists($pg)
    {
        return Functions::testVar(self::getFrom($pg));
    }

    static public function getRandId()
    {
        $n = self::count();
        return $n > 1 ? random_int(1, $n) : 1;
    }

}
