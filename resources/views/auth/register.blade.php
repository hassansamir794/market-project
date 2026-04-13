<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('messages.auth_register_title') }}</h1>
        <p class="mt-1 text-sm text-gray-600">{{ __('messages.auth_register_subtitle') }}</p>

        <div>
            <x-input-label for="name" :value="__('messages.label_name')" class="mt-5" />
            <x-text-input id="name" class="block mt-1 w-full px-4 py-3" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" :value="__('messages.label_email')" />
            <x-text-input id="email" class="block mt-1 w-full px-4 py-3" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('messages.label_password')" />

            <x-text-input id="password"
                            class="block mt-1 w-full px-4 py-3"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <p class="mt-1 text-xs text-gray-500">{{ __('messages.auth_password_hint') }}</p>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('messages.label_confirm_password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full px-4 py-3"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        @if(config('admin.self_registration_enabled'))
            <div class="mt-4">
                <x-input-label for="admin_code" :value="__('messages.auth_admin_code_optional')" />
                <x-text-input id="admin_code" class="block mt-1 w-full px-4 py-3" type="text" name="admin_code" :value="old('admin_code')" autocomplete="off" />
                <p class="mt-1 text-xs text-gray-500">{{ __('messages.auth_admin_code_hint') }}</p>
                <x-input-error :messages="$errors->get('admin_code')" class="mt-2" />
            </div>
        @endif

        <div class="mt-5 flex items-center justify-between">
            <a class="text-sm font-semibold text-gray-700 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('messages.auth_already_registered') }}
            </a>
        </div>

        <x-primary-button class="mt-5 w-full justify-center px-5 py-3">
            {{ __('messages.auth_register_title') }}
        </x-primary-button>
    </form>
</x-guest-layout>
