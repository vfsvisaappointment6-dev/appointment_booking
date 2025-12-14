<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromoCode;
use App\Models\Service;
use Illuminate\Support\Str;

class AdminPromoCodeController extends Controller
{
    /**
     * Display a listing of promo codes.
     */
    public function index()
    {
        $promoCodes = PromoCode::orderBy('created_at', 'desc')->paginate(15);
        $activeCount = PromoCode::where('status', 'active')->where('expires_at', '>=', now())->count();
        $expiredCount = PromoCode::where('expires_at', '<', now())->count();

        return view('admin.promos.index', compact('promoCodes', 'activeCount', 'expiredCount'));
    }

    /**
     * Show the form for creating a new promo code.
     */
    public function create()
    {
        $services = Service::all();

        return view('admin.promos.create', compact('services'));
    }

    /**
     * Store a newly created promo code.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:promo_codes,code|uppercase|max:20',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_percentage' => 'required_if:discount_type,percentage|nullable|integer|min:1|max:100',
            'discount_amount' => 'required_if:discount_type,fixed_amount|nullable|numeric|min:0.01',
            'usage_limit' => 'nullable|integer|min:1',
            'minimum_order_value' => 'nullable|numeric|min:0',
            'applicable_to' => 'required|in:all,first_booking,specific_service',
            'service_id' => 'required_if:applicable_to,specific_service|nullable|exists:services,service_id',
            'expires_at' => 'required|date|after:today',
            'status' => 'required|in:active,inactive',
        ]);

        PromoCode::create([
            'promo_code_id' => (string) Str::uuid(),
            ...$validated,
        ]);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo code created successfully!');
    }

    /**
     * Show the form for editing a promo code.
     */
    public function edit($promo)
    {
        $promo = PromoCode::findOrFail($promo);
    }

    /**
     * Update the promo code.
     */
    public function update(Request $request, $promo)
    {
        $promo = PromoCode::findOrFail($promo);
        $validated = $request->validate([
            'code' => 'required|unique:promo_codes,code,' . $promo->promo_code_id . ',promo_code_id|uppercase|max:20',
            'description' => 'nullable|string|max:255',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_percentage' => 'required_if:discount_type,percentage|nullable|integer|min:1|max:100',
            'discount_amount' => 'required_if:discount_type,fixed_amount|nullable|numeric|min:0.01',
            'usage_limit' => 'nullable|integer|min:1',
            'minimum_order_value' => 'nullable|numeric|min:0',
            'applicable_to' => 'required|in:all,first_booking,specific_service',
            'service_id' => 'required_if:applicable_to,specific_service|nullable|exists:services,service_id',
            'expires_at' => 'required|date|after:today',
            'status' => 'required|in:active,inactive',
        ]);

        $promo->update($validated);

        return redirect()->route('admin.promos.index')
            ->with('success', 'Promo code updated successfully!');
    }

    /**
     * Delete a promo code.
     */
    public function destroy($promo)
    {
        $promo = PromoCode::findOrFail($promo);
        $code = $promo->code;
        $promo->delete();

        return redirect()->route('admin.promos.index')
            ->with('success', "Promo code '{$code}' deleted successfully!");
    }

    /**
     * Deactivate a promo code.
     */
    public function deactivate($promo)
    {
        $promo = PromoCode::findOrFail($promo);
        $promo->update(['status' => 'inactive']);

        return back()->with('success', 'Promo code deactivated!');
    }

    /**
     * Activate a promo code.
     */
    public function activate($promo)
    {
        $promo = PromoCode::findOrFail($promo);
        $promo->update(['status' => 'active']);

        return back()->with('success', 'Promo code activated!');
    }
}
