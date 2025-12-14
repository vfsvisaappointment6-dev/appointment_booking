<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            Booking::with(['service', 'customer', 'staff'])
                ->paginate(15)
        );
    }

    public function store(StoreBookingRequest $request)
    {
        $item = Booking::create($request->validated());
        return response()->json($item->load(['service', 'customer', 'staff']), 201);
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        return response()->json($booking->load(['service', 'customer', 'staff']));
    }

    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $this->authorize('update', $booking);
        $booking->update($request->validated());
        return response()->json($booking->load(['service', 'customer', 'staff']));
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);
        $booking->delete();
        return response()->json(null, 204);
    }

    public function complete(Booking $booking)
    {
        $this->authorize('update', $booking);
        if ($booking->status === 'completed') {
            return response()->json(['message' => 'Booking already completed'], 400);
        }

        $booking->update(['status' => 'completed']);
        return response()->json($booking);
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('update', $booking);
        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return response()->json(['message' => 'Cannot cancel booking in this status'], 400);
        }

        $booking->update(['status' => 'cancelled']);
        return response()->json($booking);
    }
}
