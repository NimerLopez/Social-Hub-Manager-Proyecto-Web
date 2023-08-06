<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Horarios de Cola') }}
    </h2>
</x-slot>
<div class="max-w-md mx-auto mt-8">
    <form action="#" method="POST">
        @csrf

        <div class="mb-4">
            <label for="day" class="block text-gray-700">Día de la semana:</label>
            <select id="day" name="day" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
                <option value="1">Lunes</option>
                <option value="2">Martes</option>
                <option value="3">Miércoles</option>
                <option value="4">Jueves</option>
                <option value="5">Viernes</option>
                <option value="6">Sábado</option>
                <option value="0">Domingo</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="time" class="block text-gray-700">Hora de publicación:</label>
            <input type="time" id="time" name="time" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Crear publicación</button>
    </form>
</div>
</x-app-layout>