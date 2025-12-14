<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Appointment Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="w-full max-w-md">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-lg mb-4" style="background: rgba(255, 127, 57, 0.1);">
                    <i class="fas fa-lock text-2xl" style="color: #FF7F39;"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Admin Portal</h1>
                <p class="text-gray-600 mt-2">Secure access for administrators only</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-8">
                @if($errors->any())
                    <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>Login Failed</strong>
                        <p class="text-sm mt-1">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6" autocomplete="off">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" autocomplete="off" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('email') border-red-500 @enderror" placeholder="admin@example.com" value="" required autofocus>
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" autocomplete="new-password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('password') border-red-500 @enderror" placeholder="••••••••" value="" required>
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-orange-500 focus:ring-orange-500">
                        <label for="remember" class="ml-2 text-sm text-gray-700">Keep me logged in</label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                    </button>
                </form>

                <!-- Footer Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-500 text-center">
                        <i class="fas fa-shield-alt mr-1"></i>
                        This is a secure admin login page. This URL should not be shared with unauthorized users.
                    </p>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-900">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Need help?</strong> Contact your system administrator if you've forgotten your credentials.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
