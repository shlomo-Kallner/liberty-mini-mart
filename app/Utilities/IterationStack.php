<?php

namespace App\Utilities\IterationStack;


class IterationStack 
{

    protected $stack = [];
    protected $current;

    public function __construct(array &$elems, &$parent = null)
    {
        //dd($elems);
        $this->current = new IterationFrame($elems, $parent);
    }

    public function push($key)
    {
        if ((is_string($key) || is_int($key)) 
            && !$this->empty() && $this->current->get($key) !== null) {
            $this->stack[] = $this->current;
            $tArr = $this->current->get($key);
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

    public function empty()
    {
        if (count($this->stack) === 0 && $this->current === null) {
            return true;
        } else {
            return false;
        }
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

    public function len()
    {
        return count($this->elems);
    }

    public function current()
    {
        return $this->elems[$this->index];
    }

    public function get($key, $default = null)
    {
        //dd($key);
        if ($this->has($key)) {
            return $this->elems[$this->index][$key];
        } else {
            return $default;
        }
    }

    public function has($key)
    {
        //dd($key);
        if ((is_string($key) && $key !== '') || is_int($key)) {
            //dd($key);
            return !empty($this->elems[$this->index][$key]);
        } else {
            return false;
        }
    }

    public function set($key, $value = null)
    {
        if (((is_string($key) && $key !== '' ) || is_int($key)) && !empty($value)) {
            $this->elems[$this->index][$key] = $value;
        } elseif (is_array($key)) {
            foreach ($key as $idx => $val) {
                $this->elems[$this->index][$idx] = $val;
            }
        }
        return $this;
    }

}
