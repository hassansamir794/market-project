<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Create account</h1>
        <p class="mt-1 text-sm text-gray-600">Register a customer account, or use admin code for admin access.</p>

        <div>
            <x-input-label for="name" :value="__('Name')" class="mt-5" />
            <x-text-input id="name" class="block mt-1 w-full rounded-xl border-gray-300 px-4 py-3 focus:border-black focus:ring-black" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border-gray-300 px-4 py-3 focus:border-black focus:ring-black" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password"
                            class="block mt-1 w-full rounded-xl border-gray-300 px-4 py-3 focus:border-black focus:ring-black"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <p class="mt-1 text-xs text-gray-500">Use at least 10 chars with uppercase, lowercase, number, and symbol.</p>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-xl border-gray-300 px-4 py-3 focus:border-black focus:ring-black"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="admin_code" value="Admin Code (Optional)" />
            <x-text-input id="admin_code" class="block mt-1 w-full rounded-xl border-gray-300 px-4 py-3 focus:border-black focus:ring-black" type="text" name="admin_code" :value="old('admin_code')" autocomplete="off" />
            <p class="mt-1 text-xs text-gray-500">Only enter this if you should be an admin.</p>
            <x-input-error :messages="$errors->get('admin_code')" class="mt-2" />
        </div>

        <div class="mt-5 flex items-center justify-between">
            <a class="text-sm font-semibold text-gray-700 hover:text-black" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </div>

        <x-primary-button class="mt-5 w-full justify-center rounded-xl bg-black px-5 py-3 text-sm font-semibold normal-case tracking-normal hover:bg-gray-900 focus:bg-gray-900 active:bg-black focus:ring-black">
            {{ __('Register') }}
        </x-primary-button>
    </form>
</x-guest-layout>
