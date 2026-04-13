@extends('layouts.app')

@section('title', $product->name . ' | Rasan Market')
@section('meta_description', \Illuminate\Support\Str::limit($product->description ?: __('messages.product_meta_fallback', ['product' => $product->name]), 150))
@section('meta_image', $product->image ? asset('storage/'.$product->image) : asset('images/logo.jpg'))

@section('content')
    @php
        use Carbon\Carbon;

        $now = Carbon::now('Asia/Baghdad');
        $openTime = Carbon::createFromTime(9, 0, 0, 'Asia/Baghdad');
        $closeTime = Carbon::createFromTime(1, 0, 0, 'Asia/Baghdad');
        $isOpen = $now->between($openTime, Carbon::createFromTime(23, 59, 59, 'Asia/Baghdad'))
                || $now->between(Carbon::createFromTime(0, 0, 0, 'Asia/Baghdad'), $closeTime);
        $isAvailable = (bool) ($product->is_available ?? true);
        $inStock = (int) ($product->stock ?? 0) > 0;
        $schemaAvailability = ($isAvailable && $inStock)
            ? 'https://schema.org/InStock'
            : 'https://schema.org/OutOfStock';
        $schemaPrice = number_format((float) $product->price * (float) config('currency.rate', 1), (int) config('currency.decimals', 0), '.', '');
        $schemaCurrency = config('currency.code', 'IQD');
        $galleryImages = collect($product->gallery_images)->filter()->values();
        $galleryAssets = $galleryImages->map(function ($path) {
            $thumbPath = str_replace('products/', 'products/thumbs/', $path);
            $thumbExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($thumbPath);

            return [
                'full' => asset('storage/' . $path),
                'thumb' => asset('storage/' . ($thumbExists ? $thumbPath : $path)),
            ];
        })->values();
        $waNumber = config('contact.whatsapp');
        $rate = (float) config('currency.rate', 1);
        $safeRate = $rate > 0 ? $rate : 1;
        $displayPrice = $product->price * $safeRate;
        $currencySymbol = config('currency.symbol', 'IQD');
        $priceText = number_format($displayPrice, (int) config('currency.decimals', 0)) . ' ' . $currencySymbol;
        $waMessage = rawurlencode(
            __('messages.wa_order_greeting') . "\n"
            . __('messages.wa_order_intro') . "\n"
            . __('messages.wa_order_product_label') . ": {$product->name}\n"
            . __('messages.wa_order_price_label') . ": {$priceText}\n\n"
            . __('messages.wa_order_closing')
        );
    @endphp

    <div class="mt-6 pb-24 sm:pb-0">
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900">
            {{ __('messages.back_to_products') }}
        </a>

        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product->name,
                'image' => $product->image ? asset('storage/'.$product->image) : asset('images/logo.jpg'),
                'description' => $product->description ?: __('messages.product_meta_fallback', ['product' => $product->name]),
                'offers' => [
                    '@type' => 'Offer',
                    'priceCurrency' => $schemaCurrency,
                    'price' => $schemaPrice,
                    'availability' => $schemaAvailability,
                    'url' => url()->current(),
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>

        <div class="mt-4 product-stage">
            <div class="product-gallery-frame">
                <div
                    x-data="{ active: @js($galleryAssets->first()['full'] ?? ($product->image ? asset('storage/' . $product->image) : null)) }"
                >
                    <div class="aspect-[1/1] sm:aspect-[4/3] overflow-hidden rounded-[1.75rem] bg-stone-100/80">
                        <template x-if="active">
                            <img :src="active" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        </template>
                        <template x-if="!active">
                            <div class="flex h-full w-full items-center justify-center text-gray-400">{{ __('messages.no_image') }}</div>
                        </template>
                    </div>

                    @if($galleryAssets->count() > 1)
                        <div class="mt-4 grid grid-cols-3 gap-2.5 sm:grid-cols-5 sm:gap-3">
                            @foreach($galleryAssets as $image)
                                <button type="button" class="overflow-hidden rounded-2xl border border-stone-200/80 bg-white/70 shadow-sm" @click="active = '{{ $image['full'] }}'">
                                    <img src="{{ $image['thumb'] }}" alt="{{ $product->name }}" class="h-16 w-full object-cover sm:h-20">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="product-hero-card">
                <div class="product-hero-meta">
                    @if($isOpen)
                        <span class="status-pill status-pill-success">
                            <span class="status-dot"></span>
                            {{ __('messages.open_now') }}
                        </span>
                    @else
                        <span class="status-pill status-pill-danger">
                            <span class="status-dot"></span>
                            {{ __('messages.closed') }}
                        </span>
                    @endif

                    @if(!$isAvailable)
                        <span class="product-hero-chip">{{ __('messages.product_hidden') }}</span>
                    @elseif(!$inStock)
                        <span class="product-hero-chip">{{ __('messages.product_out_of_stock') }}</span>
                    @else
                        <span class="product-hero-chip">{{ __('messages.product_ready_today') }}</span>
                    @endif
                </div>

                <div class="mt-5">
                    <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">{{ $product->name }}</h1>
                    <p class="mt-3 max-w-xl">
                        {{ __('messages.product_intro') }}
                    </p>
                </div>

                <div class="mt-6 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div class="product-price-showcase">
                        <span class="product-price-label">{{ __('messages.product_price_label') }}</span>
                        <span class="product-price-value">{{ $priceText }}</span>
                    </div>

                    <div class="text-sm">
                        @if($averageRating > 0)
                            <div class="font-semibold text-white">{{ __('messages.product_rated', ['rating' => number_format($averageRating, 1)]) }}</div>
                        @else
                            <div class="font-semibold text-white">{{ __('messages.product_fresh_page') }}</div>
                        @endif
                        <div class="mt-1 text-white/70">{{ __('messages.product_support_text') }}</div>
                    </div>
                </div>

                <div class="mt-6 product-stat-grid">
                    <div class="product-stat">
                        <div class="product-stat-label">{{ __('messages.product_stat_availability') }}</div>
                        <div class="product-stat-value">{{ $isAvailable && $inStock ? __('messages.product_stat_availability_value') : __('messages.product_stat_availability_check') }}</div>
                    </div>
                    <div class="product-stat">
                        <div class="product-stat-label">{{ __('messages.product_stat_contact') }}</div>
                        <div class="product-stat-value">{{ __('messages.product_stat_contact_value') }}</div>
                    </div>
                    <div class="product-stat">
                        <div class="product-stat-label">{{ __('messages.product_stat_ordering') }}</div>
                        <div class="product-stat-value">{{ __('messages.product_stat_ordering_value') }}</div>
                    </div>
                </div>

                @if($product->categories?->count())
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach($product->categories as $cat)
                            <a href="{{ route('categories.show', $cat->slug) }}" class="product-hero-chip">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                @if($product->description)
                    <div class="mt-6 max-w-2xl">
                        <h2 class="text-lg font-semibold text-white">{{ __('messages.description') }}</h2>
                        <p class="mt-2 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @if(!empty($waNumber))
                        <a target="_blank"
                           href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3.5 text-sm font-semibold text-stone-900 shadow-sm hover:opacity-90">
                            {{ __('messages.order_whatsapp') }}
                        </a>
                    @endif

                    <a href="#order-request"
                       class="inline-flex items-center justify-center rounded-2xl border border-white/20 bg-white/10 px-5 py-3.5 text-sm font-semibold text-white hover:bg-white/14">
                        {{ __('messages.request_order') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-6 trust-grid">
            <div class="trust-card">
                <div class="trust-title">{{ __('messages.trust_fast_response_title') }}</div>
                <div class="trust-copy">{{ __('messages.trust_fast_response_text') }}</div>
            </div>
            <div class="trust-card">
                <div class="trust-title">{{ __('messages.trust_clear_ordering_title') }}</div>
                <div class="trust-copy">{{ __('messages.trust_clear_ordering_text') }}</div>
            </div>
            <div class="trust-card">
                <div class="trust-title">{{ __('messages.trust_easy_follow_up_title') }}</div>
                <div class="trust-copy">{{ __('messages.trust_easy_follow_up_text') }}</div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 xl:grid-cols-[1.1fr_0.9fr] gap-6">
        <div id="order-request" class="order-showcase">
            <div class="order-header">
                <div>
                    <span class="order-pill">{{ __('messages.order_simple_flow') }}</span>
                    <h2 class="mt-3 text-2xl font-bold text-gray-900">{{ __('messages.request_order') }}</h2>
                    <p class="mt-2">{{ __('messages.order_intro') }}</p>
                </div>

                <div class="order-note max-w-sm">
                    <div class="font-semibold text-gray-900">{{ __('messages.order_why_title') }}</div>
                    <div class="mt-1">{{ __('messages.order_why_text') }}</div>
                </div>
            </div>

            <div class="mt-5 order-steps">
                <div class="order-step">
                    <span class="order-step-number">1</span>
                    <div class="mt-3 font-semibold text-gray-900">{{ __('messages.order_step_1_title') }}</div>
                    <div class="mt-1 text-sm text-gray-600">{{ __('messages.order_step_1_text') }}</div>
                </div>
                <div class="order-step">
                    <span class="order-step-number">2</span>
                    <div class="mt-3 font-semibold text-gray-900">{{ __('messages.order_step_2_title') }}</div>
                    <div class="mt-1 text-sm text-gray-600">{{ __('messages.order_step_2_text') }}</div>
                </div>
                <div class="order-step">
                    <span class="order-step-number">3</span>
                    <div class="mt-3 font-semibold text-gray-900">{{ __('messages.order_step_3_title') }}</div>
                    <div class="mt-1 text-sm text-gray-600">{{ __('messages.order_step_3_text') }}</div>
                </div>
            </div>

            @if(session('order_success'))
                <div class="mt-5 rounded-2xl bg-emerald-50 px-4 py-4 text-sm text-emerald-800">
                    {{ session('order_success') }}
                    @if(session('order_whatsapp_link'))
                        <div class="mt-2">
                            <a target="_blank" class="font-semibold underline text-emerald-800" href="{{ session('order_whatsapp_link') }}">
                                {{ __('messages.order_whatsapp') }}
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <form class="mt-5 space-y-4" method="POST" action="{{ route('products.order-requests.store', $product) }}">
                @csrf
                <div class="order-form-grid">
                    <div>
                        <label class="field-label">{{ __('messages.your_name') }}</label>
                        <input class="input-clean" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div>
                        <label class="field-label">{{ __('messages.phone') }}</label>
                        <input class="input-clean" name="phone" value="{{ old('phone') }}" required>
                    </div>
                </div>
                <div class="order-form-grid">
                    <div>
                        <label class="field-label">{{ __('messages.quantity') }}</label>
                        <input class="input-clean" name="quantity" type="number" min="1" step="1" value="{{ old('quantity', 1) }}" required>
                    </div>
                    <div>
                        <label class="field-label">{{ __('messages.estimated_item_total') }}</label>
                        <div class="input-clean flex items-center font-semibold">{{ $priceText }}</div>
                    </div>
                </div>
                <div>
                    <label class="field-label">{{ __('messages.note_optional') }}</label>
                    <textarea class="textarea-clean" name="note" rows="4" placeholder="{{ __('messages.order_note_placeholder') }}">{{ old('note') }}</textarea>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button class="btn-primary px-6">{{ __('messages.send_request') }}</button>
                    @if(!empty($waNumber))
                        <a target="_blank" href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" class="btn-outline px-6">
                            {{ __('messages.order_whatsapp') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="space-y-6">
            <div class="review-panel">
                <h2 class="text-lg font-semibold">{{ __('messages.leave_review') }}</h2>

                @if(session('review_success'))
                    <div class="mt-3 rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">
                        {{ session('review_success') }}
                    </div>
                @endif

                <form class="mt-4 space-y-4" method="POST" action="{{ route('products.reviews.store', $product) }}">
                    @csrf
                    <div>
                        <label class="field-label">{{ __('messages.your_name') }}</label>
                        <input class="input-clean" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div>
                        <label class="field-label">{{ __('messages.rating') }} (1-5)</label>
                        <input class="input-clean" name="rating" type="number" min="1" max="5" step="1" value="{{ old('rating', 5) }}" required>
                    </div>
                    <div>
                        <label class="field-label">{{ __('messages.note_optional') }}</label>
                        <textarea class="textarea-clean" name="comment" rows="4">{{ old('comment') }}</textarea>
                    </div>
                    <button class="btn-primary">{{ __('messages.submit_review') }}</button>
                </form>
            </div>

            <div class="review-panel">
                <h2 class="text-lg font-semibold">{{ __('messages.recent_reviews') }}</h2>
                @forelse($reviews as $review)
                    <div class="border-b border-stone-200/70 py-3 last:border-b-0">
                        <div class="flex items-center justify-between gap-3">
                            <div class="font-semibold">{{ $review->name }}</div>
                            <div class="chip">{{ $review->rating }} / 5</div>
                        </div>
                        @if($review->comment)
                            <div class="text-sm text-gray-700 mt-2">{{ $review->comment }}</div>
                        @endif
                    </div>
                @empty
                    <div class="text-gray-500 mt-2">{{ __('messages.no_reviews') }}</div>
                @endforelse
            </div>

            <div class="review-panel">
                <h2 class="text-lg font-semibold">{{ __('messages.need_help') }}</h2>
                <p class="mt-2">{{ __('messages.need_help_text') }}</p>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a target="_blank" href="{{ config('contact.maps_url') }}" class="btn-outline w-full">{{ __('messages.location') }}</a>
                    <a href="tel:{{ config('contact.phone') }}" class="btn-primary w-full">{{ __('messages.call_market') }}</a>
                </div>
                <a href="{{ route('about') }}" class="mt-4 inline-block font-semibold underline">
                    {{ __('messages.go_to_about') }}
                </a>
            </div>
        </div>
    </div>

    <div class="sm:hidden fixed bottom-3 left-3 right-3 z-40">
        <div class="grid grid-cols-2 gap-2 rounded-2xl shell-panel p-2">
            <a href="#order-request" class="btn-primary px-4 py-3 text-sm font-semibold">
                {{ __('messages.order_now') }}
            </a>
            <a target="_blank" href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" class="btn-outline px-4 py-3 text-sm font-semibold">
                {{ __('messages.order_whatsapp') }}
            </a>
        </div>
    </div>
@endsection
