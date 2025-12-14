<nav class="bg-black border-b border-gray-800 sticky top-0 z-40" style="background: rgba(10, 10, 10, 0.85); backdrop-filter: blur(8px);">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <h1 class="text-xl font-bold text-orange-500" style="color: #FF7F39; font-family: 'Playfair Display', serif; letter-spacing: 0.05em;">3_Aura</h1>
            </div>



            <!-- Right - User Menu -->
            @php
                use Illuminate\Support\Facades\Auth;
                $user = Auth::user();
                $notificationCount = 0;
                if ($user) {
                    try {
                        $notificationCount = $user->notifications()->where('status', '!=', 'read')->count();
                    } catch (\Throwable $e) {
                        $notificationCount = 0;
                    }
                }
            @endphp
            <div class="flex items-center space-x-4">
                <!-- User Dropdown -->
                <div class="relative group">
                    <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-900 transition" style="background: rgba(255, 127, 57, 0.05);">
                        @if($user)
                            <img src="{{ $user->profile_picture_url }}"
                                 alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover">
                            <span class="text-gray-200 font-medium hidden md:inline">{{ $user->name }}</span>
                        @else
                            <img src="https://ui-avatars.com/api/?name=Guest&background=888888&color=fff" alt="Guest" class="w-8 h-8 rounded-full">
                            <span class="text-gray-200 font-medium hidden md:inline">Guest</span>
                        @endif
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-gray-900 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200" style="background: #1F1F1F; border: 1px solid #333333;">
                        @if($user && ($user->role ?? null) === 'customer')
                            <a href="{{ route('account-settings') }}" class="block px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-orange-500">Account Settings</a>
                        @elseif($user)
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-orange-500">Profile Settings</a>
                        @endif
                        @if($user)
                            <form method="POST" action="{{ route('logout') }}" class="block" onsubmit="confirmLogout(event)">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-orange-500">Logout</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
