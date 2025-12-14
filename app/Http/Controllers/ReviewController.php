<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Events\ReviewSubmitted;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(
            Review::with(['booking', 'customer', 'staff'])->paginate(15)
        );
    }

    public function store(StoreReviewRequest $request)
    {
        $item = Review::create($request->validated());
        ReviewSubmitted::dispatch($item);
        return response()->json($item->load(['booking', 'customer', 'staff']), 201);
    }

    public function show(Review $review)
    {
        $this->authorize('view', $review);
        return response()->json($review->load(['booking', 'customer', 'staff']));
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $this->authorize('update', $review);
        $review->update($request->validated());
        return response()->json($review->load(['booking', 'customer', 'staff']));
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();
        return response()->json(null, 204);
    }
}
