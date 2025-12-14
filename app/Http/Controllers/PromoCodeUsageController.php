<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromoCodeUsage;

class PromoCodeUsageController extends Controller
{
    /**
     * Get all promo code usages (read-only)
     */
    public function index(Request $request)
    {
        return response()->json(PromoCodeUsage::paginate(15));
    }

    /**
     * Get a specific promo code usage
     */
    public function show(PromoCodeUsage $promoCodeUsage)
    {
        return response()->json($promoCodeUsage);
    }
}
