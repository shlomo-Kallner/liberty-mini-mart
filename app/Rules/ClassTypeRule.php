<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ClassTypeRule implements Rule
{
    protected $type;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($typename = null)
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
        if (!empty($this->type)) {
            return $value instanceof $this->type;
        } else {
            return is_object($value);
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return empty($this->type) 
            ? ':attribute must be a PHP object.'
            : ':attribute must be an instance of the class ' . $this->type;
    }
}
