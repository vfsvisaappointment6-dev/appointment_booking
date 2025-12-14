@extends('layouts.app')

@section('title', $title ?? 'Page')

@section('content')
<div class="max-w-4xl mx-auto py-12">
    <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
        <h1 class="text-2xl font-bold mb-4">{{ $title ?? 'Coming Soon' }}</h1>
        <p class="text-gray-600">This page is a placeholder. Implement the full page when ready.</p>
        <div class="mt-6">
            <a href="{{ url()->previous() ?: route('dashboard') }}" class="px-4 py-2 bg-teal-600 text-white rounded">Go back</a>
        </div>
    </div>
</div>
@endsection
