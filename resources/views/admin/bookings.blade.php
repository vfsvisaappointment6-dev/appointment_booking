@php
    // Redirect old placeholder view to the new controller-backed index
    redirect()->route('admin.bookings.index')->send();
    exit;
@endphp
