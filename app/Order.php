<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    static public function getFromId(int $id)
    {
        return self::where('id', $id)->first();
    }

    static public function existsId(int $id)
    {
        return Functions::testVar(self::getFromId($id));
    }

    static public function createNew()
    {
        //
    }

    static public function createNewFrom(array $array)
    {
        return self::createNew();//
    }

    public function validate()
    {
        //
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
