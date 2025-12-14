<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromoCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'sometimes|string|unique:promo_codes,code,' . $this->promoCode?->promo_code_id . ',promo_code_id|max:50',
            'discount_percentage' => 'sometimes|integer|between:0,100',
            'expires_at' => 'sometimes|date|after:today',
            'status' => 'sometimes|in:active,expired',
        ];
    }
}
