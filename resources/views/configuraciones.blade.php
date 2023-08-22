<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuraciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 text-center"> 
                                 
                    @if (Auth::user()->google2fa_enabled)
                        <p class="block text-gray-700 font-bold mb-2">Configuraciones desactivar 2 fact</p>
                        <?php $google2faEnabled = false;?>
                    @else
                        <p class="mb-4 font-bold">Configuraciones activar 2fac</p>
                        <?php $google2faEnabled = true;?>
                    @endif
                    <form action="/set/estado" method="POST" class="inline-block">
                        @csrf
                        <input type="hidden" name="google2fa_enabled" value="{{ $google2faEnabled ? '1' : '0' }}">
                        <button type="submit" class="py-2 px-4 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-md">
                                Cambiar Estado
                            </button>
                    </form>
                    @error('google2fa_enabled')
                        <p class="text-red-500 mt-2">{{ $message }}</p>
                    @enderror

                    @if (session('status'))
                        <p class="text-green-500 mt-2">{{ session('status') }}</p>
                    @endif

                    
                </div>
                <div class="p-6 bg-white border-b border-gray-200 text-center"> 
                    <div>
                        <form action="/set/keys" method="POST">
                            @csrf                           
                            <button type="submit" class="py-2 px-4 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-md">
                                Cambiar Claves
                            </button>
                        </form>
                        @if (session('status-change'))
                            <p class="text-red-500 mt-2">{{ session('status-change') }}</p>
                        @endif
                    </div>                        
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
