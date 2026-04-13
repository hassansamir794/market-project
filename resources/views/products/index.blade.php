@extends('layouts.app')

@section('title', $pageTitle ?? __('messages.products_title') . ' | Rasan Market')
@section('meta_description', $metaDescription ?? __('messages.products_meta_description'))

@section('content')
    <section class="mt-3">
        <div class="hero-panel">
            <div class="max-w-3xl">
                <h1 class="text-2xl sm:text-4xl font-bold tracking-tight">{{ __('messages.products_hero_title') }}</h1>
                @if(!empty($categoryModel))
                    <p class="mt-2 text-white/80">{{ __('messages.filter_category_label') }}: <span class="font-semibold">{{ $categoryModel->name }}</span></p>
                @endif
                <p class="mt-3 text-white/80">
                    {{ __('messages.products_hero_text') }}
                </p>

                <form method="GET" action="{{ route('products.index') }}" class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <input
                        name="q"
                        value="{{ $q ?? '' }}"
                        placeholder="{{ __('messages.search_products') }}"
                        class="hero-input lg:col-span-2"
                    >

                    <select
                        name="category"
                        aria-label="{{ __('messages.categories') }}"
                        class="hero-input"
                    >
                        <option value="" class="hero-option">{{ __('messages.all_categories') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" class="hero-option" @selected(($category ?? '') === $cat->slug)>
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
                        class="hero-input"
                    >

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="max_price"
                        value="{{ $maxPriceInput ?? '' }}"
                        placeholder="{{ __('messages.max_price') }} (IQD)"
                        class="hero-input"
                    >

                    <select
                        name="sort"
                        aria-label="{{ __('messages.sort_by') }}"
                        class="hero-input"
                    >
                        <option value="newest" class="hero-option" @selected(($sort ?? 'newest') === 'newest')>{{ __('messages.sort_newest') }}</option>
                        <option value="price_low" class="hero-option" @selected(($sort ?? '') === 'price_low')>{{ __('messages.sort_price_low') }}</option>
                        <option value="price_high" class="hero-option" @selected(($sort ?? '') === 'price_high')>{{ __('messages.sort_price_high') }}</option>
                        <option value="most_viewed" class="hero-option" @selected(($sort ?? '') === 'most_viewed')>{{ __('messages.sort_most_viewed') }}</option>
                    </select>

                    <div class="sm:col-span-2 lg:col-span-6 flex flex-col sm:flex-row gap-3">
                        <button class="rounded-xl bg-white text-stone-900 px-5 py-3 font-semibold hover:opacity-90 transition w-full sm:w-auto shadow-sm">
                            {{ __('messages.search') }}
                        </button>

                        @if(!empty($q) || !empty($category) || !empty($minPrice) || !empty($maxPrice))
                            <a href="{{ route('products.index') }}"
                               class="rounded-xl px-5 py-3 font-semibold ring-1 ring-white/20 hover:bg-white/10 transition text-center w-full sm:w-auto">
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
                <span>{{ __('messages.filter_search_label') }}: <span class="font-semibold">{{ $q }}</span></span>
            @endif
            @if(!empty($category))
                <span>{{ __('messages.filter_category_label') }}: <span class="font-semibold">{{ optional($categories->firstWhere('slug', $category))->name ?? $category }}</span></span>
            @endif
            @if(is_numeric($minPriceInput))
                <span>{{ __('messages.filter_min_label') }}: <span class="font-semibold"><x-money :amount="$minPriceInput" :convert="false" /></span></span>
            @endif
            @if(is_numeric($maxPriceInput))
                <span>{{ __('messages.filter_max_label') }}: <span class="font-semibold"><x-money :amount="$maxPriceInput" :convert="false" /></span></span>
            @endif
            @if(($sort ?? 'newest') !== 'newest')
                <span>{{ __('messages.filter_sort_label') }}: <span class="font-semibold">{{ $sort === 'price_low' ? __('messages.sort_price_low') : ($sort === 'price_high' ? __('messages.sort_price_high') : __('messages.sort_most_viewed')) }}</span></span>
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
                <a href="{{ $remove('q') }}" class="chip border">
                    {{ __('messages.filter_search_label') }}: {{ $q }} x
                </a>
            @endif
            @if(!empty($category))
                <a href="{{ $remove('category') }}" class="chip border">
                    {{ __('messages.filter_category_label') }}: {{ optional($categories->firstWhere('slug', $category))->name ?? $category }} x
                </a>
            @endif
            @if(is_numeric($minPriceInput))
                <a href="{{ $remove('min_price') }}" class="chip border">
                    {{ __('messages.filter_min_label') }}: {{ number_format((float) $minPriceInput, 0) }} x
                </a>
            @endif
            @if(is_numeric($maxPriceInput))
                <a href="{{ $remove('max_price') }}" class="chip border">
                    {{ __('messages.filter_max_label') }}: {{ number_format((float) $maxPriceInput, 0) }} x
                </a>
            @endif
            @if(($sort ?? 'newest') !== 'newest')
                <a href="{{ $remove('sort') }}" class="chip border">
                    {{ __('messages.filter_sort_label') }}: {{ $sort === 'price_low' ? __('messages.sort_price_low') : ($sort === 'price_high' ? __('messages.sort_price_high') : __('messages.sort_most_viewed')) }} x
                </a>
            @endif
            <a href="{{ route('products.index') }}" class="btn-primary px-3 py-1.5 text-xs">
                {{ __('messages.clear_filters') }}
            </a>
        </div>
    @endif

    <section class="mt-4">
        <div class="grid grid-cols-1 min-[420px]:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($products as $product)
                @php
                    $isAvailable = (bool) ($product->is_available ?? true);
                    $inStock = (int) ($product->stock ?? 0) > 0;
                @endphp
                <a href="{{ route('products.show', $product) }}"
                   class="group glass-card hover:-translate-y-1 hover:shadow-[0_16px_30px_rgba(15,23,42,0.18)] transition duration-200 overflow-hidden">

                    <div class="relative aspect-[1/1] sm:aspect-[4/3] bg-stone-100/80 overflow-hidden">
                        <x-product-image
                            :path="$product->image"
                            :alt="$product->name"
                            class="h-full w-full object-cover group-hover:scale-[1.04] transition duration-300"
                        />
                        <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>

                    <div class="p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="font-semibold text-gray-900 line-clamp-1">{{ $product->name }}</div>
                            <div class="price-tag px-3 py-1 text-sm">
                                <x-money :amount="$product->price" />
                            </div>
                        </div>

                        @if(!$isAvailable || !$inStock)
                            <div class="mt-2 status-pill status-pill-danger text-xs">
                                {{ __('messages.home_out_of_stock') }}
                            </div>
                        @endif

                        @if($product->categories?->count())
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($product->categories as $cat)
                                <span class="chip">
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

                <div class="mt-4 text-sm font-semibold text-slate-800 group-hover:text-black">
                    {{ __('messages.view_details') }}
                </div>
            </div>
        </a>
    @empty
        <div class="col-span-full empty-state">
            <p class="font-semibold text-gray-900">{{ __('messages.no_products') }}</p>
            <p class="mt-2">{{ __('messages.products_empty_hint') }}</p>
            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="btn-primary">
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
                           class="chip hover:opacity-90 transition">
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
