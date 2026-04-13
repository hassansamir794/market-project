@extends('layouts.app')

@section('title', __('messages.error_500_title') . ' | Rasan Market')
@section('meta_robots', 'noindex,nofollow')

@section('content')
    <div class="min-h-[55vh] flex items-center justify-center">
        <div class="glass-card p-8 max-w-xl w-full text-center">
            <p class="text-sm font-semibold text-gray-500">500</p>
            <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900">{{ __('messages.error_500_title') }}</h1>
            <p class="mt-3 text-gray-600">
                {{ __('messages.error_500_text') }}
            </p>
            <div class="mt-6">
                <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-xl bg-black text-white px-5 py-3 font-semibold">
                    {{ __('messages.back_to_home') }}
                </a>
            </div>
        </div>
    </div>
@endsection
