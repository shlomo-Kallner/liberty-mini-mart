<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule,
    App\Utilities\Functions\Functions, 
    DB;

class UrlIsUniqueRule implements FieldIsUniqueRule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $table, bool $withTrashed = false)
    {
        parent::__construct($table, 'url', $withTrashed);
    }
}
