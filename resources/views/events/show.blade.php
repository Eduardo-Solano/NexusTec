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

                                @role('student')
                                    @if($event->is_active)
                                        <div class="mt-10">
                                            <a href="{{ route('teams.create', ['event_id' => $event->id]) }}" 
                                               class="inline-flex items-center px-8 py-4 bg-white text-gray-900 hover:bg-ito-orange hover:text-white font-black text-lg rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_30px_rgba(240,94,35,0.6)]">
                                                <span>🚀 Inscribir a mi Equipo</span>
                                            </a>
                                        </div>
                                    @endif
                                @endrole
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
                            
                            <div class="group relative flex flex-col justify-between h-full bg-gray-800 border-2 border-gray-700 rounded-2xl p-6 transition-all duration-300 hover:-translate-y-2 hover:border-ito-orange hover:shadow-[0_10px_40px_-10px_rgba(240,94,35,0.2)]">
                                
                                <div>
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="w-12 h-12 rounded-xl bg-gray-900 border border-gray-600 flex items-center justify-center text-gray-500 group-hover:text-ito-orange group-hover:border-ito-orange transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        
                                        <span class="text-xs font-bold text-gray-400 bg-black/40 px-2 py-1 rounded-md border border-gray-600">
                                            {{ $team->members->count() }}/5
                                        </span>
                                    </div>

                                    <h4 class="text-lg font-bold text-white mb-2 truncate group-hover:text-ito-orange transition">
                                        {{ $team->name }}
                                    </h4>
                                    
                                    <div class="flex items-center text-sm text-gray-400 mb-4">
                                        <div class="w-6 h-6 rounded-full bg-blue-900/50 flex items-center justify-center text-xs text-blue-400 font-bold mr-2 border border-blue-900">
                                            L
                                        </div>
                                        <span class="truncate">{{ $team->leader->name }}</span>
                                    </div>
                                </div>

                                @role('student')
                                    <div class="mt-4 pt-4 border-t border-gray-700/50">
                                        
                                        @if($team->members->contains(Auth::id()))
                                            <div class="w-full py-2 bg-green-500/10 border border-green-500/20 text-green-400 text-xs font-bold uppercase rounded-lg text-center shadow-[0_0_10px_rgba(74,222,128,0.1)]">
                                                ✅ Tu Equipo
                                            </div>

                                        @elseif($userHasTeam)
                                            <button disabled class="w-full py-2 bg-gray-900 border border-gray-700 text-gray-600 text-xs font-bold uppercase rounded-lg cursor-not-allowed opacity-50">
                                                🚫 Ya tienes equipo
                                            </button>

                                        @elseif(!$event->is_active)
                                            <div class="text-center text-gray-500 text-xs font-bold uppercase">
                                                Evento Cerrado
                                            </div>

                                        @elseif($team->members->count() >= 5)
                                            <div class="text-center w-full py-2 bg-red-500/10 text-red-400 text-xs font-bold uppercase rounded-lg border border-red-500/20">
                                                ⛔ Equipo Lleno
                                            </div>

                                        @else
                                            <form action="{{ route('teams.join', $team) }}" method="POST" 
                                                onsubmit="return confirm('⚠️ CONFIRMACIÓN ⚠️\n\n¿Deseas unirte al equipo {{ $team->name }}?');">
                                                @csrf
                                                <button type="submit" class="w-full py-2.5 bg-gray-700 hover:bg-ito-orange text-white text-xs font-bold uppercase tracking-wider rounded-lg transition-all duration-200 shadow-md group-hover:shadow-orange-500/20 border border-gray-600 group-hover:border-orange-500">
                                                    Unirme al Escuadrón
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                @endrole
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