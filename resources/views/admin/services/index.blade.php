@extends('layouts.admin')

@section('title', 'Services - Admin')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl font-bold text-gray-900">Services Management</h1>
        <a href="{{ route('admin.services.create') }}" class="px-6 py-2 rounded-lg font-medium text-white transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
            <i class="fas fa-plus mr-2"></i>Add Service
        </a>
    </div>
    <p class="text-gray-600">Manage and organize all services offered by your business</p>
</div>

<!-- Search and Filter -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
    <form method="GET" action="{{ route('admin.services.index') }}" class="flex gap-4 flex-wrap items-end">
        <div class="flex-1 min-w-fit">
            <label class="block text-sm font-medium text-gray-700 mb-2">Search Services</label>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or description..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
        </div>
        <button type="submit" class="px-6 py-2 rounded-lg font-medium text-white transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
            <i class="fas fa-search mr-2"></i>Search
        </button>
        @if($search)
            <a href="{{ route('admin.services.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-times mr-2"></i>Clear
            </a>
        @endif
    </form>
</div>

<!-- Services Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    @if($services->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($services as $service)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900">{{ $service->name }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600 truncate max-w-xs">{{ $service->description ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-semibold text-gray-900">₵{{ number_format($service->price, 2) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-600">{{ $service->duration }} mins</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($service->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                <a href="{{ route('admin.services.edit', $service->service_id) }}" class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.services.destroy', $service->service_id) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $services->links() }}
        </div>
    @else
        <div class="px-6 py-12 text-center">
            <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
            <p class="text-gray-600 mb-4">No services found.</p>
            <a href="{{ route('admin.services.create') }}" class="inline-block px-6 py-2 rounded-lg font-medium text-white transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                <i class="fas fa-plus mr-2"></i>Create First Service
            </a>
        </div>
    @endif
</div>
@endsection
