<x-app-layout>
    
    <div class="min-h-screen bg-gray-900 relative">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-gray-900 via-transparent to-gray-900"></div>

        <div class="relative py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">
                
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-gray-700">
                    
                    <div class="absolute inset-0">
                        <img src="{{ asset('img/portada-ito.jpg') }}" class="w-full h-full object-cover opacity-20 blur-sm" alt="Fondo">
                        <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/90 to-tecnm-blue/40"></div>
                    </div>

                    <div class="relative p-8 md:p-12">
                        <div class="flex justify-between items-start mb-8">
                            <a href="{{ route('events.index') }}" class="flex items-center text-sm font-bold text-gray-400 hover:text-white transition group">
                                <div class="w-8 h-8 rounded-full bg-black/50 border border-gray-600 flex items-center justify-center mr-2 group-hover:border-ito-orange transition">
                                    &larr;
                                </div>
                                Volver
                            </a>
                            
                            <div class="px-4 py-1.5 rounded-full border {{ $event->is_active ? 'bg-green-500/10 border-green-500/50 text-green-400' : 'bg-red-500/10 border-red-500/50 text-red-400' }} backdrop-blur-md shadow-[0_0_15px_rgba(0,0,0,0.5)]">
                                <span class="flex items-center text-xs font-black uppercase tracking-widest gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $event->is_active ? 'bg-green-400 animate-pulse' : 'bg-red-400' }}"></span>
                                    {{ $event->is_active ? 'Inscripciones Abiertas' : 'Cerrado' }}
                                </span>
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-12 gap-10">
                            <div class="lg:col-span-8">
                                <h1 class="text-5xl md:text-7xl font-black text-white leading-none mb-6 drop-shadow-lg">
                                    {{ $event->name }}
                                </h1>
                                <div class="h-1 w-24 bg-ito-orange mb-6 rounded-full"></div>
                                <p class="text-xl text-gray-300 leading-relaxed font-light max-w-2xl">
                                    {{ $event->description }}
                                </p>

                                @can('events.join')
                                    @if($event->is_active)
                                        <div class="mt-10">
                                            @if($userHasTeam)
                                                <div class="inline-flex items-center px-6 py-3 bg-green-500/10 border border-green-500/20 rounded-xl">
                                                    <div class="flex items-center gap-3">
                                                        <div class="p-2 bg-green-500 rounded-full animate-pulse"></div>
                                                        <div>
                                                            <p class="text-green-400 font-bold text-sm uppercase tracking-wider">Tu participación está activa</p>
                                                            <p class="text-gray-400 text-xs">Ya perteneces a un equipo en este evento</p>
                                                        </div>
                                                    </div>
                                                </div>

                                            @else
                                                <a href="{{ route('teams.create', ['event_id' => $event->id]) }}" 
                                                class="inline-flex items-center px-8 py-4 bg-white text-gray-900 hover:bg-ito-orange hover:text-white font-black text-lg rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_30px_rgba(240,94,35,0.6)]">
                                                    <svg class="w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    <span>Crear Nuevo Equipo</span>
                                                </a>
                                                <p class="mt-4 text-sm text-gray-500">¿Buscas equipo? Revisa la lista de abajo y únete a uno.</p>
                                            @endif
                                        </div>
                                    @endif
                                @endcan
                            </div>

                            <div class="lg:col-span-4 flex flex-col gap-4">
                                <div class="bg-gray-800/80 backdrop-blur-md border-l-4 border-ito-orange p-5 rounded-r-xl shadow-lg">
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Inicia</p>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-4xl font-black text-white">{{ $event->start_date->format('d') }}</span>
                                        <span class="text-xl font-bold text-gray-300">{{ $event->start_date->format('M') }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $event->start_date->format('l, Y') }}</p>
                                </div>

                                <div class="bg-gray-800/80 backdrop-blur-md border-l-4 border-gray-600 p-5 rounded-r-xl shadow-lg">
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Finaliza</p>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-4xl font-black text-white">{{ $event->end_date->format('d') }}</span>
                                        <span class="text-xl font-bold text-gray-300">{{ $event->end_date->format('M') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECCIÓN: Mi Equipo / Equipo a Asesorar --}}
                @if($myTeam || $teamToAdvise)
                    <div class="space-y-6">
                        {{-- Tarjeta: Mi Equipo (Estudiante) --}}
                        @if($myTeam)
                            <div class="bg-gradient-to-r from-blue-900/40 to-gray-800 border-2 border-blue-500/30 rounded-2xl p-6 shadow-xl">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-blue-400 font-bold uppercase tracking-widest mb-1">Mi Equipo</p>
                                            <h3 class="text-2xl font-bold text-white">{{ $myTeam->name }}</h3>
                                            <div class="flex items-center gap-3 mt-1">
                                                <span class="text-xs text-gray-400">{{ $myTeam->members->count() }}/5 miembros</span>
                                                @if($myTeam->project)
                                                    <span class="text-xs text-green-400 flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                                        Proyecto entregado
                                                    </span>
                                                @else
                                                    <span class="text-xs text-yellow-400 flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                                        Proyecto pendiente
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('teams.show', $myTeam) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold text-sm rounded-xl transition-all duration-300 shadow-lg hover:shadow-blue-500/30">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver Mi Equipo
                                        </a>
                                        @can('teams.edit')
                                            <a href="{{ route('teams.edit', $myTeam) }}" 
                                               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-bold text-sm rounded-xl transition-all duration-300 border border-gray-600">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar Equipo
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Tarjeta: Equipo a Asesorar (Docente) --}}
                        @if($teamToAdvise)
                            <div class="bg-gradient-to-r from-purple-900/40 to-gray-800 border-2 border-purple-500/30 rounded-2xl p-6 shadow-xl">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-xl bg-purple-600/20 border border-purple-500/30 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs text-purple-400 font-bold uppercase tracking-widest mb-1">Equipo que Asesoro</p>
                                            <h3 class="text-2xl font-bold text-white">{{ $teamToAdvise->name }}</h3>
                                            <div class="flex items-center gap-3 mt-1">
                                                <span class="text-xs text-gray-400">Líder: {{ $teamToAdvise->leader->name ?? 'Sin líder' }}</span>
                                                @if($teamToAdvise->project)
                                                    <span class="text-xs text-green-400 flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                                        Proyecto entregado
                                                    </span>
                                                @else
                                                    <span class="text-xs text-yellow-400 flex items-center gap-1">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                                        Proyecto pendiente
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-3">
                                        <a href="{{ route('teams.show', $teamToAdvise) }}" 
                                           class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-500 text-white font-bold text-sm rounded-xl transition-all duration-300 shadow-lg hover:shadow-purple-500/30">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Ver Equipo Asesorado
                                        </a>
                                        @can('teams.edit')
                                            <a href="{{ route('teams.edit', $teamToAdvise) }}" 
                                               class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white font-bold text-sm rounded-xl transition-all duration-300 border border-gray-600">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar Equipo
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <div>
                    <div class="flex items-end justify-between mb-8 border-b border-gray-800 pb-4">
                        <div>
                            <h3 class="text-3xl font-bold text-white">Equipos</h3>
                            <p class="text-gray-500 mt-1">Participantes registrados en el evento</p>
                        </div>
                        <div class="text-4xl font-black text-gray-800">{{ str_pad($event->teams->count(), 2, '0', STR_PAD_LEFT) }}</div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($event->teams as $team)
                            
                            <div class="group relative flex flex-col h-full bg-gray-800 border-2 border-gray-700 rounded-2xl p-5 transition-all duration-300 hover:-translate-y-2 hover:border-ito-orange hover:shadow-[0_10px_40px_-10px_rgba(240,94,35,0.2)]">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="w-10 h-10 rounded-lg bg-gray-900 border border-gray-600 flex items-center justify-center text-gray-500 group-hover:text-ito-orange group-hover:border-ito-orange transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        @can('teams.edit')
                                            <a href="{{ route('teams.edit', $team) }}" 
                                               class="p-1.5 bg-gray-700 hover:bg-blue-600 text-gray-400 hover:text-white rounded-lg border border-gray-600 hover:border-blue-500 transition" 
                                               title="Editar Equipo">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        @endcan
                                        <span class="text-[10px] font-bold text-gray-400 bg-black/40 px-2 py-1 rounded border border-gray-600">
                                            {{ $team->members->count() }}/5
                                        </span>
                                    </div>
                                </div>

                                <h4 class="text-lg font-bold text-white mb-4 truncate group-hover:text-ito-orange transition">
                                    {{ $team->name }}
                                </h4>
                                
                                <div class="flex-grow space-y-2 mb-6">
                                    @php $leader = $team->members->find($team->leader_id); @endphp
                                    
                                    @if($leader)
                                    <div class="flex items-center justify-between text-sm p-2 rounded bg-blue-900/20 border border-blue-500/30">
                                        <div class="flex items-center gap-2 overflow-hidden">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0 animate-pulse"></span>
                                            <span class="text-gray-200 truncate font-bold text-xs">{{ Str::limit($leader->name, 14) }}</span>
                                        </div>
                                        <span class="text-[9px] uppercase font-black text-blue-300 bg-blue-900/50 px-2 py-0.5 rounded border border-blue-500/30 tracking-wider">
                                            {{ $leader->pivot->role ?? 'Capitán' }}
                                        </span>
                                    </div>
                                    @endif

                                    @foreach($team->members as $member)
                                        @if($member->id !== $team->leader_id)
                                            <div class="flex items-center justify-between text-xs p-1.5 rounded hover:bg-gray-700/50 transition group/member">
                                                <div class="flex items-center gap-2 overflow-hidden">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-600 group-hover/member:bg-ito-orange transition shrink-0"></span>
                                                    <span class="text-gray-400 truncate group-hover/member:text-gray-200 transition">{{ Str::limit($member->name, 14) }}</span>
                                                </div>
                                                
                                                <span class="text-[9px] uppercase font-bold text-gray-500 shrink-0 ml-1 border border-gray-700 px-2 py-0.5 rounded bg-gray-800 group-hover/member:border-gray-500 group-hover/member:text-gray-300 transition">
                                                    {{ $member->pivot->role ?? 'Miembro' }}
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                    
                                    @if($team->members->count() < 5)
                                        <div class="pt-2">
                                            <div class="border border-dashed border-gray-700 rounded p-1.5 text-center">
                                                <span class="text-[9px] text-gray-600 italic">Espacio disponible ({{ 5 - $team->members->count() }})</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if(Auth::id() === $team->leader_id)
                                        @php
                                            // Filtramos los miembros pendientes
                                            $pendingMembers = $team->members()->wherePivot('is_accepted', false)->get();
                                        @endphp

                                        @if($pendingMembers->count() > 0)
                                            <div class="mt-4 pt-4 border-t border-gray-700 bg-gray-900/50 -mx-5 -mb-5 p-4 rounded-b-2xl">
                                                <p class="text-xs text-gray-400 font-bold uppercase mb-3">Solicitudes Pendientes</p>
                                                
                                                @foreach($pendingMembers as $pending)
                                                    <div class="flex items-center justify-between bg-gray-800 p-2 rounded-lg mb-2 border border-gray-700">
                                                        <div>
                                                            <p class="text-white text-xs font-bold">{{ $pending->name }}</p>
                                                            <p class="text-[10px] text-gray-500">{{ $pending->pivot->role }}</p>
                                                        </div>
                                                        
                                                        <div class="flex gap-1">
                                                            <form action="{{ route('teams.accept', [$team, $pending]) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="p-1.5 bg-green-600 hover:bg-green-500 text-white rounded transition" title="Aceptar">
                                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                </button>
                                                            </form>

                                                            <form action="{{ route('teams.reject', [$team, $pending]) }}" method="POST">
                                                                @csrf
                                                                <button type="submit" class="p-1.5 bg-red-600 hover:bg-red-500 text-white rounded transition" title="Rechazar">
                                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                @can('teams.join')
                                    <div class="mt-auto pt-4 border-t border-gray-700/50">>
                                        
                                        @php
                                            // Buscamos si el usuario actual está en la lista de miembros
                                            $membership = $team->members->find(Auth::id());
                                        @endphp

                                        @if($membership)
                                            @if($membership->pivot->is_accepted)
                                                <div class="flex flex-col gap-2">
                                                    <div class="w-full py-1 bg-green-500/10 border border-green-500/20 text-green-400 text-[10px] font-bold uppercase rounded text-center">
                                                        ✅ Tu Equipo
                                                    </div>
                                                    @if($team->project)
                                                        <a href="{{ route('projects.show', $team->project) }}" class="w-full py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-bold uppercase rounded text-center transition">
                                                            VER PROYECTO
                                                        </a>
                                                    @elseif($team->leader_id === Auth::id())
                                                        <a href="{{ route('projects.create', ['team_id' => $team->id]) }}" class="w-full py-1.5 bg-purple-600 hover:bg-purple-500 text-white text-[10px] font-bold uppercase rounded text-center transition">
                                                            ENTREGAR
                                                        </a>
                                                    @else
                                                        <div class="w-full py-1.5 bg-gray-700/50 border border-gray-600 text-gray-400 text-[10px] font-bold uppercase rounded text-center flex items-center justify-center gap-1">
                                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                            </svg>
                                                            SOLO LÍDER
                                                        </div>
                                                    @endif
                                                </div>

                                            @else
                                                <div class="w-full py-2 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 text-[10px] font-bold uppercase rounded-lg text-center animate-pulse">
                                                    ⏳ Solicitud Pendiente
                                                </div>
                                            @endif

                                        @elseif($userHasTeam)
                                            <button disabled class="w-full py-2 bg-gray-900 border border-gray-700 text-gray-600 text-xs font-bold uppercase rounded cursor-not-allowed">
                                                🚫 Ya tienes equipo
                                            </button>

                                        @elseif(!$event->is_active)
                                            <div class="text-center text-gray-500 text-xs font-bold uppercase">Cerrado</div>

                                        @elseif($team->members->count() >= 5)
                                            <div class="text-center w-full py-2 bg-red-500/10 text-red-400 text-xs font-bold uppercase rounded border border-red-500/20">
                                                ⛔ Lleno
                                            </div>

                                        @else
                                            <form action="{{ route('teams.join', $team) }}" method="POST" class="space-y-2">
                                                @csrf
                                                <select name="role" required class="w-full bg-gray-900 border-gray-600 text-gray-300 text-[10px] rounded focus:border-ito-orange focus:ring-0 py-1">
                                                    <option value="" disabled selected>Elige Rol...</option>
                                                    @foreach($event->available_roles as $role)
                                                        <option value="{{ $role }}">{{ $role }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" onclick="return confirm('¿Enviar solicitud a {{ $team->name }}?')" 
                                                        class="w-full py-1.5 bg-gray-700 hover:bg-ito-orange text-white text-[10px] font-bold uppercase rounded transition">
                                                    ENVIAR SOLICITUD
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endcan
                            </div>

                        @empty
                            <div class="col-span-full py-16 text-center border-2 border-dashed border-gray-700 rounded-3xl bg-gray-800/30">
                                <div class="inline-flex p-4 rounded-full bg-gray-800 mb-4">
                                    <svg class="w-8 h-8 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                </div>
                                <h3 class="text-lg font-bold text-white">Zona Desierta</h3>
                                <p class="text-gray-500 mt-1">Sé el primer equipo en la arena.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
        
    </div>
</x-app-layout>