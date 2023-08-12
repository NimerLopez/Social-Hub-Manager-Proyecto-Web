<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('LinkedIn Publicaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-lg font-semibold mb-4">LinkedIn</h2>
                    <img src="https://cdn-icons-png.flaticon.com/512/61/61109.png"alt="Imagen" class="w-40 h-40 rounded-full mr-4">

                    <!-- Mostrar mensajes de éxito -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
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

                    <form action="{{ route('linkedin.post') }}" method="post">
                        @csrf                       
                        <div class="mb-4">
                            <label for="texto" class="block font-semibold">Mensaje</label>
                            <textarea name="linkedin_text" id="linkedin_text" rows="4" class="w-full p-2 border rounded @error('linkedin_text') border-red-500 @enderror">{{ old('linkedin_text') }}</textarea>
                            @error('linkedin_text')
                                <p class="text-red-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <x-form.postsQueueVal></x-form.postsQueueVal>
                        <div>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Submit</button>
                            <button type="submit" formaction="{{ route('send-to-post-queue-linkedin') }}" class="px-4 py-2 bg-black text-white rounded">SendToPostQueue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
                       