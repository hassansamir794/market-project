@extends('layouts.app')

@section('title', 'Page Not Found | Market')
@section('meta_robots', 'noindex,nofollow')

@section('content')
    <div class="min-h-[55vh] flex items-center justify-center">
        <div class="glass-card p-8 max-w-xl w-full text-center">
            <p class="text-sm font-semibold text-gray-500">404</p>
            <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">Page not found</h1>
            <p class="mt-3 text-gray-600">
                The page you are looking for does not exist or was moved.
            </p>
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-xl bg-black text-white px-5 py-3 font-semibold">
                    Go Home
                </a>
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-xl border px-5 py-3 font-semibold">
                    Browse Products
                </a>
            </div>
        </div>
    </div>
@endsection
