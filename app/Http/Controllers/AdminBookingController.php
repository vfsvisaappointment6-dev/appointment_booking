<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['service', 'staff', 'customer']);

        // Search by customer name or booking ID
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_id', 'ilike', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Sort options
        $sort = $request->sort ?? 'date_desc';
        switch ($sort) {
            case 'date_asc':
                $query->orderBy('date', 'asc');
                break;
            case 'customer':
                $query->whereHas('customer')->with('customer')->orderBy('customer_id');
                break;
            default:
                $query->orderBy('date', 'desc');
        }

        $bookings = $query->paginate(20);
        $statuses = ['pending', 'confirmed', 'cancelled', 'completed'];

        return view('admin.bookings.index', compact('bookings', 'statuses'));
    }

    public function show($id)
    {
        $booking = Booking::where('booking_id', $id)->with(['service', 'staff', 'customer'])->firstOrFail();
        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::where('booking_id', $id)->firstOrFail();

        $data = $request->validate([
            'status' => 'required|string',
            'date' => 'nullable|date',
            'time' => 'nullable',
        ]);

        $booking->update($data);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking updated');
    }

    public function destroy($id)
    {
        $booking = Booking::where('booking_id', $id)->firstOrFail();
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted');
    }
}
