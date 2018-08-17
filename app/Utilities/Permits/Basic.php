<?php

namespace App\Utilities\Permits;

use App\Utilities\Functions\Functions;

class Basic extends Permits
{
    protected  $basics;
    
    const BASIC_TYPE = ['_internal_:type' => 'BASIC'];

    public function __construct(int $user_id = -1, bool $p = false)
    {
        parent::__construct($user_id, $p);
        $this->basics = $this->getBasics();
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

    private function getBasics()
    {
        $res = [];
        if ($this->testIfInPerms(parent::ADMIN_ROLE, 1, self::BASIC_TYPE) 
            || $this->testIfInPerms(parent::ADMIN_ROLE, 2, self::BASIC_TYPE) 
            || $this->testIfInPerms(parent::ADMIN_ROLE, 3, self::BASIC_TYPE)
        ) {
            $res[] = parent::ADMIN_ROLE;
        }
        if ($this->testIfInPerms(parent::CONTENT_ROLE, 1, self::BASIC_TYPE) 
            || $this->testIfInPerms(parent::CONTENT_ROLE, 2, self::BASIC_TYPE) 
            || $this->testIfInPerms(parent::CONTENT_ROLE, 3, self::BASIC_TYPE)
        ) {
            $res[] = parent::CONTENT_ROLE;
        }
        if ($this->testIfInPerms(parent::AUTH_USER_ROLE, 1, self::BASIC_TYPE)) {
            $res[] = parent::AUTH_USER_ROLE;
        }
        if ($this->testIfInPerms(parent::GUEST_USER_ROLE, 1, self::BASIC_TYPE)) {
            $res[] = parent::GUEST_USER_ROLE;
        }
        return $res;
    }


    // BASIC PUBLIC permission Testing methods..

    public function regen()
    {
        $this->basics = $this->getBasics();
    }

    public function isAdmin() 
    {
        //$user_id = self::getUserId($user);
        return in_array(parent::ADMIN_ROLE, $this->basics, true);
    }

    public function isContentCreator() 
    {
        return in_array(parent::CONTENT_ROLE, $this->basics, true); 
    }

    public function isAuthUser()
    {
        return in_array(parent::AUTH_USER_ROLE, $this->basics, true); 
    }

    public function isGuestUser()
    {
        return in_array(parent::GUEST_USER_ROLE, $this->basics, true); 
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