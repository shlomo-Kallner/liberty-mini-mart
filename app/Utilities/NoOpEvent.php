<?php

/// 'inspired' by the Cart::fireEvent($name, $value = []) in the Darryldecode/Cart package..

namespace App\Utilities;

class NoOpEvent 
{
    public function fire($name, $value = [])
    {
        return true;//no-op.
    }
}