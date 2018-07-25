<?php

namespace App\Utilities\Permits;


use Illuminate\Support\Collection,
    Illuminate\Support\HtmlString,
    Illuminate\Support\Facades\Crypt,
    HTMLPurifier, DB,
    Composer\Semver\Comparator,
    App\Utilities\Functions\Functions,
    App\UserRole;

/**
 * Undocumented class
 */
class Permits
{
    // OUR Permissions Utilities FUNCTIONS!

    //protected static $_roles_table = 'userRoles';
    protected $user_id, $perms;

    // permit retrieval zone..

    public function __construct(int $user_id = -1)
    {
        $this->user_id = $user_id;
        //$this->perms = self::getPermissions($user_id);
        if ($user_id > 0) {
            $this->perms = UserRole::getForUser($user_id);
        } else {
            $this->perms = collect([]);
        }
        
    }
    
    /**
     * Function getPermisions  - just gets all permissions of a user.
     *
     * @param [type] $user
     * @return void
     */
    static protected function getPermissions(int $user_id)
    {
        // OBSOLETE!!
        //$user_id = self::getUserId($user);
        // $tmp = DB::table(self::$_roles_table)
        //  ->where('user_id', $user_id)->get();
        
        $tmp = UserRole::getForUser($user_id);
        return $tmp->count() > 0 ? $tmp->toArray() : [];
    }

    

    // permit Creation zone..

    public function addPermit(
        string $role, int $level = 1, array $extra = null
    ) {
        $tmp = self::makePermit($this->user_id, $role, $level, $extra);
        if ($tmp !== false) {
            $this->perms->push($tmp);
            return true;
        }
        
        // todo finish!!

        //ERROR RETURN!
        return false;
    }

    static protected function makePermit(
        int $user_id, string $role, int $level = 1, 
        array $extra = null
    ) {
        $perm = self::translate2perm($role, $level);
        // this renders 'genHashedPermStr' OBSOLETE!
        $hash = Hash::make(self::genPermStr($user_id, $perm[0]));
        if (!Functions::testVar($extra)) {
            $extra = [];
        }
        if (is_array($extra) && !isset($extra[$hash])) {
            $extra[$hash] = str_random($perm[1]);
            return UserRole::createNewRole(
                $user_id, $hash,
                Crypt::encrypt($extra)
            );
        }
        return false;
    }

    // general private utilities zone..

    static protected function genPermStr(int $user_id, int $perm = 0)
    {
        //$user_id = self::getUserId($user);
        $tmp = '_perm_' . $perm . '_user_' . $user_id;
        return $tmp;
    }

    static protected function translate2perm(
        string $role, int $level = 1, int $prev = -1
    ) {
        $tmp = 0;
        if ($role == 'admin') {
            // site admin
            if ($level == 1) {
                $tmp = 4;
            } elseif ($level == 2) {
                $tmp = 3;
            } elseif ($level == 3) {
                $tmp = 2;
            } else {
                // non existent admin level -> return fake data
                $tmp = random_int(0, 1);
            }
        } elseif ($role == 'creator') {
            // content creator 
            if ($level == 1) {
                $tmp = 7;
            } elseif ($level == 2) {
                $tmp = 6;
            } elseif ($level == 3) {
                $tmp = 5;
            } else {
                // non existent creator level -> return fake data
                $tmp = random_int(0, 1);
            }
        } elseif ($role == 'user') {
            // an ordinary authenticated User
            if ($level == 1) {
                $tmp = 8;
            } else {
                // non existent user level -> return fake data
                $tmp = random_int(0, 1);
            }
        } elseif ($role == 'guest') {
            // an unauthenticated guest
            if ($level == 1) {
                $tmp = 9;
            } else {
                // non existent user level -> return fake data
                $tmp = random_int(0, 1);
            }
        } else {
            // fake data...
            $tmp = random_int(0, 1);
        }
        $ran = $prev < 0 ? random_int(0, 9) : $prev;
        return [($tmp * 10) + $ran, $ran];
    }


    // permission Testing methods..

    protected function testPerm(
        $permit, string $role, int $level = 1
    ) {
        if (is_array($permit)) {
            $extraStr = $permit['extra'];
            $roleStr = $permit['role'];
        } elseif ($permit instanceof UserRole) {
            $extraStr = $permit->extra;
            $roleStr = $permit->role;
        }
        $tmp = Crypt::decrypt($extraStr)[$roleStr] ?? -1;
        $prev = is_string($tmp) ? strlen($tmp) : -1;
        $perm = self::translate2perm($role, $level, $prev);
        $plain = self::genPermStr($this->user_id, $perm[0]);
        return Hash::check($plain, $roleStr);
    }

    protected function testIfInPerms(
        string $role, int $level = 1
    ) {
        $bol = false;
        foreach ($this->perms as $perm) {
            if ($this->testPerm($perm, $role, $level)) {
                $bol = true;
                break;
            }
        }
        return $bol;
    }

    protected function getIfIsInPerms(
        string $role, int $level = 1
    ) {
        $res = [];
        foreach ($this->perms as $perm) {
            if ($this->testPerm($perm, $role, $level)) {
                $res[] = $perm;
            }
        }
        return $res;
    }

    // BASIC PUBLIC permission Testing methods..

    public function isAdmin() 
    {
        //$user_id = self::getUserId($user);
        return $this->testIfInPerms('admin', 1) ||
            $this->testIfInPerms('admin', 2) ||
            $this->testIfInPerms('admin', 3);
    }

    public function isContentCreator() 
    {
        return $this->testIfInPerms('creator', 1) ||
            $this->testIfInPerms('creator', 2) ||
            $this->testIfInPerms('creator', 3);
    }

    public function isAuthUser()
    {
        return $this->testIfInPerms('user', 1);
    }

    public function isGuestUser()
    {
        return $this->testIfInPerms('guest', 1);
    }
    
}