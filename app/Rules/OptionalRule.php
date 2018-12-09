<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Utilities\Functions\Functions;
use App\Rules\ScalarTypeRule;

class OptionalRule extends ScalarTypeRule
{
    protected $name;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $varname, string $typename = '')
    {
        parent::__construct($typename);
        $this->name = $varname;
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
        if (Functions::isPropKeyIn($value, $this->name)) {
            return parent::passes($attribute, $value);
        }
        return true;
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
