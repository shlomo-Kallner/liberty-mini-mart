<?php

namespace App\Utilities\Permits;

class Basic extends Permits
{
    protected  $basics;
    
    const BASIC_TYPE = ['_internal_:type' => 'BASIC'];

    public function __construct(int $user_id = -1)
    {
        parent::__construct($user_id);
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
            foreach (BASIC_TYPE as $key => $val) {
                $extra[$key] = $val;
            }
        } else {
            $extra = BASIC_TYPE;
        }
        $tmp = parent::addPermit($role, $level, $extra);
        if ($regen && $tmp) {
            $this->regen();
        }
        return $tmp;
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
            foreach (BASIC_TYPE as $key => $val) {
                $extra[$key] = $val;
            }
        } else {
            $extra = BASIC_TYPE;
        }
        parent::removePermit($role, $level, $extra);
        if ($regen) {
            $this->regen();
        }
        return $tmp;
    }

    // BASIC Private/Protected Testing method

    private function getBasics()
    {
        $res = [];
        if ($this->testIfInPerms(parent::ADMIN_ROLE, 1, BASIC_TYPE) 
            || $this->testIfInPerms(parent::ADMIN_ROLE, 2, BASIC_TYPE) 
            || $this->testIfInPerms(parent::ADMIN_ROLE, 3, BASIC_TYPE)
        ) {
            $res[] = parent::ADMIN_ROLE;
        }
        if ($this->testIfInPerms(parent::CONTENT_ROLE, 1, BASIC_TYPE) 
            || $this->testIfInPerms(parent::CONTENT_ROLE, 2, BASIC_TYPE) 
            || $this->testIfInPerms(parent::CONTENT_ROLE, 3, BASIC_TYPE)
        ) {
            $res[] = parent::CONTENT_ROLE;
        }
        if ($this->testIfInPerms(parent::AUTH_USER_ROLE, 1, BASIC_TYPE)) {
            $res[] = parent::AUTH_USER_ROLE;
        }
        if ($this->testIfInPerms(parent::GUEST_USER_ROLE, 1, BASIC_TYPE)) {
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

}