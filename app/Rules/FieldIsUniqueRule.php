<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule,
    App\Utilities\Functions\Functions, 
    DB;

class FieldIsUniqueRule implements Rule
{
    protected $table = '';
    protected $field = '';
    protected $withTrashed = false;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table, string $field, bool $withTrashed = false)
    {
        $this->table = $table;
        $this->field = $field;
        $this->withTrashed = $withTrashed;
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
        return !Functions::countHas($tmp);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute already exists and is in use!';
    }
}
