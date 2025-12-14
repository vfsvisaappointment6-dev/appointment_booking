<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChatMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sender_id' => 'required|exists:users,user_id',
            'receiver_id' => 'required|exists:users,user_id|different:sender_id',
            'message' => 'required|string|max:5000',
            'seen' => 'nullable|boolean',
        ];
    }
}
