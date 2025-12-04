<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <p class="text-sm text-indigo-400 font-bold uppercase tracking-wider mb-1">Analítica</p>
                    <h1 class="text-3xl font-black text-white flex items-center gap-3">
                        <span class="p-2 bg-indigo-500/10 rounded-xl">📊</span>
                        Reportes y Estadísticas
                    </h1>
                    <p class="text-gray-400 mt-2">Análisis detallado del sistema y participación</p>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2 bg-green-500/10 rounded-lg">
                            <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <span class="text-xs text-green-400 font-bold">{{ $stats['active_events'] }} activos</span>
                    </div>
                    <p class="text-3xl font-black text-white">{{ $stats['total_events'] }}</p>
                    <p class="text-sm text-gray-400">Eventos Totales</p>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2 bg-blue-500/10 rounded-lg">
                            <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-3xl font-black text-white">{{ $stats['total_teams'] }}</p>
                    <p class="text-sm text-gray-400">Equipos Registrados</p>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2 bg-purple-500/10 rounded-lg">
                            <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-3xl font-black text-white">{{ $stats['total_students'] }}</p>
                    <p class="text-sm text-gray-400">Estudiantes</p>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <span class="p-2 bg-yellow-500/10 rounded-lg">
                            <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-3xl font-black text-white">{{ $stats['total_awards'] }}</p>
                    <p class="text-sm text-gray-400">Premios Otorgados</p>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('reports.by-event') }}" 
                    class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg hover:border-indigo-500/50 transition group">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-indigo-500/10 rounded-xl group-hover:bg-indigo-500/20 transition">
                            <svg class="w-7 h-7 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white group-hover:text-indigo-400 transition">Por Evento</h3>
                            <p class="text-sm text-gray-400">Análisis detallado por evento</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('reports.by-career') }}" 
                    class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg hover:border-blue-500/50 transition group">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-500/10 rounded-xl group-hover:bg-blue-500/20 transition">
                            <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white group-hover:text-blue-400 transition">Por Carrera</h3>
                            <p class="text-sm text-gray-400">Participación por carrera</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('reports.by-period') }}" 
                    class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg hover:border-green-500/50 transition group">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-green-500/10 rounded-xl group-hover:bg-green-500/20 transition">
                            <svg class="w-7 h-7 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white group-hover:text-green-400 transition">Por Período</h3>
                            <p class="text-sm text-gray-400">Actividad en rango de fechas</p>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Top Carreras --}}
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span class="p-1.5 bg-blue-500/10 rounded-lg">🎓</span>
                        Top 5 Carreras por Participación
                    </h3>
                    @if($topCareers->count() > 0)
                        <div class="space-y-3">
                            @foreach($topCareers as $index => $career)
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-400">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm text-white font-medium">{{ $career->name }}</span>
                                            <span class="text-sm text-gray-400">{{ $career->participants }}</span>
                                        </div>
                                        <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                                            @php $maxParticipants = $topCareers->max('participants'); @endphp
                                            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full" 
                                                style="width: {{ $maxParticipants > 0 ? ($career->participants / $maxParticipants) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No hay datos de participación</p>
                    @endif
                </div>

                {{-- Top Eventos --}}
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span class="p-1.5 bg-green-500/10 rounded-lg">🏆</span>
                        Top 5 Eventos por Equipos
                    </h3>
                    @if($topEvents->count() > 0)
                        <div class="space-y-3">
                            @foreach($topEvents as $index => $event)
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-400">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-sm text-white font-medium truncate">{{ Str::limit($event->name, 30) }}</span>
                                            <span class="text-sm text-gray-400">{{ $event->teams_count }}</span>
                                        </div>
                                        <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                                            @php $maxTeams = $topEvents->max('teams_count'); @endphp
                                            <div class="h-full bg-gradient-to-r from-green-500 to-emerald-500 rounded-full" 
                                                style="width: {{ $maxTeams > 0 ? ($event->teams_count / $maxTeams) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No hay eventos registrados</p>
                    @endif
                </div>
            </div>

            {{-- Top Proyectos --}}
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <span class="p-1.5 bg-yellow-500/10 rounded-lg">⭐</span>
                    Top 5 Proyectos Mejor Evaluados
                </h3>
                @if($topProjects->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-700">
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase">#</th>
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase">Proyecto</th>
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase">Equipo</th>
                                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-400 uppercase">Evento</th>
                                    <th class="text-right py-3 px-4 text-xs font-bold text-gray-400 uppercase">Puntaje Promedio</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @foreach($topProjects as $index => $project)
                                    <tr class="hover:bg-gray-700/30 transition">
                                        <td class="py-3 px-4">
                                            <span class="w-6 h-6 rounded-full {{ $index === 0 ? 'bg-yellow-500/20 text-yellow-400' : ($index === 1 ? 'bg-gray-400/20 text-gray-300' : ($index === 2 ? 'bg-amber-600/20 text-amber-500' : 'bg-gray-700 text-gray-400')) }} flex items-center justify-center text-xs font-bold">
                                                {{ $index + 1 }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-white font-medium">{{ $project->name }}</td>
                                        <td class="py-3 px-4 text-gray-400">{{ $project->team->name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 text-gray-400">{{ Str::limit($project->team->event->name ?? 'N/A', 20) }}</td>
                                        <td class="py-3 px-4 text-right">
                                            <span class="px-2 py-1 bg-yellow-500/10 text-yellow-400 rounded-lg text-sm font-bold">
                                                {{ number_format($project->evaluations_avg_score, 1) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No hay proyectos evaluados</p>
                @endif
            </div>

            {{-- Participación por Mes --}}
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <span class="p-1.5 bg-purple-500/10 rounded-lg">📈</span>
                    Equipos Registrados por Mes (Últimos 6 meses)
                </h3>
                @if($participationByMonth->count() > 0)
                    <div class="h-64">
                        <canvas id="participationChart"></canvas>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No hay datos de participación en los últimos 6 meses</p>
                @endif
            </div>

        </div>
    </div>

    @if($participationByMonth->count() > 0)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('participationChart').getContext('2d');
            const data = @json($participationByMonth);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => {
                        const [year, month] = item.month.split('-');
                        const date = new Date(year, month - 1);
                        return date.toLocaleDateString('es-MX', { month: 'short', year: 'numeric' });
                    }),
                    datasets: [{
                        label: 'Equipos',
                        data: data.map(item => item.count),
                        backgroundColor: 'rgba(139, 92, 246, 0.5)',
                        borderColor: 'rgba(139, 92, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#9CA3AF' },
                            grid: { color: 'rgba(55, 65, 81, 0.5)' }
                        },
                        x: {
                            ticks: { color: '#9CA3AF' },
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
    @endif
</x-app-layout>
