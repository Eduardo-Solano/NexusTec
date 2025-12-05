<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <div
                class="bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-ito-orange/20 rounded-full blur-3xl"></div>

                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400 font-bold uppercase tracking-wider mb-1">Panel de Control</p>
                        <h1 class="text-3xl font-black text-white">
                            Hola, <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">{{ Auth::user()->name }}</span>
                            👋
                        </h1>
                        <p class="text-gray-400 mt-2">Bienvenido de nuevo a NexusTec. Aquí tienes un resumen de tu
                            actividad.</p>
                    </div>
                    <div class="hidden md:block">
                        <div
                            class="h-12 w-12 bg-gray-700 rounded-full flex items-center justify-center border border-gray-600">
                            <span class="text-xl">📊</span>
                        </div>
                    </div>
                </div>
            </div>

            @can('teams.advise')

                @php
                    $myAdvisories = \App\Models\Team::where('advisor_id', Auth::id())
                        ->where('advisor_status', 'accepted')
                        ->with(['event', 'project'])
                        ->get();
                @endphp

                @if ($myAdvisories->count() > 0)
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <span class="bg-green-500/10 text-green-400 p-1.5 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </span>
                            Proyectos bajo tu Asesoría
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($myAdvisories as $team)
                                <div
                                    class="bg-gray-800 border border-gray-700 rounded-xl p-5 shadow-lg hover:border-green-500/50 transition group">
                                    <div class="flex justify-between items-start mb-3">
                                        <span
                                            class="text-[10px] font-bold uppercase tracking-wider text-blue-300 bg-blue-900/30 px-2 py-1 rounded border border-blue-500/20">
                                            {{ Str::limit($team->event->name ?? 'Sin evento', 15) }}
                                        </span>
                                        <span class="text-xs text-green-400 font-bold flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Activo
                                        </span>
                                    </div>
                                    <h4 class="text-lg font-bold text-white mb-1 group-hover:text-green-400 transition">
                                        {{ $team->name }}</h4>
                                    <p class="text-sm text-gray-400 mb-4">Proyecto: {{ $team->project->name ?? 'Sin proyecto aún' }}</p>
                                    <div class="pt-4 border-t border-gray-700 flex justify-between items-center">
                                        @if($team->project)
                                            <a href="{{ route('projects.show', $team->project) }}"
                                                class="text-sm font-bold text-white hover:text-green-400 transition">Ver
                                                Proyecto &rarr;</a>
                                            <a href="{{ $team->project->repository_url }}" target="_blank"
                                                class="text-gray-500 hover:text-white transition"><svg class="w-5 h-5"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                                                </svg></a>
                                        @else
                                            <a href="{{ route('teams.show', $team) }}"
                                                class="text-sm font-bold text-white hover:text-green-400 transition">Ver
                                                Equipo &rarr;</a>
                                            <span class="text-xs text-gray-500">Pendiente de proyecto</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            {{-- Sección para Jueces: Proyectos Asignados --}}
            @if(Auth::user()->hasRole('judge'))
                @php
                    $assignedProjects = $data['assigned_projects'] ?? collect();
                    $pendingProjects = $data['pending_projects'] ?? collect();
                    $completedProjects = $data['completed_projects'] ?? collect();
                @endphp

                {{-- Estadísticas del Juez --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-amber-900/30 to-gray-800 p-6 rounded-2xl border border-amber-500/30 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-400 text-xs font-bold uppercase">Pendientes</h3>
                            <span class="p-2 bg-amber-500/20 text-amber-400 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-black text-amber-400">{{ $data['pending_count'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">proyectos por evaluar</p>
                    </div>

                    <div class="bg-gradient-to-br from-green-900/30 to-gray-800 p-6 rounded-2xl border border-green-500/30 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-400 text-xs font-bold uppercase">Completados</h3>
                            <span class="p-2 bg-green-500/20 text-green-400 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-black text-green-400">{{ $data['completed_count'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">proyectos evaluados</p>
                    </div>

                    <div class="bg-gradient-to-br from-blue-900/30 to-gray-800 p-6 rounded-2xl border border-blue-500/30 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-400 text-xs font-bold uppercase">Total Asignados</h3>
                            <span class="p-2 bg-blue-500/20 text-blue-400 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-black text-blue-400">{{ $assignedProjects->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">proyectos asignados</p>
                    </div>

                    <div class="bg-gradient-to-br from-purple-900/30 to-gray-800 p-6 rounded-2xl border border-purple-500/30 shadow-lg">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-400 text-xs font-bold uppercase">Promedio</h3>
                            <span class="p-2 bg-purple-500/20 text-purple-400 rounded-lg">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-black text-purple-400">{{ $data['avg_score'] ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">puntuación promedio</p>
                    </div>
                </div>

                {{-- Barra de progreso --}}
                @if($assignedProjects->count() > 0)
                    <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 mb-8">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-bold text-white">Tu progreso de evaluación</span>
                            <span class="text-sm text-gray-400">{{ $completedProjects->count() }} / {{ $assignedProjects->count() }}</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-3">
                            @php
                                $progressPercent = $assignedProjects->count() > 0 
                                    ? round(($completedProjects->count() / $assignedProjects->count()) * 100) 
                                    : 0;
                            @endphp
                            <div class="bg-gradient-to-r from-amber-500 to-green-500 h-3 rounded-full transition-all duration-500" 
                                 style="width: {{ $progressPercent }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            @if($progressPercent == 100)
                                🎉 ¡Excelente! Has completado todas tus evaluaciones.
                            @elseif($progressPercent >= 50)
                                👍 Vas muy bien, sigue así.
                            @else
                                ⏰ Tienes evaluaciones pendientes.
                            @endif
                        </p>
                    </div>
                @endif

                <div class="mb-8">
                    <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                        <span class="bg-amber-500/10 text-amber-400 p-1.5 rounded-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </span>
                        Proyectos Asignados para Evaluar
                        <span class="ml-auto text-sm font-normal text-gray-400">
                            {{ $pendingProjects->count() }} pendientes / {{ $assignedProjects->count() }} total
                        </span>
                    </h3>

                    @if($assignedProjects->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($assignedProjects as $project)
                                    <div class="bg-gray-800 border {{ $project->pivot->is_completed ? 'border-green-500/30' : 'border-amber-500/30' }} rounded-xl p-5 shadow-lg hover:shadow-xl transition group relative overflow-hidden">
                                        {{-- Badge de estado --}}
                                        <div class="absolute top-0 right-0">
                                            @if($project->pivot->is_completed)
                                                <span class="bg-green-600 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg">
                                                    ✓ EVALUADO
                                                </span>
                                            @else
                                                <span class="bg-amber-600 text-white text-[10px] font-bold px-3 py-1 rounded-bl-lg animate-pulse">
                                                    PENDIENTE
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex justify-between items-start mb-3 mt-2">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-300 bg-blue-900/30 px-2 py-1 rounded border border-blue-500/20">
                                                {{ Str::limit($project->team->event->name ?? 'Sin evento', 20) }}
                                            </span>
                                        </div>

                                        <h4 class="text-lg font-bold text-white mb-1 group-hover:text-amber-400 transition">
                                            {{ $project->name }}
                                        </h4>
                                        <p class="text-sm text-gray-400 mb-2">Equipo: {{ $project->team->name }}</p>
                                        <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ Str::limit($project->description, 80) }}</p>

                                        {{-- Integrantes --}}
                                        <div class="flex items-center gap-1 mb-4">
                                            @foreach($project->team->members->take(4) as $member)
                                                <div class="w-7 h-7 rounded-full bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-300 border-2 border-gray-800 -ml-2 first:ml-0" title="{{ $member->name }}">
                                                    {{ substr($member->name, 0, 1) }}
                                                </div>
                                            @endforeach
                                            @if($project->team->members->count() > 4)
                                                <span class="text-xs text-gray-500 ml-1">+{{ $project->team->members->count() - 4 }}</span>
                                            @endif
                                        </div>

                                        <div class="pt-4 border-t border-gray-700 flex justify-between items-center">
                                            <a href="{{ route('projects.show', $project) }}"
                                                class="text-sm font-bold {{ $project->pivot->is_completed ? 'text-green-400 hover:text-green-300' : 'text-amber-400 hover:text-amber-300' }} transition">
                                                {{ $project->pivot->is_completed ? 'Ver Evaluación' : 'Evaluar Proyecto' }} &rarr;
                                            </a>
                                            <a href="{{ $project->repository_url }}" target="_blank"
                                                class="text-gray-500 hover:text-white transition" title="Ver repositorio">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-800 border border-gray-700 rounded-xl p-8 text-center">
                                <div class="inline-flex p-4 bg-gray-700 rounded-full mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <h4 class="text-white font-bold mb-1">Sin proyectos asignados</h4>
                                <p class="text-gray-400 text-sm">Aún no te han asignado proyectos para evaluar.</p>
                            </div>
                        @endif
                    </div>
                @endif

                @can('dashboard.stats')
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-400 text-xs font-bold uppercase">Estudiantes</h3>
                                <span class="p-2 bg-blue-500/10 text-blue-400 rounded-lg"><svg class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg></span>
                            </div>
                            <p class="text-3xl font-black text-white">{{ $data['total_students'] }}</p>
                        </div>

                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-400 text-xs font-bold uppercase">Eventos</h3>
                                <span class="p-2 bg-green-500/10 text-green-400 rounded-lg"><svg class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg></span>
                            </div>
                            <p class="text-3xl font-black text-white">{{ $data['active_events'] }}</p>
                        </div>

                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-400 text-xs font-bold uppercase">Equipos</h3>
                                <span class="p-2 bg-purple-500/10 text-purple-400 rounded-lg"><svg class="w-5 h-5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg></span>
                            </div>
                            <p class="text-3xl font-black text-white">{{ $data['total_teams'] }}</p>
                        </div>

                        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-gray-400 text-xs font-bold uppercase">Proyectos</h3>
                                <span class="p-2 bg-orange-500/10 text-orange-400 rounded-lg"><svg class="w-5 h-5"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg></span>
                            </div>
                            <p class="text-3xl font-black text-white">{{ $data['projects_delivered'] }}</p>
                        </div>
                    </div>

                    {{-- ========== NUEVO: Progreso del Evento Activo ========== --}}
                    @if(isset($data['event_progress']))
                        <div class="bg-gradient-to-br from-gray-800 via-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-6 shadow-xl mt-8">
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-gradient-to-br from-ito-orange/20 to-purple-500/20 rounded-xl">
                                        <svg class="w-7 h-7 text-ito-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-white">Progreso del Evento Activo</h3>
                                        <p class="text-sm text-gray-400">{{ $data['event_progress']['event']->name }}</p>
                                    </div>
                                </div>
                                
                                {{-- Countdown --}}
                                @if($data['event_progress']['days_remaining'] > 0)
                                    <div class="flex items-center gap-2 px-4 py-2 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-blue-400 font-bold">{{ $data['event_progress']['days_remaining'] }} días restantes</span>
                                    </div>
                                @elseif($data['event_progress']['days_remaining'] == 0)
                                    <div class="flex items-center gap-2 px-4 py-2 bg-amber-500/10 border border-amber-500/20 rounded-xl animate-pulse">
                                        <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-amber-400 font-bold">¡Último día!</span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 px-4 py-2 bg-red-500/10 border border-red-500/20 rounded-xl">
                                        <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-red-400 font-bold">Evento Finalizado</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Progress Steps --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                {{-- Equipos Registrados --}}
                                <div class="bg-gray-900/50 rounded-xl p-4 border border-gray-700">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-2xl">👥</span>
                                        <span class="text-xs font-bold text-gray-500">PASO 1</span>
                                    </div>
                                    <p class="text-2xl font-black text-white">{{ $data['event_progress']['teams_count'] }}</p>
                                    <p class="text-xs text-gray-400">Equipos Inscritos</p>
                                    <div class="mt-2 h-1.5 bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-blue-500 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>

                                {{-- Proyectos Entregados --}}
                                <div class="bg-gray-900/50 rounded-xl p-4 border border-gray-700">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-2xl">📋</span>
                                        <span class="text-xs font-bold text-gray-500">PASO 2</span>
                                    </div>
                                    <p class="text-2xl font-black text-white">{{ $data['event_progress']['projects_count'] }}</p>
                                    <p class="text-xs text-gray-400">Proyectos Entregados</p>
                                    <div class="mt-2 h-1.5 bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-purple-500 rounded-full transition-all duration-500" style="width: {{ $data['event_progress']['projects_percent'] }}%"></div>
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-1">{{ $data['event_progress']['projects_percent'] }}% de equipos</p>
                                </div>

                                {{-- Evaluaciones --}}
                                <div class="bg-gray-900/50 rounded-xl p-4 border border-gray-700">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-2xl">⚖️</span>
                                        <span class="text-xs font-bold text-gray-500">PASO 3</span>
                                    </div>
                                    <p class="text-2xl font-black text-white">{{ $data['event_progress']['total_evaluations'] }}<span class="text-sm text-gray-500">/{{ $data['event_progress']['required_evaluations'] }}</span></p>
                                    <p class="text-xs text-gray-400">Evaluaciones Completadas</p>
                                    <div class="mt-2 h-1.5 bg-gray-700 rounded-full overflow-hidden">
                                        @php
                                            $evalPercent = $data['event_progress']['required_evaluations'] > 0 
                                                ? round(($data['event_progress']['total_evaluations'] / $data['event_progress']['required_evaluations']) * 100) 
                                                : 0;
                                        @endphp
                                        <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: {{ $evalPercent }}%"></div>
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-1">{{ $evalPercent }}% completado</p>
                                </div>

                                {{-- Premiaciones --}}
                                <div class="bg-gray-900/50 rounded-xl p-4 border border-gray-700">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-2xl">🏆</span>
                                        <span class="text-xs font-bold text-gray-500">PASO 4</span>
                                    </div>
                                    <p class="text-2xl font-black text-white">{{ $data['event_progress']['awards_count'] }}</p>
                                    <p class="text-xs text-gray-400">Premios Asignados</p>
                                    <div class="mt-2 h-1.5 bg-gray-700 rounded-full overflow-hidden">
                                        @php
                                            $awardsPercent = $data['event_progress']['evaluated_count'] > 0 && $data['event_progress']['awards_count'] > 0 ? 100 : 0;
                                        @endphp
                                        <div class="h-full bg-green-500 rounded-full transition-all duration-500" style="width: {{ $awardsPercent }}%"></div>
                                    </div>
                                    <p class="text-[10px] text-gray-500 mt-1">{{ $data['event_progress']['evaluated_count'] }} proyectos evaluados</p>
                                </div>
                            </div>

                            {{-- Acciones Rápidas --}}
                            <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-700">
                                <a href="{{ route('events.show', $data['event_progress']['event']) }}" 
                                    class="px-4 py-2 bg-ito-orange hover:bg-orange-600 text-white text-sm font-bold rounded-lg transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver Evento
                                </a>
                                <a href="{{ route('events.rankings', $data['event_progress']['event']) }}" 
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-500 text-white text-sm font-bold rounded-lg transition flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Ver Rankings
                                </a>
                                <a href="{{ route('export.diplomas', $data['event_progress']['event']) }}" 
                                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold rounded-lg transition flex items-center gap-2 border border-gray-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Generar Diplomas
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Sección de Gráficas --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                        {{-- Gráfica de Equipos por Día --}}
                        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-blue-500/10 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-white">Equipos Registrados</h3>
                                        <p class="text-xs text-gray-500">Últimos 14 días</p>
                                    </div>
                                </div>
                            </div>
                            <div class="h-48">
                                <canvas id="teamsChart"></canvas>
                            </div>
                        </div>

                        {{-- Gráfica de Estudiantes por Carrera --}}
                        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-purple-500/10 rounded-lg">
                                        <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-white">Estudiantes por Carrera</h3>
                                        <p class="text-xs text-gray-500">Top 5 carreras</p>
                                    </div>
                                </div>
                            </div>
                            <div class="h-48">
                                <canvas id="careersChart"></canvas>
                            </div>
                        </div>

                        {{-- Gráfica de Equipos por Evento --}}
                        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg lg:col-span-2">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-green-500/10 rounded-lg">
                                        <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-white">Participación por Evento</h3>
                                        <p class="text-xs text-gray-500">Equipos inscritos en cada evento</p>
                                    </div>
                                </div>
                            </div>
                            <div class="h-64">
                                <canvas id="eventsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden shadow-lg mt-8">
                        <div class="px-6 py-5 border-b border-gray-700 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-purple-500/10 rounded-lg">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-white">Equipos Registrados Recientemente</h3>
                                    <p class="text-xs text-gray-500">Últimos equipos inscritos en eventos activos</p>
                                </div>
                            </div>
                            <a href="{{ route('teams.index') }}" class="text-xs text-gray-400 hover:text-white transition flex items-center gap-1">
                                Ver todos
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                        
                        <div class="divide-y divide-gray-700/50">
                            @forelse ($data['recent_teams'] as $team)
                                <div class="px-6 py-4 hover:bg-gray-700/30 transition group">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            {{-- Avatar del equipo --}}
                                            <div class="relative">
                                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                                    {{ strtoupper(substr($team->name, 0, 2)) }}
                                                </div>
                                                <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-gray-800 rounded-full"></span>
                                            </div>
                                            
                                            {{-- Info del equipo --}}
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h4 class="font-bold text-white group-hover:text-purple-400 transition">{{ $team->name }}</h4>
                                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                                        {{ $team->members->count() }}/5
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-xs text-gray-500">{{ $team->event->name }}</span>
                                                    <span class="text-gray-600">•</span>
                                                    <span class="text-xs text-gray-500">Líder: {{ $team->leader->name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Fecha y acciones --}}
                                        <div class="flex items-center gap-4">
                                            <div class="text-right hidden sm:block">
                                                <p class="text-xs text-gray-500">Registrado</p>
                                                <p class="text-sm text-gray-400 font-mono">{{ $team->created_at->diffForHumans() }}</p>
                                            </div>
                                            <a href="{{ route('teams.show', $team) }}" 
                                                class="p-2.5 bg-gray-700/50 hover:bg-ito-orange text-gray-400 hover:text-white rounded-xl transition group-hover:bg-ito-orange group-hover:text-white">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-6 py-12 text-center">
                                    <div class="inline-flex p-3 rounded-full bg-gray-700/50 mb-3">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500 text-sm">No hay equipos registrados recientemente</p>
                                </div>
                            @endforelse
                        </div>
                        
                        @if(count($data['recent_teams']) > 0)
                            <div class="px-6 py-4 bg-gray-900/30 border-t border-gray-700">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500">Mostrando {{ count($data['recent_teams']) }} equipos más recientes</span>
                                    <a href="{{ route('events.index') }}" class="text-ito-orange hover:text-orange-400 font-bold transition">
                                        Ver todos los eventos →
                                    </a>
                                </div>
                            </div>
                        @endif
                @endcan

            {{-- ========== SECCIÓN ESTUDIANTES ========== --}}
            @role('student')
                <div class="grid lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        <h3 class="text-xl font-bold text-white">Tu Actividad</h3>
                        @if (isset($data['my_team']))
                            <div
                                class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg relative overflow-hidden group">
                                <div
                                    class="absolute top-0 right-0 w-32 h-32 bg-ito-orange/10 rounded-full blur-3xl -mr-10 -mt-10">
                                </div>
                                <div class="relative z-10">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <p class="text-xs text-ito-orange font-bold uppercase tracking-wider mb-1">
                                                Participando en</p>
                                            <h2 class="text-2xl font-black text-white">{{ $data['my_team']->event->name }}
                                            </h2>
                                        </div>
                                        <span
                                            class="bg-green-500/10 text-green-400 border border-green-500/20 px-3 py-1 rounded-lg text-xs font-bold uppercase">Activo</span>
                                    </div>
                                    <div
                                        class="bg-gray-900/50 p-4 rounded-xl border border-gray-700 mb-6 flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center text-2xl border border-gray-600">
                                            🛡️</div>
                                        <div>
                                            <p class="text-gray-400 text-xs uppercase font-bold">Tu Equipo</p>
                                            <p class="text-white font-bold text-lg">{{ $data['my_team']->name }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <a href="{{ route('events.show', $data['my_team']->event_id) }}"
                                            class="flex items-center justify-center py-3 bg-white text-gray-900 font-bold rounded-xl hover:bg-gray-200 transition">Ver
                                            Detalles</a>
                                        @if ($data['my_team']->project)
                                            <a href="{{ route('projects.show', $data['my_team']->project) }}"
                                                class="flex items-center justify-center py-3 bg-gray-700 text-white font-bold rounded-xl hover:bg-gray-600 transition border border-gray-600">Ver
                                                Proyecto</a>
                                        @elseif ($data['my_team']->leader_id === Auth::id())
                                            <a href="{{ route('projects.create', ['team_id' => $data['my_team']->id]) }}"
                                                class="flex items-center justify-center py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-500 transition shadow-lg shadow-purple-500/20">Entregar
                                                Proyecto</a>
                                        @else
                                            <div class="flex items-center justify-center py-3 bg-gray-700/50 text-gray-400 font-medium rounded-xl border border-gray-600 text-sm">
                                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                                Solo líder
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- ========== NUEVO: Progreso del Equipo ========== --}}
                            @if(isset($data['team_progress']))
                                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-purple-500/10 rounded-lg">
                                                <svg class="w-5 h-5 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-white">Tu Progreso</h3>
                                                <p class="text-xs text-gray-500">{{ $data['team_progress']['completed'] }}/{{ $data['team_progress']['total'] }} pasos completados</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">{{ $data['team_progress']['percent'] }}%</span>
                                        </div>
                                    </div>

                                    {{-- Barra de progreso general --}}
                                    <div class="h-3 bg-gray-700 rounded-full overflow-hidden mb-6">
                                        <div class="h-full bg-gradient-to-r from-purple-500 to-blue-500 rounded-full transition-all duration-700" style="width: {{ $data['team_progress']['percent'] }}%"></div>
                                    </div>

                                    {{-- Pasos del progreso --}}
                                    <div class="space-y-3">
                                        @foreach($data['team_progress']['steps'] as $key => $step)
                                            <div class="flex items-center gap-3 p-3 rounded-lg {{ $step['completed'] ? 'bg-green-500/10 border border-green-500/20' : 'bg-gray-900/50 border border-gray-700' }}">
                                                <span class="text-xl">{{ $step['icon'] }}</span>
                                                <div class="flex-1">
                                                    <p class="text-sm font-bold {{ $step['completed'] ? 'text-green-400' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                                                </div>
                                                @if($step['completed'])
                                                    <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Puntaje si está disponible --}}
                                    @if(isset($data['team_progress']['score']))
                                        <div class="mt-6 pt-4 border-t border-gray-700">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-xs text-gray-500 uppercase font-bold">Tu Puntaje Actual</p>
                                                    <p class="text-3xl font-black text-white">{{ $data['team_progress']['score'] }}<span class="text-lg text-gray-500">/{{ $data['my_team']->event->max_score ?? 100 }}</span></p>
                                                </div>
                                                <div class="w-20 h-20">
                                                    <svg viewBox="0 0 36 36" class="circular-chart">
                                                        <path class="circle-bg" stroke="#374151" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                                        <path class="circle" stroke="url(#gradient)" stroke-width="3" stroke-linecap="round" fill="none" stroke-dasharray="{{ $data['team_progress']['score_percent'] }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                                                        <defs>
                                                            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                                                <stop offset="0%" stop-color="#A855F7"/>
                                                                <stop offset="100%" stop-color="#3B82F6"/>
                                                            </linearGradient>
                                                        </defs>
                                                        <text x="18" y="20.35" class="percentage" fill="white" font-size="8" font-weight="bold" text-anchor="middle">{{ $data['team_progress']['score_percent'] }}%</text>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Días restantes --}}
                                    @if(isset($data['team_progress']['days_remaining']))
                                        <div class="mt-4 flex items-center justify-center gap-2 p-3 rounded-lg {{ $data['team_progress']['days_remaining'] > 3 ? 'bg-blue-500/10 border border-blue-500/20' : ($data['team_progress']['days_remaining'] > 0 ? 'bg-amber-500/10 border border-amber-500/20' : 'bg-green-500/10 border border-green-500/20') }}">
                                            <svg class="w-5 h-5 {{ $data['team_progress']['days_remaining'] > 3 ? 'text-blue-400' : ($data['team_progress']['days_remaining'] > 0 ? 'text-amber-400' : 'text-green-400') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            @if($data['team_progress']['days_remaining'] > 0)
                                                <span class="font-bold {{ $data['team_progress']['days_remaining'] > 3 ? 'text-blue-400' : 'text-amber-400' }}">{{ $data['team_progress']['days_remaining'] }} días restantes para el evento</span>
                                            @elseif($data['team_progress']['days_remaining'] == 0)
                                                <span class="font-bold text-amber-400 animate-pulse">¡Último día del evento!</span>
                                            @else
                                                <span class="font-bold text-green-400">Evento finalizado</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="bg-gray-800 border-2 border-dashed border-gray-700 rounded-2xl p-10 text-center">
                                <div class="inline-flex p-4 rounded-full bg-gray-700 mb-4 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-white">No estás inscrito en ningún evento</h3>
                                <p class="text-gray-400 mt-2 mb-6">Explora los eventos disponibles y únete a la
                                    competencia.</p>
                                <a href="{{ route('events.index') }}"
                                    class="inline-block px-6 py-3 bg-ito-orange hover:bg-orange-600 text-white font-bold rounded-xl transition">Ver
                                    Eventos</a>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-6">
                        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6">
                            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Próximos Eventos</h3>
                            <ul class="space-y-4">
                                @forelse($data['upcoming_events'] ?? [] as $evt)
                                    <li class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-lg bg-gray-900 flex flex-col items-center justify-center border border-gray-700 text-center">
                                            <span
                                                class="text-[10px] font-bold text-ito-orange uppercase">{{ $evt->start_date->format('M') }}</span>
                                            <span
                                                class="text-sm font-black text-white leading-none">{{ $evt->start_date->format('d') }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-white truncate">{{ $evt->name }}</p>
                                            <p class="text-xs text-gray-500 truncate">{{ $evt->teams_count ?? 0 }} equipos
                                                inscritos</p>
                                        </div>
                                        <a href="{{ route('events.show', $evt) }}"
                                            class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition">&rarr;</a>
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-500 italic">No hay eventos próximos.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            @endrole

        </div>
    </div>

    @can('dashboard.stats')
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración global de Chart.js
            Chart.defaults.color = '#9CA3AF';
            Chart.defaults.borderColor = '#374151';

            // Datos para gráfica de equipos por día
            const teamsData = @json($data['teams_by_day'] ?? []);
            const dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
            
            const teamsLabels = teamsData.map(item => {
                const date = new Date(item.date + 'T00:00:00');
                return dayNames[date.getDay()] + ' ' + date.getDate();
            });
            const teamsCounts = teamsData.map(item => item.count);

            // Gráfica de Equipos por Día (Línea con área)
            new Chart(document.getElementById('teamsChart'), {
                type: 'line',
                data: {
                    labels: teamsLabels.length ? teamsLabels : ['Sin datos'],
                    datasets: [{
                        label: 'Equipos',
                        data: teamsCounts.length ? teamsCounts : [0],
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                        pointBorderColor: '#1F2937',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
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
                            ticks: { stepSize: 1 },
                            grid: { color: 'rgba(55, 65, 81, 0.5)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Datos para gráfica de estudiantes por carrera
            const careersData = @json($data['students_by_career'] ?? []);
            const careersLabels = careersData.map(item => item.name.length > 15 ? item.name.substring(0, 15) + '...' : item.name);
            const careersCounts = careersData.map(item => item.count);

            // Gráfica de Estudiantes por Carrera (Dona)
            new Chart(document.getElementById('careersChart'), {
                type: 'doughnut',
                data: {
                    labels: careersLabels.length ? careersLabels : ['Sin datos'],
                    datasets: [{
                        data: careersCounts.length ? careersCounts : [1],
                        backgroundColor: [
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                        ],
                        borderColor: '#1F2937',
                        borderWidth: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: { size: 11 }
                            }
                        }
                    }
                }
            });

            // Datos para gráfica de eventos
            const eventsData = @json($data['projects_by_event'] ?? []);
            const eventsLabels = eventsData.map(item => item.name.length > 20 ? item.name.substring(0, 20) + '...' : item.name);
            const eventsCounts = eventsData.map(item => item.teams_count);

            // Gráfica de Participación por Evento (Barras Horizontales)
            new Chart(document.getElementById('eventsChart'), {
                type: 'bar',
                data: {
                    labels: eventsLabels.length ? eventsLabels : ['Sin eventos'],
                    datasets: [{
                        label: 'Equipos inscritos',
                        data: eventsCounts.length ? eventsCounts : [0],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                        ],
                        borderColor: [
                            'rgba(16, 185, 129, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(139, 92, 246, 1)',
                            'rgba(245, 158, 11, 1)',
                            'rgba(239, 68, 68, 1)',
                        ],
                        borderWidth: 1,
                        borderRadius: 6,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 },
                            grid: { color: 'rgba(55, 65, 81, 0.5)' }
                        },
                        y: {
                            grid: { display: false }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
    @endcan
</x-app-layout>
