<x-guest-layout>
    <x-auth-header />

    <x-auth-card>
    <x-slot name="logo">
            <a href="/" class="px-7 py-4 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-600 uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 ">
                Trodo Manager
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <div class="flex flex-col justify-center items-center">
            <h1 class="font-semibold text-lg">Verificación de Doble Factor(2FA)</h1>
            <p class="whitespace-nowrap mb-4 font-semibold">Escanea el código QR con tu aplicación "Authenticator"</p>
            <img src="{{ $user->google2fa_qr }}" alt="Código QR de Google Authenticator" class="w-45 h-45">
        </div>
        <form method="POST" action="/verificar2fa">
            @csrf

            <!-- 2FA Token -->
            <div>
                <x-label for="2fa_token" :value="__('2FA Token')" />
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <x-input id="2fa_token" class="block mt-1 w-full border" type="text" name="2fa_token" required autofocus onkeypress="return event.charCode >= 48 && event.charCode <= 57" />

            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Verify 2FA') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
