@extends('layouts.app')

@section('title', $product->name . ' | Market')
@section('meta_description', \Illuminate\Support\Str::limit($product->description ?: ('Buy ' . $product->name . ' from Market.'), 150))
@section('meta_image', $product->image ? asset('storage/'.$product->image) : asset('images/logo.jpg'))

@section('content')
    @php
        use Carbon\Carbon;

        $now = Carbon::now('Asia/Baghdad');
        $openTime = Carbon::createFromTime(9, 0, 0, 'Asia/Baghdad');
        $closeTime = Carbon::createFromTime(1, 0, 0, 'Asia/Baghdad');
        $isOpen = $now->between($openTime, Carbon::createFromTime(23, 59, 59, 'Asia/Baghdad'))
                || $now->between(Carbon::createFromTime(0, 0, 0, 'Asia/Baghdad'), $closeTime);
@endphp

    <div class="mt-6">
        @php
            $isAvailable = (bool) ($product->is_available ?? true);
            $inStock = (int) ($product->stock ?? 0) > 0;
            $schemaAvailability = ($isAvailable && $inStock)
                ? 'https://schema.org/InStock'
                : 'https://schema.org/OutOfStock';
            $schemaPrice = number_format((float) $product->price * (float) config('currency.rate', 1), (int) config('currency.decimals', 0), '.', '');
            $schemaCurrency = config('currency.code', 'IQD');
        @endphp

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
                'description' => $product->description ?: ('Buy ' . $product->name . ' from Market.'),
                'offers' => [
                    '@type' => 'Offer',
                    'priceCurrency' => $schemaCurrency,
                    'price' => $schemaPrice,
                    'availability' => $schemaAvailability,
                    'url' => url()->current(),
                ],
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>

        <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="glass-card overflow-hidden">
                <div class="aspect-[1/1] sm:aspect-[4/3] bg-gray-100">
                    <x-product-image
                        :path="$product->image"
                        :alt="$product->name"
                        class="h-full w-full object-cover"
                    />
                </div>
            </div>

            <div class="glass-card p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">{{ $product->name }}</h1>

                    @if($isOpen)
                        <span class="shrink-0 inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800">
                            <span class="h-2 w-2 rounded-full bg-green-600"></span>
                            {{ __('messages.open_now') }}
                        </span>
                    @else
                        <span class="shrink-0 inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800">
                            <span class="h-2 w-2 rounded-full bg-red-600"></span>
                            {{ __('messages.closed') }}
                        </span>
                    @endif
                </div>

                <div class="mt-3 inline-flex items-center gap-2 rounded-2xl bg-black text-white px-4 py-2 font-semibold">
                    <x-money :amount="$product->price" />
                </div>

                @if($averageRating > 0)
                    <div class="mt-3 text-sm text-gray-700">
                        {{ __('messages.rating') }}: <span class="font-semibold">{{ number_format($averageRating, 1) }}</span> / 5
                    </div>
                @endif

                @if(!$isAvailable)
                    <div class="mt-3 inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-semibold text-gray-700">
                        Not available
                    </div>
                @elseif(!$inStock)
                    <div class="mt-3 inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800">
                        Out of stock
                    </div>
                @endif

                @if($product->categories?->count())
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($product->categories as $cat)
                            <a href="{{ route('categories.show', $cat->slug) }}"
                               class="inline-flex items-center rounded-full bg-white/70 px-3 py-1 text-sm font-semibold text-gray-700 hover:bg-white transition">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                @if($product->description)
                    <div class="mt-6">
                        <h2 class="text-lg font-semibold">{{ __('messages.description') }}</h2>
                        <p class="mt-2 text-gray-700 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <a target="_blank"
                       href="{{ config('contact.maps_url') }}"
                       class="inline-flex items-center justify-center rounded-xl glass-card bg-white/70 px-4 py-3 font-semibold hover:bg-white transition">
                        {{ __('messages.location') }}
                    </a>

                    <a href="tel:{{ config('contact.phone') }}"
                       class="inline-flex items-center justify-center rounded-xl bg-black text-white px-4 py-3 font-semibold hover:opacity-90 transition">
                        {{ __('messages.call_market') }}
                    </a>
                </div>

                <div class="mt-6 glass-card p-4 bg-white/70 text-sm text-gray-700">
                    <div class="font-semibold">{{ __('messages.need_help') }}</div>
                    <div class="mt-1">
                        Visit the About page for our address, contact details, and opening hours.
                    </div>
                    <a href="{{ route('about') }}" class="mt-2 inline-block font-semibold underline">
                        {{ __('messages.go_to_about') }}
                    </a>
                </div>

                @php
                    $waNumber = config('contact.whatsapp');
                    $rate = (float) config('currency.rate', 1);
                    $safeRate = $rate > 0 ? $rate : 1;
                    $displayPrice = $product->price * $safeRate;
                    $currencySymbol = config('currency.symbol', 'IQD');
                    $priceText = number_format($displayPrice, (int) config('currency.decimals', 0)) . ' ' . $currencySymbol;
                    $waMessage = urlencode('Hello, I want to order: ' . $product->name . ' (ID: ' . $product->id . '). Price: ' . $priceText . '.');
                @endphp

                @if(!empty($waNumber))
                    <a target="_blank"
                       href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}"
                       class="mt-4 inline-flex items-center justify-center rounded-xl bg-green-600 text-white px-4 py-3 font-semibold hover:opacity-90 transition">
                        {{ __('messages.order_whatsapp') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="glass-card p-6">
            <h2 class="text-lg font-semibold">{{ __('messages.request_order') }}</h2>

            @if(session('order_success'))
                <div class="mt-3 rounded-xl bg-green-50 text-green-800 px-4 py-3 text-sm">
                    {{ session('order_success') }}
                    @if(session('order_whatsapp_link'))
                        <div class="mt-2">
                            <a target="_blank" class="underline text-green-800" href="{{ session('order_whatsapp_link') }}">
                                {{ __('messages.order_whatsapp') }}
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            <form class="mt-4 space-y-4" method="POST" action="{{ route('products.order-requests.store', $product) }}">
                @csrf
                <div>
                    <label class="block mb-1 font-semibold">{{ __('messages.your_name') }}</label>
                    <input class="w-full border rounded-xl p-3" name="name" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="block mb-1 font-semibold">{{ __('messages.phone') }}</label>
                    <input class="w-full border rounded-xl p-3" name="phone" value="{{ old('phone') }}" required>
                </div>
                <div>
                    <label class="block mb-1 font-semibold">{{ __('messages.quantity') }}</label>
                    <input class="w-full border rounded-xl p-3" name="quantity" type="number" min="1" step="1" value="{{ old('quantity', 1) }}" required>
                </div>
                <div>
                    <label class="block mb-1 font-semibold">{{ __('messages.note_optional') }}</label>
                    <textarea class="w-full border rounded-xl p-3" name="note" rows="3">{{ old('note') }}</textarea>
                </div>
                <button class="px-5 py-3 rounded-xl bg-black text-white font-semibold">{{ __('messages.send_request') }}</button>
            </form>
        </div>

        <div class="glass-card p-6">
            <h2 class="text-lg font-semibold">{{ __('messages.leave_review') }}</h2>

            @if(session('review_success'))
                <div class="mt-3 rounded-xl bg-green-50 text-green-800 px-4 py-3 text-sm">
                    {{ session('review_success') }}
                </div>
            @endif

            <form class="mt-4 space-y-4" method="POST" action="{{ route('products.reviews.store', $product) }}">
                @csrf
                <div>
                    <label class="block mb-1 font-semibold">{{ __('messages.your_name') }}</label>
                    <input class="w-full border rounded-xl p-3" name="name" value="{{ old('name') }}" required>
                </div>
                <div>
                    <label class="block mb-1 font-semibold">{{ __('messages.rating') }} (1-5)</label>
                    <input class="w-full border rounded-xl p-3" name="rating" type="number" min="1" max="5" step="1" value="{{ old('rating', 5) }}" required>
                </div>
                <div>
                    <label class="block mb-1 font-semibold">{{ __('messages.note_optional') }}</label>
                    <textarea class="w-full border rounded-xl p-3" name="comment" rows="4">{{ old('comment') }}</textarea>
                </div>
                <button class="px-5 py-3 rounded-xl bg-black text-white font-semibold">{{ __('messages.submit_review') }}</button>
            </form>
        </div>

        <div class="glass-card p-6">
            <h2 class="text-lg font-semibold">{{ __('messages.recent_reviews') }}</h2>
            @forelse($reviews as $review)
                <div class="border-b py-3">
                    <div class="font-semibold">{{ $review->name }}</div>
                    <div class="text-sm text-gray-600">{{ __('messages.rating') }}: {{ $review->rating }} / 5</div>
                    @if($review->comment)
                        <div class="text-sm text-gray-700 mt-1">{{ $review->comment }}</div>
                    @endif
                </div>
            @empty
                <div class="text-gray-500 mt-2">{{ __('messages.no_reviews') }}</div>
            @endforelse
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
