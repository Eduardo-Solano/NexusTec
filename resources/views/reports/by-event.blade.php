<x-app-layout>
    {{-- Animated Background --}}
    <div class="circuit-background-app"></div>
    <div class="light-particles-app"></div>

    <div class="min-h-screen py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="glass-card rounded-2xl p-8 shadow-2xl relative overflow-hidden animate-fade-in-down">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <nav class="flex items-center text-sm font-medium text-indigo-400 mb-4 animate-fade-in-left" style="animation-delay: 100ms;">
                        <a href="{{ route('reports.index') }}" class="group flex items-center hover:text-white transition-colors duration-300">
                            <div class="w-8 h-8 rounded-full bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mr-3 group-hover:border-indigo-500/50 group-hover:bg-indigo-500/20 transition-all duration-300">
                                <svg class="w-4 h-4 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </div>
                            <span>Volver a Reportes</span>
                        </a>
                    </nav>

                    <h1 class="text-4xl font-black text-white flex items-center gap-3 animate-fade-in-up" style="animation-delay: 200ms;">
                        <span class="p-2 bg-indigo-500/10 rounded-xl border border-indigo-500/20">📅</span>
                        Estadísticas por Evento
                    </h1>
                    <p class="text-gray-400 mt-2 text-sm max-w-2xl animate-fade-in-up" style="animation-delay: 300ms;">
                        Análisis detallado y métricas clave de cada evento registrado en el sistema.
                    </p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="glass-card rounded-2xl p-6 shadow-lg animate-fade-in-up" style="animation-delay: 400ms;">
                <form method="GET" action="{{ route('reports.by-event') }}" class="flex flex-col md:flex-row items-end gap-4">
                    <div class="flex-1 w-full md:w-auto relative group">
                        <label for="event_id" class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wider">Seleccionar Evento</label>
                        <div class="relative">
                            <select name="event_id" id="event_id" 
                                class="w-full pl-4 pr-10 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:ring-1 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all duration-300 backdrop-blur-sm appearance-none cursor-pointer hover:bg-black/30">
                                <option value="" class="bg-gray-900 text-gray-400">Seleccione un evento...</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }} class="bg-gray-900 text-white">
                                        {{ $event->name }} ({{ $event->start_date->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full md:w-auto">
                        <button type="submit" 
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-indigo-500/40 w-full md:w-auto">
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
                <div class="glass-card rounded-2xl p-6 shadow-lg border border-indigo-500/30 animate-fade-in-up" style="animation-delay: 500ms;">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h2 class="text-3xl font-bold text-white">{{ $selectedEvent->name }}</h2>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border
                                    {{ $selectedEvent->status === 'active' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 
                                       ($selectedEvent->status === 'upcoming' ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : 'bg-gray-500/10 text-gray-400 border-gray-500/20') }}">
                                    {{ ucfirst($selectedEvent->status) }}
                                </span>
                            </div>
                            <p class="text-gray-400 mb-3">{{ $selectedEvent->description }}</p>
                            <div class="flex items-center text-sm text-gray-500 font-mono bg-black/20 px-3 py-1.5 rounded-lg border border-white/5 inline-flex">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $selectedEvent->start_date->format('d/m/Y') }} - {{ $selectedEvent->end_date->format('d/m/Y') }}
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('reports.export-participants', ['event_id' => $selectedEvent->id]) }}" 
                                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-emerald-500/40">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Excel
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 animate-fade-in-up" style="animation-delay: 600ms;">
                    <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-blue-500 hover:scale-105 transition-transform duration-300">
                        <p class="text-3xl font-black text-white mb-1">{{ $eventStats['total_teams'] }}</p>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Equipos</p>
                    </div>
                    <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-purple-500 hover:scale-105 transition-transform duration-300">
                        <p class="text-3xl font-black text-white mb-1">{{ $eventStats['total_projects'] }}</p>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Proyectos</p>
                    </div>
                    <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-green-500 hover:scale-105 transition-transform duration-300">
                        <p class="text-3xl font-black text-white mb-1">{{ $eventStats['total_participants'] }}</p>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Participantes</p>
                    </div>
                    <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-orange-500 hover:scale-105 transition-transform duration-300">
                        <p class="text-3xl font-black text-white mb-1">{{ $eventStats['total_judges'] }}</p>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Jueces</p>
                    </div>
                    <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-yellow-500 hover:scale-105 transition-transform duration-300">
                        <p class="text-3xl font-black text-white mb-1">{{ $eventStats['total_evaluations'] }}</p>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Evaluaciones</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-fade-in-up" style="animation-delay: 700ms;">
                    {{-- Teams by Status --}}
                    <div class="glass-card rounded-2xl p-6 shadow-lg">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            Equipos por Estado
                        </h3>
                        @if(count($eventStats['teams_by_status']) > 0)
                            <div class="space-y-3">
                                @foreach($eventStats['teams_by_status'] as $status => $count)
                                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-xl border border-white/5 hover:border-white/10 transition-colors">
                                        <div class="flex items-center gap-3">
                                            <span class="w-2 h-2 rounded-full 
                                                {{ $status === 'active' ? 'bg-green-500' : 
                                                   ($status === 'pending' ? 'bg-yellow-500' : 
                                                   ($status === 'registered' ? 'bg-blue-500' : 'bg-gray-500')) }}"></span>
                                            <span class="text-sm font-medium text-gray-300">{{ ucfirst($status) }}</span>
                                        </div>
                                        <span class="text-xl font-bold text-white">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center h-48 text-gray-500">
                                 <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                 <p class="text-sm">No hay equipos registrados</p>
                            </div>
                        @endif
                    </div>

                    {{-- Average Scores --}}
                    <div class="glass-card rounded-2xl p-6 shadow-lg">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                            Resultados de Evaluación
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-6 bg-white/5 rounded-2xl text-center border border-white/5 hover:border-yellow-500/30 transition-colors">
                                <p class="text-4xl font-black text-yellow-400 mb-2">
                                    {{ number_format($eventStats['avg_score'], 1) }}
                                </p>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Promedio</p>
                            </div>
                            <div class="p-6 bg-white/5 rounded-2xl text-center border border-white/5 hover:border-green-500/30 transition-colors">
                                <p class="text-4xl font-black text-green-400 mb-2">
                                    {{ number_format($eventStats['max_score'], 1) }}
                                </p>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Máximo</p>
                            </div>
                            <div class="p-6 bg-white/5 rounded-2xl text-center border border-white/5 hover:border-red-500/30 transition-colors">
                                <p class="text-4xl font-black text-red-400 mb-2">
                                    {{ number_format($eventStats['min_score'], 1) }}
                                </p>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Mínimo</p>
                            </div>
                            <div class="p-6 bg-white/5 rounded-2xl text-center border border-white/5 hover:border-purple-500/30 transition-colors">
                                <p class="text-4xl font-black text-purple-400 mb-2">
                                    {{ $eventStats['evaluated_projects'] }}
                                </p>
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Proyectos Evaluados</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top Projects --}}
                <div class="glass-card rounded-2xl p-6 shadow-lg animate-fade-in-up" style="animation-delay: 800ms;">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        Ranking de Proyectos
                    </h3>
                    @if(count($eventStats['top_projects']) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="bg-black/20 text-left">
                                        <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Pos.</th>
                                        <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Proyecto</th>
                                        <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Equipo</th>
                                        <th class="text-center py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Evaluaciones</th>
                                        <th class="text-right py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Puntaje</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @foreach($eventStats['top_projects'] as $index => $project)
                                        <tr class="hover:bg-white/5 transition duration-200">
                                            <td class="py-4 px-6">
                                                @if($index === 0)
                                                    <span class="text-2xl drop-shadow-[0_0_10px_rgba(234,179,8,0.5)]">🥇</span>
                                                @elseif($index === 1)
                                                    <span class="text-2xl drop-shadow-[0_0_10px_rgba(156,163,175,0.5)]">🥈</span>
                                                @elseif($index === 2)
                                                    <span class="text-2xl drop-shadow-[0_0_10px_rgba(180,83,9,0.5)]">🥉</span>
                                                @else
                                                    <span class="w-8 h-8 bg-white/5 rounded-full flex items-center justify-center text-gray-400 font-bold font-mono border border-white/10">
                                                        {{ $index + 1 }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6">
                                                <p class="text-white font-bold">{{ $project->name }}</p>
                                                <p class="text-xs text-gray-500 mt-1 max-w-sm truncate">{{ $project->description }}</p>
                                            </td>
                                            <td class="py-4 px-6 text-gray-300">{{ $project->team->name ?? 'N/A' }}</td>
                                            <td class="py-4 px-6 text-center">
                                                <span class="px-2 py-1 bg-white/5 rounded text-xs text-gray-400 font-mono">
                                                    {{ $project->evaluations_count }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-right">
                                                <span class="px-3 py-1 bg-yellow-500/10 text-yellow-400 rounded-lg text-sm font-black border border-yellow-500/20 shadow-[0_0_10px_rgba(234,179,8,0.2)]">
                                                    {{ number_format($project->evaluations_avg_score, 1) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-48 text-gray-500">
                             <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                             <p class="text-sm">No hay proyectos evaluados en este evento</p>
                        </div>
                    @endif
                </div>

            @else
                {{-- No event selected --}}
                <div class="glass-card rounded-2xl p-16 text-center border border-white/10 flex flex-col items-center justify-center animate-fade-in-up" style="animation-delay: 500ms;">
                    <div class="p-6 bg-indigo-500/10 rounded-full w-24 h-24 flex items-center justify-center mb-6 animate-pulse-slow border border-indigo-500/20">
                        <svg class="w-10 h-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Selecciona un Evento</h3>
                    <p class="text-gray-400 max-w-md">Elige una competencia de la lista superior para visualizar el análisis de rendimiento y estadísticas detalladas.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
