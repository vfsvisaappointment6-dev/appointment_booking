@extends('layouts.admin')

@section('title','Edit User')

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
        <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
        <p class="text-gray-600 mt-1">Update user information and permissions</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <p class="font-medium mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form method="POST" action="{{ route('admin.users.update', $user->user_id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Name Field -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" name="name" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('name') border-red-500 @else border-gray-300 @enderror" value="{{ old('name', $user->name) }}" placeholder="e.g., John Doe" required>
                        @error('name')
                            <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('email') border-red-500 @else border-gray-300 @enderror" value="{{ old('email', $user->email) }}" placeholder="e.g., user@example.com" required>
                        @error('email')
                            <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Changing the email address will affect login credentials.</p>
                    </div>

                    <!-- Phone Field -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('phone') border-red-500 @else border-gray-300 @enderror" value="{{ old('phone', $user->phone) }}" placeholder="e.g., +1 (555) 123-4567">
                        @error('phone')
                            <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Role Field -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 mb-2">User Role</label>
                        <select name="role" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('role') border-red-500 @else border-gray-300 @enderror" required>
                            <option value="">Select a role...</option>
                            <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>
                                Customer - Can book services
                            </option>
                            <option value="staff" {{ old('role', $user->role) === 'staff' ? 'selected' : '' }}>
                                Staff - Can provide services
                            </option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                Admin - Full system access
                            </option>
                        </select>
                        @error('role')
                            <p class="text-red-600 text-sm mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Changing the role will affect what the user can do in the system.</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-3 border-t border-gray-100 pt-6">
                        <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                        <a href="{{ route('admin.users.show', $user->user_id) }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-20">
                <h3 class="font-bold text-gray-900 mb-4">User Information</h3>

                <div class="space-y-4">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">User ID</p>
                        <p class="text-sm font-mono text-gray-900 break-all">{{ substr($user->user_id, 0, 8) }}...</p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">Current Role</p>
                        <span class="text-xs font-semibold px-2 py-1 rounded {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : ($user->role === 'staff' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">Member Since</p>
                        <p class="text-sm text-gray-900">{{ $user->created_at?->format('M d, Y') ?? 'Unknown' }}</p>
                    </div>

                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-xs text-gray-600 mb-1">Last Updated</p>
                        <p class="text-sm text-gray-900">{{ $user->updated_at?->format('M d, Y g:i A') ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-900"><strong>Tip:</strong> Changes are saved immediately. You can edit this user anytime.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
