<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Horarios de Cola') }}
        </h2>
    </x-slot>
    <div class="mx-auto mt-8 flex justify-center h-screen">

            <div>
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Modificar Horario:</h3>   
            <form action="{{ route('schedule.update', ['id' => $schedule->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <x-schedule.week :selectedDay="$schedule->day"></x-schedule-week>
                <x-schedule.hour :selectedTime="$schedule->hour"></x-schedule-hour>
                <div class="flex gap-4 mt-4">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Modificar publicación</button>
                    <a href="/shedule/create" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">Regresar</a>
                </div>
            </form>    
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