<?php

namespace Laraditz\UserSecurity\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class MatchSecurityPin implements Rule
{
    private $model;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($model = null)
    {
        $this->model = $model ?? (auth()->check() ? auth()->user() : null);
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
        return Hash::check($value, data_get($this->model, 'security.security_pin'));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute does not match with existing record.');
    }
}
