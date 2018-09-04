<?php

namespace App\Utilities;

use Illuminate\Session\Store;
use SessionHandlerInterface;
use App\UserSession,
    App\Utilities\Functions\Functions;
use App\Utilities\DatabaseSessionHandler;

class DatabaseSession extends Store
{

    public function __construct($name, UserSession $us, $id = null)
    { // parent::__construct($name, SessionHandlerInterface $handler, $id = null)
        parent::__construct(
            $name, new DatabaseSessionHandler($us), $id
        );
    }

}