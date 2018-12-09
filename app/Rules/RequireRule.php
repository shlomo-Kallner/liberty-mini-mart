<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Utilities\Functions\Functions;

class RequireRule implements Rule
{
    protected $name;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $varname)
    {
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
        return Functions::isPropKeyIn($value, $this->name) && !empty($value);
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
