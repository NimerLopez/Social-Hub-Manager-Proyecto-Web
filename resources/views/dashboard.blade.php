<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="text-center mt-5">
        <h3 class="font-semibold">Presiona cualquiera de los logos para conectarte</h3>
    </div>
    <div class="py-12 flex space-x-20 justify-center">
        <div class="bg-black overflow-hidden shadow-lg m:rounded-lg transition duration-300 transform hover:shadow-2xl">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="/connect/oaut/twitter" method="POST" class="">
                    @csrf
                    <input type="image" src="https://graffica.ams3.digitaloceanspaces.com/2023/07/rQYXqS5v-F1ySdm9WYAIbjHo-1024x1024.jpeg" alt="Imagen" class="w-56 h-56 rounded-full mx-auto">
                </form>
            </div>
        </div>
        <!-- reddit -->
        <div class="bg-black overflow-hidden shadow-lg m:rounded-lg transition duration-300 transform hover:shadow-2xl">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('reddit.auth') }}" method="get" class="">
                    @csrf
                    <input type="image" src="https://cdn3.iconfinder.com/data/icons/2018-social-media-black-and-white-logos/1000/2018_social_media_popular_app_logo_reddit-512.png" alt="Imagen" class="w-56 h-56 rounded-full mx-auto relative">
                </form>
            </div>
        </div>
        
        <!-- Linkedin -->
        <div class="bg-black overflow-hidden shadow-lg m:rounded-lg transition duration-300 transform hover:shadow-2xl">
            <div class="p-6 bg-white border-b border-gray-200">
                <form action="{{ route('linkedin.auth') }}" method="get" class="">
                    @csrf
                    <input type="image" src="https://cdn-icons-png.flaticon.com/512/61/61109.png" alt="Imagen" class="w-56 h-56 rounded-full mx-auto relative">
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
