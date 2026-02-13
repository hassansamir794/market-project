<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Market')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen text-gray-900 relative overflow-x-hidden">

    <!-- 🌍 Background Video -->
    <div class="fixed inset-0 -z-10 overflow-hidden">

        <!-- Fallback image (mobile safe) -->
        <img
            src="{{ asset('images/background.jpg') }}"
            class="absolute inset-0 h-full w-full object-cover sm:hidden"
            alt=""
        >

        <!-- Video -->
        <video
            autoplay
            muted
            loop
            playsinline
            class="absolute inset-0 h-full w-full object-cover hidden sm:block"
        >
            <source src="{{ asset('images/hero.mp4') }}" type="video/mp4">
        </video>

        <!-- Dark overlay -->
        <div class="absolute inset-0 bg-black/60"></div>
    </div>

    <!-- Top bar -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">

            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.jpg') }}" class="h-10 w-auto" alt="">
                <span class="font-bold text-lg">Market</span>
            </a>

            <nav class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg hover:bg-gray-100">Home</a>
                <a href="{{ route('products.index') }}" class="px-3 py-2 rounded-lg hover:bg-gray-100">Products</a>
                <a href="{{ route('about') }}" class="px-3 py-2 rounded-lg hover:bg-gray-100">About</a>

                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.products.index') }}"
                           class="px-3 py-2 rounded-lg bg-black text-white">
                            Admin
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-3 py-2 text-red-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 bg-black text-white rounded-lg">
                        Login
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Page content -->
    <main class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 py-10">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t bg-white/90 backdrop-blur">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 text-sm text-gray-600 flex justify-between">
            <div>© {{ date('Y') }} Market</div>
            <div class="flex gap-4">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('products.index') }}">Products</a>
                <a href="{{ route('about') }}">About</a>
            </div>
        </div>
    </footer>

</body>
</html>
