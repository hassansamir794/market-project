@extends('layouts.app')

@section('title', __('messages.error_404_title') . ' | Rasan Market')
@section('meta_robots', 'noindex,nofollow')

@section('content')
    <div class="min-h-[55vh] flex items-center justify-center">
        <div class="glass-card p-8 max-w-xl w-full text-center">
            <p class="text-sm font-semibold text-gray-500">404</p>
            <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">{{ __('messages.error_404_title') }}</h1>
            <p class="mt-3 text-gray-600">
                {{ __('messages.error_404_text') }}
            </p>
            <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-xl bg-black text-white px-5 py-3 font-semibold">
                    {{ __('messages.go_home') }}
                </a>
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-xl border px-5 py-3 font-semibold">
                    {{ __('messages.browse_products') }}
                </a>
            </div>
        </div>
    </div>
@endsection
