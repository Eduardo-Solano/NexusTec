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
                    </div>
                @endcan

            @endcan

            @can('events.join')
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
            @endcan

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
