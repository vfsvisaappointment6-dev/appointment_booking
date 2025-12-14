@php
    // Redirect old placeholder view to the new controller-backed index
    redirect()->route('admin.users.index')->send();
    exit;
@endphp
