<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user?->user_id;

        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $userId . ',user_id',
            'phone' => 'sometimes|string|unique:users,phone,' . $userId . ',user_id|max:20',
            'password' => 'sometimes|string|min:8|confirmed',
            'role' => 'sometimes|in:customer,staff,admin',
        ];
    }
}
