<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePromoCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|unique:promo_codes,code|max:50',
            'discount_percentage' => 'required|integer|between:0,100',
            'expires_at' => 'required|date|after:today',
            'status' => 'nullable|in:active,expired',
        ];
    }
}
