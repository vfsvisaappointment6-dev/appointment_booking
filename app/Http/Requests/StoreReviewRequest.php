<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:bookings,booking_id',
            'customer_id' => 'required|exists:users,user_id',
            'staff_id' => 'required|exists:users,user_id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:5000',
        ];
    }
}
