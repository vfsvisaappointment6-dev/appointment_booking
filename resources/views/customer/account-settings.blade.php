@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Page Header -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif; color: #0A0A0A;">Account Settings</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-2" style="color: #757575;">Manage your personal information and security preferences</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <!-- Main Content (Forms) - 2/3 width on desktop, full on mobile -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Profile Picture -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Profile Picture</h2>

                <form method="POST" action="{{ route('profile.picture') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Current Picture -->
                    <div class="flex flex-col items-center gap-4">
                        <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-orange-200 bg-gray-100 flex items-center justify-center">
                            <img src="{{ auth()->user()->profile_picture_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        </div>
                        <p class="text-sm text-gray-600">Your current profile picture</p>
                    </div>

                    <!-- Upload Input -->
                    <div>
                        <label for="profile_picture" class="block text-sm font-medium text-gray-700 mb-2">Choose a new picture</label>
                        <div class="flex items-center gap-4">
                            <label for="profile_picture" class="flex items-center justify-center w-full px-4 py-3 border-2 border-dashed border-orange-300 rounded-lg cursor-pointer hover:bg-orange-50 transition">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <span class="text-sm font-medium text-orange-600" id="file-label">Choose file</span>
                                </div>
                            </label>
                            <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden"
                                   onchange="updateFileLabel(this)">
                        </div>
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (Max 2MB)</p>
                        @error('profile_picture')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="px-6 py-2 rounded-lg font-medium text-white transition"
                                style="background: #FF7F39;"
                                onmouseover="this.style.background='#EA6C2F'"
                                onmouseout="this.style.background='#FF7F39'">
                            Update Picture
                        </button>
                    </div>
                </form>
            </div>

            <!-- Personal Information -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Personal Information</h2>

                <form method="POST" action="{{ route('profile.update-personal') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('name') border-red-500 @else border-gray-300 @enderror"
                                   placeholder="Enter your full name" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('email') border-red-500 @else border-gray-300 @enderror"
                                   placeholder="Enter your email" required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('phone') border-red-500 @else border-gray-300 @enderror"
                               placeholder="Enter your phone number" required>
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="px-6 py-2 rounded-lg font-medium text-white transition"
                                style="background: #FF7F39;"
                                onmouseover="this.style.background='#EA6C2F'"
                                onmouseout="this.style.background='#FF7F39'">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Change Password</h2>

                <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('current_password') border-red-500 @else border-gray-300 @enderror"
                               placeholder="Enter your current password" required>
                        @error('current_password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" id="password" name="password"
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 @error('password') border-red-500 @else border-gray-300 @enderror"
                                   placeholder="Enter a new password" required>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">Minimum 8 characters</p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"
                                   placeholder="Confirm your new password" required>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="px-6 py-2 rounded-lg font-medium text-white transition"
                                style="background: #FF7F39;"
                                onmouseover="this.style.background='#EA6C2F'"
                                onmouseout="this.style.background='#FF7F39'">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preferences -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Preferences</h2>

                <form method="POST" action="{{ route('profile.preferences') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="email_notifications"
                                   {{ old('email_notifications', true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded" style="accent-color: #FF7F39;">
                            <div>
                                <p class="font-medium text-gray-900">Email Notifications</p>
                                <p class="text-sm text-gray-600">Receive updates about your bookings and services</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="booking_reminders"
                                   {{ old('booking_reminders', true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded" style="accent-color: #FF7F39;">
                            <div>
                                <p class="font-medium text-gray-900">Booking Reminders</p>
                                <p class="text-sm text-gray-600">Get reminded before your appointments</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="promotional_emails"
                                   {{ old('promotional_emails', false) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded" style="accent-color: #FF7F39;">
                            <div>
                                <p class="font-medium text-gray-900">Promotional Offers</p>
                                <p class="text-sm text-gray-600">Receive special offers and promotions</p>
                            </div>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="px-6 py-2 rounded-lg font-medium text-white transition"
                                style="background: #FF7F39;"
                                onmouseover="this.style.background='#EA6C2F'"
                                onmouseout="this.style.background='#FF7F39'">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>

            <!-- Danger Zone -->
            <div class="bg-red-50 rounded-lg border border-red-200 p-6">
                <h2 class="text-2xl font-bold text-red-900 mb-4" style="font-family: 'Playfair Display', serif;">Danger Zone</h2>

                <p class="text-sm text-red-700 mb-4">
                    Deleting your account is permanent and cannot be undone. All your bookings, messages, and data will be deleted.
                </p>

                <form method="POST" action="{{ route('profile.delete') }}" onsubmit="return confirm('Are you absolutely sure? This action cannot be reversed.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 rounded-lg font-medium text-white transition"
                            style="background: #E74C3C;"
                            onmouseover="this.style.background='#C0392B'"
                            onmouseout="this.style.background='#E74C3C'">
                        Delete Account
                    </button>
                </form>
            </div>
        </div>

        <!-- sidebar is provided globally via layout; page-level sidebar removed to avoid duplication -->
    </div>
</div>

<style>
    input:focus, textarea:focus {
        border-color: #FF7F39 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(255, 127, 57, 0.1);
    }
</style>

<script>
    function updateFileLabel(input) {
        const label = document.getElementById('file-label');
        if (input.files && input.files[0]) {
            label.textContent = input.files[0].name;
        }
    }
</script>
@endsection
