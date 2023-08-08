@props(['selectedTime' => ''])

<div class="mb-4">
    <label for="time" class="block text-gray-700">Hora de publicación:</label>
    <input type="time" value="{{ $selectedTime }}" id="time" name="time" class="mt-1 block w-1/2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50">
</div>