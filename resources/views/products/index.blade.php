@extends('layouts.app')

@section('title', $pageTitle ?? 'Products | Market')
@section('meta_description', $metaDescription ?? 'Browse all products at Market. Filter by category, name, and price range.')

@section('content')
    <section class="mt-6">
        <div class="rounded-3xl bg-gradient-to-br from-black to-gray-800 text-white p-6 sm:p-10 shadow-sm">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-4xl font-bold tracking-tight">Find what you need, fast.</h1>
                @if(!empty($categoryModel))
                    <p class="mt-2 text-white/80">Category: <span class="font-semibold">{{ $categoryModel->name }}</span></p>
                @endif
                <p class="mt-3 text-white/80">
                    Browse our products with clear prices and simple navigation.
                </p>

                <form method="GET" action="{{ route('products.index') }}" class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <input
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="{{ __('messages.search_products') }}"
                        class="lg:col-span-2 w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-white placeholder:text-white/60 ring-1 ring-white/15 focus:ring-2 focus:ring-white/40 focus:outline-none"
                    >

                    <select
                        name="category"
                        aria-label="{{ __('messages.categories') }}"
                        class="w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-white ring-1 ring-white/15 focus:ring-2 focus:ring-white/40 focus:outline-none"
                    >
                        <option value="" class="text-black">{{ __('messages.all_categories') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" class="text-black" @selected(($category ?? '') === $cat->slug)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="min_price"
                        value="{{ $minPriceInput ?? '' }}"
                        placeholder="{{ __('messages.min_price') }} (IQD)"
                        class="w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-white placeholder:text-white/60 ring-1 ring-white/15 focus:ring-2 focus:ring-white/40 focus:outline-none"
                    >

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="max_price"
                        value="{{ $maxPriceInput ?? '' }}"
                        placeholder="{{ __('messages.max_price') }} (IQD)"
                        class="w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-white placeholder:text-white/60 ring-1 ring-white/15 focus:ring-2 focus:ring-white/40 focus:outline-none"
                    >

                    <select
                        name="sort"
                        aria-label="{{ __('messages.sort_by') }}"
                        class="w-full rounded-xl border-0 bg-white/10 px-4 py-3 text-white ring-1 ring-white/15 focus:ring-2 focus:ring-white/40 focus:outline-none"
                    >
                        <option value="newest" class="text-black" @selected(($sort ?? 'newest') === 'newest')>{{ __('messages.sort_newest') }}</option>
                        <option value="price_low" class="text-black" @selected(($sort ?? '') === 'price_low')>{{ __('messages.sort_price_low') }}</option>
                        <option value="price_high" class="text-black" @selected(($sort ?? '') === 'price_high')>{{ __('messages.sort_price_high') }}</option>
                        <option value="most_viewed" class="text-black" @selected(($sort ?? '') === 'most_viewed')>{{ __('messages.sort_most_viewed') }}</option>
                    </select>

                    <div class="sm:col-span-2 lg:col-span-6 flex flex-col sm:flex-row gap-3">
                        <button class="rounded-xl bg-white text-black px-5 py-3 font-semibold hover:opacity-90 transition">
                            {{ __('messages.search') }}
                        </button>

                        @if(!empty($q) || !empty($category) || !empty($minPrice) || !empty($maxPrice))
                            <a href="{{ route('products.index') }}"
                               class="rounded-xl px-5 py-3 font-semibold ring-1 ring-white/20 hover:bg-white/10 transition text-center">
                                {{ __('messages.clear_filters') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </section>

    <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <h2 class="text-xl font-semibold">{{ __('messages.all_products') }}</h2>

        <div class="text-sm text-gray-600 flex flex-wrap gap-2">
            @if(!empty($q))
                <span>Search: <span class="font-semibold">{{ $q }}</span></span>
            @endif
            @if(!empty($category))
                <span>Category: <span class="font-semibold">{{ optional($categories->firstWhere('slug', $category))->name ?? $category }}</span></span>
            @endif
            @if(is_numeric($minPriceInput))
                <span>Min: <span class="font-semibold"><x-money :amount="$minPriceInput" :convert="false" /></span></span>
            @endif
            @if(is_numeric($maxPriceInput))
                <span>Max: <span class="font-semibold"><x-money :amount="$maxPriceInput" :convert="false" /></span></span>
            @endif
            @if(($sort ?? 'newest') !== 'newest')
                <span>Sort: <span class="font-semibold">{{ $sort === 'price_low' ? __('messages.sort_price_low') : ($sort === 'price_high' ? __('messages.sort_price_high') : __('messages.sort_most_viewed')) }}</span></span>
            @endif
        </div>
    </div>

    @php
        $query = request()->query();
        $remove = fn ($key) => route('products.index', \Illuminate\Support\Arr::except($query, [$key]));
    @endphp

    @if(!empty($q) || !empty($category) || is_numeric($minPriceInput) || is_numeric($maxPriceInput) || (($sort ?? 'newest') !== 'newest'))
        <div class="mt-3 flex flex-wrap gap-2 text-sm">
            @if(!empty($q))
                <a href="{{ $remove('q') }}" class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 border">
                    Search: {{ $q }} ✕
                </a>
            @endif
            @if(!empty($category))
                <a href="{{ $remove('category') }}" class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 border">
                    Category: {{ optional($categories->firstWhere('slug', $category))->name ?? $category }} ✕
                </a>
            @endif
            @if(is_numeric($minPriceInput))
                <a href="{{ $remove('min_price') }}" class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 border">
                    Min: {{ number_format((float) $minPriceInput, 0) }} ✕
                </a>
            @endif
            @if(is_numeric($maxPriceInput))
                <a href="{{ $remove('max_price') }}" class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 border">
                    Max: {{ number_format((float) $maxPriceInput, 0) }} ✕
                </a>
            @endif
            @if(($sort ?? 'newest') !== 'newest')
                <a href="{{ $remove('sort') }}" class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 border">
                    Sort: {{ $sort === 'price_low' ? __('messages.sort_price_low') : ($sort === 'price_high' ? __('messages.sort_price_high') : __('messages.sort_most_viewed')) }} ✕
                </a>
            @endif
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 rounded-full bg-black text-white px-3 py-1">
                {{ __('messages.clear_filters') }}
            </a>
        </div>
    @endif

    <section class="mt-4">
        <div class="grid grid-cols-1 min-[420px]:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($products as $product)
                @php
                    $isAvailable = (bool) ($product->is_available ?? true);
                    $inStock = (int) ($product->stock ?? 0) > 0;
                @endphp
                <a href="{{ route('products.show', $product) }}"
                   class="group glass-card hover:shadow-md transition overflow-hidden">

                    <div class="aspect-[1/1] sm:aspect-[4/3] bg-gray-100 overflow-hidden">
                        <x-product-image
                            :path="$product->image"
                            :alt="$product->name"
                            class="h-full w-full object-cover group-hover:scale-[1.02] transition"
                        />
                    </div>

                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="font-semibold text-gray-900 line-clamp-1">{{ $product->name }}</div>
                            <div class="shrink-0 rounded-xl bg-black text-white px-3 py-1 text-sm font-semibold">
                                <x-money :amount="$product->price" />
                            </div>
                        </div>

                        @if(!$isAvailable || !$inStock)
                            <div class="mt-2 inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-800">
                                Out of stock
                            </div>
                        @endif

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
                    {{ __('messages.view_details') }}
                </div>
            </div>
        </a>
    @empty
        <div class="col-span-full glass-card p-8 text-center text-gray-600">
            <p class="font-semibold text-gray-900">{{ __('messages.no_products') }}</p>
            <p class="mt-2">Try a different keyword, remove category, or widen your price range.</p>
            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center rounded-xl bg-black text-white px-4 py-2 font-semibold">
                    {{ __('messages.reset_filters') }}
                </a>
            </div>
        </div>
    @endforelse
        </div>

        @if($categories->count())
            <div class="mt-6 glass-card p-4">
                <div class="font-semibold text-gray-900">{{ __('messages.quick_links') }}</div>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach($categories as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}"
                           class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 hover:bg-gray-200 transition">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </section>
@endsection
