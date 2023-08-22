
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reddit Publicaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="sm:px-10 lg:px-10">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="w-full p-6 bg-white border-b border-gray-200 flex">
                    <h2 class="text-lg font-semibold mb-4">Reddit</h2>
                    <div class="mx-20">
                        <img src="https://cdn3.iconfinder.com/data/icons/2018-social-media-black-and-white-logos/1000/2018_social_media_popular_app_logo_reddit-512.png" alt="Imagen" class="w-40 h-40 rounded-full mr-4">
                    </div>
                    <!-- Mostrar mensajes de éxito -->
                    @if(session('success'))
                    <div class="mt-20 h-3/8 w-100 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded absolute mb-4" role="alert">
                            <strong class="font-bold">Exito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @elseif(session('error-status'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error-status') }}</span>
                        </div>
                    @endif

                    <!-- Mostrar mensajes de error -->
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Errores:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('reddit.post') }}" method="post" class="w-1/2 ml-10">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block font-semibold">Title</label>
                            <input type="text" name="title" id="title" class="w-full p-2 border rounded @error('title') border-red-500 @enderror" value="{{ old('title') }}">
                            @error('title')
                                <p class="text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="content" class="block font-semibold">Content</label>
                            <textarea name="content" id="content" class="w-full p-2 border rounded @error('content') border-red-500 @enderror" rows="4">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="subreddit" class="block font-semibold">Subreddit</label>
                            <input type="text" name="subreddit" id="subreddit" class="w-full p-2 border rounded @error('subreddit') border-red-500 @enderror" value="{{ old('subreddit') }}">
                            @error('subreddit')
                                <p class="text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <x-form.postsQueueVal></x-form.postsQueueVal>
                        
                        <div>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Submit</button>
                            <button type="submit" formaction="{{ route('send-to-post-queue-reddit') }}" class="px-4 py-2 bg-black text-white rounded">Enviar a cola</button>
                        </div>                     
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
                       