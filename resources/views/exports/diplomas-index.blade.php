<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                    Generar Diplomas: {{ $event->name }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Genera diplomas de participación y reconocimientos para ganadores
                </p>
            </div>
            <div class="flex items-center gap-3">
                {{-- Botón envío masivo por correo de TODO el evento --}}
                <form action="{{ route('export.diplomas.send', $event) }}" method="POST" class="inline"
                      onsubmit="return confirm('¿Enviar diplomas por correo a TODOS los participantes del evento? Esta acción enviará un correo a cada participante.')">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-lg transition font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Enviar Todo por Correo
                    </button>
                </form>
                {{-- Botón descarga masiva de TODO el evento --}}
                <a href="{{ route('export.diplomas.event', $event) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg transition font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Descargar Todo (ZIP)
                </a>
                <a href="{{ route('events.rankings', $event) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a Rankings
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Diplomas de Ganadores --}}
            @if($event->awards->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-yellow-500/10 to-orange-500/10">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            🏆 Diplomas de Ganadores
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Genera diplomas personalizados para cada miembro de los equipos premiados
                        </p>
                    </div>
                    
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($event->awards as $award)
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">
                                            @if(Str::contains(strtolower($award->category), ['oro', 'primer', '1er', '1°']))
                                                🥇
                                            @elseif(Str::contains(strtolower($award->category), ['plata', 'segundo', '2do', '2°']))
                                                🥈
                                            @elseif(Str::contains(strtolower($award->category), ['bronce', 'tercer', '3er', '3°']))
                                                🥉
                                            @else
                                                🏆
                                            @endif
                                        </span>
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $award->category }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                Equipo: <span class="font-medium">{{ $award->team->name }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-bold rounded-full">
                                        {{ $award->team->members->count() }} miembros
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($award->team->members as $member)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-full bg-yellow-500/20 flex items-center justify-center text-yellow-600 dark:text-yellow-400 font-bold text-sm">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $member->id === $award->team->leader_id ? '⭐ Líder' : 'Miembro' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                {{-- Enviar por correo --}}
                                                <form action="{{ route('export.diploma.send', [$event, $member]) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('¿Enviar diploma de ganador a {{ $member->name }}?')">
                                                    @csrf
                                                    <input type="hidden" name="type" value="winner">
                                                    <input type="hidden" name="award_id" value="{{ $award->id }}">
                                                    <button type="submit"
                                                            class="p-2 bg-purple-500 hover:bg-purple-400 text-white rounded-lg transition"
                                                            title="Enviar diploma por correo">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                                {{-- Descargar PDF --}}
                                                <a href="{{ route('export.diploma.winner', [$award, $member]) }}"
                                                   class="p-2 bg-yellow-500 hover:bg-yellow-400 text-white rounded-lg transition"
                                                   title="Descargar diploma de ganador">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Diplomas de Participación --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-500/10 to-purple-500/10">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        📜 Diplomas de Participación
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Genera diplomas para todos los participantes del evento
                    </p>
                </div>
                
                @if($event->teams->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($event->teams as $team)
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold">
                                            {{ strtoupper(substr($team->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $team->name }}</h4>
                                            @if($team->project)
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    Proyecto: {{ Str::limit($team->project->name ?? $team->project->title ?? 'Sin nombre', 40) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-bold rounded-full">
                                            {{ $team->members->count() }} miembros
                                        </span>
                                        {{-- Botón envío masivo por correo al equipo --}}
                                        <form action="{{ route('export.diplomas.team.send', [$event, $team]) }}" method="POST" class="inline"
                                              onsubmit="return confirm('¿Enviar diplomas por correo a todos los miembros del equipo {{ $team->name }}?')">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1 bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold rounded-lg transition"
                                                    title="Enviar diplomas por correo a todo el equipo">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                Email
                                            </button>
                                        </form>
                                        {{-- Botón descarga masiva por equipo --}}
                                        <a href="{{ route('export.diplomas.team', [$event, $team]) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold rounded-lg transition"
                                           title="Descargar todos los diplomas del equipo">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            ZIP
                                        </a>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($team->members as $member)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $member->id === $team->leader_id ? '⭐ Líder' : 'Miembro' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                {{-- Enviar por correo --}}
                                                <form action="{{ route('export.diploma.send', [$event, $member]) }}" method="POST" class="inline"
                                                      onsubmit="return confirm('¿Enviar diploma de participación a {{ $member->name }}?')">
                                                    @csrf
                                                    <input type="hidden" name="type" value="participation">
                                                    <button type="submit"
                                                            class="p-2 bg-purple-500 hover:bg-purple-400 text-white rounded-lg transition"
                                                            title="Enviar diploma por correo">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                                {{-- Descargar PDF --}}
                                                <a href="{{ route('export.diploma.participation', [$event, $member]) }}"
                                                   class="p-2 bg-blue-500 hover:bg-blue-400 text-white rounded-lg transition"
                                                   title="Descargar diploma de participación">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">No hay equipos registrados en este evento</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
