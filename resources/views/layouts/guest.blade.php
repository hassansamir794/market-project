@php
    $locale = app()->getLocale();
    $rtlLocales = config('localization.rtl_locales', ['ar', 'ku']);
    $isRtl = in_array($locale, $rtlLocales, true);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $locale) }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Market') }}</title>
    {!! app(\App\Support\ViteAssetResolver::class)->tags(['resources/css/app.css', 'resources/js/app.js']) !!}
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('theme-dark');
            }
        })();
    </script>
    <style>
        [x-cloak]{display:none !important;}
        html{color-scheme:light}
        html.theme-dark{color-scheme:dark}
        body{margin:0;background:#f4efe7;color:#1d1a16}
        .guest-shell{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1rem}
        .guest-wrap{width:100%;max-width:28rem}
        .guest-brand{margin-bottom:1.5rem;text-align:center}
        .guest-brand a{display:inline-flex;align-items:center;gap:.75rem;text-decoration:none;color:#1d1a16}
        .guest-brand img{width:2.75rem;height:2.75rem;border-radius:1rem;object-fit:cover}
        .guest-brand-copy{margin-top:.5rem;font-size:.9rem;color:#66594b}
        .form-panel{background:rgba(255,251,247,.92);border:1px solid rgba(96,75,51,.14);border-radius:1.5rem;padding:1.5rem;box-shadow:0 14px 32px rgba(60,43,24,.10)}
        .input-clean{width:100%;box-sizing:border-box;border:1px solid rgba(96,75,51,.22);background:rgba(255,255,255,.88);color:#1d1a16;border-radius:.85rem;padding:.8rem .95rem}
        .btn-primary{display:inline-flex;align-items:center;justify-content:center;width:100%;border:none;border-radius:.9rem;padding:.9rem 1rem;background:linear-gradient(135deg,#2f5d50,#3c7665);color:#f8f5f1;font-weight:700;text-decoration:none}
        .theme-toggle-btn{border:1px solid rgba(96,75,51,.14);background:rgba(255,251,247,.92);color:#1d1a16;border-radius:.75rem;padding:.6rem .9rem}
        .auth-links{display:flex;flex-wrap:wrap;gap:.75rem;align-items:center;justify-content:space-between;margin-top:1.25rem;padding-top:1rem;border-top:1px solid rgba(96,75,51,.10)}
        .auth-link-chip{display:inline-flex;align-items:center;justify-content:center;padding:.72rem 1rem;border-radius:999px;background:rgba(255,255,255,.72);border:1px solid rgba(96,75,51,.12);color:#2f5d50;text-decoration:none;font-weight:700;font-size:.92rem}
        .auth-link-chip:hover{background:rgba(255,255,255,.96);color:#22463c}
        html.theme-dark body{background:#16120f;color:#f4ede5}
        html.theme-dark .guest-brand a{color:#f4ede5}
        html.theme-dark .guest-brand-copy{color:#d0c0af}
        html.theme-dark .form-panel{background:rgba(33,28,23,.9);border-color:rgba(212,192,163,.14);box-shadow:0 18px 36px rgba(0,0,0,.34)}
        html.theme-dark .input-clean{background:rgba(41,35,30,.82);border-color:rgba(212,192,163,.24);color:#f4ede5}
        html.theme-dark .theme-toggle-btn{background:rgba(41,35,30,.96);border-color:rgba(212,192,163,.24);color:#f4ede5}
        html.theme-dark .btn-primary{background:linear-gradient(135deg,#89b2a3,#6c9d8c);color:#10231d}
        html.theme-dark .auth-links{border-top-color:rgba(212,192,163,.12)}
        html.theme-dark .auth-link-chip{background:rgba(41,35,30,.84);border-color:rgba(212,192,163,.18);color:#b7e2cf}
        html.theme-dark .auth-link-chip:hover{background:rgba(41,35,30,.98);color:#d5eee4}
    </style>
</head>
<body class="page-shell min-h-screen text-gray-900 antialiased">
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 page-overlay"></div>
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/35 blur-3xl"></div>
        <div class="absolute -bottom-16 -right-12 h-72 w-72 rounded-full bg-emerald-200/25 blur-3xl"></div>
    </div>

    <div class="guest-shell min-h-screen flex items-center justify-center p-4">
        <div class="guest-wrap w-full max-w-md">
            <div class="guest-brand mb-6 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Rasan Market logo" class="h-11 w-11 rounded-2xl object-cover ring-1 ring-white/70 shadow-sm">
                    <span class="text-xl font-bold tracking-tight">{{ __('messages.brand') }}</span>
                </a>
                <p class="guest-brand-copy mt-2 text-sm">Secure access to your market account</p>
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
