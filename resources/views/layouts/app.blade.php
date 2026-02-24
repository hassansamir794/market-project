@php
    $locale = app()->getLocale();
    $rtlLocales = config('localization.rtl_locales', ['ar', 'ku']);
    $isRtl = in_array($locale, $rtlLocales, true);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Market')</title>
    <meta name="description" content="@yield('meta_description', 'Market with clear product prices, categories, and fast local browsing.')">
    <meta name="robots" content="@yield('meta_robots', 'index,follow')">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'Market')">
    <meta property="og:description" content="@yield('meta_description', 'Market with clear product prices, categories, and fast local browsing.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('meta_image', asset('images/logo.jpg'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Market')">
    <meta name="twitter:description" content="@yield('meta_description', 'Market with clear product prices, categories, and fast local browsing.')">
    <meta name="twitter:image" content="@yield('meta_image', asset('images/logo.jpg'))">
    <link rel="canonical" href="{{ url()->current() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen text-gray-900 relative overflow-x-hidden">

    <div class="fixed inset-0 -z-10 overflow-hidden">
        <img
            src="{{ asset('images/background.jpg') }}"
            class="absolute inset-0 h-full w-full object-cover sm:hidden"
            alt=""
            loading="eager"
        >

        <video
            autoplay
            muted
            loop
            playsinline
            class="absolute inset-0 h-full w-full object-cover hidden sm:block"
        >
            <source src="{{ asset('images/hero.mp4') }}" type="video/mp4">
        </video>

        <div class="absolute inset-0 bg-black/60"></div>
    </div>

    <header x-data="{ mobileNavOpen: false }" class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3 min-w-0">
                <img src="{{ asset('images/logo.jpg') }}" class="h-9 sm:h-10 w-auto" alt="Market logo" loading="lazy">
                <span class="font-bold text-base sm:text-lg truncate">{{ __('messages.brand') }}</span>
            </a>

            <button
                @click="mobileNavOpen = !mobileNavOpen"
                class="md:hidden inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-semibold text-gray-700"
                type="button"
                aria-label="Toggle menu"
            >
                {{ __('messages.menu') }}
            </button>

            <nav class="hidden md:flex items-center gap-3">
                <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg hover:bg-gray-100">{{ __('messages.home') }}</a>
                <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-lg hover:bg-gray-100">{{ __('messages.products') }}</a>
                <a href="{{ route('about') }}" class="px-3 py-2 rounded-lg hover:bg-gray-100">{{ __('messages.about') }}</a>

                <div class="flex items-center gap-2">
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs rounded-lg border">EN</a>
                    <a href="{{ route('lang.switch', 'ar') }}" class="px-2 py-1 text-xs rounded-lg border">AR</a>
                    <a href="{{ route('lang.switch', 'ku') }}" class="px-2 py-1 text-xs rounded-lg border">KU</a>
                </div>

                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg bg-black text-white">
                            {{ __('messages.admin') }}
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-3 py-2 text-red-600">{{ __('messages.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 bg-black text-white rounded-lg">
                        {{ __('messages.login') }}
                    </a>
                @endauth
            </nav>
        </div>

        <div x-show="mobileNavOpen" x-transition class="md:hidden border-t border-gray-200 bg-white/95">
            <nav class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex flex-col gap-2">
                <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg hover:bg-gray-100">{{ __('messages.home') }}</a>
                <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-lg hover:bg-gray-100">{{ __('messages.products') }}</a>
                <a href="{{ route('about') }}" class="px-3 py-2 rounded-lg hover:bg-gray-100">{{ __('messages.about') }}</a>

                <div class="flex items-center gap-2 px-3 py-2">
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs rounded-lg border">EN</a>
                    <a href="{{ route('lang.switch', 'ar') }}" class="px-2 py-1 text-xs rounded-lg border">AR</a>
                    <a href="{{ route('lang.switch', 'ku') }}" class="px-2 py-1 text-xs rounded-lg border">KU</a>
                </div>

                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg bg-black text-white text-center">
                            {{ __('messages.admin') }}
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-3 py-2 text-red-600 rounded-lg hover:bg-gray-100">
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 bg-black text-white rounded-lg text-center">
                        {{ __('messages.login') }}
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-10">
        @yield('content')
    </main>

    <footer class="relative z-10 border-t bg-white/90 backdrop-blur">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 text-sm text-gray-600 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-3">
            <div>&copy; {{ date('Y') }} {{ __('messages.brand') }}</div>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('home') }}">{{ __('messages.home') }}</a>
                <a href="{{ route('products.index') }}">{{ __('messages.products') }}</a>
                <a href="{{ route('about') }}">{{ __('messages.about') }}</a>
            </div>
        </div>
    </footer>

</body>
</html>
