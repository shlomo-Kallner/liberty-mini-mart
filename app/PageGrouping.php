<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Database\Eloquent\SoftDeletes,
    App\Utilities\Functions\Functions,
    App\PageGroup,
    App\Page;

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

    static public function createNew(
        $group, $page, int $order, 
        bool $retObj = false
    ) {
        $page_id = Page::getIdFrom($page, false, null);
        $group_id = PageGroup::getIdFrom(
            $group, false, PageGroup::getRandId('Pages')
        );
        if (Functions::testVar($page_id) && Functions::testVar($group_id)) {
            if ($order < 1) {
                $order_num = self::getRandOrder($group_id);
                if ($order_num > 1) {
                    $order_num += 1;
                } 
            } else {
                $order_num = $order;
            }
            //dd($group_id, $page, $order_num, "myFirst");
            $tg = self::where(
                [
                    ['group_id', '=', $group_id],
                    ['page_id', '=', $page_id],
                    ['order', '=', $order_num]
                ]
            )->get();
            //dd($tg, "mySecond");
            if (!Functions::testVar($tg) || !Functions::countHas($tg)) {
                $tmp = new self;
                //dd($tmp, "myThird");
                $tmp->group_id = $group_id;
                $tmp->page_id = $page_id;
                $tmp->order = $order_num;
                //dd($tmp, "myFourth");
                if ($tmp->save()) {
                    //dd($tmp, "myFifth");
                    if (self::reorderAround($tmp->group_id, $tmp->page_id, $tmp->order, $tmp->id)) {
                        //dd($tmp, "mySixth");
                        return $retObj ? $tmp : $tmp->id;
                    }
                }
            }
        }
        return null;
    }

    static public function createNewFrom(
        array $array, bool $retObj = false
    ) {
        return self::createNew(
            $array['group'], $array['page'], 
            $array['order'], $retObj
        );
    }

    static public function reorderAround(
        int $group, int $page, int $order,
        int $pgId = -1, bool $withTrashed = false
    ) {
        $tg = self::getGroup($group, $withTrashed);
        $bol = true;
        if (Functions::testVar($tg)) {
            if (count($tg) !== 0) {
                // need to 'move' (increment 'order' on)
                // all models from '$order' upward if exists..
                //dd($tg, $group, $page, $order, $pgId);
                //$tpg = self::where()->get();
                foreach ($tg as $item) {
                    //dd($tg, $item);
                    if ($item->order >= $order && $item->id !== $pgId) {
                        $item->order += 1;
                        if (!$item->save()) {
                            // if for some unknown reason
                            // the update failed..
                            $bol = false;
                            break;
                        }
                    }
                    //dd($item);
                }
            }
        } 
        return $bol;
    }

    static public function getMaxOrder($group, bool $withTrashed = false)
    {
        $query = self::getGroupQuery($group, 'asc', $withTrashed);
        if (Functions::testVar($query)) {
            $col = $query->get();
            if (Functions::testVar($col) && Functions::countHas($col)) {
                return $col->max('order');
            }
        }
        return null;
    }

    static public function getMinOrder($group, bool $withTrashed = false)
    {
        $query = self::getGroupQuery($group, 'asc', $withTrashed);
        if (Functions::testVar($query)) {
            $col = $query->get();
            if (Functions::testVar($col) && Functions::countHas($col)) {
                return $col->min('order');
            }
        }
        return null;
    }

    static public function getRandOrder($group, bool $withTrashed = false)
    {
        $query = self::getGroupQuery($group, 'asc', $withTrashed);
        if (Functions::testVar($query)) {
            $col = $query->get();
            if (Functions::testVar($col) && Functions::countHas($col)) {
                $min = $col->min('order');
                $max = $col->max('order');
            } else {
                $min = 1;
                $max = 1;
            }
            return $max >= 1 && $min >= 1 ? random_int($min, $max) : 1;
        }
        return null;
    }

    static public function getGroupQuery($group, string $dir = 'asc', bool $withTrashed = false)
    {
        $group_id = PageGroup::getIdFrom($group, false, null);
        if (Functions::testVar($group_id)) {
            return $withTrashed
            ? self::withTrashed()->where('group_id', $group_id)
                ->orderBy('order', $dir)
            : self::where('group_id', $group_id)
                ->orderBy('order', $dir);
        }
        return null;
    }

    static public function getGroupingsOfPage($page, string $dir = 'asc', bool $withTrashed = false)
    {
        $page_id = Page::getIdFrom($page, false, null);
        if (Functions::testVar($page_id)) {
            $tmp = $withTrashed 
            ? self::withTrashed()->where('page_id', $page_id)->get()
            : self::where('page_id', $page_id)->get();
            if (Functions::testVar($tmp) && Functions::countHas($tmp)) {
                return $tmp;
            }
        }
        return null;
    }

    static public function getGroup($group, string $dir = 'asc', bool $withTrashed = false)
    {
        $query = self::getGroupQuery($group, $dir, $withTrashed);
        if (Functions::testVar($query)) {
            return $query->get();
        }
        return null;
    }

    static public function getGroups(string $dir = 'asc', bool $withTrashed = false)
    {
        return self::orderBy('group_id', $dir)->get();
    }

    static public function getPageAndGroupListing($page, $group, bool $withTrashed = false)
    {
        $page_id = Page::getIdFrom($page, false, null);
        $group_id = PageGroup::getIdFrom($group, false, null);
        if (Functions::testVar($page_id) && Functions::testVar($group_id)) {
            $tmp = $withTrashed 
            ? self::withTrashed()->where(
                [
                    ['page_id', '=', $page_id],
                    ['group_id', '=', $group_id]
                ]
            )->get()
            : self::where(
                [
                    ['page_id', '=', $page_id],
                    ['group_id', '=', $group_id]
                ]
            )->get();
            if (Functions::testVar($tmp) && Functions::countHas($tmp)) {
                return $tmp;
            }
        }
        return null;
    }

    static public function getOrderForPage($page, $group, bool $withTrashed = false)
    {
        $tmp = self::getPageAndGroupListing($page, $group, $withTrashed);
        if (Functions::testVar($tmp) && Functions::countHas($tmp)) {
            return $tmp[0]->order;
        }
        return null;
    }

    public function group()
    {
        return $this->belongsTo('App\PageGroup', 'group_id', 'id');
    }

    public function page()
    {
        return $this->hasOne('App\Page', 'id', 'page_id');
    }

}
