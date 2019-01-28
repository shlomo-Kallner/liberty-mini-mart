<?php

namespace App\Utilities\Permits;

use App\Utilities\Functions\Functions,
    Illuminate\Support\Collection,
    Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Constraint\IsTrue;

class Basic extends Permits
{
    protected  $basics, $version;
    
    const BASIC_TYPE = ['_internal_:type' => 'BASIC'];

    public function __construct(int $user_id = -1, bool $p = false, int $version = 2)
    {
        parent::__construct($user_id, $p);
        $this->version = $version;
        $this->basics = $this->getBasics($version);
    }


    //  Permit Addition..

    public function addPermit(string $role, int $level = 1) 
    {
        return $this->addPermitPlus($role, $level, null, false);
    }

    public function addPermitRegen(string $role, int $level = 1) 
    {
        return $this->addPermitPlus($role, $level, null, true);
    }

    public function addPermitPlus(
        string $role, int $level = 1, array $extra = null, 
        bool $regen = false
    ) {
        if (Functions::testVar($extra)) {
            foreach (self::BASIC_TYPE as $key => $val) {
                $extra[$key] = $val;
            }
        } else {
            $extra = self::BASIC_TYPE;
        }
        $tmp = parent::addPermitExtra($role, $level, $extra);
        if ($regen && $tmp) {
            $this->regen();
        }
        return $tmp;
    } 

    // some Permit addition shortcuts.. YIKES!!

    public function setAdmin(bool $regen = false)
    {
        return $this->addPermitPlus(
            parent::ADMIN_ROLE, parent::READ_LEVEL,
            null, $regen
        );
    }

    public function setContentCreator(bool $regen = false)
    {
        return $this->addPermitPlus(
            parent::CONTENT_ROLE, parent::READ_LEVEL,
            null, $regen
        );
    }

    public function setAuthUser(bool $regen = false)
    {
        return $this->addPermitPlus(
            parent::AUTH_USER_ROLE, parent::READ_LEVEL,
            null, $regen
        );
    }

    public function setGuestUser(bool $regen = false)
    {
        return $this->addPermitPlus(
            parent::GUEST_USER_ROLE, parent::READ_LEVEL,
            null, $regen
        );
    }

    

    // Permit Removal..
    
    public function removePermit(string $role, int $level = 1) 
    {
        $this->removePermitPlus($role, $level, null, false);
    }

    public function removePermitRegen(string $role, int $level = 1)
    {
        $this->removePermitPlus($role, $level, null, true);
    }

    public function removePermitPlus(
        string $role, int $level = 1, array $extra = null, 
        bool $regen = false
    ) {
        if (Functions::testVar($extra)) {
            foreach (self::BASIC_TYPE as $key => $val) {
                $extra[$key] = $val;
            }
        } else {
            $extra = self::BASIC_TYPE;
        }
        parent::removePermitExtra($role, $level, $extra);
        if ($regen) {
            $this->regen();
        }
        return $tmp;
    } 

    // some Permit removal shortcuts.. YIKES!!

    public function remAdmin(bool $regen = false)
    {
        return $this->removePermitPlus(
            parent::ADMIN_ROLE, parent::READ_LEVEL,
            null, $regen
        );
    }

    public function remContentCreator(bool $regen = false)
    {
        return $this->removePermitPlus(
            parent::CONTENT_ROLE, parent::READ_LEVEL,
            null, $regen
        );
    }

    public function remAuthUser(bool $regen = false)
    {
        return $this->removePermitPlus(
            parent::AUTH_USER_ROLE, parent::READ_LEVEL,
            null, $regen
        );
    }

    public function remGuestUser(bool $regen = false)
    {
        return $this->removePermitPlus(
            parent::GUEST_USER_ROLE, parent::READ_LEVEL,
            null, $regen
        );
    }

    // BASIC Private/Protected Testing method

