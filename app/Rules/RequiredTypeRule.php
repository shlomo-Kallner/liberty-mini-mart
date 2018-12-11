<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Utilities\Functions\Functions;
use App\Rules\ScalarTypeRule;

class RequiredTypeRule extends ScalarTypeRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $typename = '')
    {
        parent::__construct($typename);
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
        // dd($value);
        return !empty($value) && parent::passes($attribute, $value);
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
