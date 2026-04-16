<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ __('messages.auth_forgot_title') }}</h1>
        <p class="mt-1 text-sm text-gray-600">{{ __('messages.auth_forgot_subtitle') }}</p>

        <div class="mt-5 surface-muted rounded-2xl border p-4">
            <div class="text-sm font-semibold text-gray-900">{{ __('messages.auth_secure_access') }}</div>
            <p class="mt-1 text-sm text-gray-600">{{ __('messages.auth_forgot_note') }}</p>
        </div>

        <div>
            <x-input-label for="email" :value="__('messages.label_email')" class="mt-5" />
            <x-text-input id="email" class="block mt-1 w-full px-4 py-3" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-5 flex items-center justify-between gap-3">
            <a class="text-sm font-semibold text-gray-700 hover:text-gray-900" href="{{ route('login') }}">
                {{ __('messages.auth_back_to_login') }}
            </a>

            <x-primary-button class="justify-center px-5 py-3">
                {{ __('messages.auth_send_reset_link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
