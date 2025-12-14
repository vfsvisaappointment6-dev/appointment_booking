<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,user_id',
            'type' => 'required|string|max:100',
            'message' => 'required|string|max:5000',
            'status' => 'nullable|in:sent,failed',
        ];
    }
}
