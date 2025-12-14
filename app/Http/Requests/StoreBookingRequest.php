<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Will be handled by policy
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,user_id',
            'staff_id' => 'required|exists:users,user_id',
            'service_id' => 'required|exists:services,service_id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'status' => 'nullable|in:pending,confirmed,completed,cancelled',
            'payment_status' => 'nullable|in:paid,unpaid,refunded',
        ];
    }
}
