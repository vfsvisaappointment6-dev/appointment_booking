<!-- Reviews Report -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Reviews -->
    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg shadow-sm border border-yellow-200 p-6">
        <p class="text-yellow-700 text-sm font-medium">Total Reviews</p>
        <p class="text-3xl font-bold text-yellow-900 mt-2">{{ $totalReviews ?? 0 }}</p>
    </div>

    <!-- Average Rating -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg shadow-sm border border-blue-200 p-6">
        <p class="text-blue-700 text-sm font-medium">Average Rating</p>
        <div class="flex items-end gap-2">
            <p class="text-3xl font-bold text-blue-900">{{ $averageRating ?? 0 }}</p>
            <div class="text-blue-500 mb-1">
                @for($i = 0; $i < 5; $i++)
                    <i class="fas fa-star text-sm"></i>
                @endfor
            </div>
        </div>
    </div>

    <!-- Satisfaction Rate -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg shadow-sm border border-green-200 p-6">
        <p class="text-green-700 text-sm font-medium">High Ratings (4-5 stars)</p>
        <p class="text-3xl font-bold text-green-900 mt-2">
            @php
                $highRatings = ($ratingDistribution[5] ?? 0) + ($ratingDistribution[4] ?? 0);
                $percent = $totalReviews > 0 ? round(($highRatings / $totalReviews) * 100) : 0;
            @endphp
            {{ $percent }}%
        </p>
    </div>
</div>

<!-- Rating Distribution -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <h3 class="font-bold text-gray-900 mb-4">Rating Distribution</h3>
    <div class="space-y-4">
        @php
            $colors = [5 => '#10B981', 4 => '#3B82F6', 3 => '#F59E0B', 2 => '#F97316', 1 => '#EF4444'];
        @endphp
        @for($rating = 5; $rating >= 1; $rating--)
            @php
                $count = $ratingDistribution[$rating] ?? 0;
                $total = array_sum($ratingDistribution ?? []) ?: 1;
            @endphp
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2 min-w-32">
                        @for($i = 0; $i < 5; $i++)
                            @if($i < $rating)
                                <i class="fas fa-star text-yellow-400 text-sm"></i>
                            @else
                                <i class="far fa-star text-gray-300 text-sm"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-sm text-gray-600 w-12 text-right">{{ $count }} ({{ round(($count / $total) * 100) }}%)</span>
                </div>
                <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                    <div class="h-full" style="background: {{ $colors[$rating] }}; width: {{ ($count / $total * 100) }}%"></div>
                </div>
            </div>
        @endfor
    </div>
</div>

<!-- Recent High Reviews -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Top Reviews</h3>
        <div class="space-y-3">
            @forelse($topReviews ?? [] as $review)
                <div class="p-3 bg-gray-50 rounded-lg border border-green-100">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $review->title ?? 'Review' }}</p>
                            <p class="text-sm text-gray-600">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < $review->rating)
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @else
                                        <i class="far fa-star text-gray-300 text-xs"></i>
                                    @endif
                                @endfor
                            </p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-700 line-clamp-2">{{ $review->comment }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No reviews found</p>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="font-bold text-gray-900 mb-4">Recent Reviews</h3>
        <div class="space-y-3">
            @forelse($recentReviews ?? [] as $review)
                <div class="p-3 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $review->title ?? 'Review' }}</p>
                            <p class="text-sm text-gray-600">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < $review->rating)
                                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    @else
                                        <i class="far fa-star text-gray-300 text-xs"></i>
                                    @endif
                                @endfor
                            </p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-700 line-clamp-2">{{ $review->comment }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No reviews found</p>
            @endforelse
        </div>
    </div>
</div>

<!-- Review Insights -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="font-bold text-gray-900 mb-4">Insights</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-green-50 rounded-lg border border-green-100">
            <p class="text-sm text-gray-600 mb-1">Excellent (5 stars)</p>
            <p class="text-2xl font-bold text-green-600">{{ $ratingDistribution[5] ?? 0 }}</p>
            <p class="text-xs text-gray-600 mt-1">{{ $totalReviews > 0 ? round((($ratingDistribution[5] ?? 0) / $totalReviews) * 100) : 0 }}% of reviews</p>
        </div>
        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100">
            <p class="text-sm text-gray-600 mb-1">Good (4 stars)</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $ratingDistribution[4] ?? 0 }}</p>
            <p class="text-xs text-gray-600 mt-1">{{ $totalReviews > 0 ? round((($ratingDistribution[4] ?? 0) / $totalReviews) * 100) : 0 }}% of reviews</p>
        </div>
        <div class="p-4 bg-red-50 rounded-lg border border-red-100">
            <p class="text-sm text-gray-600 mb-1">Low Ratings (1-2 stars)</p>
            <p class="text-2xl font-bold text-red-600">{{ ($ratingDistribution[1] ?? 0) + ($ratingDistribution[2] ?? 0) }}</p>
            <p class="text-xs text-gray-600 mt-1">{{ $totalReviews > 0 ? round(((($ratingDistribution[1] ?? 0) + ($ratingDistribution[2] ?? 0)) / $totalReviews) * 100) : 0 }}% of reviews</p>
        </div>
    </div>
</div>
