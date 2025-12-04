<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-green-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <a href="{{ route('reports.index') }}" class="text-sm text-gray-400 hover:text-indigo-400 transition flex items-center gap-1 mb-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a Reportes
                    </a>
                    <h1 class="text-3xl font-black text-white flex items-center gap-3">
                        <span class="p-2 bg-green-500/10 rounded-xl">📆</span>
                        Actividad por Período
                    </h1>
                    <p class="text-gray-400 mt-2">Análisis de actividad en un rango de fechas</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                <form method="GET" action="{{ route('reports.by-period') }}" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[150px]">
                        <label for="start_date" class="block text-sm font-medium text-gray-400 mb-2">Fecha Inicio</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label for="end_date" class="block text-sm font-medium text-gray-400 mb-2">Fecha Fin</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <button type="submit" 
                            class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Analizar
                        </button>
                    </div>
                </form>
                
                {{-- Quick Date Ranges --}}
                <div class="flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('reports.by-period', ['start_date' => now()->subDays(7)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600 transition">
                        Últimos 7 días
                    </a>
                    <a href="{{ route('reports.by-period', ['start_date' => now()->subDays(30)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600 transition">
                        Últimos 30 días
                    </a>
                    <a href="{{ route('reports.by-period', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600 transition">
                        Este mes
                    </a>
                    <a href="{{ route('reports.by-period', ['start_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600 transition">
                        Mes anterior
                    </a>
                    <a href="{{ route('reports.by-period', ['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-700 text-gray-300 hover:bg-gray-600 transition">
                        Este año
                    </a>
                </div>
            </div>

            {{-- Period Summary --}}
            <div class="bg-gradient-to-r from-green-900/30 to-emerald-900/30 border border-green-500/30 rounded-2xl p-6">
                <div class="flex items-center gap-3 mb-2">
                    <span class="p-2 bg-green-500/20 rounded-lg">
                        <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-white font-bold">Período Analizado</p>
                        <p class="text-green-400 text-sm">
                            {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                            <span class="text-gray-400">({{ $startDate->diffInDays($endDate) }} días)</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 text-center">
                    <div class="p-2 bg-blue-500/10 rounded-lg w-10 h-10 mx-auto mb-2 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-black text-white">{{ $periodStats['new_users'] }}</p>
                    <p class="text-sm text-gray-400">Nuevos Usuarios</p>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 text-center">
                    <div class="p-2 bg-purple-500/10 rounded-lg w-10 h-10 mx-auto mb-2 flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-black text-white">{{ $periodStats['new_teams'] }}</p>
                    <p class="text-sm text-gray-400">Nuevos Equipos</p>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 text-center">
                    <div class="p-2 bg-orange-500/10 rounded-lg w-10 h-10 mx-auto mb-2 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-black text-white">{{ $periodStats['new_projects'] }}</p>
                    <p class="text-sm text-gray-400">Nuevos Proyectos</p>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 text-center">
                    <div class="p-2 bg-yellow-500/10 rounded-lg w-10 h-10 mx-auto mb-2 flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-black text-white">{{ $periodStats['new_evaluations'] }}</p>
                    <p class="text-sm text-gray-400">Evaluaciones</p>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Daily Activity --}}
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span class="p-1.5 bg-green-500/10 rounded-lg">📈</span>
                        Actividad Diaria
                    </h3>
                    @if(count($dailyActivity) > 0)
                        <div class="h-64">
                            <canvas id="dailyActivityChart"></canvas>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">No hay actividad en este período</p>
                    @endif
                </div>

                {{-- Activity by Type --}}
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                        <span class="p-1.5 bg-purple-500/10 rounded-lg">🔄</span>
                        Distribución de Actividad
                    </h3>
                    <div class="space-y-4">
                        @php
                            $total = $periodStats['new_users'] + $periodStats['new_teams'] + $periodStats['new_projects'] + $periodStats['new_evaluations'];
                        @endphp
                        
                        @if($total > 0)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm text-gray-400">Usuarios</span>
                                    <span class="text-sm text-blue-400 font-bold">{{ $periodStats['new_users'] }}</span>
                                </div>
                                <div class="h-3 bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ ($periodStats['new_users'] / $total) * 100 }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm text-gray-400">Equipos</span>
                                    <span class="text-sm text-purple-400 font-bold">{{ $periodStats['new_teams'] }}</span>
                                </div>
                                <div class="h-3 bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-purple-500 rounded-full" style="width: {{ ($periodStats['new_teams'] / $total) * 100 }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm text-gray-400">Proyectos</span>
                                    <span class="text-sm text-orange-400 font-bold">{{ $periodStats['new_projects'] }}</span>
                                </div>
                                <div class="h-3 bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-orange-500 rounded-full" style="width: {{ ($periodStats['new_projects'] / $total) * 100 }}%"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm text-gray-400">Evaluaciones</span>
                                    <span class="text-sm text-yellow-400 font-bold">{{ $periodStats['new_evaluations'] }}</span>
                                </div>
                                <div class="h-3 bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-yellow-500 rounded-full" style="width: {{ ($periodStats['new_evaluations'] / $total) * 100 }}%"></div>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No hay actividad en este período</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Events in Period --}}
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <span class="p-1.5 bg-indigo-500/10 rounded-lg">📅</span>
                    Eventos en el Período
                </h3>
                @if($eventsInPeriod->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($eventsInPeriod as $event)
                            <div class="p-4 bg-gray-900/50 rounded-xl border border-gray-700">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-white font-medium">{{ $event->name }}</h4>
                                        <p class="text-sm text-gray-400 mt-1">
                                            {{ $event->start_date->format('d/m/Y') }} - {{ $event->end_date->format('d/m/Y') }}
                                        </p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-medium rounded-lg
                                        {{ $event->status === 'active' ? 'bg-green-500/20 text-green-400' : 
                                           ($event->status === 'upcoming' ? 'bg-blue-500/20 text-blue-400' : 'bg-gray-500/20 text-gray-400') }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4 mt-3 text-sm">
                                    <span class="text-gray-400">
                                        <span class="text-blue-400 font-bold">{{ $event->teams_count ?? 0 }}</span> equipos
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No hay eventos en este período</p>
                @endif
            </div>

        </div>
    </div>

    @if(count($dailyActivity) > 0)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dailyActivityChart').getContext('2d');
            const data = @json($dailyActivity);
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(item => {
                        const date = new Date(item.date);
                        return date.toLocaleDateString('es-MX', { day: '2-digit', month: 'short' });
                    }),
                    datasets: [{
                        label: 'Registros',
                        data: data.map(item => item.count),
                        fill: true,
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 2,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                        pointBorderColor: '#1f2937',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
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
                            ticks: { color: '#9CA3AF', maxRotation: 45 },
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
