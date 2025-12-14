<?php

namespace App\Validations;

/**
 * Booking Validation Rules
 */
class BookingValidation
{
    public static function rules($action = 'create')
    {
        $rules = [
            'service_id' => 'required|uuid|exists:services,service_id',
            'user_id' => 'required|uuid|exists:users,user_id',
            'staff_id' => 'required|uuid|exists:users,user_id',
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
        ];

        if ($action === 'update') {
            $rules['status'] = 'in:pending,confirmed,completed,cancelled';
            $rules['payment_status'] = 'in:paid,unpaid,refunded';
        }

        return $rules;
    }

    public static function messages()
    {
        return [
            'date.after' => 'Booking date must be in the future',
            'staff_id.exists' => 'Staff member not found',
            'service_id.exists' => 'Service not found',
        ];
    }
}

/**
 * Payment Validation Rules
 */
class PaymentValidation
{
    public static function rules($action = 'create')
    {
        return [
            'booking_id' => 'required|uuid|exists:bookings,booking_id',
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:momo,card,paystack',
            'transaction_id' => 'nullable|string|unique:payments,transaction_id',
            'status' => 'in:success,failed',
        ];
    }

    public static function messages()
    {
        return [
            'amount.min' => 'Payment amount must be greater than 0',
            'transaction_id.unique' => 'This transaction ID has already been recorded',
        ];
    }
}

/**
 * Review Validation Rules
 */
class ReviewValidation
{
    public static function rules($action = 'create')
    {
        return [
            'booking_id' => 'required|uuid|exists:bookings,booking_id',
            'customer_id' => 'required|uuid|exists:users,user_id',
            'staff_id' => 'required|uuid|exists:users,user_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public static function messages()
    {
        return [
            'rating.min' => 'Rating must be at least 1 star',
            'rating.max' => 'Rating cannot exceed 5 stars',
            'comment.max' => 'Comment cannot exceed 1000 characters',
        ];
    }
}

/**
 * PromoCode Validation Rules
 */
class PromoCodeValidation
{
    public static function rules($action = 'create')
    {
        $rules = [
            'code' => 'required|string|unique:promo_codes,code|max:50',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'expires_at' => 'required|date|after:today',
            'status' => 'in:active,expired',
        ];

        if ($action === 'update') {
            $rules['code'] = 'string|unique:promo_codes,code|max:50';
            $rules['discount_percentage'] = 'integer|min:0|max:100';
            $rules['expires_at'] = 'date|after:today';
        }

        return $rules;
    }

    public static function messages()
    {
        return [
            'code.unique' => 'This promo code already exists',
            'discount_percentage.min' => 'Discount must be at least 0%',
            'discount_percentage.max' => 'Discount cannot exceed 100%',
            'expires_at.after' => 'Expiration date must be in the future',
        ];
    }
}

/**
 * User Validation Rules
 */
class UserValidation
{
    public static function rules($action = 'create')
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string|unique:users,phone|max:30',
            'role' => 'required|in:admin,staff,customer',
        ];

        if ($action === 'update') {
            $rules['email'] = 'email|unique:users,email';
            $rules['phone'] = 'string|unique:users,phone|max:30';
            $rules['password'] = 'nullable|min:8|confirmed';
        }

        return $rules;
    }

    public static function messages()
    {
        return [
            'password.min' => 'Password must be at least 8 characters',
            'email.unique' => 'Email already registered',
            'phone.unique' => 'Phone number already in use',
        ];
    }
}
