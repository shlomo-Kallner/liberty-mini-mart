<?php

namespace App\Utilities\IterationStack;

use App\Utilities\Functions\Functions;


class IterationStack 
{

    protected $stack, $current;

    public function __construct(array &$elems, &$parent = null)
    {
        //dd($elems);
        $this->current = new IterationFrame($elems, $parent);
        $this->stack = [];
    }

    static public function isAcceptableKey($key)
    {
        if (is_string($key)) {
            return !empty($key); 
        } elseif (is_int($key)) {
            return $key >= 0;
        }
        return false;
    }

    public function empty()
    {
        if (count($this->stack) === 0 && $this->current === null) {
            return true;
        } else {
            return false;
        }
    }

    public function testKey($key) 
    {
        if (!$this->empty() && self::isAcceptableKey($key)) {
            return Functions::getVar($this->current->get($key), false);
        }
        return false;
    }

    public function push($key)
    {
        $tArr = $this->testKey($key);
        if ($tArr !== false) {
            $this->stack[] = $this->current;
            $tmp = new IterationFrame($tArr, $this->current);
            $this->current = $tmp;
        }
        return $this;
    }

    public function pop()
    {
        if (!$this->empty()) {
            $this->current = array_pop($this->stack);
        }
        return $this;
    }

    public function current()
    {
        return $this->current;
    }

    public function inc()
    {
        if (!$this->empty()) {
            $this->current->next();
        }
        return $this;
    }

    public function dec()
    {
        if (!$this->empty()) {
            $this->current->prev();
        }
        return $this;
    }

    public function eof()
    {
        return $this->empty() || $this->current->eoa();
    }

    public function bof()
    {
        return !$this->empty() && $this->current->boa();
    }

}

class IterationFrame 
{
    
    /*

        $currentFrame = [
            'parent' => null,
            'index'=> 0,
            'children' => &$sidebar2['sidebar'],
            'elem' => &$sidebar2['sidebar'][0],
        ];

    */

    protected   $parent,
                $elems,
                $index;

    public function __construct(array &$elems, &$parent = null, int $index = 0)
    {
        //dd($elems , "fsfsf");
        $this->index = $index;
        $this->elems = $elems;
        $this->parent = $parent;
    }

    public function __destruct()
    {
        $this->elems = [];
        $this->parent = null;
    }

    public function eoa()
    {
        if ($this->index >= count($this->elems)) {
            return true;
        } else {
            return false;
        }
    }

    public function boa()
    {
        if ($this->index <= 0 ) {
            return true;
        } else {
            return false;
        }
    }

    private function testIndex() 
    {
        if (is_int($this->index)) {
            return $this->index > -1 && $this->index < count($this->elems);
        }
        return false;
    }

    public function next(bool $wrap = false)
    {
        if (!$this->eoa()) {
            $this->index++;
        } else {
            if ($wrap) {
                // if end of array reached wrap around
                $this->index = 0;
            }
        }
    }

    public function prev(bool $wrap = false)
    {
        if (!$this->boa()) { 
            $this->index--;
        } else {            
            if ($wrap) {
                // if end of array reached wrap around
                $this->index = count($this->elems);
            }
        }
    }

    public function parent()
    {
        return $this->parent;
    }

    public function len()
    {
        return count($this->elems);
    }

    public function current()
    {
        if ($this->testIndex()) {
            return $this->elems[$this->index];
        }
        return null;
    }

    static public function testKey($key)
    {
        if (is_string($key)) {
            return !empty($key);
        } elseif (is_int($key)) {
            return $key >= 0;
        }
        return false;
    }

    public function has($key)
    {
        //dd($this, $key);
        if ($this->testIndex()) {
            $cur = $this->current();
            //dd($this, $key, $cur, self::testKey($key), 'in ' . __METHOD__);
            if (self::testKey($key)) {
                return Functions::isPropKeyIn($cur, $key, false);
            }
        }
        return false;
    }

    public function get($key, $default = null)
    {
        //dd($key);
        if ($this->testIndex() && self::testKey($key)) {
            $cur = $this->current();
            //dd($this, $key, $cur, self::testKey($key), 'in ' . __METHOD__);
            return Functions::getPropKey($cur, $key, $default);
        } else {
            return $default;
        }
    }

    public function set($key, $value = null)
    {
        if ($this->testIndex()) {
            if (self::testKey($key) && !empty($value)) {
                //$cur = $this->current();
                Functions::setPropKey($this->current(), $key, $value);
                // $this->elems[$this->index][$key] = $value;
            } elseif (is_array($key)) {
                foreach ($key as $idx => $val) {
                    $this->set($idx, $val);
                    // if (self::testKey($idx) && !empty($val)) {
                    //     //$this->elems[$this->index][$idx] = $val;
                    //     Functions::setPropKey($this->current(), $idx, $val);
                    // }
                }
            }
            return $this;
        }
        return null;
    }

}
