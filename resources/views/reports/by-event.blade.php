<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <a href="{{ route('reports.index') }}" class="text-sm text-gray-400 hover:text-indigo-400 transition flex items-center gap-1 mb-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a Reportes
                    </a>
                    <h1 class="text-3xl font-black text-white flex items-center gap-3">
                        <span class="p-2 bg-indigo-500/10 rounded-xl">📅</span>
                        Estadísticas por Evento
                    </h1>
                    <p class="text-gray-400 mt-2">Análisis detallado de cada evento</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                <form method="GET" action="{{ route('reports.by-event') }}" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="event_id" class="block text-sm font-medium text-gray-400 mb-2">Seleccionar Evento</label>
                        <select name="event_id" id="event_id" 
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Seleccione un evento...</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }} ({{ $event->start_date->format('d/m/Y') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" 
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Ver Estadísticas
                        </button>
                    </div>
                </form>
            </div>

            @if($selectedEvent)
                {{-- Event Info --}}
                <div class="bg-gradient-to-r from-indigo-900/50 to-purple-900/50 border border-indigo-500/30 rounded-2xl p-6 shadow-lg">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-white">{{ $selectedEvent->name }}</h2>
                            <p class="text-gray-400 mt-1">{{ $selectedEvent->description }}</p>
                            <div class="flex items-center gap-4 mt-3">
                                <span class="px-3 py-1 rounded-lg text-sm font-medium
                                    {{ $selectedEvent->status === 'active' ? 'bg-green-500/20 text-green-400' : 
                                       ($selectedEvent->status === 'upcoming' ? 'bg-blue-500/20 text-blue-400' : 'bg-gray-500/20 text-gray-400') }}">
                                    {{ ucfirst($selectedEvent->status) }}
                                </span>
                                <span class="text-gray-400 text-sm">
                                    📅 {{ $selectedEvent->start_date->format('d/m/Y') }} - {{ $selectedEvent->end_date->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('reports.export-participants', ['event_id' => $selectedEvent->id]) }}" 
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Exportar
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
                        <p class="text-3xl font-black text-blue-400">{{ $eventStats['total_teams'] }}</p>
                        <p class="text-sm text-gray-400">Equipos</p>
                    </div>
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
                        <p class="text-3xl font-black text-purple-400">{{ $eventStats['total_projects'] }}</p>
                        <p class="text-sm text-gray-400">Proyectos</p>
                    </div>
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
                        <p class="text-3xl font-black text-green-400">{{ $eventStats['total_participants'] }}</p>
                        <p class="text-sm text-gray-400">Participantes</p>
                    </div>
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
                        <p class="text-3xl font-black text-orange-400">{{ $eventStats['total_judges'] }}</p>
                        <p class="text-sm text-gray-400">Jueces</p>
                    </div>
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
                        <p class="text-3xl font-black text-yellow-400">{{ $eventStats['total_evaluations'] }}</p>
                        <p class="text-sm text-gray-400">Evaluaciones</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Teams by Status --}}
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <span class="p-1.5 bg-blue-500/10 rounded-lg">👥</span>
                            Equipos por Estado
                        </h3>
                        @if(count($eventStats['teams_by_status']) > 0)
                            <div class="space-y-3">
                                @foreach($eventStats['teams_by_status'] as $status => $count)
                                    <div class="flex items-center justify-between p-3 bg-gray-900/50 rounded-xl">
                                        <span class="px-3 py-1 rounded-lg text-sm font-medium
                                            {{ $status === 'active' ? 'bg-green-500/20 text-green-400' : 
                                               ($status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : 
                                               ($status === 'registered' ? 'bg-blue-500/20 text-blue-400' : 'bg-gray-500/20 text-gray-400')) }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                        <span class="text-2xl font-bold text-white">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No hay equipos registrados</p>
                        @endif
                    </div>

                    {{-- Average Scores --}}
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                        <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                            <span class="p-1.5 bg-yellow-500/10 rounded-lg">⭐</span>
                            Estadísticas de Evaluación
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-gray-900/50 rounded-xl text-center">
                                <p class="text-3xl font-black text-yellow-400">
                                    {{ number_format($eventStats['avg_score'], 1) }}
                                </p>
                                <p class="text-sm text-gray-400">Puntaje Promedio</p>
                            </div>
                            <div class="p-4 bg-gray-900/50 rounded-xl text-center">
                                <p class="text-3xl font-black text-green-400">
                                    {{ number_format($eventStats['max_score'], 1) }}
                                </p>
                                <p class="text-sm text-gray-400">Puntaje Máximo</p>
                            </div>
                            <div class="p-4 bg-gray-900/50 rounded-xl text-center">
                                <p class="text-3xl font-black text-red-400">
                                    {{ number_format($eventStats['min_score'], 1) }}
                                </p>
                                <p class="text-sm text-gray-400">Puntaje Mínimo</p>
                            </div>
                            <div class="p-4 bg-gray-900/50 rounded-xl text-center">
                                <p class="text-3xl font-black text-purple-400">
                                    {{ $eventStats['evaluated_projects'] }}
                                </p>
                                <p class="text-sm text-gray-400">Proyectos Evaluados</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top Projects --}}
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span class="p-1.5 bg-purple-500/10 rounded-lg">🏆</span>
                        Ranking de Proyectos
                    </h3>
                    @if(count($eventStats['top_projects']) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-700">
                                        <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase">Pos.</th>
                                        <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase">Proyecto</th>
                                        <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase">Equipo</th>
                                        <th class="text-center py-3 px-4 text-xs font-bold text-gray-400 uppercase">Evaluaciones</th>
                                        <th class="text-right py-3 px-4 text-xs font-bold text-gray-400 uppercase">Puntaje</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-700">
                                    @foreach($eventStats['top_projects'] as $index => $project)
                                        <tr class="hover:bg-gray-700/30 transition">
                                            <td class="py-3 px-4">
                                                @if($index === 0)
                                                    <span class="text-2xl">🥇</span>
                                                @elseif($index === 1)
                                                    <span class="text-2xl">🥈</span>
                                                @elseif($index === 2)
                                                    <span class="text-2xl">🥉</span>
                                                @else
                                                    <span class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center text-gray-400 font-bold">
                                                        {{ $index + 1 }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                <p class="text-white font-medium">{{ $project->name }}</p>
                                                <p class="text-xs text-gray-500">{{ Str::limit($project->description ?? '', 50) }}</p>
                                            </td>
                                            <td class="py-3 px-4 text-gray-400">{{ $project->team->name ?? 'N/A' }}</td>
                                            <td class="py-3 px-4 text-center text-gray-400">{{ $project->evaluations_count }}</td>
                                            <td class="py-3 px-4 text-right">
                                                <span class="px-3 py-1 bg-yellow-500/10 text-yellow-400 rounded-lg font-bold">
                                                    {{ number_format($project->evaluations_avg_score, 1) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No hay proyectos evaluados en este evento</p>
                    @endif
                </div>

            @else
                {{-- No event selected --}}
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-12 text-center">
                    <div class="p-4 bg-indigo-500/10 rounded-full w-20 h-20 mx-auto flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Selecciona un Evento</h3>
                    <p class="text-gray-400">Elige un evento del listado para ver sus estadísticas detalladas</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
