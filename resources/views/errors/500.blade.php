@extends('layouts.app')

@section('title', 'Server Error | Market')
@section('meta_robots', 'noindex,nofollow')

@section('content')
    <div class="min-h-[55vh] flex items-center justify-center">
        <div class="glass-card p-8 max-w-xl w-full text-center">
            <p class="text-sm font-semibold text-gray-500">500</p>
            <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">Something went wrong</h1>
            <p class="mt-3 text-gray-600">
                The server hit an unexpected error. Please try again in a moment.
            </p>
            <div class="mt-6">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-xl bg-black text-white px-5 py-3 font-semibold">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
@endsection
