<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|required|exists:users,user_id',
            'staff_id' => 'sometimes|required|exists:users,user_id',
            'service_id' => 'sometimes|required|exists:services,service_id',
            'date' => 'sometimes|required|date|after_or_equal:today',
            'time' => 'sometimes|required|date_format:H:i',
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
        ];
    }
}
