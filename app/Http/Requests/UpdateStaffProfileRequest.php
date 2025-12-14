<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'specialty' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string|max:1000',
            'status' => 'sometimes|in:active,inactive',
        ];
    }
}
