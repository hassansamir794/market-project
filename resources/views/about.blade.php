@extends('layouts.app')

@section('title', 'About | Market')
@section('meta_description', 'Find Market address, opening hours, and contact details.')

@section('content')
    @php
        use Carbon\Carbon;

        $now = Carbon::now('Asia/Baghdad');
        $openTime = Carbon::createFromTime(9, 0, 0, 'Asia/Baghdad');
        $closeTime = Carbon::createFromTime(1, 0, 0, 'Asia/Baghdad');
        $isOpen = $now->between($openTime, Carbon::createFromTime(23, 59, 59, 'Asia/Baghdad'))
                  || $now->between(Carbon::createFromTime(0, 0, 0, 'Asia/Baghdad'), $closeTime);
    @endphp

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card p-6">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">{{ __('messages.about_title') }}</h1>

            <p class="mt-4 text-gray-700 leading-relaxed">
                Welcome to <span class="font-semibold">Market</span>.
                We provide quality products with clear pricing and a simple shopping experience.
            </p>

            <div class="mt-6 glass-card p-4 bg-white/70">
                <div class="text-sm font-semibold text-gray-900">{{ __('messages.address') }}</div>
                <div class="mt-1 text-gray-700">
                    Rasan Market<br>
                    Direct street to Barzan
                </div>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    <div class="glass-card p-3 bg-white/70">
                        <div class="font-semibold">{{ __('messages.phone') }}</div>
                        <div class="text-gray-700">{{ config('contact.phone') }}</div>
                    </div>
                    <div class="glass-card p-3 bg-white/70">
                        <div class="font-semibold">{{ __('messages.email') }}</div>
                        <div class="text-gray-700 break-all">{{ config('contact.email') }}</div>
                    </div>
                </div>

                <a target="_blank"
                   class="mt-4 inline-flex items-center justify-center rounded-xl bg-black text-white px-4 py-2 font-semibold hover:opacity-90 transition"
                   href="{{ config('contact.maps_url') }}">
                    {{ __('messages.open_maps') }}
                </a>
            </div>

            <div class="mt-6 glass-card p-4 bg-white/70">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <h2 class="text-lg font-semibold">{{ __('messages.opening_hours') }}</h2>

                    @if($isOpen)
                        <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800">
                            <span class="h-2 w-2 rounded-full bg-green-600"></span>
                            {{ __('messages.open_now') }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800">
                            <span class="h-2 w-2 rounded-full bg-red-600"></span>
                            {{ __('messages.closed') }}
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    @foreach(['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day)
                        <div class="flex items-center justify-between glass-card p-3 bg-white/70">
                            <span class="font-medium">{{ $day }}</span>
                            <span class="text-gray-700 font-semibold">9:00 AM - 1:00 AM</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="glass-card overflow-hidden">
            <div class="p-6 border-b border-white/30 bg-white/70">
                <h2 class="text-lg font-semibold">{{ __('messages.our_location') }}</h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('messages.find_on_map') }}</p>
            </div>

            <iframe
                src="{{ config('contact.maps_embed') }}"
                width="100%"
                height="420"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

    <div class="sm:hidden fixed bottom-3 left-3 right-3 z-40">
        <div class="grid grid-cols-2 gap-2 rounded-2xl bg-white/95 p-2 border shadow-lg backdrop-blur">
            <a href="tel:{{ config('contact.phone') }}" class="inline-flex items-center justify-center rounded-xl bg-black text-white px-4 py-3 text-sm font-semibold">
                {{ __('messages.call_market') }}
            </a>
            <a target="_blank" href="{{ config('contact.maps_url') }}" class="inline-flex items-center justify-center rounded-xl border px-4 py-3 text-sm font-semibold text-gray-800">
                {{ __('messages.directions') }}
            </a>
        </div>
    </div>
@endsection
