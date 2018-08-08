<?php

namespace App;

use Illuminate\Database\Eloquent\Model,
    Illuminate\Database\Eloquent\SoftDeletes,
    App\Utilities\Functions\Functions;

class UserRole extends Model
{
    use SoftDeletes;

    /**
    * use Illuminate\Database\Eloquent\SoftDeletes;
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    //
    static public function getForUser(int $user_id)
    {
        return self::where('user_id', $user_id)->get();
    }

    static public function createNewRole(
        int $user_id, string $role, string $extra = null
    ) {
        $tmp = new self;
        $tmp->user_id = $user_id;
        $tmp->role = $role;
        $tmp->extra = $extra;
        $tmp->save();
        return $tmp;
    }

    public function updateRole(string $role, string $extra = null)
    {
        $bol = false;
        if (Functions::testVar($role)) {
            $this->role = $role;
            $bol = true;
        }
        if (Functions::testVar($extra)) {
            $this->extra = $extra;
            $bol = true;
        }
        if ($bol) {
            $this->save();
        }
        return $bol;
    }

    public function deleteRole()
    {
        $this->delete();
    }
    
}
