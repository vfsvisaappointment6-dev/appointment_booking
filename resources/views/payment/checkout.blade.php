@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900" style="font-family: 'Playfair Display', serif;">Complete Your Payment</h1>
        <p class="text-gray-600 mt-2">Secure payment powered by Paystack</p>
    </div>

    <!-- Payment Card -->
    <div class="bg-white rounded-lg border border-gray-200 p-8">
        <!-- Booking Summary -->
        <div class="mb-8 pb-8 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-900 mb-6" style="font-family: 'Playfair Display', serif;">Booking Summary</h2>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Service</p>
                        <p class="font-semibold text-gray-900">{{ $booking->service->name }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Date & Time</p>
                        <p class="font-semibold text-gray-900">{{ $booking->date->format('F d, Y') }} at {{ $booking->time->format('h:i A') }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Duration</p>
                        <p class="font-semibold text-gray-900">{{ $booking->service->duration }} minutes</p>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-600">Staff Member</p>
                        <p class="font-semibold text-gray-900">{{ $booking->staff->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Promo Code Section -->
        <div class="mb-8 pb-8 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Have a Discount Code?</h3>
            <div class="flex gap-2">
                <input type="text" id="promo-code-input" placeholder="Enter promo code (e.g., WELCOME50)"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent uppercase"
                    autocomplete="off">
                <button type="button" id="apply-promo-btn"
                    class="px-6 py-2 rounded-lg font-medium text-white transition"
                    style="background: #FF7F39;"
                    onmouseover="this.style.background='#EA6C2F'"
                    onmouseout="this.style.background='#FF7F39'">
                    Apply
                </button>
            </div>
            <div id="promo-message" class="mt-2 text-sm"></div>
        </div>

        <!-- Price Breakdown -->
        <div class="mb-8 pb-8 border-b border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-semibold text-gray-900">₵<span id="subtotal">{{ number_format($booking->service->price, 2) }}</span></span>
            </div>
            <div id="discount-row" class="flex justify-between items-center mb-4 hidden">
                <span class="text-gray-600">Discount</span>
                <span class="font-semibold text-green-600">-₵<span id="discount-amount">0.00</span></span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-600">Tax/Fees</span>
                <span class="font-semibold text-gray-900">₵0.00</span>
            </div>
        </div>

        <!-- Total Amount -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                <div class="text-right">
                    <div>
                        <span id="original-total" class="text-gray-900 text-xl">₵{{ number_format($booking->service->price, 2) }}</span>
                        <span id="new-total" class="text-3xl font-bold text-orange-500 ml-3 hidden">₵<span id="new-total-value">{{ number_format($booking->service->price, 2) }}</span></span>
                    </div>
                    <div id="save-badge-checkout" class="mt-2 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-50 text-green-800 border border-green-100 hidden">
                        <i class="fas fa-gift"></i>
                        <span id="save-text-checkout"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="mb-8 pb-8 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Payment Method</h3>
            <div class="p-4 border-2 rounded-lg" style="border-color: #FF7F39; background: #FFF5EE;">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" style="color: #FF7F39;">
                        <path d="M19 6h-2V4a1 1 0 00-1-1H8a1 1 0 00-1 1v2H5a3 3 0 00-3 3v10a3 3 0 003 3h14a3 3 0 003-3V9a3 3 0 00-3-3zm-9-1h4v2h-4V5zm9 12a1 1 0 01-1 1H5a1 1 0 01-1-1V9a1 1 0 011-1h14a1 1 0 011 1v8zm-7-5a2 2 0 100 4 2 2 0 000-4z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">Paystack</p>
                        <p class="text-sm text-gray-600">Secure online payment gateway</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
            <h3 class="font-semibold text-gray-900 mb-2">Payment Information</h3>
            <ul class="text-sm text-gray-700 space-y-2">
                <li class="flex gap-2">
                    <span class="text-blue-600">✓</span>
                    <span>Your payment is processed securely by Paystack</span>
                </li>
                <li class="flex gap-2">
                    <span class="text-blue-600">✓</span>
                    <span>You will be redirected to complete your payment</span>
                </li>
                <li class="flex gap-2">
                    <span class="text-blue-600">✓</span>
                    <span>Your booking will be confirmed once payment is successful</span>
                </li>
                <li class="flex gap-2">
                    <span class="text-blue-600">✓</span>
                    <span>You will receive a confirmation email with booking details</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
            <a href="{{ route('bookings.show', $booking->booking_id) }}" class="px-6 py-3 border-2 rounded-lg font-medium transition" style="border-color: #e5e7eb; color: #6b7280;">
                Cancel
            </a>
            <form action="{{ route('payment.initialize', $booking->booking_id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full px-6 py-3 rounded-lg font-medium text-white transition" style="background: #FF7F39;" onmouseover="this.style.background='#EA6C2F'" onmouseout="this.style.background='#FF7F39'">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pay ₵{{ number_format($booking->service->price, 2) }}
                </button>
            </form>
        </div>

        <!-- Security Notice -->
        <div class="mt-6 text-center text-xs text-gray-500">
            <p>🔒 Your payment information is encrypted and secure</p>
        </div>
    </div>
</div>

<script>
    const basePrice = {{ $booking->service->price }};
    let appliedPromo = null;

    document.getElementById('apply-promo-btn').addEventListener('click', function() {
        const promoCode = document.getElementById('promo-code-input').value.trim().toUpperCase();
        const messageEl = document.getElementById('promo-message');

        if (!promoCode) {
            messageEl.innerHTML = '<span class="text-red-500">Please enter a promo code</span>';
            return;
        }

        // Disable button while processing
        this.disabled = true;
        this.textContent = 'Checking...';

        fetch('{{ route("payment.validate-promo") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                code: promoCode,
                booking_id: '{{ $booking->booking_id }}',
                service_id: '{{ $booking->service->service_id }}'
            })
        })
        .then(response => response.text().then(text => {
            try { return JSON.parse(text); } catch (e) { throw new Error('Invalid JSON response from server'); }
        }))
        .then(data => {
            const btn = document.getElementById('apply-promo-btn');
            btn.disabled = false;
            btn.textContent = 'Apply';

            if (data.valid) {
                appliedPromo = data.promo;

                let discountValue = 0;
                if (data.promo.discount_type === 'percentage') {
                    discountValue = (basePrice * data.promo.discount_percentage) / 100;
                } else {
                    discountValue = parseFloat(data.promo.discount_amount);
                }

                const finalPrice = Math.max(0, basePrice - discountValue);

                // Update UI
                document.getElementById('discount-row').classList.remove('hidden');
                document.getElementById('discount-amount').textContent = discountValue.toFixed(2);

                // Animate total change
                const originalTotalEl = document.getElementById('original-total');
                const newTotalEl = document.getElementById('new-total');
                const newTotalValueEl = document.getElementById('new-total-value');

                originalTotalEl.classList.add('line-through', 'text-gray-400');
                newTotalEl.classList.remove('hidden');
                try { animateNumber(newTotalValueEl, parseFloat(newTotalValueEl.textContent || 0), finalPrice, 700); } catch (e) { newTotalValueEl.textContent = finalPrice.toFixed(2); }

                // Show save badge and confetti
                const saveBadge = document.getElementById('save-badge-checkout');
                document.getElementById('save-text-checkout').textContent = `You saved ₵${discountValue.toFixed(2)} — sweet!`;
                saveBadge.classList.remove('hidden');
                saveBadge.classList.add('animate-pulse');
                try { fireConfetti(); } catch (e) { console.debug('checkout confetti failed', e); }

                // Store applied discount in form
                let discountInput = document.getElementById('applied-discount');
                if (!discountInput) {
                    discountInput = document.createElement('input');
                    discountInput.type = 'hidden';
                    discountInput.id = 'applied-discount';
                    discountInput.name = 'applied_discount';
                    document.querySelector('form').appendChild(discountInput);
                }
                discountInput.value = discountValue.toFixed(2);

                // Store promo code in form
                let promoInput = document.getElementById('promo-code');
                if (!promoInput) {
                    promoInput = document.createElement('input');
                    promoInput.type = 'hidden';
                    promoInput.id = 'promo-code';
                    promoInput.name = 'promo_code';
                    document.querySelector('form').appendChild(promoInput);
                }
                promoInput.value = promoCode;

                messageEl.innerHTML = `<span class="text-green-600"><i class="fas fa-check mr-1"></i>Promo code applied! You saved ₵${discountValue.toFixed(2)}</span>`;

                // Disable promo input after success
                document.getElementById('promo-code-input').disabled = true;
            } else {
                messageEl.innerHTML = `<span class="text-red-500"><i class="fas fa-times mr-1"></i>${data.message}</span>`;
                document.getElementById('discount-row').classList.add('hidden');
                // Revert visuals
                document.getElementById('original-total').classList.remove('line-through', 'text-gray-400');
                document.getElementById('new-total').classList.add('hidden');
                document.getElementById('new-total-value').textContent = basePrice.toFixed(2);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageEl.innerHTML = '<span class="text-red-500">Error validating promo code</span>';
            document.getElementById('apply-promo-btn').disabled = false;
            document.getElementById('apply-promo-btn').textContent = 'Apply';
        });
    });

    // Allow Enter key to apply promo
    document.getElementById('promo-code-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.getElementById('apply-promo-btn').click();
        }
    });

    /* Polished number tween + wallet animation for checkout */
    function animateNumber(el, start, end, duration = 700) {
        const startTime = performance.now();
        function tick(now) {
            const elapsed = now - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const value = start + (end - start) * (1 - Math.pow(1 - progress, 3));
            el.textContent = value.toFixed(2);
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    // confetti loader and fire function (shared)
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
@endsection
