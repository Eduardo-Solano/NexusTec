<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Premios: {{ $event->name }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Gestiona los premios y reconocimientos del evento
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if(!$event->is_active)
                    <a href="{{ route('public.event-winners', $event) }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Vista Pública
                    </a>
                @endif
                <a href="{{ route('events.rankings', $event) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Ver Rankings
                </a>
                @if(!$event->is_active)
                    <a href="{{ route('awards.create', ['event_id' => $event->id]) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg transition font-bold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Asignar Premio
                    </a>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-400 text-gray-200 rounded-lg cursor-not-allowed" title="El evento debe estar cerrado">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Evento Activo
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Mensajes de éxito/error --}}
            @if(session('success'))
                <div class="p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 rounded-lg text-green-700 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Lista de Premios --}}
            @if($awards->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($awards as $award)
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
                            $bgClass = match($award->category) {
                                '1er Lugar' => 'from-yellow-500/20 to-yellow-600/10 border-yellow-500/50',
                                '2do Lugar' => 'from-gray-400/20 to-gray-500/10 border-gray-400/50',
                                '3er Lugar' => 'from-orange-500/20 to-orange-600/10 border-orange-500/50',
                                default => 'from-indigo-500/20 to-indigo-600/10 border-indigo-500/50'
                            };
                        @endphp
                        <div class="bg-gradient-to-br {{ $bgClass }} border-2 rounded-2xl p-6 shadow-lg hover:shadow-xl transition relative overflow-hidden">
                            {{-- Emoji de fondo decorativo --}}
                            <div class="absolute -right-4 -top-4 text-8xl opacity-10">{{ $medalEmoji }}</div>
                            
                            <div class="relative">
                                {{-- Categoría --}}
                                <div class="flex items-center gap-2 mb-4">
                                    <span class="text-4xl">{{ $medalEmoji }}</span>
                                    <div>
                                        <h3 class="font-black text-lg text-gray-800 dark:text-white">{{ $award->name }}</h3>
                                        @if($award->category !== $award->name)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $award->category }}</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Equipo Ganador --}}
                                <div class="bg-white/50 dark:bg-gray-800/50 rounded-xl p-4 mb-4">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase font-bold tracking-wider mb-1">Equipo Ganador</p>
                                    <p class="font-bold text-gray-800 dark:text-white">{{ $award->team->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $award->team->project->name ?? 'Sin proyecto' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Líder: {{ $award->team->leader->name ?? 'N/A' }}
                                    </p>
                                </div>

                                {{-- Fecha --}}
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">
                                    Otorgado: {{ $award->awarded_at->format('d/m/Y') }}
                                </p>

                                {{-- Acciones --}}
                                <div class="flex gap-2">
                                    <a href="{{ route('awards.edit', $award) }}" 
                                       class="flex-1 px-3 py-2 text-center text-sm font-bold bg-gray-600 hover:bg-gray-500 text-white rounded-lg transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('awards.destroy', $award) }}" method="POST" class="flex-1"
                                          onsubmit="return confirm('¿Eliminar este premio?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="w-full px-3 py-2 text-sm font-bold bg-red-600 hover:bg-red-500 text-white rounded-lg transition">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Sin premios --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <div class="text-6xl mb-4">🏆</div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">No hay premios asignados</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        @if($event->is_active)
                            Los premios se asignarán cuando el evento finalice.
                        @else
                            Aún no se han otorgado premios para este evento.
                        @endif
                    </p>
                    @if(!$event->is_active)
                        <a href="{{ route('awards.create', ['event_id' => $event->id]) }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg transition font-bold">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Asignar Primer Premio
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 px-6 py-3 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Esperando cierre del evento
                        </span>
                    @endif
                </div>
            @endif

            {{-- Volver al evento --}}
            <div class="text-center">
                <a href="{{ route('events.show', $event) }}" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-sm">
                    ← Volver al evento
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
