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
                    <p class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-1">Analítica</p>
                    <h1 class="text-4xl font-black text-white flex items-center gap-3">
                        <span class="p-2 bg-indigo-500/10 rounded-xl border border-indigo-500/20">📊</span>
                        Reportes y Estadísticas
                    </h1>
                    <p class="text-gray-400 mt-2 text-sm">Visión general del rendimiento del sistema y datos clave.</p>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 animate-fade-in-up" style="animation-delay: 100ms;">
                <div class="glass-card rounded-2xl p-6 shadow-lg border-b-4 border-b-green-500 hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="p-2 bg-green-500/10 rounded-lg border border-green-500/20">
                            <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <span class="px-2 py-1 bg-green-500/20 rounded-full text-xs text-green-300 font-bold border border-green-500/30">{{ $stats['active_events'] }} activos</span>
                    </div>
                    <p class="text-4xl font-black text-white mb-1">{{ $stats['total_events'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Eventos Totales</p>
                </div>

                <div class="glass-card rounded-2xl p-6 shadow-lg border-b-4 border-b-blue-500 hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="p-2 bg-blue-500/10 rounded-lg border border-blue-500/20">
                            <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-4xl font-black text-white mb-1">{{ $stats['total_teams'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Equipos Registrados</p>
                </div>

                <div class="glass-card rounded-2xl p-6 shadow-lg border-b-4 border-b-purple-500 hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="p-2 bg-purple-500/10 rounded-lg border border-purple-500/20">
                            <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-4xl font-black text-white mb-1">{{ $stats['total_students'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Estudiantes</p>
                </div>

                <div class="glass-card rounded-2xl p-6 shadow-lg border-b-4 border-b-yellow-500 hover:scale-105 transition-transform duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="p-2 bg-yellow-500/10 rounded-lg border border-yellow-500/20">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-4xl font-black text-white mb-1">{{ $stats['total_awards'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Premios Otorgados</p>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 200ms;">
                <a href="{{ route('reports.by-event') }}" 
                    class="glass-card rounded-2xl p-6 shadow-lg hover:bg-white/10 hover:border-indigo-500/50 transition duration-300 group">
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-indigo-500/10 rounded-2xl group-hover:bg-indigo-500/20 transition-colors border border-indigo-500/20">
                            <svg class="w-7 h-7 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white group-hover:text-indigo-400 transition-colors">Por Evento</h3>
                            <p class="text-xs text-gray-400 mt-1">Análisis detallado de eventos individuales</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('reports.by-career') }}" 
                    class="glass-card rounded-2xl p-6 shadow-lg hover:bg-white/10 hover:border-blue-500/50 transition duration-300 group">
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-blue-500/10 rounded-2xl group-hover:bg-blue-500/20 transition-colors border border-blue-500/20">
                            <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white group-hover:text-blue-400 transition-colors">Por Carrera</h3>
                            <p class="text-xs text-gray-400 mt-1">Participación estudiantil por área</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('reports.by-period') }}" 
                    class="glass-card rounded-2xl p-6 shadow-lg hover:bg-white/10 hover:border-green-500/50 transition duration-300 group">
                    <div class="flex items-center gap-4">
                        <div class="p-4 bg-green-500/10 rounded-2xl group-hover:bg-green-500/20 transition-colors border border-green-500/20">
                            <svg class="w-7 h-7 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white group-hover:text-green-400 transition-colors">Por Período</h3>
                            <p class="text-xs text-gray-400 mt-1">Actividad en rangos de fechas</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-fade-in-up" style="animation-delay: 300ms;">
                {{-- Top Carreras --}}
                <div class="glass-card rounded-2xl p-6 shadow-lg">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        Top 5 Carreras
                    </h3>
                    @if($topCareers->count() > 0)
                        <div class="space-y-4">
                            @foreach($topCareers as $index => $career)
                                <div class="flex items-center gap-4">
                                    <span class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-xs font-bold text-gray-400 font-mono shadow-inner">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-white font-bold">{{ $career->name }}</span>
                                            <span class="text-xs text-blue-300 font-mono bg-blue-500/10 px-2 py-0.5 rounded border border-blue-500/20">{{ $career->participants }} part.</span>
                                        </div>
                                        <div class="h-2 bg-black/40 rounded-full overflow-hidden border border-white/5">
                                            @php $maxParticipants = $topCareers->max('participants'); @endphp
                                            <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-500 rounded-full relative" 
                                                style="width: {{ $maxParticipants > 0 ? ($career->participants / $maxParticipants) * 100 : 0 }}%">
                                                <div class="absolute inset-0 bg-white/20 animate-pulse-slow"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-48 text-gray-500">
                             <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                             <p class="text-sm">No hay datos de participación</p>
                        </div>
                    @endif
                </div>

                {{-- Top Eventos --}}
                <div class="glass-card rounded-2xl p-6 shadow-lg">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        Top 5 Eventos
                    </h3>
                    @if($topEvents->count() > 0)
                        <div class="space-y-4">
                            @foreach($topEvents as $index => $event)
                                <div class="flex items-center gap-4">
                                    <span class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-xs font-bold text-gray-400 font-mono shadow-inner">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm text-white font-bold truncate">{{ Str::limit($event->name, 30) }}</span>
                                            <span class="text-xs text-green-300 font-mono bg-green-500/10 px-2 py-0.5 rounded border border-green-500/20">{{ $event->teams_count }} equipos</span>
                                        </div>
                                        <div class="h-2 bg-black/40 rounded-full overflow-hidden border border-white/5">
                                            @php $maxTeams = $topEvents->max('teams_count'); @endphp
                                            <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full relative" 
                                                style="width: {{ $maxTeams > 0 ? ($event->teams_count / $maxTeams) * 100 : 0 }}%">
                                                <div class="absolute inset-0 bg-white/20 animate-pulse-slow"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-48 text-gray-500">
                             <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                             <p class="text-sm">No hay eventos registrados</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Top Proyectos --}}
            <div class="glass-card rounded-2xl p-6 shadow-lg animate-fade-in-up" style="animation-delay: 400ms;">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                    Top 5 Proyectos Mejor Evaluados
                </h3>
                @if($topProjects->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10 bg-white/5">
                                    <th class="text-left py-4 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">#</th>
                                    <th class="text-left py-4 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Proyecto</th>
                                    <th class="text-left py-4 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Equipo</th>
                                    <th class="text-left py-4 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Evento</th>
                                    <th class="text-right py-4 px-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Puntaje</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($topProjects as $index => $project)
                                    <tr class="hover:bg-white/5 transition duration-200">
                                        <td class="py-4 px-4">
                                            <span class="w-8 h-8 rounded-lg {{ $index === 0 ? 'bg-yellow-500/20 text-yellow-400 border-yellow-500/40' : ($index === 1 ? 'bg-gray-400/20 text-gray-300 border-gray-400/40' : ($index === 2 ? 'bg-amber-700/20 text-amber-600 border-amber-700/40' : 'bg-white/5 text-gray-400 border-white/10')) }} border flex items-center justify-center text-sm font-bold shadow-lg">
                                                {{ $index === 0 ? '🥇' : ($index === 1 ? '🥈' : ($index === 2 ? '🥉' : $index + 1)) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-white font-bold">{{ $project->name }}</td>
                                        <td class="py-4 px-4 text-gray-300 text-sm">{{ $project->team->name ?? 'N/A' }}</td>
                                        <td class="py-4 px-4 text-gray-400 text-sm italic">{{ Str::limit($project->team->event->name ?? 'N/A', 25) }}</td>
                                        <td class="py-4 px-4 text-right">
                                            <span class="px-3 py-1 bg-yellow-500/10 text-yellow-400 rounded-full text-sm font-bold border border-yellow-500/20 shadow-[0_0_10px_rgba(234,179,8,0.2)]">
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
                         <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
                         <p class="text-sm">No hay proyectos evaluados</p>
                    </div>
                @endif
            </div>

            {{-- Participación por Mes --}}
            <div class="glass-card rounded-2xl p-6 shadow-lg animate-fade-in-up" style="animation-delay: 500ms;">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    Equipos Registrados (Últimos 6 meses)
                </h3>
                @if($participationByMonth->count() > 0)
                    <div class="h-72 relative">
                        <canvas id="participationChart"></canvas>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-48 text-gray-500">
                         <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" /></svg>
                         <p class="text-sm">No hay datos de participación reciente</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @if($participationByMonth->count() > 0)
        @push('scripts')
            <script>
                window.participationData = @json($participationByMonth);
            </script>
            @vite('resources/js/pages/reports-index.js')
        @endpush
    @endif
</x-app-layout>
