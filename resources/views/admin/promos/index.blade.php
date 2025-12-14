@extends('layouts.admin')

@section('title', 'Discount Codes - Admin')

@section('content')
<div>
    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900">Discount Codes</h1>
            <p class="text-gray-600 mt-2">Manage promo codes and special offers</p>
        </div>
        <a href="{{ route('admin.promos.create') }}" class="px-6 py-3 rounded-lg text-white font-medium transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
            <i class="fas fa-plus mr-2"></i>Create Discount
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Active Codes</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total Codes</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $promoCodes->total() }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-tag text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Expired Codes</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $expiredCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Promo Codes Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Code</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Discount</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Description</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Usage</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Valid Until</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($promoCodes as $promo)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <code class="bg-gray-100 px-3 py-1 rounded font-mono text-sm">{{ $promo->code }}</code>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-900 font-semibold">
                                    @if($promo->discount_type === 'percentage')
                                        <span class="text-orange-600">{{ $promo->discount_percentage }}%</span> off
                                    @else
                                        <span class="text-orange-600">₵{{ number_format($promo->discount_amount, 2) }}</span> off
                                    @endif
                                </div>
                                @if($promo->minimum_order_value > 0)
                                    <p class="text-xs text-gray-500">Min: ₵{{ number_format($promo->minimum_order_value, 2) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $promo->description ?? '-' }}</td>
                            <td class="px-6 py-4">
                                @if($promo->usage_limit)
                                    <div class="text-sm">
                                        <span class="font-semibold">{{ $promo->times_used }}</span> / {{ $promo->usage_limit }}
                                        <div class="w-20 h-2 bg-gray-200 rounded-full mt-1">
                                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ ($promo->times_used / $promo->usage_limit) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Unlimited</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {{ $promo->expires_at->format('M d, Y') }}
                                @if($promo->expires_at < now())
                                    <p class="text-xs text-red-500">Expired</p>
                                @else
                                    <p class="text-xs text-gray-500">{{ $promo->expires_at->diffForHumans() }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($promo->status === 'active' && $promo->expires_at >= now())
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-circle text-green-600"></i> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-circle text-gray-600"></i> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.promos.edit', $promo) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-blue-600 hover:bg-blue-50">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    @if($promo->status === 'active' && $promo->expires_at >= now())
                                        <form method="POST" action="{{ route('admin.promos.deactivate', $promo) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-yellow-600 hover:bg-yellow-50">
                                                <i class="fas fa-ban text-sm"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.promos.activate', $promo) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-green-600 hover:bg-green-50">
                                                <i class="fas fa-check text-sm"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <button onclick="if(confirm('Delete this discount code?')) { document.getElementById('delete-form-{{ $promo->promo_code_id }}').submit(); }" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 hover:bg-red-50">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                    <form id="delete-form-{{ $promo->promo_code_id }}" method="POST" action="{{ route('admin.promos.destroy', $promo) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-tags text-gray-300 text-4xl mb-4"></i>
                                    <p class="text-gray-600 font-medium">No discount codes yet</p>
                                    <a href="{{ route('admin.promos.create') }}" class="text-orange-600 hover:text-orange-700 text-sm mt-2">Create your first code</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $promoCodes->links() }}
    </div>
</div>
@endsection
