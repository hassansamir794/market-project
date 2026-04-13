@extends('layouts.app')

@section('title', __('messages.home_title') . ' | Rasan Market')
@section('meta_description', __('messages.home_meta_description'))

@section('content')
    <div class="mt-6 space-y-8 sm:space-y-10">
        <section class="home-hero">
            <div class="grid grid-cols-1 xl:grid-cols-[1.1fr_0.9fr] gap-8 items-center">
                <div class="home-hero-copy">
                    <span class="home-hero-kicker">{{ __('messages.home_kicker') }}</span>
                    <h1 class="home-hero-title">
                        {{ __('messages.home_hero_title') }}
                    </h1>
                    <p class="home-hero-text">
                        {{ __('messages.home_hero_text') }}
                    </p>

                    <div class="home-hero-actions">
                        <a href="{{ route('products.index') }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 font-semibold text-stone-900 shadow-sm hover:opacity-90">
                            {{ __('messages.browse_products') }}
                        </a>

                        <a href="{{ route('about') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-6 py-3.5 font-semibold text-white hover:bg-white/14">
                            {{ __('messages.about_location') }}
                        </a>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="home-hero-panel">
                        @if($isOpen)
                            <div class="status-pill status-pill-success">
                                <span class="status-dot"></span>
                                {{ __('messages.open_now') }}
                                <span class="font-medium opacity-80">({{ __('messages.daily_hours') }})</span>
                            </div>
                        @else
                            <div class="status-pill status-pill-danger">
                                <span class="status-dot"></span>
                                {{ __('messages.closed') }}
                                <span class="font-medium opacity-80">({{ __('messages.daily_hours') }})</span>
                            </div>
                        @endif

                        <div class="mt-5 home-hero-stats">
                            <div class="home-hero-stat">
                                <div class="home-hero-stat-label">{{ __('messages.home_stats_categories') }}</div>
                                <div class="home-hero-stat-value">{{ __('messages.home_stats_categories_value', ['count' => $categories->count()]) }}</div>
                            </div>
                            <div class="home-hero-stat">
                                <div class="home-hero-stat-label">{{ __('messages.home_stats_products') }}</div>
                                <div class="home-hero-stat-value">{{ __('messages.home_stats_products_value', ['count' => $latestProducts->count()]) }}</div>
                            </div>
                            <div class="home-hero-stat col-span-2 sm:col-span-1">
                                <div class="home-hero-stat-label">{{ __('messages.home_stats_ordering') }}</div>
                                <div class="home-hero-stat-value">{{ __('messages.home_stats_ordering_value') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="home-hero-panel">
                        <div class="text-sm font-semibold uppercase tracking-[0.22em] text-white/55">{{ __('messages.home_why_title') }}</div>
                        <div class="mt-3 space-y-3 text-sm text-white/78">
                            <div>{{ __('messages.home_why_1') }}</div>
                            <div>{{ __('messages.home_why_2') }}</div>
                            <div>{{ __('messages.home_why_3') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-shell">
            <div class="section-header">
                <div>
                    <h2 class="section-title">{{ __('messages.categories') }}</h2>
                    <p class="section-copy">
                        {{ __('messages.home_categories_text') }}
                    </p>
                </div>
                <a class="action-link" href="{{ route('products.index') }}">
                    {{ __('messages.view_all') }}
                </a>
            </div>

            <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                @forelse($categories as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="category-spotlight">
                        <div class="meta-label">{{ __('messages.home_category_label') }}</div>
                        <div class="mt-3 text-xl font-bold text-gray-900">{{ $cat->name }}</div>
                        <div class="mt-2 text-sm text-gray-600">
                            {{ __('messages.home_category_text') }}
                        </div>
                        <div class="category-spotlight-arrow">
                            {{ __('messages.home_browse_now') }}
                            <span>-></span>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full empty-state">
                        {{ __('messages.home_no_categories') }}
                    </div>
                @endforelse
            </div>
        </section>

        <section class="section-shell">
            <div class="section-header">
                <div>
                    <h2 class="section-title">{{ __('messages.latest_products') }}</h2>
                    <p class="section-copy">
                        {{ __('messages.home_latest_text') }}
                    </p>
                </div>
                <a class="action-link" href="{{ route('products.index') }}">
                    {{ __('messages.view_all') }}
                </a>
            </div>

            <div class="mt-5 home-products-grid">
                @forelse($latestProducts as $product)
                    @php
                        $isAvailable = (bool) ($product->is_available ?? true);
                        $inStock = (int) ($product->stock ?? 0) > 0;
                    @endphp
                    <a href="{{ route('products.show', $product) }}" class="group home-product-card">
                        <div class="aspect-[1/1] sm:aspect-[4/3] overflow-hidden bg-stone-100/80">
                            <x-product-image
                                :path="$product->image"
                                :alt="$product->name"
                                class="h-full w-full object-cover group-hover:scale-[1.04] transition duration-300"
                            />
                        </div>

                        <div class="home-product-copy">
                            <div class="flex items-start justify-between gap-3">
                                <div class="font-semibold text-gray-900 line-clamp-1">{{ $product->name }}</div>
                                <div class="price-tag px-3 py-1 text-sm">
                                    <x-money :amount="$product->price" />
                                </div>
                            </div>

                            @if(!$isAvailable || !$inStock)
                                <div class="mt-3 status-pill status-pill-danger text-xs">
                                    {{ __('messages.home_out_of_stock') }}
                                </div>
                            @endif

                            @if($product->categories?->count())
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($product->categories as $cat)
                                        <span class="chip">{{ $cat->name }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="home-product-link">
                                {{ __('messages.view_details') }}
                                <span>-></span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full empty-state">
                        {{ __('messages.home_no_products') }}
                    </div>
                @endforelse
            </div>
        </section>

        <section class="home-callout">
            <div class="home-callout-card">
                <div class="meta-label">{{ __('messages.home_ordering_kicker') }}</div>
                <h2 class="mt-3 text-2xl font-bold text-gray-900">{{ __('messages.home_ordering_title') }}</h2>
                <p class="mt-3 text-gray-600">
                    {{ __('messages.home_ordering_text') }}
                </p>
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn-primary">
                        {{ __('messages.home_explore_products') }}
                    </a>
                    <a href="{{ route('about') }}" class="btn-outline">
                        {{ __('messages.visit_contact') }}
                    </a>
                </div>
            </div>

            <div class="home-callout-dark">
                <div class="meta-label !text-white/55">{{ __('messages.home_trust_kicker') }}</div>
                <h2 class="mt-3 text-2xl font-bold">{{ __('messages.home_trust_title') }}</h2>
                <p class="mt-3">
                    {{ __('messages.home_trust_text') }}
                </p>
                <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="home-hero-stat">
                        <div class="home-hero-stat-label">{{ __('messages.home_trust_direct_contact') }}</div>
                        <div class="home-hero-stat-value">{{ __('messages.home_trust_direct_contact_value') }}</div>
                    </div>
                    <div class="home-hero-stat">
                        <div class="home-hero-stat-label">{{ __('messages.home_trust_clear_journey') }}</div>
                        <div class="home-hero-stat-value">{{ __('messages.home_trust_clear_journey_value') }}</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
