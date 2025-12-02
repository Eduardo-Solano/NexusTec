<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Eventos NexusTec') }}
            </h2>

            @can('events.create')
                <a href="{{ route('events.create') }}"
                    class="bg-ito-orange hover:bg-orange-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition transform hover:scale-105 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Nuevo Evento
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"
                    role="alert">
                    <p class="font-bold">¡Éxito!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($events as $event)
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700 hover:shadow-2xl transition duration-300 group">

                        <div class="h-2 bg-gradient-to-r from-tecnm-blue to-ito-orange"></div>

                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <span
                                    class="px-3 py-1 text-xs font-bold rounded-full {{ $event->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800' }}">
                                    {{ $event->is_active ? '🟢 Activo' : '🔴 Finalizado' }}
                                </span>

                                <div class="text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </div>
                            </div>

                            <h3
                                class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-ito-orange transition">
                                {{ $event->name }}
                            </h3>

                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 h-12 overflow-hidden">
                                {{ Str::limit($event->description ?? 'Sin descripción disponible para este evento.', 90) }}
                            </p>

                            <div
                                class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6 bg-gray-50 dark:bg-gray-700 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-tecnm-blue dark:text-blue-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $event->start_date->format('d M') }} -
                                    {{ $event->end_date->format('d M, Y') }}</span>
                            </div>

                            <div
                                class="flex justify-between items-center border-t border-gray-100 dark:border-gray-700 pt-4 mt-auto">

                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ $event->teams_count ?? 0 }} Equipos
                                </div>

                                <div class="flex items-center gap-3">
                                    <a href="{{ route('events.show', $event) }}"
                                        class="text-sm font-bold text-ito-orange hover:text-orange-500 flex items-center transition">
                                        Ver Detalles
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    @can('events.edit')
                                        <a href="{{ route('events.edit', $event) }}"
                                            class="text-gray-400 hover:text-blue-500 transition" title="Editar Evento">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endcan

                                    @can('events.delete')
                                        <form action="{{ route('events.destroy', $event) }}" method="POST"
                                            onsubmit="return confirm('¿Estás seguro de eliminar este evento?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition"
                                                title="Eliminar Evento">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan

                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay eventos activos</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comienza creando el primer evento
                            académico.</p>
                        @can('events.create')
                            <div class="mt-6">
                                <a href="{{ route('events.create') }}"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-ito-orange hover:bg-orange-700">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Crear Evento
                                </a>
                            </div>
                        @endcan
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
