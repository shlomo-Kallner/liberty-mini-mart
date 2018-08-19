<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Database\Eloquent\SoftDeletes,
    App\Utilities\Functions\Functions;


class PageGroup extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_groups';

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
                $o = self::count() + 1;
            } else {
                self::orderAround($order);
                $o = $order;
            }
            $data = new self;
            $data->name = $name;
            $data->order = $o;
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
        } elseif ($pg instanceof self) {
            $tmp = $pg;
        } else {
            $tmp = null;
        }
        return $tmp;
    }

    static public function exists($pg)
    {
        return Functions::testVar(self::getFrom($pg));
    }

    /**
     * Function getRandId - get a random ID of an existing PageGroup
     *                      if there are none create and return one 
     *                      using $newName for the new PageGroup's name.
     *                      Returns null on Failure.
     *
     * @param string $newName 
     * @return int|null
     */
    static public function getRandId(string $newName = 'Pages')
    {
        $n = self::count();
        if ($n > 0) {
            return $n !== 1 ? random_int(1, $n) : 1;
        } else {
            if (Functions::testVar($ng = self::getFrom($newName))) {
                return $ng->id;
            } elseif (Functions::testVar($newName)) {
                $tng = self::createNew($newName);
                return Functions::testVar($tng) ? $tng : null;
            }
        }
        return null;
    }

    public function pages()
    {
        return $this->hasManyThrough(
            'App\Page', 'App\PageGrouping',
            'group_id', 'id',
            'id', 'page_id'
        );
    }

}
