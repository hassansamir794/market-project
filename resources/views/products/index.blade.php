@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <!-- Hero -->
    <section class="mt-6">
        <div class="rounded-3xl bg-gradient-to-br from-black to-gray-800 text-white p-8 sm:p-10 shadow-sm">
            <div class="max-w-2xl">
                <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Find what you need, fast.</h1>
                <p class="mt-3 text-white/80">
                    Browse our products with clear prices and simple navigation.
                </p>

                <form method="GET" action="{{ route('products.index') }}" class="mt-6 flex flex-col sm:flex-row gap-3">
                    <input
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="Search products..."
                        class="w-full sm:flex-1 rounded-xl border-0 bg-white/10 px-4 py-3 text-white placeholder:text-white/60 ring-1 ring-white/15 focus:ring-2 focus:ring-white/40 focus:outline-none"
                    >

                    <select
                        name="category"
                        class="w-full sm:w-56 rounded-xl border-0 bg-white/10 px-4 py-3 text-white ring-1 ring-white/15 focus:ring-2 focus:ring-white/40 focus:outline-none"
                    >
                        <option value="" class="text-black">All categories</option>
                        @foreach($categories as $cat)
                            <option
                                value="{{ $cat->slug }}"
                                class="text-black"
                                @selected(($category ?? '') === $cat->slug)
                            >
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <button class="rounded-xl bg-white text-black px-5 py-3 font-semibold hover:opacity-90 transition">
                        Search
                    </button>

                    @if(!empty($q) || !empty($category))
                        <a href="{{ route('products.index') }}"
                           class="rounded-xl px-5 py-3 font-semibold ring-1 ring-white/20 hover:bg-white/10 transition text-center">
                            Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </section>

    <!-- Results info -->
    <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <h2 class="text-xl font-semibold">All Products</h2>

        <div class="text-sm text-gray-600 flex flex-wrap gap-2">
            @if(!empty($q))
                <span>
                    Searching: <span class="font-semibold">{{ $q }}</span>
                </span>
            @endif

            @if(!empty($category))
                <span>
                    Category:
                    <span class="font-semibold">
                        {{ optional($categories->firstWhere('slug', $category))->name ?? $category }}
                    </span>
                </span>
            @endif
        </div>
    </div>

    <!-- Grid -->
    <section class="mt-4">
        <div class="grid grid-cols-1 min-[420px]:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($products as $product)
                <a href="{{ route('products.show', $product) }}"
                   class="group glass-card hover:shadow-md transition overflow-hidden">

                    <div class="aspect-[1/1] sm:aspect-[4/3] bg-gray-100 overflow-hidden">
                        @if($product->image)
                            <img
                                src="{{ asset('storage/'.$product->image) }}"
                                alt="{{ $product->name }}"
                                class="h-full w-full object-cover group-hover:scale-[1.02] transition"
                            >
                        @else
                            <div class="h-full w-full flex items-center justify-center text-gray-400">
                                No Image
                            </div>
                        @endif
                    </div>

                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="font-semibold text-gray-900 line-clamp-1">
                                {{ $product->name }}
                            </div>
                            <div class="shrink-0 rounded-xl bg-black text-white px-3 py-1 text-sm font-semibold">
                                ${{ number_format($product->price, 2) }}
                            </div>
                        </div>

                        {{-- Category badges --}}
                        @if($product->categories?->count())
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($product->categories as $cat)
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                        {{ $cat->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        @if($product->description)
                            <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                {{ $product->description }}
                            </p>
                        @endif

                        <div class="mt-4 text-sm font-semibold text-gray-900">
                            View details →
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full glass-card p-8 text-center text-gray-600">
                    No products found.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </section>
@endsection
