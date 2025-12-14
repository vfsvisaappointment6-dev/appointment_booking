<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromoCode;
use App\Http\Requests\StorePromoCodeRequest;
use App\Http\Requests\UpdatePromoCodeRequest;

class PromoCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware(function ($request, $next) {
            if (auth()->user()?->role !== 'admin') {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        return response()->json(PromoCode::paginate(15));
    }

    public function store(StorePromoCodeRequest $request)
    {
        $item = PromoCode::create($request->validated());
        return response()->json($item, 201);
    }

    public function show(PromoCode $promoCode)
    {
        return response()->json($promoCode);
    }

    public function update(UpdatePromoCodeRequest $request, PromoCode $promoCode)
    {
        $promoCode->update($request->validated());
        return response()->json($promoCode);
    }

    public function destroy(PromoCode $promoCode)
    {
        $promoCode->delete();
        return response()->json(null, 204);
    }
}
