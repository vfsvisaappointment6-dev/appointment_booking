@extends('layouts.admin')

@section('title','User Details')

@section('content')
<div>
    <!-- Header -->
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i><span>Back to Users</span>
        </a>
    </div>

    <!-- Title -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
        <p class="text-gray-600 mt-1">User profile and information</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6">User Information</h2>

                <div class="space-y-6">
                    <!-- Name -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Full Name</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->name }}</p>
                    </div>

                    <!-- Email -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Email Address</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->email }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            @if($user->email_verified_at)
                                <i class="fas fa-check-circle text-green-600 mr-1"></i>Verified
                            @else
                                <i class="fas fa-exclamation-circle text-yellow-600 mr-1"></i>Not verified
                            @endif
                        </p>
                    </div>

                    <!-- Phone -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Phone Number</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->phone ?? 'Not provided' }}</p>
                    </div>

                    <!-- Role -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">User Role</p>
                        <span class="px-4 py-2 rounded-full text-sm font-medium inline-block {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : ($user->role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            <i class="fas {{ $user->role === 'admin' ? 'fa-crown' : ($user->role === 'staff' ? 'fa-user-tie' : 'fa-user') }} mr-2"></i>{{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <!-- Account Status -->
                    <div class="pb-6 border-b border-gray-100">
                        <p class="text-sm text-gray-600 mb-1">Account Status</p>
                        <div class="flex items-center space-x-2">
                            <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span>
                            <p class="text-sm text-gray-900">Active</p>
                        </div>
                    </div>

                    <!-- Member Since -->
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Member Since</p>
                        <p class="text-sm text-gray-500">{{ $user->created_at?->format('F d, Y') ?? 'Unknown' }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex space-x-3">
                    <a href="{{ route('admin.users.edit', $user->user_id) }}" class="flex-1 text-center bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition">
                        <i class="fas fa-edit mr-2"></i>Edit User
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-20">
                <h3 class="font-bold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 rounded-lg bg-red-50 border border-red-200 text-red-600 hover:bg-red-100 transition font-medium">
                            <i class="fas fa-trash mr-2"></i>Delete User
                        </button>
                    </form>
                </div>

                <!-- User Stats -->
                <div class="mt-6 space-y-3">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600">User ID</p>
                        <p class="text-sm font-mono text-gray-900">{{ substr($user->user_id, 0, 8) }}...</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600">Last Updated</p>
                        <p class="text-sm text-gray-900">{{ $user->updated_at?->format('M d, Y g:i A') ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-900"><strong>Note:</strong> Edit user information or delete the account using the options above.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
