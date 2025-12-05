<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Rankings: {{ $event->name }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $event->start_date->format('d M Y') }} - {{ $event->end_date->format('d M Y') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                @if(Auth::user()->hasAnyRole(['admin', 'staff']))
                    {{-- Botones de Exportación --}}
                    <div class="flex items-center gap-2">
                        @if($event->isClosed())
                            <a href="{{ route('export.diplomas', $event) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-lg transition font-bold"
                               title="Generar Diplomas">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Diplomas
                            </a>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-500 text-gray-300 rounded-lg cursor-not-allowed font-bold"
                                  title="Disponible cuando el evento finalice">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Diplomas
                            </span>
                        @endif
                        <a href="{{ route('export.rankings.excel', $event) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg transition font-bold"
                           title="Exportar a Excel">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Excel
                        </a>
                        <a href="{{ route('export.rankings.pdf', $event) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg transition font-bold"
                           title="Exportar a PDF">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            PDF
                        </a>
                    </div>
                @endif
                @if(!$event->is_active)
                    <a href="{{ route('public.event-winners', $event) }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white rounded-lg transition font-bold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Vista Pública
                    </a>
                @endif
                @if(Auth::user()->hasAnyRole(['admin', 'staff']))
                    <a href="{{ route('awards.index', ['event_id' => $event->id]) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg transition font-bold">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        Premios
                    </a>
                @endif
                <a href="{{ route('events.show', $event) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al Evento
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Estadísticas Generales --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['total_projects'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Proyectos Totales</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['fully_evaluated'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Evaluados Completamente</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['pending_evaluation'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Pendientes de Evaluación</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ number_format($stats['average_score'], 1) }}%</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Promedio General</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Criterios de Evaluación --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Criterios de Evaluación
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($criteria as $criterion)
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm">
                            {{ $criterion->name }}
                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $criterion->max_points }} pts)</span>
                        </span>
                    @endforeach
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 rounded-full text-sm font-bold">
                        Total: {{ $criteria->sum('max_points') }} pts
                    </span>
                </div>
            </div>

            {{-- Tabla de Rankings --}}
            @if($rankedProjects->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Pos.
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Proyecto / Equipo
                                    </th>
                                    @foreach($criteria as $criterion)
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                            {{ Str::limit($criterion->name, 15) }}
                                            <span class="block text-[10px] font-normal text-gray-400">máx {{ $criterion->max_points }}</span>
                                        </th>
                                    @endforeach
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Estado
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($rankedProjects as $index => $data)
                                    @php
                                        $position = $index + 1;
                                        $medalClass = match($position) {
                                            1 => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 ring-2 ring-yellow-400',
                                            2 => 'bg-gray-100 dark:bg-gray-600/30 text-gray-600 dark:text-gray-300 ring-2 ring-gray-400',
                                            3 => 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 ring-2 ring-orange-400',
                                            default => 'bg-gray-50 dark:bg-gray-700/30 text-gray-500 dark:text-gray-400'
                                        };
                                        $rowClass = $position <= 3 ? 'bg-gradient-to-r from-transparent to-yellow-50/30 dark:to-yellow-900/10' : '';
                                    @endphp
                                    <tr class="{{ $rowClass }} hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                        {{-- Posición --}}
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $medalClass }} font-bold text-lg">
                                                @if($position === 1)
                                                    🥇
                                                @elseif($position === 2)
                                                    🥈
                                                @elseif($position === 3)
                                                    🥉
                                                @else
                                                    {{ $position }}
                                                @endif
                                            </div>
                                        </td>
                                        
                                        {{-- Proyecto / Equipo --}}
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <a href="{{ route('projects.show', $data['project']) }}" 
                                                       class="font-semibold text-gray-800 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                                        {{ $data['project']->name }}
                                                    </a>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $data['team']->name }} • 
                                                        <span class="text-gray-400">{{ $data['team']->leader->name ?? 'Sin líder' }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        {{-- Puntajes por Criterio --}}
                                        @foreach($criteria as $criterion)
                                            <td class="px-4 py-4 text-center">
                                                @if(isset($data['scores_by_criterion'][$criterion->id]['average']))
                                                    @php
                                                        $score = $data['scores_by_criterion'][$criterion->id]['average'];
                                                        $max = $criterion->max_points;
                                                        $pct = ($score / $max) * 100;
                                                        $colorClass = $pct >= 80 ? 'text-green-600 dark:text-green-400' : 
                                                                     ($pct >= 60 ? 'text-yellow-600 dark:text-yellow-400' : 
                                                                     'text-red-600 dark:text-red-400');
                                                    @endphp
                                                    <span class="font-mono font-bold {{ $colorClass }}">
                                                        {{ $score }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400 text-xs">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        
                                        {{-- Total --}}
                                        <td class="px-4 py-4 text-center">
                                            <div class="inline-flex flex-col items-center">
                                                <span class="font-mono font-bold text-lg text-gray-800 dark:text-white">
                                                    {{ number_format($data['total_score'], 1) }}
                                                </span>
                                                <span class="text-[10px] text-gray-500">
                                                    {{ $data['percentage'] }}%
                                                </span>
                                            </div>
                                        </td>
                                        
                                        {{-- Estado --}}
                                        <td class="px-4 py-4 text-center">
                                            @if($data['judges_total'] === 0)
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full text-xs">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                    Sin jueces
                                                </span>
                                            @elseif($data['is_fully_evaluated'])
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-full text-xs font-bold">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Completo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-full text-xs">
                                                    <svg class="w-3 h-3 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $data['judges_completed'] }}/{{ $data['judges_total'] }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Leyenda --}}
                <div class="flex flex-wrap justify-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                        ≥80% Excelente
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                        60-79% Bueno
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                        &lt;60% Mejorable
                    </span>
                </div>

            @else
                {{-- Sin proyectos --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">No hay proyectos registrados</h3>
                    <p class="text-gray-500 dark:text-gray-400">
                        Aún no hay equipos que hayan entregado proyectos en este evento.
                    </p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
