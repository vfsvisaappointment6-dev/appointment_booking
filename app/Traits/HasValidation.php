<?php

namespace App\Traits;

trait HasValidation
{
    /**
     * Get the validation rules for the model.
     */
    public static function rules($action = 'create')
    {
        return [];
    }

    /**
     * Get custom validation messages.
     */
    public static function messages()
    {
        return [];
    }

    /**
     * Get custom attribute names for validation.
     */
    public static function attributes()
    {
        return [];
    }
}
