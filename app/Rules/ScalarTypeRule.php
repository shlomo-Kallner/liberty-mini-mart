<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ScalarTypeRule implements Rule
{
    protected $type;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $typename = '')
    {
        $this->type = $typename;
    }



    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->type === 'int') {
            return is_int($value);
        } elseif ($this->type === 'bool') {
            return is_bool($value);
        } elseif ($this->type === 'string') {
            return is_string($value);
        } elseif ($this->type === 'float') {
            return is_float($value);
        } elseif ($this->type === 'array') {
            return is_array($value);
        } elseif ($this->type === 'function') {
            return is_callable($value);
        } elseif ($this->type === 'iterator') {
            return is_iterable($value);
        } elseif ($this->type === 'null') {
            return is_null($value);
        } elseif ($this->type === 'int') {
            return ($value);
        } elseif ($this->type === 'number') {
            return is_numeric($value);
        } elseif ($this->type === 'object') {
            return is_object($value);
        } elseif ($this->type === 'scalar') {
            return is_scalar($value);
        } elseif (!empty($this->type)) {
            return $value instanceof $this->type;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
