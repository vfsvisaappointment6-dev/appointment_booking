@extends('layouts.app')

@section('title', auth()->user()->role === 'staff' ? 'Messages' : 'Chat with Staff')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Header -->
        <div class="mb-8 pt-6">
            <h1 class="text-3xl font-bold text-gray-900">
                {{ auth()->user()->role === 'staff' ? 'Messages' : 'Chat with Staff' }}
            </h1>
            <p class="text-gray-600 mt-2 text-sm">
                {{ auth()->user()->role === 'staff' ? 'Connect with your customers and manage conversations' : 'Stay connected with your service providers' }}
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[650px]">
            <!-- Conversations List -->
            <div class="lg:col-span-1 bg-white rounded-xl border border-gray-100 shadow-sm flex flex-col overflow-hidden">
                <!-- Search Bar -->
                <div class="p-4 border-b border-gray-100">
                    <div class="relative">
                        <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            type="text"
                            placeholder="Search conversations..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent transition"
                            id="searchInput">
                    </div>
                </div>

                <!-- Conversations -->
                <div class="flex-1 overflow-y-auto">
                    @php
                        // Get all chat conversation partners (by messages)
                        $conversations = \App\Models\ChatMessage::where(function($q) {
                            $q->where('sender_id', auth()->id())
                              ->orWhere('receiver_id', auth()->id());
                        })->with(['sender', 'receiver'])
                        ->latest('created_at')
                        ->get()
                        ->groupBy(function($message) {
                            return auth()->id() === $message->sender_id ? $message->receiver_id : $message->sender_id;
                        });

                        // Also include recent booking partners
                        $bookedPartners = collect();

                        if (auth()->user()->role === 'staff') {
                            $bookedPartners = \App\Models\Booking::where('staff_id', auth()->id())
                                ->with('customer')
                                ->get()
                                ->pluck('customer')
                                ->filter()
                                ->unique('user_id')
                                ->keyBy('user_id');
                        } else {
                            $bookedPartners = \App\Models\Booking::where('user_id', auth()->id())
                                ->with('staff')
                                ->get()
                                ->pluck('staff')
                                ->filter()
                                ->unique('user_id')
                                ->keyBy('user_id');
                        }

                        foreach ($bookedPartners as $partnerId => $partnerUser) {
                            if (! isset($conversations[$partnerId])) {
                                $placeholder = collect([ (object)[
                                    'message' => 'Start a conversation',
                                    'sender_id' => auth()->id(),
                                    'receiver_id' => $partnerId,
                                    'created_at' => now(),
                                    'sender' => auth()->user(),
                                    'receiver' => $partnerUser,
                                ]]);
                                $conversations->put($partnerId, $placeholder);
                            }
                        }
                    @endphp

                    @forelse($conversations as $conversationPartnerId => $messages)
                        @php
                            $lastMessage = $messages->first();
                            $partner = auth()->id() === $lastMessage->sender_id ? $lastMessage->receiver : $lastMessage->sender;
                            $unreadCount = $messages->where('receiver_id', auth()->id())->where('seen', false)->count();
                            $partnerIdAttr = $conversationPartnerId;
                            $partnerNameAttr = $partner->name ?? 'Unknown';
                            $partnerLastSeen = $partner->last_activity ? \Illuminate\Support\Carbon::parse($partner->last_activity)->diffForHumans() : 'Never';
                        @endphp
                            <div class="conversation-item px-4 py-3 border-b border-gray-50 hover:bg-orange-50 cursor-pointer transition-all duration-200 hover:shadow-sm conversation-link group"
                                data-partner-id="{{ $partnerIdAttr }}"
                                data-partner-name="{{ $partnerNameAttr }}"
                                data-partner-avatar="{{ $partner->profile_picture_url }}"
                                data-unread="{{ $unreadCount }}">
                            <div class="flex items-center gap-3">
                                <div class="relative shrink-0">
                                    <img src="{{ $partner->profile_picture_url }}"
                                         alt="{{ $partner->name }}"
                                         class="w-12 h-12 rounded-full object-cover ring-2 ring-transparent group-hover:ring-orange-200 transition">
                                    <div class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-gray-300 ring-2 ring-white" data-online-indicator="{{ $partnerIdAttr }}"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-semibold text-gray-900 truncate text-sm">{{ $partner->name }}</h3>
                                        <span class="text-xs text-gray-500 ml-2 shrink-0">{{ $lastMessage->created_at->diffForHumans(short: true) }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 truncate">{{ Str::limit($lastMessage->message, 35) }}</p>
                                    <div class="text-xs text-gray-500 mt-1" data-status-for="{{ $partnerIdAttr }}">Last seen: {{ $partnerLastSeen }}</div>
                                </div>
                                @if($unreadCount > 0)
                                    <div class="flex items-center justify-center w-5 h-5 rounded-full text-white text-xs font-bold shrink-0 animate-pulse" style="background: linear-gradient(135deg, #FF7F39, #EA6C2F);">
                                        {{ min($unreadCount, 9) }}{{ $unreadCount > 9 ? '+' : '' }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="font-semibold text-gray-600">No conversations yet</p>
                            <p class="text-sm mt-1 text-gray-500">
                                {{ auth()->user()->role === 'staff' ? 'Start chatting with customers' : 'Start chatting with staff members' }}
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Chat Window -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm flex flex-col overflow-hidden">
                <!-- Header -->
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between" id="chatHeader" style="background: linear-gradient(to right, #FFF5EE, white);">
                            <div class="flex items-center gap-4" id="headerContent">
                                <div class="w-12 h-12 rounded-full" style="background: #F0F0F0;"></div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 text-sm">Select a conversation</h3>
                                    <p class="text-xs text-gray-500" id="headerStatus">Choose a chat to start messaging</p>
                                </div>
                            </div>
                        </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto px-6 py-6 space-y-4" id="messagesContainer" style="background: #FAFAFA; scroll-behavior: smooth;">
                    <div class="text-center text-gray-500 py-16">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="font-semibold text-gray-600">No messages yet</p>
                        <p class="text-sm mt-1">Select a conversation to view messages</p>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="px-6 py-4 border-t border-gray-100 bg-white">
                    <div class="flex gap-3">
                        <textarea
                            id="messageInput"
                            placeholder="Write your message..."
                            class="flex-1 px-4 py-3 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-none transition"
                            rows="1"
                            disabled></textarea>
                        <button
                            id="sendBtn"
                            class="px-6 py-3 text-white rounded-full font-medium transition transform hover:scale-105 active:scale-95 flex items-center gap-2 shadow-md"
                            style="background: linear-gradient(135deg, #FF7F39, #EA6C2F);"
                            disabled>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPartnerId = null;
    let currentPartnerName = null;

    // Handle conversation selection
    document.querySelectorAll('.conversation-link').forEach(item => {
        item.addEventListener('click', function() {
            const partnerId = this.dataset.partnerId;
            const partnerName = this.dataset.partnerName;

            // Remove previous active state
            document.querySelectorAll('.conversation-link').forEach(el => {
                el.style.backgroundColor = '';
            });

            // Add active state
            this.style.backgroundColor = '#FFF5EE';

            currentPartnerId = partnerId;
            currentPartnerName = partnerName;

            const partnerAvatar = this.dataset.partnerAvatar;
            updateChatHeader(partnerName, partnerId, partnerAvatar);
            loadMessages(partnerId);

            document.getElementById('messageInput').disabled = false;
            document.getElementById('sendBtn').disabled = false;
        });
    });

    function updateChatHeader(name, partnerId, avatarUrl) {
        const imgSrc = avatarUrl && avatarUrl.length ? avatarUrl : `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=FF7F39&color=fff`;

        document.getElementById('headerContent').innerHTML = `
            <img src="${imgSrc}"
                 alt="${name}" class="w-12 h-12 rounded-full object-cover ring-2 ring-orange-100">
            <div>
                <h3 class="font-semibold text-gray-900">${name}</h3>
                <p class="text-xs" id="headerStatus">Checking status...</p>
            </div>
        `;
    }

    // Fetch presence for a given partner id
    function fetchPartnerStatus(partnerId) {
        return fetch('/users/' + partnerId + '/status', {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        })
        .then(res => {
            if (!res.ok) {
                console.error('Status endpoint error:', res.status, res.statusText);
                return null;
            }
            return res.json();
        })
        .catch(err => {
            console.error('Fetch error for partner status:', err);
            return null;
        });
    }

    function updateHeaderStatusFromData(data) {
        const el = document.getElementById('headerStatus');
        if (!el) return;
        if (!data) {
            console.warn('No data received for header status update');
            return;
        }
        console.log('Updating header status with:', data);
        if (data.online) {
            el.innerHTML = '<span class="text-xs text-green-600 font-medium">● Online</span>';
        } else {
            el.innerHTML = `<span class="text-xs text-gray-500">Last seen: ${data.last_seen_human}</span>`;
        }
    }

    // Update conversation list items statuses
    function updateConversationStatusElement(partnerId, data) {
        const el = document.querySelector(`[data-status-for="${partnerId}"]`);
        const indicator = document.querySelector(`[data-online-indicator="${partnerId}"]`);

        if (!data) return;

        if (el) {
            if (data.online) {
                el.textContent = 'Online';
                el.classList.remove('text-gray-500');
                el.classList.add('text-green-600');
            } else {
                el.textContent = `Last seen: ${data.last_seen_human}`;
                el.classList.remove('text-green-600');
                el.classList.add('text-gray-500');
            }
        }

        if (indicator) {
            if (data.online) {
                indicator.classList.remove('bg-gray-300');
                indicator.classList.add('bg-green-500');
            } else {
                indicator.classList.remove('bg-green-500');
                indicator.classList.add('bg-gray-300');
            }
        }
    }

    // Poll current partner every 10 seconds
    setInterval(() => {
        if (!currentPartnerId) return;
        fetchPartnerStatus(currentPartnerId).then(data => updateHeaderStatusFromData(data));
    }, 10000);

    // Poll visible conversation items every 30 seconds
    setInterval(() => {
        document.querySelectorAll('[data-partner-id]').forEach(item => {
            const pid = item.dataset.partnerId;
            fetchPartnerStatus(pid).then(data => updateConversationStatusElement(pid, data));
        });
    }, 30000);

    function loadMessages(partnerId) {
        document.getElementById('messagesContainer').innerHTML = `
            <div class="text-center text-gray-500 py-16">
                <svg class="w-6 h-6 mx-auto animate-spin text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <p class="font-medium mt-2">Loading messages...</p>
            </div>
        `;

        fetch('/messages/fetch?partner_id=' + partnerId, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            let messagesHtml = '';
            const currentUserId = '{{ auth()->user()->user_id }}';

            if (data.messages && data.messages.length > 0) {
                messagesHtml = '<div class="space-y-3">';
                data.messages.forEach(msg => {
                    const isOwn = msg.sender_id === currentUserId;
                    const alignment = isOwn ? 'justify-end' : 'justify-start';
                    const bgColor = isOwn ? 'linear-gradient(135deg, #FF7F39, #EA6C2F)' : '#F3F4F6';
                    const textColor = isOwn ? 'text-white' : '#111827';
                    const rounded = isOwn ? 'rounded-tr-none' : 'rounded-tl-none';
                    const timeColor = isOwn ? 'text-orange-100' : 'text-gray-500';

                    const messageDate = new Date(msg.created_at);
                    const timeString = messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                    messagesHtml += `
                        <div class="flex ${alignment} animate-fadeIn">
                            <div class="max-w-xs px-4 py-2.5 rounded-2xl ${rounded} shadow-sm" style="background: ${bgColor}; color: ${textColor};">
                                <p class="text-sm leading-relaxed break-words">${msg.message}</p>
                                <span class="text-xs ${timeColor} mt-1 block">${timeString}</span>
                            </div>
                        </div>
                    `;
                });
                messagesHtml += '</div>';
            } else {
                messagesHtml = `
                    <div class="text-center text-gray-500 py-16">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="font-semibold text-gray-600">No messages yet</p>
                        <p class="text-sm mt-1">Start the conversation</p>
                    </div>
                `;
            }

            document.getElementById('messagesContainer').innerHTML = messagesHtml;

            // Remove unread badge
            const conversationItem = document.querySelector(`[data-partner-id="${currentPartnerId}"]`);
            if (conversationItem) {
                const badge = conversationItem.querySelector('[style*="linear-gradient"]');
                if (badge) badge.remove();
                conversationItem.setAttribute('data-unread', '0');
            }

            setTimeout(() => {
                document.getElementById('messagesContainer').scrollTop = document.getElementById('messagesContainer').scrollHeight;
            }, 100);
        })
        .catch(error => {
            console.error('Error loading messages:', error);
            document.getElementById('messagesContainer').innerHTML = '<div class="text-center text-red-500 py-12">Error loading messages</div>';
        });
    }

    // Send message
    document.getElementById('sendBtn').addEventListener('click', function() {
        const message = document.getElementById('messageInput').value.trim();
        if (!message || !currentPartnerId) return;

        const formData = new FormData();
        formData.append('receiver_id', currentPartnerId);
        formData.append('message', message);

        fetch('{{ route("messages.send") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('messageInput').value = '';
            loadMessages(currentPartnerId);
        })
        .catch(error => console.error('Error:', error));
    });

    // Send on Enter
    document.getElementById('messageInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('sendBtn').click();
        }
    });

    // Search conversations
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.conversation-item').forEach(item => {
            const name = item.dataset.partnerName.toLowerCase();
            item.style.display = name.includes(searchTerm) ? 'block' : 'none';
        });
    });
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    #messageInput {
        max-height: 120px;
    }

    #messageInput::-webkit-scrollbar {
        width: 4px;
    }

    #messageInput::-webkit-scrollbar-track {
        background: transparent;
    }

    #messageInput::-webkit-scrollbar-thumb {
        background: #CBD5E0;
        border-radius: 2px;
    }
</style>
@endsection
