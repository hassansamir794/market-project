@extends('layouts.app')

@section('title', $product->name)

@section('content')
    @php
        use Carbon\Carbon;

        $now = Carbon::now('Asia/Baghdad');

        // Market hours: 9:00 AM -> 1:00 AM (next day)
        $openTime = Carbon::createFromTime(9, 0, 0, 'Asia/Baghdad');
        $closeTime = Carbon::createFromTime(1, 0, 0, 'Asia/Baghdad');

        $isOpen = $now->between($openTime, Carbon::createFromTime(23, 59, 59, 'Asia/Baghdad'))
                || $now->between(Carbon::createFromTime(0, 0, 0, 'Asia/Baghdad'), $closeTime);
    @endphp

    <div class="mt-6">
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
            ← Back to products
        </a>

        <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Image -->
            <div class="glass-card overflow-hidden">
                <div class="aspect-[1/1] sm:aspect-[4/3] bg-gray-100">
                    @if($product->image)
                        <img
                            src="{{ asset('storage/'.$product->image) }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-cover"
                        >
                    @else
                        <div class="h-full w-full flex items-center justify-center text-gray-400">
                            No Image
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="glass-card p-6">
                <div class="flex items-start justify-between gap-3">
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">
                        {{ $product->name }}
                    </h1>

                    @if($isOpen)
                        <span class="shrink-0 inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800">
                            <span class="h-2 w-2 rounded-full bg-green-600"></span>
                            Open now
                        </span>
                    @else
                        <span class="shrink-0 inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800">
                            <span class="h-2 w-2 rounded-full bg-red-600"></span>
                            Closed
                        </span>
                    @endif
                </div>

                <!-- Price -->
                <div class="mt-3 inline-flex items-center gap-2 rounded-2xl bg-black text-white px-4 py-2 font-semibold">
                    ${{ number_format($product->price, 2) }}
                </div>

                <!-- Categories -->
                @if($product->categories?->count())
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($product->categories as $cat)
                            <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                               class="inline-flex items-center rounded-full bg-white/70 px-3 py-1 text-sm font-semibold text-gray-700 hover:bg-white transition">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Description -->
                @if($product->description)
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold">Description</h2>
                        <p class="mt-2 text-gray-700 leading-relaxed">
                            {{ $product->description }}
                        </p>
                    </div>
                @endif

                <!-- Quick actions -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a target="_blank"
                       href="https://www.google.com/maps?q=36.85261225541263,44.130020767406336"
                       class="inline-flex items-center justify-center rounded-xl glass-card bg-white/70 px-4 py-3 font-semibold hover:bg-white transition">
                        📍 Location
                    </a>

                    <a href="tel:+9647504473964"
                       class="inline-flex items-center justify-center rounded-xl bg-black text-white px-4 py-3 font-semibold hover:opacity-90 transition">
                        📞 Call Market
                    </a>
                </div>

                <!-- Help -->
                <div class="mt-6 glass-card p-4 bg-white/70 text-sm text-gray-700">
                    <div class="font-semibold">Need help?</div>
                    <div class="mt-1">
                        Visit the About page for our address, contact details, and opening hours.
                    </div>
                    <a href="{{ route('about') }}" class="mt-2 inline-block font-semibold underline">
                        Go to About →
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
