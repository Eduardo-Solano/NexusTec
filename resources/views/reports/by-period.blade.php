<x-app-layout>
    {{-- Animated Background --}}
    <div class="circuit-background-app"></div>
    <div class="light-particles-app"></div>

    <div class="min-h-screen py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="glass-card rounded-2xl p-8 shadow-2xl relative overflow-hidden animate-fade-in-down">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-green-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <nav class="flex items-center text-sm font-medium text-green-400 mb-4 animate-fade-in-left" style="animation-delay: 100ms;">
                        <a href="{{ route('reports.index') }}" class="group flex items-center hover:text-white transition-colors duration-300">
                            <div class="w-8 h-8 rounded-full bg-green-500/10 border border-green-500/20 flex items-center justify-center mr-3 group-hover:border-green-500/50 group-hover:bg-green-500/20 transition-all duration-300">
                                <svg class="w-4 h-4 group-hover:text-green-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </div>
                            <span>Volver a Reportes</span>
                        </a>
                    </nav>

                    <h1 class="text-4xl font-black text-white flex items-center gap-3 animate-fade-in-up" style="animation-delay: 200ms;">
                        <span class="p-2 bg-green-500/10 rounded-xl border border-green-500/20">📆</span>
                        Actividad por Período
                    </h1>
                    <p class="text-gray-400 mt-2 text-sm max-w-2xl animate-fade-in-up" style="animation-delay: 300ms;">
                        Análisis temporal de registros y actividad en la plataforma dentro de un rango de fechas.
                    </p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="glass-card rounded-2xl p-6 shadow-lg animate-fade-in-up" style="animation-delay: 400ms;">
                <form method="GET" action="{{ route('reports.by-period') }}" class="flex flex-col lg:flex-row items-end gap-4">
                    <div class="flex flex-col sm:flex-row gap-4 flex-1 w-full">
                        <div class="flex-1 relative group">
                            <label for="start_date" class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wider">Fecha Inicio</label>
                            <input type="date" name="start_date" id="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:ring-1 focus:ring-green-500/50 focus:border-green-500/50 transition-all duration-300 backdrop-blur-sm hover:bg-black/30 placeholder-gray-500">
                        </div>
                        <div class="flex-1 relative group">
                            <label for="end_date" class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wider">Fecha Fin</label>
                            <input type="date" name="end_date" id="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:ring-1 focus:ring-green-500/50 focus:border-green-500/50 transition-all duration-300 backdrop-blur-sm hover:bg-black/30 placeholder-gray-500">
                        </div>
                    </div>
                    <div class="w-full lg:w-auto">
                        <button type="submit" 
                            class="px-8 py-3 bg-green-600 hover:bg-green-500 text-white rounded-xl font-bold transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-green-500/40 w-full lg:w-auto">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Analizar Período
                        </button>
                    </div>
                </form>
                
                {{-- Quick Date Ranges --}}
                <div class="flex flex-wrap gap-2 mt-6 pt-4 border-t border-white/5">
                    @foreach([
                        ['label' => 'Últimos 7 días', 'days' => 7],
                        ['label' => 'Últimos 30 días', 'days' => 30]
                    ] as $range)
                        <a href="{{ route('reports.by-period', ['start_date' => now()->subDays($range['days'])->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                            class="px-3 py-1.5 text-xs font-bold rounded-lg bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/5 hover:border-white/10 transition-all duration-300">
                            {{ $range['label'] }}
                        </a>
                    @endforeach
                    <a href="{{ route('reports.by-period', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}"
                        class="px-3 py-1.5 text-xs font-bold rounded-lg bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/5 hover:border-white/10 transition-all duration-300">
                        Este mes
                    </a>
                    <a href="{{ route('reports.by-period', ['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                        class="px-3 py-1.5 text-xs font-bold rounded-lg bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/5 hover:border-white/10 transition-all duration-300">
                        Este año
                    </a>
                </div>
            </div>

            {{-- Period Summary --}}
            <div class="glass-card rounded-2xl p-6 border border-green-500/30 animate-fade-in-up" style="animation-delay: 500ms;">
                <div class="flex items-center gap-4">
                    <span class="p-3 bg-green-500/10 rounded-xl border border-green-500/20">
                        <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-1">Resumen del Período</p>
                        <p class="text-white font-bold text-lg flex items-center gap-2">
                            {{ $startDate->format('d/m/Y') }} 
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                            {{ $endDate->format('d/m/Y') }}
                            <span class="text-xs bg-green-500/20 text-green-300 px-2 py-0.5 rounded border border-green-500/30 ml-2">
                                {{ $startDate->diffInDays($endDate) }} días
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-fade-in-up" style="animation-delay: 600ms;">
                <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-blue-500 group hover:scale-105 transition-transform duration-300">
                    <div class="p-3 bg-blue-500/10 rounded-xl w-12 h-12 mx-auto mb-3 flex items-center justify-center group-hover:bg-blue-500/20 transition-colors">
                        <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-black text-white mb-1 group-hover:text-blue-400 transition-colors">{{ $periodStats['new_users'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Nuevos Usuarios</p>
                </div>

                <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-purple-500 group hover:scale-105 transition-transform duration-300">
                    <div class="p-3 bg-purple-500/10 rounded-xl w-12 h-12 mx-auto mb-3 flex items-center justify-center group-hover:bg-purple-500/20 transition-colors">
                        <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-black text-white mb-1 group-hover:text-purple-400 transition-colors">{{ $periodStats['new_teams'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Nuevos Equipos</p>
                </div>

                <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-orange-500 group hover:scale-105 transition-transform duration-300">
                    <div class="p-3 bg-orange-500/10 rounded-xl w-12 h-12 mx-auto mb-3 flex items-center justify-center group-hover:bg-orange-500/20 transition-colors">
                        <svg class="w-6 h-6 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-black text-white mb-1 group-hover:text-orange-400 transition-colors">{{ $periodStats['new_projects'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Nuevos Proyectos</p>
                </div>

                <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-yellow-500 group hover:scale-105 transition-transform duration-300">
                    <div class="p-3 bg-yellow-500/10 rounded-xl w-12 h-12 mx-auto mb-3 flex items-center justify-center group-hover:bg-yellow-500/20 transition-colors">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <p class="text-3xl font-black text-white mb-1 group-hover:text-yellow-400 transition-colors">{{ $periodStats['new_evaluations'] }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Evaluaciones</p>
                </div>
            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 animate-fade-in-up" style="animation-delay: 700ms;">
                {{-- Daily Activity --}}
                <div class="glass-card rounded-2xl p-6 shadow-lg">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        Actividad Diaria
                    </h3>
                    @if(count($dailyActivity) > 0)
                        <div class="h-64 relative">
                            <canvas id="dailyActivityChart"></canvas>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center h-48 text-gray-500">
                             <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                             <p class="text-sm">No hay actividad en este período</p>
                        </div>
                    @endif
                </div>

                {{-- Activity by Type --}}
                <div class="glass-card rounded-2xl p-6 shadow-lg">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                        Distribución de Actividad
                    </h3>
                    <div class="space-y-6">
                        @php
                            $total = $periodStats['new_users'] + $periodStats['new_teams'] + $periodStats['new_projects'] + $periodStats['new_evaluations'];
                        @endphp
                        
                        @if($total > 0)
                            @foreach([
                                ['label' => 'Usuarios', 'value' => $periodStats['new_users'], 'color' => 'blue'],
                                ['label' => 'Equipos', 'value' => $periodStats['new_teams'], 'color' => 'purple'],
                                ['label' => 'Proyectos', 'value' => $periodStats['new_projects'], 'color' => 'orange'],
                                ['label' => 'Evaluaciones', 'value' => $periodStats['new_evaluations'], 'color' => 'yellow']
                            ] as $stat)
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-300">{{ $stat['label'] }}</span>
                                        <span class="text-sm font-bold text-{{ $stat['color'] }}-400">{{ $stat['value'] }}</span>
                                    </div>
                                    <div class="h-2 bg-black/40 rounded-full overflow-hidden border border-white/5">
                                        @php 
                                            // Handle colors dynamically (for tailwind compilation, static classes are better, but this logic assumes classes exist)
                                            $bgClass = match($stat['color']) {
                                                'blue' => 'from-blue-600 to-indigo-500',
                                                'purple' => 'from-purple-600 to-violet-500',
                                                'orange' => 'from-orange-500 to-red-500',
                                                'yellow' => 'from-yellow-500 to-amber-500',
                                                default => 'from-gray-500 to-gray-400'
                                            };
                                        @endphp
                                        <div class="h-full bg-gradient-to-r {{ $bgClass }} rounded-full relative" 
                                            style="width: {{ ($stat['value'] / $total) * 100 }}%">
                                            <div class="absolute inset-0 bg-white/20 animate-pulse-slow"></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                           <div class="flex flex-col items-center justify-center h-48 text-gray-500">
                                 <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                 <p class="text-sm">No hay distribución disponible</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Events in Period --}}
            <div class="glass-card rounded-2xl p-6 shadow-lg animate-fade-in-up" style="animation-delay: 800ms;">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    Eventos en el Período
                </h3>
                @if($eventsInPeriod->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($eventsInPeriod as $event)
                            <div class="p-5 bg-white/5 rounded-2xl border border-white/5 hover:bg-white/10 hover:border-indigo-500/30 transition-all cursor-default relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-2 opacity-10 group-hover:opacity-20 transition-opacity">
                                    <svg class="w-16 h-16 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-start justify-between mb-2">
                                        <span class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded border
                                            {{ $event->status === 'active' ? 'bg-green-500/10 text-green-400 border-green-500/20' : 
                                               ($event->status === 'upcoming' ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : 'bg-gray-500/10 text-gray-400 border-gray-500/20') }}">
                                            {{ ucfirst($event->status) }}
                                        </span>
                                    </div>
                                    <h4 class="text-lg font-bold text-white mb-1 group-hover:text-indigo-400 transition-colors">{{ $event->name }}</h4>
                                    <p class="text-sm text-gray-400 mb-3 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $event->start_date->format('d/m/Y') }} - {{ $event->end_date->format('d/m/Y') }}
                                    </p>
                                    <div class="flex items-center text-xs font-bold text-gray-500 bg-black/20 rounded-lg p-2 inline-flex border border-white/5">
                                        <span class="text-indigo-400 mr-1">{{ $event->teams_count ?? 0 }}</span> equipos registrados
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-48 text-gray-500">
                         <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                         <p class="text-sm">No hay eventos en este período</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    @if(count($dailyActivity) > 0)
        @push('scripts')
            <script>
                window.dailyActivityData = @json($dailyActivity);
            </script>
            @vite('resources/js/pages/reports-by-period.js')
        @endpush
    @endif
</x-app-layout>
