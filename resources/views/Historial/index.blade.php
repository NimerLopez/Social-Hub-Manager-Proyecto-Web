
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historial') }}
        </h2>
    </x-slot>
    <table class="table-auto w-full mt-10 bg-gray-400">
    <thead>
        <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Tipo</th>
            <th class="px-4 py-2">Mensaje</th>
            <th class="px-4 py-2">Fecha/hora</th>
        </tr>
    </thead>
    <tbody>
        @foreach($historial as $registro)
        <tr class="{{ $registro->tipo === 'status' ? 'bg-green-600' : 'bg-red-500' }} text-white">
            <td class="px-4 py-2 items-center text-center">{{ $registro->id }}</td>
            <td class="px-4 py-2 items-center text-center">{{ $registro->tipo }}</td>
            <td class="px-4 py-2 items-center text-center">{{ $registro->mensaje }}</td>
            <td class="px-4 py-2 items-center text-center">{{ $registro->fecha }}</td>
        </tr>

        @endforeach
    </tbody>
</table>
</x-app-layout>
                       