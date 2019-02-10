<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule,
    App\Utilities\Functions\Functions;
use DB;

class FieldIsUniqueOrEqualRule extends FieldIsUniqueRule
{
    protected $value;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table, string $field, $value, bool $withTrashed = false)
    {
        parent::__construct($table, $field, $withTrashed);
        $this->value = $value;
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
        $tmp = $this->withTrashed
            ? DB::table($this->table)->withTrashed()->where($this->field, $value)->get()
            : DB::table($this->table)->where($this->field, $value)->get();
        return $this->value === $value 
        ? Functions::countHas($tmp) && count($tmp) === 1
        : !Functions::countHas($tmp);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute must not overide another existing :attribute.';
    }
}
