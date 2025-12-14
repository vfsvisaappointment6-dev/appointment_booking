<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => 'required|exists:bookings,booking_id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:paystack',
            'transaction_id' => 'nullable|string|unique:payments,transaction_id',
            'status' => 'nullable|in:success,failed',
        ];
    }
}
