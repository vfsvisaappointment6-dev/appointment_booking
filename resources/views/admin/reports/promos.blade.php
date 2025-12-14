<!-- Promotions Report -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Promos -->
        <div>
            <p class="text-gray-600 text-sm font-medium">Total Promo Codes</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPromos ?? 0 }}</p>
        </div>

        <!-- Active Promos -->
        <div>
            <p class="text-gray-600 text-sm font-medium">Active Promos</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activePromos ?? 0 }}</p>
        </div>

        <!-- Total Discounts Given -->
        <div>
            <p class="text-gray-600 text-sm font-medium">Total Discount Given</p>
            <p class="text-3xl font-bold text-gray-900 mt-2" style="color: #FF7F39;">
                ${{ number_format($totalDiscountGiven ?? 0, 2) }}
            </p>
        </div>
    </div>
</div>

<!-- Promo Performance -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="font-bold text-gray-900 mb-4">Promo Code Performance</h3>

    @if($promoPerformance && count($promoPerformance) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Code</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-600">Discount</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600">Times Used</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-600">Total Discount Given</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($promoPerformance as $promo)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-mono text-xs">
                                    {{ $promo['code'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-900">{{ $promo['discount'] }}%</td>
                            <td class="py-3 px-4 text-right font-semibold text-gray-900">{{ $promo['usage_count'] }}</td>
                            <td class="py-3 px-4 text-right font-semibold text-orange-600">
                                ${{ number_format($promo['total_discount_given'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-inbox text-3xl opacity-20 mb-2"></i>
            <p>No promo codes found for this period</p>
        </div>
    @endif
</div>
