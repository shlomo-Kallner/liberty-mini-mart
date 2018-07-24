<?php

namespace App\Utilities\Permits;


use Illuminate\Support\Collection,
    Illuminate\Support\HtmlString,
    Illuminate\Support\Facades\Crypt,
    HTMLPurifier, DB,
    Composer\Semver\Comparator,
    App\Utilities\Functions\Functions;

/**
 * Undocumented class
 */
class Permits
{
    // OUR Permissions Utilities FUNCTIONS!

    protected static $_roles_table = 'userRoles';
    protected $_user_id, $_perms;

    // permit retrieval zone..

    public function __construct(int $user_id)
    {
        $this->_user_id = $user_id;
        $this->_perms = self::getPermissions($user_id);
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
        
        $tmp = DB::table(self::$_roles_table)
            ->where('user_id', $user_id)
            ->get();
        return $tmp->count() > 0 ? $tmp->toArray() : null;
    }

    

    // permit Creation zone..

    protected function __construct(
        int $user_id, string $role, int $level, $extra, bool $save = false
        ) {
        $this->_user_id = $user_id;
        $perm = [
            'user_id' => $user_id,
            'extra' => Crypt::encrypt($extra),
            'role' => '',
            // todo finish!! should use genHashedPermStr below..
        ];
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

    static protected function testIfInPerms(
        $perms, int $user_id, string $role, int $level = 1
    ) {
        $bol = false;
        foreach ($perms as $perm) {
            $roleStr = $perm['role'];
            $plain = self::genPermStr($user_id, self::translate2perm($role, $level));
            if (Hash::check($plain, $roleStr)) {
                $bol = true;
                break;
            }
        }
        return $bol;
    }

    static protected function translate2perm(string $role, int $level = 1)
    {
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
        return ($tmp * 10) + random_int(0, 9);
    }

    static protected function setPermission(int $user_id, $role, int $level = 1)
    {
        $permit = self::translate2perm($role, $level);
        $role
    }

    static public function isAdmin(int $user_id, $perms) 
    {
        //$user_id = self::getUserId($user);
        return self::testIfInPerms($perms, $user, 'admin', 1) ||
            self::testIfInPerms($perms, $user, 'admin', 2) ||
            self::testIfInPerms($perms, $user, 'admin', 3);
    }
    
}