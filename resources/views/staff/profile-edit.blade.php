@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">Edit Your Profile</h1>
        <p class="text-gray-600 mt-2" style="color: #757575;">Manage your professional information and availability</p>
    </div>

    @php
        $staffProfile = auth()->user()->staffProfile;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Sidebar -->
        <div>
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="text-center mb-6">
                    <div class="relative inline-block mb-4">
                        <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}" class="w-24 h-24 rounded-full object-cover">
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ auth()->user()->email }}</p>
                </div>

                <!-- Profile Picture Upload -->
                <form method="POST" action="{{ route('profile.picture') }}" enctype="multipart/form-data" class="mb-4">
                    @csrf
                    @method('PUT')
                    <label for="profile_picture" class="block w-full px-3 py-2 border-2 border-dashed border-orange-300 rounded-lg cursor-pointer hover:bg-orange-50 transition text-center mb-2">
                        <svg class="w-5 h-5 text-orange-500 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-xs font-medium text-orange-600" id="file-label">Upload Photo</span>
                    </label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden"
                           onchange="updateFileLabel(this); this.form.submit();">
                    <p class="text-xs text-gray-500 text-center">Max 2MB</p>
                </form>

                <hr class="my-4">

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Profile Status</span>
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700">
                            {{ ucfirst($staffProfile->status ?? 'active') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Rating</span>
                        <div class="flex items-center gap-1">
                            <i class="fas fa-star text-yellow-400"></i>
                            <span class="font-semibold text-gray-900">{{ $staffProfile->rating ?? '0.0' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="lg:col-span-2">
            <!-- Personal Information -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Personal Information</h2>

                <form method="POST" action="{{ route('profile.update-personal') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ auth()->user()->name }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                   placeholder="Enter your full name">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ auth()->user()->email }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                   placeholder="Enter your email">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ auth()->user()->phone }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                               placeholder="Enter your phone number">
                    </div>
                </form>
            </div>

            <!-- Professional Information -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Professional Information</h2>

                <form method="POST" action="{{ route('profile.update-professional') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="specialty" class="block text-sm font-medium text-gray-700 mb-1">Specialty</label>
                        <input type="text" id="specialty" name="specialty" value="{{ $staffProfile->specialty ?? '' }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                               placeholder="e.g., Hair Styling, Massage, Consultation">
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Professional Bio</label>
                        <textarea id="bio" name="bio" rows="5"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                  placeholder="Tell us about your professional experience and expertise...">{{ $staffProfile->bio ?? '' }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">This will be visible to customers browsing your profile</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Profile Status</label>
                            <select id="status" name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500">
                                <option value="active" {{ ($staffProfile->status ?? 'active') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ ($staffProfile->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="on-leave" {{ ($staffProfile->status ?? 'active') === 'on-leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-3 rounded-lg font-medium text-white transition"
                                style="background: #FF7F39;"
                                onmouseover="this.style.background='#EA6C2F'"
                                onmouseout="this.style.background='#FF7F39'">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Security</h2>

                <form method="POST" action="{{ route('profile.update-password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current-password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" id="current-password" name="current_password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                               placeholder="Enter your current password">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="new-password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" id="new-password" name="password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                   placeholder="Enter new password">
                        </div>
                        <div>
                            <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" id="confirm-password" name="password_confirmation"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                   placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-3 rounded-lg font-medium text-white transition"
                                style="background: #FF7F39;"
                                onmouseover="this.style.background='#EA6C2F'"
                                onmouseout="this.style.background='#FF7F39'">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function updateFileLabel(input) {
        const label = document.getElementById('file-label');
        if (input.files && input.files[0]) {
            label.textContent = input.files[0].name;
        }
    }
</script>
@endsection
