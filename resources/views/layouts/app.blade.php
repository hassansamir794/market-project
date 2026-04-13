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
    <title>@yield('title', 'Rasan Market')</title>
    <meta name="description" content="@yield('meta_description', 'Rasan Market with clear product prices, categories, and fast local browsing.')">
    <meta name="robots" content="@yield('meta_robots', 'index,follow')">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title', 'Rasan Market')">
    <meta property="og:description" content="@yield('meta_description', 'Rasan Market with clear product prices, categories, and fast local browsing.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('meta_image', asset('images/logo.jpg'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Rasan Market')">
    <meta name="twitter:description" content="@yield('meta_description', 'Rasan Market with clear product prices, categories, and fast local browsing.')">
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

<body class="page-shell min-h-screen text-gray-900 transition-colors duration-300">

    <div class="fixed inset-0 -z-20 overflow-hidden">
        <img
            src="{{ asset('images/background.jpg') }}"
            class="absolute inset-0 h-full w-full object-cover sm:hidden opacity-20"
            alt=""
            loading="eager"
        >

        <video
            autoplay
            muted
            loop
            playsinline
            class="absolute inset-0 h-full w-full object-cover hidden sm:block opacity-[0.18]"
        >
            <source src="{{ asset('images/hero.mp4') }}" type="video/mp4">
        </video>

        <div class="absolute inset-0 page-overlay"></div>
    </div>

    <header x-data="{ mobileNavOpen: false }" class="site-header sticky top-0 z-50 border-b shell-panel">
        <div class="site-header-inner">
            <a href="{{ route('home') }}" class="brand-lockup">
                <img src="{{ asset('images/logo.jpg') }}" class="brand-mark" alt="Rasan Market logo" loading="lazy">
                <span class="brand-name">{{ __('messages.brand') }}</span>
            </a>

            <div class="mobile-header-actions">
                <button
                    @click="mobileNavOpen = !mobileNavOpen"
                    class="menu-trigger btn-outline"
                    type="button"
                    aria-label="Toggle menu"
                >
                    {{ __('messages.menu') }}
                </button>
            </div>

            <nav class="hidden md:flex items-center gap-2">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">{{ __('messages.home') }}</a>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*', 'categories.show') ? 'nav-link-active' : '' }}">{{ __('messages.products') }}</a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'nav-link-active' : '' }}">{{ __('messages.about') }}</a>

                <div class="flex items-center gap-2">
                    <a href="{{ route('lang.switch', 'en') }}" class="btn-outline px-2.5 py-1.5 text-xs">EN</a>
                    <a href="{{ route('lang.switch', 'ar') }}" class="btn-outline px-2.5 py-1.5 text-xs">AR</a>
                    <a href="{{ route('lang.switch', 'ku') }}" class="btn-outline px-2.5 py-1.5 text-xs">KU</a>
                </div>

                <button type="button" data-theme-toggle class="theme-toggle-btn px-3 py-2 text-sm font-semibold">
                    Dark
                </button>

                @auth
                    @if($isAdmin)
                        <a href="{{ route('admin.dashboard') }}" class="btn-primary px-3 py-2">
                            {{ __('messages.admin') }}
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="btn-outline px-3 py-2">
                            Notifications
                            @if($adminUnreadNotifications > 0)
                                <span class="ml-1 inline-flex items-center justify-center rounded-full bg-rose-600 text-white text-xs min-w-[18px] h-[18px] px-1">
                                    {{ $adminUnreadNotifications }}
                                </span>
                            @endif
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-3 py-2 text-sm font-semibold text-rose-700 hover:text-rose-800">{{ __('messages.logout') }}</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn-primary px-3 py-2">
                        {{ __('messages.login') }}
                    </a>
                @endauth
            </nav>
        </div>

        <div x-cloak x-show="mobileNavOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/28 backdrop-blur-[1px] md:hidden" @click="mobileNavOpen = false"></div>
        <div x-cloak x-show="mobileNavOpen" x-transition:enter="transition ease-out duration-220" x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-180" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0" class="mobile-nav-sheet">
            <nav @click.outside="mobileNavOpen = false" class="mobile-nav-panel">
                <div class="mobile-nav-group-label">Pages</div>
                <div class="mobile-nav-primary">
                    <a @click="mobileNavOpen = false" href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'mobile-nav-link-active' : '' }}">{{ __('messages.home') }}</a>
                    <a @click="mobileNavOpen = false" href="{{ route('products.index') }}" class="mobile-nav-link {{ request()->routeIs('products.*', 'categories.show') ? 'mobile-nav-link-active' : '' }}">{{ __('messages.products') }}</a>
                    <a @click="mobileNavOpen = false" href="{{ route('about') }}" class="mobile-nav-link {{ request()->routeIs('about') ? 'mobile-nav-link-active' : '' }}">{{ __('messages.about') }}</a>
                </div>

                <div class="mobile-nav-group-label pt-2">Language</div>
                <div class="mobile-nav-langs">
                    <a href="{{ route('lang.switch', 'en') }}" class="btn-outline px-2.5 py-1.5 text-xs">EN</a>
                    <a href="{{ route('lang.switch', 'ar') }}" class="btn-outline px-2.5 py-1.5 text-xs">AR</a>
                    <a href="{{ route('lang.switch', 'ku') }}" class="btn-outline px-2.5 py-1.5 text-xs">KU</a>
                </div>

                <button type="button" data-theme-toggle class="theme-toggle-btn text-sm font-semibold">
                    Dark
                </button>

                <div class="mobile-nav-group-label pt-2">Account</div>
                <div class="mobile-nav-secondary">
                    @auth
                        @if($isAdmin)
                            <a @click="mobileNavOpen = false" href="{{ route('admin.dashboard') }}" class="mobile-nav-secondary-link">
                                {{ __('messages.admin') }}
                            </a>
                            <a @click="mobileNavOpen = false" href="{{ route('admin.notifications.index') }}" class="mobile-nav-secondary-link">
                                Notifications
                                @if($adminUnreadNotifications > 0)
                                    <span class="ml-1 inline-flex items-center justify-center rounded-full bg-rose-600 text-white text-[10px] min-w-[16px] h-[16px] px-1">
                                        {{ $adminUnreadNotifications }}
                                    </span>
                                @endif
                            </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="mobile-nav-secondary-link-danger">
                                {{ __('messages.logout') }}
                            </button>
                        </form>
                    @else
                        <a @click="mobileNavOpen = false" href="{{ route('login') }}" class="mobile-nav-secondary-link">
                            {{ __('messages.login') }}
                        </a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 py-5 sm:py-10 {{ $isAdminArea ? 'pb-24 sm:pb-10' : '' }}">
        @yield('content')
    </main>

    @if($isAdminArea)
        <nav class="sm:hidden fixed bottom-3 left-3 right-3 z-40 rounded-2xl border shell-panel p-2">
            <div class="grid grid-cols-5 gap-1 text-[11px] font-semibold">
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl px-2 py-2 text-center {{ request()->routeIs('admin.dashboard') ? 'mobile-nav-link-active' : 'mobile-nav-link' }}">Home</a>
                <a href="{{ route('admin.products.index') }}" class="rounded-xl px-2 py-2 text-center {{ request()->routeIs('admin.products.*') ? 'mobile-nav-link-active' : 'mobile-nav-link' }}">Products</a>
                <a href="{{ route('admin.order-requests.index') }}" class="rounded-xl px-2 py-2 text-center {{ request()->routeIs('admin.order-requests.*') ? 'mobile-nav-link-active' : 'mobile-nav-link' }}">Orders</a>
                <a href="{{ route('admin.reviews.index') }}" class="rounded-xl px-2 py-2 text-center {{ request()->routeIs('admin.reviews.*') ? 'mobile-nav-link-active' : 'mobile-nav-link' }}">Reviews</a>
                <a href="{{ route('admin.notifications.index') }}" class="rounded-xl px-2 py-2 text-center {{ request()->routeIs('admin.notifications.*') ? 'mobile-nav-link-active' : 'mobile-nav-link' }}">
                    Alerts
                    @if($adminUnreadNotifications > 0)
                        <span class="inline-flex items-center justify-center rounded-full bg-rose-600 text-white text-[10px] min-w-[16px] h-[16px] px-1">{{ $adminUnreadNotifications }}</span>
                    @endif
                </a>
            </div>
        </nav>
    @endif

    <footer class="relative z-10 border-t shell-panel">
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
