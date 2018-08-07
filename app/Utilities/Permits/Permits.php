<?php

namespace App\Utilities\Permits;


use Illuminate\Support\Collection,
    // Illuminate\Support\HtmlString,
    Illuminate\Support\Facades\Crypt,
    // HTMLPurifier, DB,
    // Composer\Semver\Comparator,
    App\Utilities\Functions\Functions,
    App\UserRole;
use Illuminate\Support\Facades\Hash;

/**
 * Undocumented class
 */
class Permits
{
    // OUR Permissions Utilities FUNCTIONS!

    protected $user_id, $perms;

    const ADMIN_ROLE = 'admin';
    const CONTENT_ROLE = 'creator';
    const AUTH_USER_ROLE = 'user';
    const GUEST_USER_ROLE = 'guest';

    const READ_LEVEL = 1;
    const WRITE_LEVEL = 2; // also OVERWRITE, so ...
    const UPDATE_LEVEL = 2;
    const DELETE_LEVEL = 3;

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

    

    // permit Creation zone..

    public function addPermitExtra(
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

    // permission deletion methods..

    public function removePermitExtra(
        string $role, int $level = 1, array $extra = null
    ) {
        $this->delIfIsInPerms($role, $level, $extra);
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
        $ran = ($prev < 1 || $prev > 9) ? random_int(1, 9) : $prev;
        return [($tmp * 10) + $ran, $ran];
    }

    // Special Getters..

    static protected function getExtras($permit)
    {
        if (Functions::testVar($permit)) {
            if (is_array($permit)) {
                $extraStr = $permit['extra'];
            } elseif ($permit instanceof UserRole) {
                $extraStr = $permit->extra;
            } else {
                return false;
            }
            return Crypt::decrypt($extraStr);
        } else {
            return false;
        }
    }

    static protected function getRole($permit) 
    {
        if (Functions::testVar($permit)) {
            if (is_array($permit)) {
                $roleStr = $permit['role'];
            } elseif ($permit instanceof UserRole) {
                $roleStr = $permit->role;
            } else {
                $roleStr = false;
            }
            return $roleStr;
        } else {
            return false;
        }
    }

    // permission Testing methods..

    protected function testPermHash(
        $permit, string $role, int $level = 1
    ) {
        $extraStr = self::getExtras($permit);
        $roleStr = self::getRole($permit);
        if ($extraStr !== false && $roleStr !== false) {
            $tmp = $extraStr[$roleStr] ?? -1;
            $prev = is_string($tmp) ? strlen($tmp) : -1;
            $perm = self::translate2perm($role, $level, $prev);
            $plain = self::genPermStr($this->user_id, $perm[0]);
            return Hash::check($plain, $roleStr);
        } else {
            return false;
        }
    }

    static protected function testPermExtraHelper($extraData, $key, $val = null) 
    {
        $bol = false;
        if (Functions::testVar($extraData)) {
            $bol = Functions::isValIn($extraData, $key, $val);
        }
        return $bol;
    }

    protected function testPermExtraSingle($permit, $key, $val = null) 
    {
        $tmp = $this->getExtras($permit);
        if ($tmp !== false) {
            return self::testPermExtraHelper($tmp, $key, $val);
        } else {
            return false;
        }
    }
    
    protected function testPermExtra($permit, array $extra = null) 
    {
        $bol = true;
        if (Functions::testVar($extra)) {
            $tmp = $this->getExtras($permit);
            foreach ($extra as $key => $val) {
                if (!self::testPermExtraHelper($tmp, $key, $val)) {
                    $bol = false;
                }
            }
        }
        return $bol;
    }

    protected function testIfInPerms(
        string $role, int $level = 1, array $extra = null
    ) {
        $bol = false;
        foreach ($this->perms as $perm) {
            if ($this->testPermHash($perm, $role, $level)) {
                if (Functions::testVar($extra)) {
                    if ($this->testPermExtra($perm, $extra)) {
                        $bol = true;
                        break;
                    }
                } else {
                    $bol = true;
                    break;
                }
            }
        }
        return $bol;
    }

    protected function getIfIsInPerms(
        string $role, int $level = 1, array $extra = null
    ) {
        $res = [];
        foreach ($this->perms as $perm) {
            if ($this->testPermHash($perm, $role, $level)) {
                if (Functions::testVar($extra)) {
                    if ($this->testPermExtra($perm, $extra)) {
                        $res[] = $perm;
                    }
                } else {
                    $res[] = $perm;
                }
            }
        }
        return $res;
    }

    protected function delPerm($key, $perm)
    {
        if ($this->perms->get($key) === $perm) {
            $this->perms->pull($key);
            $perm->deleteRole();
        }
    }

    protected function delIfIsInPerms(
        string $role, int $level = 1, array $extra = null
    ) {
        foreach ($this->perms as $key => $perm) {
            if ($this->testPermHash($perm, $role, $level)) {
                if (Functions::testVar($extra)) {
                    if ($this->testPermExtra($perm, $extra)) {
                        $this->delPerm($key, $perm);
                    }
                } else {
                    $this->delPerm($key, $perm);
                }
            }
        }
    }

    
    
}