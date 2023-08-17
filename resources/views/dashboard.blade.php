<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 flex ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col items-center justify-center">
                    <form action="/connect/oaut/twitter" method="POST" class="max-w-md w-full">
                        @csrf
                        <div class="flex items-center mb-4">
                            <img src="https://graffica.ams3.digitaloceanspaces.com/2023/07/rQYXqS5v-F1ySdm9WYAIbjHo-1024x1024.jpeg" alt="Imagen" class="w-40 h-40 rounded-full mr-4">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Conectar</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>

        <!-- reddit -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col items-center justify-center">
                    <form action="{{ route('reddit.auth') }}" method="get" class="max-w-md w-full">
                        @csrf
                        <div class="flex items-center mb-4">
                            <img src="https://cdn3.iconfinder.com/data/icons/2018-social-media-black-and-white-logos/1000/2018_social_media_popular_app_logo_reddit-512.png" alt="Imagen" class="w-40 h-40 rounded-full mr-4 relative">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Conectar</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
         <!-- Linkedin -->
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col items-center justify-center">
                    <form action="{{ route('linkedin.auth') }}" method="get" class="max-w-md w-full">
                        @csrf
                        <div class="flex items-center mb-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/61/61109.png" alt="Imagen" class="w-40 h-40 rounded-full mr-4">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Conectar</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>


    </div>
</x-app-layout>
