@php
    // Redirect old placeholder view to the new controller-backed index
    redirect()->route('admin.payments.index')->send();
    exit;
@endphp
