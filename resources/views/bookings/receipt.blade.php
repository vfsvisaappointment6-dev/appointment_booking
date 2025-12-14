<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - {{ $booking->booking_id }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111827; }
        .container { max-width: 800px; margin: 32px auto; padding: 16px; border: 1px solid #e5e7eb; }
        .header { display:flex; justify-content:space-between; align-items:center; }
        .title { font-size: 20px; font-weight:600; }
        .muted { color: #6b7280; }
        .row { display:flex; justify-content:space-between; margin:12px 0; }
        .bold { font-weight:600; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <div class="title">Booking Receipt</div>
            <div class="muted">Reference: {{ $booking->booking_id }}</div>
        </div>
        <div>
            <div class="muted">Date: {{ now()->toDateString() }}</div>
        </div>
    </div>

    <hr style="margin:16px 0">

    <div class="row">
        <div>
            <div class="muted">Customer</div>
            <div class="bold">{{ $booking->customer->name ?? 'Customer' }}</div>
            <div class="muted">{{ $booking->customer->email ?? '' }}</div>
        </div>
        <div>
            <div class="muted">Provider</div>
            <div class="bold">{{ $booking->staff->name ?? 'Staff' }}</div>
            <div class="muted">{{ $booking->staff->email ?? '' }}</div>
        </div>
    </div>

    <div style="margin-top:16px">
        <div class="muted">Service</div>
        <div class="bold">{{ $booking->service->name ?? 'Service' }}</div>
        <div class="muted">Amount: ₵{{ number_format($booking->service->price ?? 0, 2) }}</div>
    </div>

    <div style="margin-top:16px">
        <div class="row">
            <div>
                <div class="muted">Booking Date</div>
                <div class="bold">{{ $booking->date->format('l, F d, Y') }}</div>
            </div>
            <div>
                <div class="muted">Time</div>
                <div class="bold">{{ $booking->time->format('h:i A') }}</div>
            </div>
        </div>
    </div>

    <hr style="margin:18px 0">
    <div class="muted">Status: <span class="bold">{{ ucfirst($booking->status) }}</span></div>
</div>
</body>
</html>
