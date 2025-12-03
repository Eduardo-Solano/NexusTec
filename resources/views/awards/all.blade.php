<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Todos los Premios
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Historial de premios otorgados
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if($events->count() > 0)
                @foreach($events as $event)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        {{-- Header del evento --}}
                        <div class="bg-gradient-to-r from-gray-800 to-gray-700 px-6 py-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-white">{{ $event->name }}</h3>
                                    <p class="text-sm text-gray-300">
                                        {{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}
                                    </p>
                                </div>
                                <a href="{{ route('awards.index', ['event_id' => $event->id]) }}" 
                                   class="text-sm text-yellow-400 hover:text-yellow-300 font-bold">
                                    Ver detalles →
                                </a>
                            </div>
                        </div>
                        
                        {{-- Premios del evento --}}
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($event->awards as $award)
                                    @php
                                        $medalEmoji = match($award->category) {
                                            '1er Lugar' => '🥇',
                                            '2do Lugar' => '🥈',
                                            '3er Lugar' => '🥉',
                                            'Mención Honorífica' => '🏅',
                                            'Mejor Innovación' => '💡',
                                            'Mejor Diseño' => '🎨',
                                            'Mejor Presentación' => '🎤',
                                            'Premio del Público' => '👥',
                                            default => '🏆'
                                        };
                                    @endphp
                                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                        <span class="text-2xl">{{ $medalEmoji }}</span>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-gray-800 dark:text-white truncate">{{ $award->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                {{ $award->team->name ?? 'Equipo eliminado' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Sin premios --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <div class="text-6xl mb-4">🏆</div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">No hay premios registrados</h3>
                    <p class="text-gray-500 dark:text-gray-400">
                        Aún no se han otorgado premios en ningún evento.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
