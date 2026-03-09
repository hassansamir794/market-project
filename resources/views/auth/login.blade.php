<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Welcome back</h1>
        <p class="mt-1 text-sm text-gray-600">Log in to manage your products and orders.</p>

        <div>
            <x-input-label for="email" :value="__('Email')" class="mt-5" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border-gray-300 px-4 py-3 focus:border-black focus:ring-black" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password"
                            class="block mt-1 w-full rounded-xl border-gray-300 px-4 py-3 focus:border-black focus:ring-black"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-black shadow-sm focus:ring-black" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-5 flex items-center justify-between">
            @if (Route::has('register'))
                <a class="text-sm font-semibold text-gray-700 hover:text-black" href="{{ route('register') }}">
                    Create account
                </a>
            @endif

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-black" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <x-primary-button class="mt-5 w-full justify-center rounded-xl bg-black px-5 py-3 text-sm font-semibold normal-case tracking-normal hover:bg-gray-900 focus:bg-gray-900 active:bg-black focus:ring-black">
                {{ __('Log in') }}
        </x-primary-button>
    </form>
</x-guest-layout>
