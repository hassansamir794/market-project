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
<body class="page-shell min-h-screen text-gray-900 antialiased">
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 page-overlay"></div>
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/35 blur-3xl"></div>
        <div class="absolute -bottom-16 -right-12 h-72 w-72 rounded-full bg-emerald-200/25 blur-3xl"></div>
    </div>

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="mb-6 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Rasan Market logo" class="h-11 w-11 rounded-2xl object-cover ring-1 ring-white/70 shadow-sm">
                    <span class="text-xl font-bold tracking-tight">{{ __('messages.brand') }}</span>
                </a>
                <p class="mt-2 text-sm">Secure access to your market account</p>
                <div class="mt-3">
                    <button type="button" data-theme-toggle class="theme-toggle-btn px-3 py-2 text-xs font-semibold">
                        Dark
                    </button>
                </div>
            </div>

            <div class="form-panel">
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
