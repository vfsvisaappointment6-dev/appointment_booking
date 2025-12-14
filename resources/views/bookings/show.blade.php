@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<!-- Back Button - Role aware -->
    @php
        $backRoute = auth()->user()->role === 'staff' ? route('staff.bookings') : route('bookings.index');
        $backLabel = auth()->user()->role === 'staff' ? 'Back to Schedule' : 'Back to Bookings';
    @endphp
    <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 font-medium mb-6 transition hover:opacity-75" style="color: #FF7F39;">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        {{ $backLabel }}
    </a>

    <!-- Booking Overview Card -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Left Side -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">{{ $booking->service->name ?? 'Service' }}</h1>

                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #FF7F39;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Date</p>
                            <p class="font-semibold text-gray-900">{{ $booking->date->format('l, F d, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #FF7F39;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Time</p>
                            <p class="font-semibold text-gray-900">{{ $booking->time->format('h:i A') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #FF7F39;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Duration</p>
                            <p class="font-semibold text-gray-900">{{ $booking->service->duration ?? 60 }} minutes</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #FF7F39;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <p class="font-semibold" style="color: {{ ['confirmed' => '#FF7F39', 'completed' => '#10b981', 'cancelled' => '#ef4444'][$booking->status] ?? '#6b7280' }};">
                                {{ ucfirst($booking->status) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Person Info -->
            <div class="bg-gray-50 rounded-lg p-4">
                @php
                    $person = auth()->user()->role === 'staff' ? $booking->customer : $booking->staff;
                @endphp
                <h3 class="text-lg font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">
                    {{ auth()->user()->role === 'staff' ? 'Customer' : 'Service Provider' }}
                </h3>

                <div class="flex items-center gap-4 mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ $person->name }}&background=FF7F39&color=fff&size=80"
                         alt="{{ $person->name }}" class="w-16 h-16 rounded-full">
                    <div>
                        <p class="font-semibold text-gray-900 text-lg">{{ $person->name }}</p>
                        <p class="text-sm text-gray-600">{{ $person->email }}</p>
                        @if($person->phone)
                            <p class="text-sm text-gray-600">{{ $person->phone }}</p>
                        @endif
                    </div>
                </div>

                @if(auth()->user()->role === 'staff' && $booking->staff->staffProfile)
                    <p class="text-sm text-gray-600 mb-4">Rating:
                        <span class="font-semibold text-gray-900">{{ $booking->staff->staffProfile->rating ?? 'No rating' }}</span>
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Notes Section -->
    @if($booking->notes)
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h3 class="font-semibold text-gray-900 mb-2">Notes</h3>
            <p class="text-gray-700">{{ $booking->notes }}</p>
        </div>
    @endif

    <!-- Payment Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">Payment Details</h2>
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm text-gray-600">Amount</p>
                <p class="text-2xl font-bold text-gray-900">
                    <span id="original-price">₵{{ number_format($booking->service->price, 2) }}</span>
                    <span id="final-price" class="ml-4 text-2xl font-extrabold text-orange-500 hidden">₵<span id="final-price-value">{{ number_format($booking->service->price, 2) }}</span></span>
                </p>

                <div id="save-badge" class="mt-2 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-50 text-green-800 border border-green-100 hidden">
                    <i class="fas fa-gift"></i>
                    <span id="save-text"></span>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <p class="font-semibold" style="color: {{ $booking->payment_status === 'paid' ? '#10b981' : '#f59e0b' }};">
                    {{ ucfirst($booking->payment_status) }}
                </p>
            </div>
        </div>

        <!-- Payment Button (show only if unpaid) -->
        @if($booking->payment_status === 'unpaid' && auth()->user()->role !== 'staff')
            <!-- Promo Code Inline (customers) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Have a Discount Code?</label>
                <div class="flex gap-2">
                    <input type="text" id="promo-code-input-booking" placeholder="Enter promo code" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg uppercase" autocomplete="off">
                    <button type="button" id="apply-promo-btn-booking" class="px-4 py-2 bg-orange-500 text-white rounded">Apply</button>
                </div>
                <div id="promo-message-booking" class="mt-2 text-sm"></div>
            </div>

            <form id="initialize-payment-form" action="{{ route('payment.initialize', $booking->booking_id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="w-full px-6 py-3 rounded-lg font-medium text-white transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pay with Paystack (₵{{ number_format($booking->service->price, 2) }})
                </button>
            </form>
        @endif

    <!-- Action Buttons -->
    @if(auth()->user()->role !== 'staff' && $booking->payment_status !== 'unpaid')
        <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($booking->payment_status === 'unpaid')
                    <a href="{{ route('payment.checkout', $booking->booking_id) }}" class="px-6 py-3 rounded-lg font-medium transition text-white" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h10M7 15H5.5a2.5 2.5 0 110-5H7m10 0h1.5a2.5 2.5 0 110 5H17" />
                        </svg>
                        Pay Now
                    </a>
                @elseif($booking->status === 'confirmed' && $booking->date->isFuture())
                    <a href="{{ route('bookings.reschedule', $booking->booking_id) }}" class="px-6 py-3 rounded-lg font-medium transition text-white" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Reschedule
                    </a>
                    <form action="{{ route('bookings.cancel', $booking->booking_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                        @csrf
                        <button type="submit" class="px-6 py-3 rounded-lg font-medium transition border-2" style="border-color: #ef4444; color: #ef4444; background: white;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='white'">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel Booking
                        </button>
                    </form>
                @endif

                <a href="{{ route('bookings.receipt', $booking->booking_id) }}" class="px-6 py-3 rounded-lg font-medium transition" style="border: 2px solid #FF7F39; color: #FF7F39; background: white;" onmouseover="this.style.background='#FFF5EE'" onmouseout="this.style.background='white'">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Receipt
                </a>
            </div>
        </div>
    @endif
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4" style="font-family: 'Playfair Display', serif;">Contact {{ auth()->user()->role === 'staff' ? 'Customer' : 'Staff' }}</h2>
        <a href="{{ route('messages.index') }}" class="px-6 py-3 rounded-lg font-medium text-white transition" style="background: #FF7F39; display: inline-block;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            Send Message
        </a>
    </div>
@endsection

@push('scripts')
<script>
    (function(){
        const applyBtn = document.getElementById('apply-promo-btn-booking');
        if (!applyBtn) return;

        const promoInput = document.getElementById('promo-code-input-booking');
        const messageEl = document.getElementById('promo-message-booking');
        const initForm = document.getElementById('initialize-payment-form');

        applyBtn.addEventListener('click', function(){
            const code = promoInput.value.trim().toUpperCase();
            if (!code) {
                messageEl.innerHTML = '<span class="text-red-500">Please enter a promo code</span>';
                return;
            }

            applyBtn.disabled = true; applyBtn.textContent = 'Checking...';

            fetch('{{ route("payment.validate-promo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: code, booking_id: '{{ $booking->booking_id }}', service_id: '{{ $booking->service->service_id }}' })
            })
            .then(r => r.json())
            .then(data => {
                applyBtn.disabled = false; applyBtn.textContent = 'Apply';
                if (data.valid) {
                    // compute discount
                    let discount = 0;
                    if (data.promo.discount_type === 'percentage') {
                        discount = ({{ $booking->service->price }} * data.promo.discount_percentage) / 100;
                    } else {
                        discount = parseFloat(data.promo.discount_amount || 0);
                    }

                    // attach hidden inputs to the initialization form
                    let discInput = document.getElementById('applied-discount-booking');
                    if (!discInput) {
                        discInput = document.createElement('input');
                        discInput.type = 'hidden';
                        discInput.name = 'applied_discount';
                        discInput.id = 'applied-discount-booking';
                        initForm.appendChild(discInput);
                    }
                    discInput.value = discount.toFixed(2);

                    let codeInput = document.getElementById('promo-code-booking');
                    if (!codeInput) {
                        codeInput = document.createElement('input');
                        codeInput.type = 'hidden';
                        codeInput.name = 'promo_code';
                        codeInput.id = 'promo-code-booking';
                        initForm.appendChild(codeInput);
                    }
                    codeInput.value = code;

                    // Update visual price with animation
                    const originalEl = document.getElementById('original-price');
                    const finalEl = document.getElementById('final-price');
                    const finalValEl = document.getElementById('final-price-value');
                    const saveBadge = document.getElementById('save-badge');

                    const originalPrice = parseFloat('{{ $booking->service->price }}');
                    const finalPrice = Math.max(0.01, originalPrice - discount);

                    // strike original and show final price
                    originalEl.classList.add('line-through', 'text-gray-400');
                    finalValEl.textContent = finalPrice.toFixed(2);
                    finalEl.classList.remove('hidden');

                    // update pay button label
                    const payBtn = initForm.querySelector('button');
                    if (payBtn) {
                        payBtn.innerHTML = `<svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">\n                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />\n                        </svg>Pay ₵${finalPrice.toFixed(2)}`;
                    }

                    // show save badge
                    document.getElementById('save-text').textContent = `You saved ₵${discount.toFixed(2)} — nice!`;
                    saveBadge.classList.remove('hidden');
                    saveBadge.classList.add('animate-pulse');

                        // fire confetti (canvas-confetti) — load once then burst
                        try { fireConfetti(); } catch (e) { console.debug('confetti failed', e); }

                    messageEl.innerHTML = `<span class="text-green-600">Applied: ${code} — you saved ₵${discount.toFixed(2)}</span>`;
                    promoInput.disabled = true;
                } else {
                    messageEl.innerHTML = `<span class="text-red-500">${data.message}</span>`;
                }
            })
            .catch(err => {
                console.error(err);
                messageEl.innerHTML = '<span class="text-red-500">Error validating promo code</span>';
                applyBtn.disabled = false; applyBtn.textContent = 'Apply';
            });
        });
    })();

    /* Number tween + confetti loader */
    function animateNumber(el, start, end, duration = 700) {
        const startTime = performance.now();
        function tick(now) {
            const elapsed = now - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const value = start + (end - start) * (1 - Math.pow(1 - progress, 3)); // easeOutCubic
            el.textContent = value.toFixed(2);
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }
</script>
    @endpush

    <script>
        // load canvas-confetti once and fire a burst
        function loadConfettiScript(cb) {
            if (window.confetti) { return cb(); }
            const s = document.createElement('script');
            s.src = 'https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js';
            s.async = true;
            s.onload = cb;
            s.onerror = function() { console.debug('failed to load confetti'); cb(); };
            document.head.appendChild(s);
        }

        function fireConfetti() {
            loadConfettiScript(() => {
                try {
                    if (window.confetti) {
                        confetti({ particleCount: 90, spread: 160, origin: { y: 0.6 } });
                    }
                } catch (e) { console.debug('confetti error', e); }
            });
        }
    </script>
