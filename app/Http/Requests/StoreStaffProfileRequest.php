<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,user_id|unique:staff_profiles,user_id',
            'specialty' => 'required|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
