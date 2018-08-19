<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Database\Eloquent\SoftDeletes,
    App\Utilities\Functions\Functions,
    App\PageGroup;

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

    static public function createNew(int $group, int $page, int $order)
    {
        if ($page > 0) {
            if ($group < 1) {
                $tgi = PageGroup::getRandId(); //self::max('group_id') + 1;
            } else {
                $tgi = $group;
            } 
            if (!Functions::testVar($gr = PageGroup::getFrom($tgi)) && $tgi === 1) {
                $gr = PageGroup::createNew('Pages');
            }
            if (Functions::testVar($gr)) {
                $group_id = $gr->id;
            }
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
                    ['page_id', '=', $page],
                    ['order', '=', $order_num]
                ]
            )->get();
            //dd($tg, "mySecond");
            if (!Functions::testVar($tg) || count($tg) === 0) {
                $tmp = new self;
                //dd($tmp, "myThird");
                $tmp->group_id = $group_id;
                $tmp->page_id = $page;
                $tmp->order = $order_num;
                //dd($tmp, "myFourth");
                if ($tmp->save()) {
                    //dd($tmp, "myFifth");
                    if (self::reorderAround($tmp->group_id, $tmp->page_id, $tmp->order, $tmp->id)) {
                        //dd($tmp, "mySixth");
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

    static public function reorderAround(
        int $group, int $page, int $order,
        int $pgId = -1
    ) {
        $tg = self::getGroup($group);
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

    static public function getRandOrder(int $group)
    {
        $m = self::where('group_id', $group)->max('order');
        return Functions::testVar($m) && $m > 1 ? random_int(1, $m) : 1;
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

    public function group()
    {
        $this->belongsTo('App\PageGroup', 'group_id', 'id');
    }

    public function page()
    {
        $this->hasOne('App\Page', 'id', 'page_id');
    }

}
