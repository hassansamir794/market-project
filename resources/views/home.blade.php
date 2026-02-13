@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="mt-6 space-y-10">

        <!-- Hero -->
        <section class="rounded-3xl bg-gradient-to-br from-black to-gray-800 text-white p-6 sm:p-10 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="max-w-2xl">
                    <h1 class="text-2xl sm:text-4xl font-bold tracking-tight">
                        Welcome to Rasan Market
                    </h1>
                    <p class="mt-3 text-white/80">
                        Browse products with clear prices, categories, and a simple experience.
                    </p>

                    <div class="mt-5 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center justify-center rounded-xl bg-white text-black px-5 py-3 font-semibold hover:opacity-90 transition">
                            Browse Products
                        </a>

                        <a href="{{ route('about') }}"
                           class="inline-flex items-center justify-center rounded-xl ring-1 ring-white/25 px-5 py-3 font-semibold hover:bg-white/10 transition">
                            About & Location
                        </a>
                    </div>
                </div>

                <!-- Open / Closed badge -->
                <div class="shrink-0">
                    @if($isOpen)
                        <div class="inline-flex items-center gap-2 rounded-2xl bg-green-100 text-green-800 px-4 py-3 font-semibold">
                            <span class="h-2 w-2 rounded-full bg-green-600"></span>
                            Open now
                            <span class="text-green-700/80 font-medium">(9:00 AM – 1:00 AM)</span>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-2 rounded-2xl bg-red-100 text-red-800 px-4 py-3 font-semibold">
                            <span class="h-2 w-2 rounded-full bg-red-600"></span>
                            Closed
                            <span class="text-red-700/80 font-medium">(9:00 AM – 1:00 AM)</span>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Categories -->
        <section>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold">Categories</h2>
                <a class="text-sm font-semibold underline" href="{{ route('products.index') }}">
                    View all →
                </a>
            </div>

            <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                @forelse($categories as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->slug]) }}"
                       class="glass-card hover:shadow-md transition p-4">
                        <div class="font-semibold">{{ $cat->name }}</div>
                        <div class="mt-1 text-sm text-gray-600">Browse products →</div>
                    </a>
                @empty
                    <div class="col-span-full glass-card p-6 text-gray-600">
                        No categories yet. (Add some from Admin)
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Latest Products -->
        <section>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold">Latest Products</h2>
                <a class="text-sm font-semibold underline" href="{{ route('products.index') }}">
                    View all →
                </a>
            </div>

            <div class="mt-4 grid grid-cols-1 min-[420px]:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($latestProducts as $product)
                    <a href="{{ route('products.show', $product) }}"
                       class="group glass-card hover:shadow-md transition overflow-hidden">

                        <div class="aspect-[1/1] sm:aspect-[4/3] bg-gray-100 overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="h-full w-full object-cover group-hover:scale-[1.02] transition">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-gray-400">
                                    No Image
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="font-semibold text-gray-900 line-clamp-1">{{ $product->name }}</div>
                                <div class="shrink-0 rounded-xl bg-black text-white px-3 py-1 text-sm font-semibold">
                                    ${{ number_format($product->price, 2) }}
                                </div>
                            </div>

                            @if($product->categories?->count())
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($product->categories as $cat)
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                            {{ $cat->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-4 text-sm font-semibold text-gray-900">
                                View details →
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full glass-card p-6 text-gray-600">
                        No products yet. (Add products from Admin)
                    </div>
                @endforelse
            </div>
        </section>

    </div>
@endsection
