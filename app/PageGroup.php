<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot,
    Illuminate\Database\Eloquent\SoftDeletes,
    App\Utilities\Functions\Functions;

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
        if ($page >= 0) {
            if ($group < 1) {
                $group_id = self::max('group') + 1;
            } else {
                $group_id = $group;
            } 
            if ($order < 1) {
                $to = self::where('group', $group_id)->max('order');
                if ($to > 0) {
                    $order_num = $to + 1;
                } else {
                    $order_num = 1;
                }
            } else {
                $order_num = $order;
            }
            $tg = self::where(
                [
                    ['group', '=', $group_id],
                    ['page', '=', $page],
                    ['order', '=', $order_num]
                ]
            )->get();
            if (!Functions::testVar($tg) || count($tg) === 0) {
                $tmp = new self;
                $tmp->group = $group_id;
                $tmp->page = $page;
                $tmp->order = $order;
                if ($tmp->save()) {
                    return $tmp->id;
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
        $tg = self::where('group', $group)
            ->orderBy('order', 'asc')
            ->get();
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

    static public function getGroup(int $group)
    {
        return self::where('group', $group)->get();
    }

    static public function getGroups(string $dir = 'asc')
    {
        return self::orderBy('group', $dir)->get();
    }
}
