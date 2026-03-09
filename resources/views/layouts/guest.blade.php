<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Market') }}</title>
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
</head>
<body class="min-h-screen text-gray-900 antialiased">
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-950 via-gray-900 to-gray-700"></div>
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute -bottom-16 -right-12 h-72 w-72 rounded-full bg-blue-300/20 blur-3xl"></div>
        <div class="absolute inset-0 bg-black/20"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="mb-6 text-center text-white">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Market logo" class="h-10 w-10 rounded-xl object-cover ring-2 ring-white/30">
                    <span class="text-xl font-bold tracking-tight">{{ __('messages.brand') }}</span>
                </a>
                <p class="mt-2 text-sm text-white/80">Secure access to your market account</p>
                <div class="mt-3">
                    <button type="button" data-theme-toggle class="theme-toggle-btn px-3 py-2 rounded-lg border border-gray-300 text-xs font-semibold">
                        Dark
                    </button>
                </div>
            </div>

            <div class="rounded-2xl border border-white/35 bg-white/92 p-6 shadow-[0_18px_36px_rgba(2,6,23,0.24)] backdrop-blur-xl">
                {{ $slot }}
            </div>
        </div>
    </div>
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