    private function getBasics(int $version = 2)
    {
        $res = [];
        $p = [
            parent::makeRLpair(parent::ADMIN_ROLE, parent::READ_LEVEL, self::BASIC_TYPE),
            parent::makeRLpair(parent::ADMIN_ROLE, parent::WRITE_LEVEL, self::BASIC_TYPE),
            parent::makeRLpair(parent::ADMIN_ROLE, parent::DELETE_LEVEL, self::BASIC_TYPE),

            parent::makeRLpair(parent::CONTENT_ROLE, parent::READ_LEVEL, self::BASIC_TYPE),
            parent::makeRLpair(parent::CONTENT_ROLE, parent::WRITE_LEVEL, self::BASIC_TYPE),
            parent::makeRLpair(parent::CONTENT_ROLE, parent::DELETE_LEVEL, self::BASIC_TYPE),
            
            parent::makeRLpair(parent::AUTH_USER_ROLE, parent::READ_LEVEL, self::BASIC_TYPE),
            
            parent::makeRLpair(parent::GUEST_USER_ROLE, parent::READ_LEVEL, self::BASIC_TYPE),
        ];
        $t = $this->testIfInPerms($p, 1, null, $version);
        // Log::info('message', [ 't' => $t]);
        $h_a = false;
        $h_c = false;
        $h_au = false;
        $h_gu = false;
        if ($t !== false && count($t) > 0) {
            foreach ($t as $r) {
                if ($version == 2 && $r['role'] === parent::ADMIN_ROLE 
                    && !$h_a 
                ) {
                    $res[] = parent::ADMIN_ROLE;
                    $h_a = true;
                } elseif ($version == 1 && $r[0]['role'] === parent::ADMIN_ROLE 
                && !$h_a && $r[1]
                ) {
                    $res[] = parent::ADMIN_ROLE;
                    $h_a = true;
                }
                if ($version == 2 && $r['role'] === parent::CONTENT_ROLE 
                    && !$h_c
                ) {
                    $res[] = parent::CONTENT_ROLE;
                    $h_c = true;
                } elseif ($version == 1 && $r[0]['role'] === parent::CONTENT_ROLE 
                && !$h_c && $r[1]
                ) {
                    $res[] = parent::CONTENT_ROLE;
                    $h_c = true;
                }
                if ($version == 2 && $r['role'] === parent::AUTH_USER_ROLE 
                    && !$h_au
                ) {
                    $res[] = parent::AUTH_USER_ROLE;
                    $h_au = true;
                } elseif ($version == 1 && $r[0]['role'] === parent::AUTH_USER_ROLE 
                && !$h_au && $r[1]
                ) {
                    $res[] = parent::AUTH_USER_ROLE;
                    $h_au = true;
                }
                if ($version == 2 && $r['role'] === parent::GUEST_USER_ROLE 
                    && !$h_gu
                ) {
                    $res[] = parent::GUEST_USER_ROLE;
                    $h_gu = true;
                } elseif ($version == 1 && $r[0]['role'] === parent::GUEST_USER_ROLE 
                && !$h_gu && $r[1]
                ) {
                    $res[] = parent::GUEST_USER_ROLE;
                    $h_gu = true;
                }
                
            }
        }
        if ($version === 1) {
            return $res;
        } elseif ($version === 2) {
            return [
                parent::ADMIN_ROLE => $h_a,
                parent::CONTENT_ROLE => $h_c,
                parent::AUTH_USER_ROLE => $h_au,
                parent::GUEST_USER_ROLE => $h_gu,
            ];
        } else {
            return [];
        }
    }


    // BASIC PUBLIC permission Testing methods..

    public function regen()
    {
        $this->basics = $this->getBasics($this->version);
    }

    public function isAdmin() 
    {
        //$user_id = self::getUserId($user);
        if ($this->version === 1) {
            return in_array(parent::ADMIN_ROLE, $this->basics, true);
        } elseif ($this->version === 2) {
            return $this->basics[parent::ADMIN_ROLE];
        }
    }

    public function isContentCreator() 
    {
        if ($this->version === 1) {
            return in_array(parent::CONTENT_ROLE, $this->basics, true); 
        } elseif ($this->version === 2) {
            return $this->basics[parent::CONTENT_ROLE];
        }
    }

    public function isAuthUser()
    {
        if ($this->version === 1) {
            return in_array(parent::AUTH_USER_ROLE, $this->basics, true); 
        } elseif ($this->version === 2) {
            return $this->basics[parent::AUTH_USER_ROLE];
        }
    }

    public function isGuestUser()
    {
        if ($this->version === 1) {
            return in_array(parent::GUEST_USER_ROLE, $this->basics, true); 
        } elseif ($this->version === 2) {
            return $this->basics[parent::GUEST_USER_ROLE];
        }
    }

    public function makeFakes(int $num = 1, bool $regen = true, int $t = 0)
    {
        if ($num > 0 && $num < 10) {
            $num_to = $num;
        } else {
            $num_to = random_int(1, 6);
        }
        for ($i = 0; $i < $num_to; $i++) {
            $this->addPermit('***', 9);
            $this->addPermit('@@@', 8);
            $this->addPermit('+++', 7);
            $num_x = $t > 0 ? 3 : random_int(3, 8);
            if ($num_x > 3) {
                $this->addPermit('&&&', 6);
                if ($num_x > 4) {
                    $this->addPermit('!!!', 9);
                    if ($num_x > 5) {
                        $this->addPermit('###', 8);
                        if ($num_x > 6) {
                            $this->addPermit('$$$', 7);
                            if ($num_x > 7) {
                                $this->addPermit('%%%', 6);
                            }
                        }
                    }
                }
            }
        }
        if ($regen) {
            $this->addPermitRegen('^^^', 5);
        } else {
            $this->addPermit('^^^', 5);
        }
        
    }

}