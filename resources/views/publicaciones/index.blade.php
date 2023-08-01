
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reddit Publicaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-lg font-semibold mb-4">Reddit</h2>
                    <img src="data:image/png;base64,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" alt="Imagen" class="w-40 h-40 rounded-full mr-4">

                    <!-- Mostrar mensajes de éxito -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Éxito!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
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

                    <form action="{{ route('reddit.post') }}" method="post">
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
                        <div>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
                       