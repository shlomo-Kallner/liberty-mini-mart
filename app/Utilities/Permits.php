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

    public function __construct(int $user_id)
    {
        $this->user_id = $user_id;
        $this->perms = self::getPermissions($user_id);
    }
    
    /**
     * Function getPermisions  - just gets all permissions of a user.
     *
     * @param [type] $user
     * @return void
     */
    static protected function getPermissions(int $user_id)
    {
        //$user_id = self::getUserId($user);
        // $tmp = DB::table(self::$_roles_table)
        //  ->where('user_id', $user_id)->get();
        
        $tmp = UserRole::getForUser($user_id);
        return $tmp->count() > 0 ? $tmp->toArray() : [];
    }

    

    // permit Creation zone..

    protected function __construct(
        int $user_id, string $role, int $level = 1, array $extra = null
        //, bool $save = false
        ) {
        $this->user_id = $user_id;
        $this->perms = [];
        $perm = self::translate2perm($role, $level);
        $hash = self::genHashedPermStr($user_id, $perm[0]);
        if (!Functions::testVar($extra)) {
            $extra = [];
        }
        if (is_array($extra) && !isset($extra[$hash])) {
            $extra[$hash] = str_random($perm[1]);
        }

        $this->perms[] = UserRole::createNewRole(
            $user_id, $hash,
            Crypt::encrypt($extra)
        );
        /* 
        $perm = [
            'user_id' => $user_id,
            'extra' => Crypt::encrypt($extra),
            'role' => '',
            // todo finish!! should use genHashedPermStr below..
        ]; 
        */
        //$perm->user_id = $user_id;
        //$perm->extra = Crypt::encrypt($extra);
        // todo finish!!
    }

    // general private utilities zone..

    static protected function genPermStr(int $user_id, int $perm = 0)
    {
        //$user_id = self::getUserId($user);
        $tmp = '_perm_' . $perm . '_user_' . $user_id;
        return $tmp;
    }

    static protected function genHashedPermStr(int $user_id, int $perm = 0)
    {
        //$faked = random_int(0, 100);
        //$permit = ($perm * 10) + random_int(0, 9);
        return Hash::make(self::genPermStr($user_id, $perm));
    }

    protected function testIfInPerms(
        string $role, int $level = 1
    ) {
        $bol = false;
        foreach ($this->perms as $perm) {
            $roleStr = $perm['role'];
            $tmp = Crypt::decrypt($perm['extra'])[$roleStr] ?? -1;
            $prev = is_string($tmp) ? strlen($tmp) : -1;
            $perm = self::translate2perm($role, $level, $prev);
            $plain = self::genPermStr($this->user_id, $perm[0]);
            if (Hash::check($plain, $roleStr)) {
                $bol = true;
                break;
            }
        }
        return $bol;
    }

    static protected testPerm(
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

    protected function getIfIsInPerms(
        string $role, int $level = 1
    ) {
        $res = [];
        foreach ($this->perms as $perm) {
            $roleStr = $perm['role'];
            $tmp = Crypt::decrypt($perm['extra'])[$roleStr] ?? -1;
            $prev = is_string($tmp) ? strlen($tmp) : -1;
            $perm = self::translate2perm($role, $level, $prev);
            $plain = self::genPermStr($this->user_id, $perm[0]);
            if (Hash::check($plain, $roleStr)) {
                $res[] = $perm;
            }
        }
        return $res;
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

    public function isAdmin(int $user_id, $perms) 
    {
        //$user_id = self::getUserId($user);
        return $this->testIfInPerms('admin', 1) ||
            $this->testIfInPerms('admin', 2) ||
            $this->testIfInPerms('admin', 3);
    }
    
}