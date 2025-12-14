<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking']);

        // Search by payment ID or transaction ID
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_id', 'ilike', "%{$search}%")
                  ->orWhere('transaction_id', 'ilike', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->method) {
            $query->where('payment_method', $request->method);
        }

        // Filter by amount range
        if ($request->amount_from) {
            $query->where('amount', '>=', $request->amount_from);
        }
        if ($request->amount_to) {
            $query->where('amount', '<=', $request->amount_to);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort options
        $sort = $request->sort ?? 'created_desc';
        switch ($sort) {
            case 'created_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'amount_asc':
                $query->orderBy('amount', 'asc');
                break;
            case 'amount_desc':
                $query->orderBy('amount', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $payments = $query->paginate(25);
        $statuses = ['pending', 'success', 'failed', 'refunded'];
        $methods = ['card', 'bank_transfer', 'cash', 'check'];

        return view('admin.payments.index', compact('payments', 'statuses', 'methods'));
    }

    public function show($id)
    {
        $payment = Payment::where('payment_id', $id)->with(['booking'])->firstOrFail();
        return view('admin.payments.show', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::where('payment_id', $id)->firstOrFail();
        $data = $request->validate(['status' => 'required|string']);
        $payment->update($data);
        return redirect()->route('admin.payments.index')->with('success', 'Payment updated');
    }

    public function destroy($id)
    {
        $payment = Payment::where('payment_id', $id)->firstOrFail();
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment removed');
    }
}
