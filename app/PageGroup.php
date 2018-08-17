<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot,
    Illuminate\Database\Eloquent\SoftDeletes,
    App\Utilities\Functions\Functions,
    App\PageGrouping;

class PageGroup extends Pivot
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

    static public function createNew(int $group, int $page, int $order)
    {
        if ($page > 0) {
            if ($group < 1) {
                $group_id = PageGrouping::getRandId(); //self::max('group_id') + 1;
            } else {
                $group_id = $group;
            } 
            if ($order < 1) {
                $order_num = self::getRandOrder($group_id);
                if ($order_num > 1) {
                    $order_num += 1;
                } 
            } else {
                $order_num = $order;
            }
            $tg = self::where(
                [
                    ['group_id', '=', $group_id],
                    ['page_id', '=', $page],
                    ['order', '=', $order_num]
                ]
            )->get();
            if (!Functions::testVar($tg) || count($tg) === 0) {
                $tmp = new self;
                $tmp->group_id = $group_id;
                $tmp->page_id = $page;
                $tmp->order = $order_num;
                if ($tmp->save()) {
                    if (self::reorderAround($tmp->group_id, $tmp->page_id, $tmp->order)) {
                        return $tmp->id;
                    }
                }
            }
        }
        return null;
    }

    static public function createNewFrom(array $array) 
    {
        return self::createNew(
            $array['group'], $array['page'], $array['order']
        );
    }

    static public function reorderAround(int $group, int $page, int $order) 
    {
        $tg = self::getGroup($group);
        $bol = true;
        if (Functions::testVar($tg)) {
            if (count($tg) !== 0) {
                // need to 'move' (increment 'order' on)
                // all models from '$order' upward if exists..
                foreach ($tg as $item) {
                    if ($tg->order >= $order) {
                        $tg->order += 1;
                        if (!$tg->save()) {
                            // if for some unknown reason
                            // the update failed..
                            $bol = false;
                            break;
                        }
                    }
                }
            }
        } 
        return $bol;
    }

    static public function getRandOrder(int $group)
    {
        $m = self::where('group_id', $group)->max('order');
        return $m > 1? random_int(1, $m) : 1;
    }

    static public function getGroup(int $group, string $dir = 'asc')
    {
        return self::where('group_id', $group)
            ->orderBy('order', $dir)
            ->get();
    }

    static public function getGroups(string $dir = 'asc')
    {
        return self::orderBy('group_id', $dir)->get();
    }
}
