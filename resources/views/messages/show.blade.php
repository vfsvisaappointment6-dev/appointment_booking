@extends('layouts.app')

@section('title', 'Chat with ' . $partner->name)

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="sticky top-0 z-10 bg-white border-b border-gray-100 shadow-sm">
            <div class="px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <img src="{{ $partner->profile_picture_url }}"
                             alt="{{ $partner->name }}"
                             class="w-14 h-14 rounded-full object-cover ring-2 ring-orange-100">
                        <div class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full" style="background: #10B981;"></div>
                    </div>
                    <div class="flex-1">
                        <h1 class="text-xl font-bold text-gray-900">{{ $partner->name }}</h1>
                        <p class="text-sm text-gray-500">
                            @if($partner->staff)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium" style="background: #FFF5EE; color: #FF7F39;">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.5 1.5H5.75A2.75 2.75 0 003 4.25v11.5A2.75 2.75 0 005.75 18.5h8.5A2.75 2.75 0 0017 15.75V8M10.5 1.5v4M10.5 1.5H17m0 0v6.5m0-6.5L10.5 8"/>
                    </svg>
                                    Service Provider
                                </span>
                            @else
                                <span class="text-gray-500">Customer</span>
                            @endif
                        </p>
                    </div>
                    <button onclick="history.back()" class="p-2 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="px-6 py-8 space-y-4 max-h-[calc(100vh-300px)] overflow-y-auto" style="scroll-behavior: smooth;">
        @forelse($messages as $message)
            @php
                $isOwn = auth()->id() === $message->sender_id;
            @endphp
            <div class="flex {{ $isOwn ? 'justify-end' : 'justify-start' }} animate-fadeIn">
                <div class="flex gap-3 max-w-xs {{ $isOwn ? 'flex-row-reverse' : 'flex-row' }}">
                    @if(!$isOwn)
                        <img src="{{ $partner->profile_picture_url }}"
                             alt="{{ $partner->name }}"
                             class="w-8 h-8 rounded-full shrink-0 mt-1 object-cover ring-1 ring-gray-100">
                    @endif

                    <div>
                        <div class="px-4 py-2.5 rounded-2xl {{ $isOwn ? 'rounded-tr-none' : 'rounded-tl-none' }} shadow-sm"
                             style="{{ $isOwn ? 'background: linear-gradient(135deg, #FF7F39, #EA6C2F); color: white;' : 'background: #F3F4F6; color: #111827;' }}">
                            <p class="text-sm leading-relaxed break-words">{{ $message->message }}</p>
                        </div>
                        <span class="text-xs text-gray-500 mt-1.5 block px-1 {{ $isOwn ? 'text-right' : 'text-left' }}">
                            {{ $message->created_at->format('h:i A') }}
                            @if($isOwn && $message->seen)
                                <svg class="w-3.5 h-3.5 inline ml-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 text-gray-500">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <p class="font-semibold text-gray-600">No messages yet</p>
                <p class="text-sm mt-1">Start the conversation below</p>
            </div>
        @endforelse
        </div>

        <!-- Input Area -->
        <div class="sticky bottom-0 bg-white border-t border-gray-100 px-6 py-4 shadow-lg">
            <form action="{{ route('messages.send') }}" method="POST" class="flex gap-3">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $partner->user_id }}">

                <textarea
                    name="message"
                    placeholder="Write your message..."
                    class="flex-1 px-4 py-3 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent resize-none transition"
                    rows="1"
                    required></textarea>

                <button
                    type="submit"
                    class="px-6 py-3 text-white rounded-full font-medium transition transform hover:scale-105 active:scale-95 flex items-center gap-2 shadow-md"
                    style="background: linear-gradient(135deg, #FF7F39, #EA6C2F);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

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

    textarea {
        max-height: 120px;
    }

    textarea::-webkit-scrollbar {
        width: 4px;
    }

    textarea::-webkit-scrollbar-track {
        background: transparent;
    }

    textarea::-webkit-scrollbar-thumb {
        background: #CBD5E0;
        border-radius: 2px;
    }
</style>
@endsection
