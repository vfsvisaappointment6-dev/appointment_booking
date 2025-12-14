<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Events\PaymentProcessed;
use App\Models\PromoCode;
use App\Models\Booking;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking', 'booking.customer', 'booking.service']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('booking_id')) {
            $query->where('booking_id', $request->booking_id);
        }

        return response()->json($query->paginate(15));
    }

    public function store(StorePaymentRequest $request)
    {
        $item = Payment::create($request->validated());
        PaymentProcessed::dispatch($item);
        return response()->json($item->load('booking'), 201);
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);
        return response()->json($payment->load('booking'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $this->authorize('update', $payment);
        $payment->update($request->validated());
        return response()->json($payment->load('booking'));
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);
        $payment->delete();
        return response()->json(null, 204);
    }

    public function process(Payment $payment)
    {
        if ($payment->status !== 'pending') {
            return response()->json(['message' => 'Payment already processed'], 400);
        }

        $payment->update(['status' => 'completed']);
        PaymentProcessed::dispatch($payment);

        return response()->json($payment);
    }

    /**
     * Validate a promo code for checkout
     */
    public function validatePromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'booking_id' => 'required|uuid',
            'service_id' => 'required|uuid',
        ]);

        try {
            $booking = Booking::findOrFail($request->booking_id);
            $code = strtoupper($request->code);

            // Find the promo code
            $promo = PromoCode::where('code', $code)->first();

            if (!$promo) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Promo code not found',
                ]);
            }

            // Check if promo is active
            if ($promo->status !== 'active') {
                return response()->json([
                    'valid' => false,
                    'message' => 'Promo code is inactive',
                ]);
            }

            // Check if promo has expired
            if ($promo->expires_at < now()) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Promo code has expired',
                ]);
            }

            // Check usage limit
            if ($promo->usage_limit && $promo->times_used >= $promo->usage_limit) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Promo code usage limit exceeded',
                ]);
            }

            // Check minimum order value
            if ($promo->minimum_order_value > 0 && $booking->service->price < $promo->minimum_order_value) {
                return response()->json([
                    'valid' => false,
                    'message' => "Minimum order value is ₵" . number_format($promo->minimum_order_value, 2),
                ]);
            }

            // Check applicability
            if ($promo->applicable_to === 'specific_service' && $promo->service_id !== $request->service_id) {
                return response()->json([
                    'valid' => false,
                    'message' => 'This promo code is not applicable to this service',
                ]);
            }

            if ($promo->applicable_to === 'first_booking') {
                // Check if user has made any bookings before
                $previousBookings = Booking::where('user_id', $booking->user_id)
                    ->where('booking_id', '!=', $booking->booking_id)
                    ->count();

                if ($previousBookings > 0) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'This promo code is only for first-time bookings',
                    ]);
                }
            }

            // All checks passed - return promo details
            return response()->json([
                'valid' => true,
                'promo' => [
                    'code' => $promo->code,
                    'discount_type' => $promo->discount_type,
                    'discount_percentage' => $promo->discount_percentage,
                    'discount_amount' => $promo->discount_amount,
                    'description' => $promo->description,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'valid' => false,
                'message' => 'An error occurred while validating the promo code',
            ]);
        }
    }
}
