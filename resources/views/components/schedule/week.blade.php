@props(['selectedDay' => ''])
<div class="mb-4">
    <label for="day" class="block text-gray-700">Día de la semana:</label>
    <select id="day" name="day" class="mt-1 block w-1/2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
    <option value="Monday" {{ $selectedDay === 'Monday' ? 'selected' : '' }}>Lunes</option>
            <option value="Tuesday" {{ $selectedDay === 'Tuesday' ? 'selected' : '' }}>Martes</option>
            <option value="Wednesday" {{ $selectedDay === 'Wednesday' ? 'selected' : '' }}>Miércoles</option>
            <option value="Thursday" {{ $selectedDay === 'Thursday' ? 'selected' : '' }}>Jueves</option>
            <option value="Friday" {{ $selectedDay === 'Friday' ? 'selected' : '' }}>Viernes</option>
            <option value="Saturday" {{ $selectedDay === 'Saturday' ? 'selected' : '' }}>Sábado</option>
            <option value="Sunday" {{ $selectedDay === 'Sunday' ? 'selected' : '' }}>Domingo</option>
    </select>
</div>