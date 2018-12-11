<?php

namespace App\Utilities\Permits;


use Illuminate\Support\Collection,
    // Illuminate\Support\HtmlString,
    Illuminate\Support\Facades\Crypt,
    // HTMLPurifier, DB,
    // Composer\Semver\Comparator,
    App\Utilities\Functions\Functions,
    App\Utilities\Validator,
    App\Rules\ScalarTypeRule,
    App\Rules\NonEmptyRule,
    App\Rules\RequiredTypeRule,
    App\Rules\OptionalRule,
    App\UserRole;
use Illuminate\Support\Facades\Hash;

/**
 * Undocumented class
 */
class Permits
{
    // OUR Permissions Utilities FUNCTIONS!

    protected $user_id, $perms;

    protected static $validate = null;

    const ADMIN_ROLE = 'admin';
    const CONTENT_ROLE = 'creator';
    const AUTH_USER_ROLE = 'user';
    const GUEST_USER_ROLE = 'guest';

    const READ_LEVEL = 1;
    const WRITE_LEVEL = 2; // also OVERWRITE, so ...
    const UPDATE_LEVEL = 2; // .. this is just an alias.
    const DELETE_LEVEL = 3;

    // permit retrieval zone..

    public function __construct(int $user_id = -1, bool $p = false)
    {
        $this->user_id = $user_id;
        //$this->perms = self::getPermissions($user_id);
        if ($user_id > 0) {
            $tmp = UserRole::getForUser($user_id);
            if ($p) {
                $this->perms = $this->p($tmp);
            } else {
                $this->perms = $tmp;
            }
        } else {
            $this->perms = [];
        }
        if (is_null(static::$validate)) {
            static::$validate = [
                'role' => [new RequiredTypeRule('string')],
                'level' => [new RequiredTypeRule('int')],
                'extra' => [new OptionalRule('array')]
            ];
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

    static private function genPermStr(int $user_id, int $perm = 0)
    {
        //$user_id = self::getUserId($user);
        $tmp = '_perm_' . $perm . '_user_' . $user_id;
        return $tmp;
    }

    static private function translate2perm(
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

    protected function _getAllRoles()
    {
        return [
            self::ADMIN_ROLE, self::CONTENT_ROLE, 
            self::AUTH_USER_ROLE, self::GUEST_USER_ROLE
        ];
    }

    protected function _getAllLevels()
    {
        return [
            self::READ_LEVEL, self::WRITE_LEVEL,
            self::UPDATE_LEVEL, self::DELETE_LEVEL
        ];
    }

    protected function _getAllRLPairs()
    {
        return [
            ['role' => self::ADMIN_ROLE, 'level' => self::READ_LEVEL],
            ['role' => self::ADMIN_ROLE, 'level' => self::WRITE_LEVEL],
            ['role' => self::ADMIN_ROLE, 'level' => self::UPDATE_LEVEL],
            ['role' => self::ADMIN_ROLE, 'level' => self::DELETE_LEVEL],

            ['role' => self::CONTENT_ROLE, 'level' => self::READ_LEVEL],
            ['role' => self::CONTENT_ROLE, 'level' => self::WRITE_LEVEL],
            ['role' => self::CONTENT_ROLE, 'level' => self::UPDATE_LEVEL],
            ['role' => self::CONTENT_ROLE, 'level' => self::DELETE_LEVEL],

            ['role' => self::AUTH_USER_ROLE, 'level' => self::READ_LEVEL],

            ['role' => self::GUEST_USER_ROLE, 'level' => self::READ_LEVEL]
        ];
    }

    protected function makeRLpair(string $role, int $level, $extra = null)
    {
        return [
            'role' => $role,
            'level' => $level,
            'extra' => $extra
        ];
    }

    // permission Testing methods..

    protected function validate(array $role) 
    {
        $bol = true;
        //dd($role, static::$validate);
        $deb = [];
        $tb = true;
        foreach (static::$validate as $key => $rule) {
            foreach ($rule as $val) {
                if (!$val->passes($key, Functions::getPropKey($role, $key))) {
                    $bol = false;
                }
                if ($tb) {
                    $deb[] = [$key, $val, $bol, Functions::getPropKey($role, $key)];
                }
            }
        }
        // dd($role, static::$validate, $deb);
        return $bol;
    }
    
    protected function p($permits)
    {
        $res = [];
        if (is_array($permits) || $permits instanceof Collection) {
            $ra = $this->_getAllRLPairs();
            foreach ($permits as $permit) {
                $t = $this->testPerms($permit, $ra);
                if ($t !== false && is_array($t) && count($t) > 0) {
                    $res[] = $permit;
                }
            }
        }
        return $res;
    }

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

    protected function testPerms($permit, array $roles) 
    {
        $extraStr = self::getExtras($permit);
        $roleStr = self::getRole($permit);
        if ($extraStr !== false && $roleStr !== false) {
            $tmp = $extraStr[$roleStr] ?? -1;
            $prev = is_string($tmp) ? strlen($tmp) : -1;
            $res = [];
            foreach ($roles as $role) {
                if ($this->validate($role)) {
                    $perm = self::translate2perm($role['role'], $role['level'], $prev);
                    $plain = self::genPermStr($this->user_id, $perm[0]);
                    if (Hash::check($plain, $roleStr)) {
                        if (Functions::hasPropKeyIn($role, 'extra')) {
                            $bol = true;
                            foreach ($role['extra'] as $key => $val) {
                                if (!self::testPermExtraHelper($extraStr, $key, $val)) {
                                    $bol = false;
                                }
                            }
                            if ($bol) {
                                $res[] = $role;    
                            }
                        } else {
                            $res[] = $role;
                        }
                    }
                }
            }
            return $res;
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
        $role, int $level = 1, array $extra = null,
        int $version = 1
    ) {
        $bol = [];
        $tmp = [];
        // dd($role);
        if (is_string($role) && $role !== '') {
            $t = [];
            $t['role'] = $role;
            if (!empty($level)) {
                $t['level'] = $level;
                if (!empty($extra)) {
                    $t['extra'] = $extra;
                }
                $tmp[] = $t;
            } 
        } elseif (is_array($role) && !empty($role)) {
            foreach ($role as $arr) {
                if ($this->validate($arr)) {
                    $tmp[] = $arr;
                } elseif ((count($arr) < 4 && count($arr) > 1) 
                    && (!empty($arr[0]) && is_string($arr[0]))
                    && (!empty($arr[1]) && is_int($arr[1]))
                ) {
                    $t = [
                        'role' => $arr[0],
                        'level' => $arr[1]
                    ];
                    if (count($arr) == 3 
                        && (!empty($arr[2]) && is_array($arr[2]))
                    ) {
                        $t['extra'] = $arr[2];
                    } 
                    $tmp[] = $t;
                }
            }
        } 
        //dd($tmp, __METHOD__);
        if (count($tmp) > 0 && count($this->perms) > 0) {
            if ($version === 1) {
                foreach ($tmp as $ro) {
                    $tBol = false;
                    foreach ($this->perms as $perm) {
                        if ($this->testPermHash($perm, $ro['role'], $ro['level'])) {
                            if (Functions::hasPropKeyIn($ro, 'extra')) {
                                if ($this->testPermExtra($perm, $ro['extra'])) {
                                    $tBol = true;
                                    break;
                                }
                            } else {
                                $tBol = true;
                                break;
                            }
                        } 
                    }
                    $bol[] = [$ro, $tBol];
                }
            } elseif ($version === 2) {
                foreach ($this->perms as $perm) {
                    $tr = $this->testPerms($perm, $tmp);
                    if ($tr !== false && count($tr) > 0) {
                        $bol = array_merge($bol, $tr);
                    } 
                }
            }
        }
        if (count($bol) === 0) {
            return false;
        } elseif (count($bol) === 1) {
            return $version === 1 ? $bol[0][1] : $bol;
        } else {
            return $bol;
        }
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