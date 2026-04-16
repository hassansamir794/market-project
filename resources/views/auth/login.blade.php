<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('messages.auth_welcome_back') }}</h1>
        <p class="mt-1 text-sm text-gray-600">{{ __('messages.auth_login_subtitle') }}</p>

        <div>
            <x-input-label for="email" :value="__('messages.label_email')" class="mt-5" />
            <x-text-input id="email" class="block mt-1 w-full px-4 py-3" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('messages.label_password')" />

            <x-text-input id="password"
                            class="block mt-1 w-full px-4 py-3"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-stone-300 shadow-sm" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('messages.auth_remember_me') }}</span>
            </label>
        </div>

        <div class="auth-links">
            @if (Route::has('register'))
                <a class="auth-link-chip" href="{{ route('register') }}">
                    {{ __('messages.auth_create_account') }}
                </a>
            @endif

            @if (Route::has('password.request'))
                <a class="auth-link-chip" href="{{ route('password.request') }}">
                    {{ __('messages.auth_forgot_password') }}
                </a>
            @endif
        </div>

        <x-primary-button class="mt-5 w-full justify-center px-5 py-3">
                {{ __('messages.login') }}
        </x-primary-button>
    </form>
</x-guest-layout>
