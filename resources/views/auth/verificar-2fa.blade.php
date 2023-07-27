<x-guest-layout>
    <x-auth-header />

    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="/verificar-2fa">
            @csrf

            <!-- 2FA Token -->
            <div>
                <x-label for="2fa_token" :value="__('2FA Token')" />

                <x-input id="2fa_token" class="block mt-1 w-full" type="text" name="2fa_token" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Verify 2FA') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
