@php
    $locale = app()->getLocale();
    $rtlLocales = config('localization.rtl_locales', ['ar', 'ku']);
    $isRtl = in_array($locale, $rtlLocales, true);
    $isAdmin = auth()->check() && (bool) auth()->user()->is_admin;
    $isAdminArea = $isAdmin && request()->routeIs('admin.*');
    $adminUnreadNotifications = $isAdmin
        ? (\App\Models\OrderRequest::whereNull('admin_seen_at')->count() + \App\Models\Review::whereNull('admin_seen_at')->count())
        : 0;
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
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('theme-dark');
            }
        })();
    </script>
    <style>[x-cloak]{display:none !important;}</style>
</head>

<body class="min-h-screen text-gray-900 relative overflow-x-hidden transition-colors duration-300">

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

    <header x-data="{ mobileNavOpen: false }" class="sticky top-0 z-50 border-b border-white/50 bg-white/78 backdrop-blur-xl shadow-[0_4px_18px_rgba(15,23,42,0.08)]">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between gap-3">
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

            <nav class="hidden md:flex items-center gap-2">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">{{ __('messages.home') }}</a>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*', 'categories.show') ? 'nav-link-active' : '' }}">{{ __('messages.products') }}</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'nav-link-active' : '' }}">{{ __('messages.about') }}</a>

                <div class="flex items-center gap-2">
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs rounded-lg border">EN</a>
                    <a href="{{ route('lang.switch', 'ar') }}" class="px-2 py-1 text-xs rounded-lg border">AR</a>
                    <a href="{{ route('lang.switch', 'ku') }}" class="px-2 py-1 text-xs rounded-lg border">KU</a>
                </div>

                <button type="button" data-theme-toggle class="theme-toggle-btn px-3 py-2 rounded-lg border border-gray-300 text-sm font-semibold">
                    Dark
                </button>

                @auth
                    @if($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg bg-black text-white">
                            {{ __('messages.admin') }}
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="px-3 py-2 rounded-lg border border-gray-300 font-semibold">
                            Notifications
                            @if($adminUnreadNotifications > 0)
                                <span class="ml-1 inline-flex items-center justify-center rounded-full bg-red-600 text-white text-xs min-w-[18px] h-[18px] px-1">
                                    {{ $adminUnreadNotifications }}
                                </span>
                            @endif
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

        <div x-cloak x-show="mobileNavOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/35 md:hidden" @click="mobileNavOpen = false"></div>
        <div x-cloak x-show="mobileNavOpen" x-transition class="md:hidden border-t border-gray-200 bg-white/95 relative z-50">
            <nav @click.outside="mobileNavOpen = false" class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex flex-col gap-2">
                <a @click="mobileNavOpen = false" href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'mobile-nav-link-active' : '' }}">{{ __('messages.home') }}</a>
                <a @click="mobileNavOpen = false" href="{{ route('products.index') }}" class="mobile-nav-link {{ request()->routeIs('products.*', 'categories.show') ? 'mobile-nav-link-active' : '' }}">{{ __('messages.products') }}</a>
                <a @click="mobileNavOpen = false" href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'mobile-nav-link-active' : '' }}">{{ __('messages.about') }}</a>

                <div class="flex items-center gap-2 px-3 py-2">
                    <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 text-xs rounded-lg border">EN</a>
                    <a href="{{ route('lang.switch', 'ar') }}" class="px-2 py-1 text-xs rounded-lg border">AR</a>
                    <a href="{{ route('lang.switch', 'ku') }}" class="px-2 py-1 text-xs rounded-lg border">KU</a>
                </div>

                <button type="button" data-theme-toggle class="theme-toggle-btn mx-3 px-3 py-2 rounded-lg border border-gray-300 text-sm font-semibold text-left">
                    Dark
                </button>

                @auth
                    @if($isAdmin)
                        <a @click="mobileNavOpen = false" href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg bg-black text-white text-center">
                            {{ __('messages.admin') }}
                        </a>
                        <a @click="mobileNavOpen = false" href="{{ route('admin.notifications.index') }}" class="px-3 py-2 rounded-lg border text-center">
                            Notifications
                            @if($adminUnreadNotifications > 0)
                                <span class="ml-1 inline-flex items-center justify-center rounded-full bg-red-600 text-white text-xs min-w-[18px] h-[18px] px-1">
                                    {{ $adminUnreadNotifications }}
                                </span>
                            @endif
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-3 py-2 text-red-600 rounded-lg hover:bg-gray-100">
                            {{ __('messages.logout') }}
                        </button>
                    </form>
                @else
                    <a @click="mobileNavOpen = false" href="{{ route('login') }}" class="px-3 py-2 bg-black text-white rounded-lg text-center">
                        {{ __('messages.login') }}
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 py-7 sm:py-10 {{ $isAdminArea ? 'pb-24 sm:pb-10' : '' }}">
        @yield('content')
    </main>

    @if($isAdminArea)
        <nav class="sm:hidden fixed bottom-3 left-3 right-3 z-40 rounded-2xl border bg-white/95 backdrop-blur shadow-lg p-2">
            <div class="grid grid-cols-5 gap-1 text-[11px] font-semibold">
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl px-2 py-2 text-center {{ request()->routeIs('admin.dashboard') ? 'bg-black text-white' : 'text-gray-700' }}">Home</a>
                <a href="{{ route('admin.products.index') }}" class="rounded-xl px-2 py-2 text-center {{ request()->routeIs('admin.products.*') ? 'bg-black text-white' : 'text-gray-700' }}">Products</a>
                <a href="{{ route('admin.order-requests.index') }}" class="rounded-xl px-2 py-2 text-center {{ request()->routeIs('admin.order-requests.*') ? 'bg-black text-white' : 'text-gray-700' }}">Orders</a>
                <a href="{{ route('admin.reviews.index') }}" class="rounded-xl px-2 py-2 text-center {{ request()->routeIs('admin.reviews.*') ? 'bg-black text-white' : 'text-gray-700' }}">Reviews</a>
                <a href="{{ route('admin.notifications.index') }}" class="rounded-xl px-2 py-2 text-center {{ request()->routeIs('admin.notifications.*') ? 'bg-black text-white' : 'text-gray-700' }}">
                    Alerts
                    @if($adminUnreadNotifications > 0)
                        <span class="inline-flex items-center justify-center rounded-full bg-red-600 text-white text-[10px] min-w-[16px] h-[16px] px-1">{{ $adminUnreadNotifications }}</span>
                    @endif
                </a>
            </div>
        </nav>
    @endif

    <footer class="relative z-10 border-t border-white/45 bg-white/78 backdrop-blur-xl">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 text-sm text-gray-600 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-3">
            <div>&copy; {{ date('Y') }} {{ __('messages.brand') }}</div>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('home') }}">{{ __('messages.home') }}</a>
                <a href="{{ route('products.index') }}">{{ __('messages.products') }}</a>
                <a href="{{ route('about') }}">{{ __('messages.about') }}</a>
            </div>
        </div>
    </footer>

    <script>
        (function () {
            const root = document.documentElement;
            const buttons = document.querySelectorAll('[data-theme-toggle]');
            const setLabel = () => {
                const isDark = root.classList.contains('theme-dark');
                buttons.forEach((btn) => {
                    btn.textContent = isDark ? 'Light' : 'Dark';
                });
            };

            buttons.forEach((btn) => {
                btn.addEventListener('click', function () {
                    root.classList.toggle('theme-dark');
                    localStorage.setItem('theme', root.classList.contains('theme-dark') ? 'dark' : 'light');
                    setLabel();
                });
            });

            setLabel();
        })();
    </script>

</body>
</html>
