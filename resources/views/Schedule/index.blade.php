<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Horarios de Cola') }}
        </h2>
    </x-slot>
    <div class="mt-8 grid grid-cols-2 gap-8">
            <div class="ml-20 border-r-2 border-gray-300">
            <h3 class="text-2xl font-semibold text-gray-800 w-3/4 mb-6 border-b-2 border-gray-300 pb-5">Crear Horario:</h3>   
            <form action="{{ route('post-new-schedule') }}" method="POST" >
                @csrf
                <x-schedule.week ></x-schedule-week>
                <x-schedule.hour></x-schedule-hour>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-5">Crear publicación</button>
            </form>    
            </div>     
        <div class="mr-14">
            <h3 class="text-2xl font-semibold text-gray-800 mb-4 w-3/4 mb-4 border-b-2 border-gray-300 pb-5">Horarios Registrados:</h3>
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2 bg-gray-100">Día</th>
                        <th class="px-4 py-2 bg-gray-100">Hora</th>
                        <th class="px-4 py-2 bg-gray-100">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $sche)
                    <tr>
                        <td class="w-1/3 px-4 py-2 border border-gray-300 items-center text-center">{{ $sche->day }}</td>
                        <td class="w-1/3 px-4 py-2 border border-gray-300 items-center text-center">{{ $sche->hour }}</td>
                        <td class="w-1/3 px-4 py-2 border border-gray-300 items-center text-center">
                        <div class="flex items-center justify-end">
                            <a href="/schedule/delete/{{ $sche->id}}" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 mr-2">Eliminar</a>
                            <a href="/schedule/update/view/{{ $sche->id }}" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Modificar</a>
                        </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="Menssage">
        @if($errors->any())
        <div class="bg-red-500 text-white p-4 mb-4">
                <ul class="list-disc list-inside">
                 @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                 @endforeach
                </ul>
            </div>
        @endif

<!-- Mostrar mensaje de éxito -->
        @if(session('status'))
            <div class="bg-green-500 text-white p-4 mb-4">
                {{ session('status') }}
            </div>
        @endif
        </div>
    </div>
</x-app-layout>