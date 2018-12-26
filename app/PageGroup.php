<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Database\Eloquent\SoftDeletes,
    App\Image,
    App\Utilities\Functions\Functions,
    App\Utilities\ContainerTransforms,
    App\Utilities\TransformableContainer,
    App\Utilities\ContainerAPI,
    App\Utilities\ContainerID;


class PageGroup extends Model implements TransformableContainer, ContainerAPI
{
    use SoftDeletes, ContainerTransforms, ContainerID;
    
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
        string $name, int $order = -1, bool $retObj = false
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
                return $retObj ? $data : $data->id;
            }
        }
        return null;
    }

    static public function createNewFrom(array $array, bool $retObj = false)
    {
        return self::createNew(
            $array['name'], $array['order']??-1,
            $retObj
        );
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

    static public function getIdFrom($pg)
    {
        if (Functions::testVar($pg)) {
            if (is_int($pg) && self::exists($pg)) {
                return $pg;
            } elseif (is_string($pg)) {
                $t = self::getFrom($pg);
                return Functions::testVar($t) ? $t->id : null;
            } elseif ($pg instanceof self) {
                return $pg->id;
            }
        }
        return null;
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

    static public function makeContentArray(
        int $id, string $name, int $order, array $pages = null
    ) {
        return [
            'id' => $id,
            'name' => $name,
            'order' => $order,
            'pages' => $pages
        ];
    }

    public function toContentArray(string $dir = 'asc')
    {
        $p = PageGrouping::getGroup($this, $dir);
        $res = [];
        foreach ($p as $tp) {
            $res[] = $tp->page->toContentArray();
        }
        return self::makeContentArray(
            $this->id, $this->name, $this->order,
            $res
        );
    }

    static public function getAllGroups(
        bool $toArray = true, string $dir = 'asc'
    ) {
        $tmp = self::orderBy('order', $dir)->get();
        if (Functions::testVar($tmp) && count($tmp) > 0) {
            if ($toArray) {
                $res = [];
                foreach ($tmp as $g) {
                    $res[] = $g->toContentArray($dir);
                }
                return $res;
            } else {
                return $tmp;
            }
        }
        return null;
    }

}
