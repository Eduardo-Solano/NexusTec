<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            <div class="relative rounded-3xl overflow-hidden shadow-2xl bg-gradient-to-br from-tecnm-blue to-gray-900">
                
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-blue-500/20 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-60 h-60 rounded-full bg-ito-orange/20 blur-3xl"></div>

                <div class="relative p-8 md:p-12 text-white">
                    
                    <div class="flex justify-between items-start mb-6">
                        <a href="{{ route('events.index') }}" class="inline-flex items-center text-sm text-blue-200 hover:text-white transition font-medium bg-black/20 px-3 py-1 rounded-full backdrop-blur-sm">
                            &larr; Volver
                        </a>

                        <span class="px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $event->is_active ? 'bg-green-500 text-white shadow-[0_0_10px_rgba(34,197,94,0.5)]' : 'bg-red-500 text-white' }}">
                            {{ $event->is_active ? '🟢 Abierto' : '🔴 Cerrado' }}
                        </span>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-12">
                        
                        <div class="lg:col-span-2">
                            <h1 class="text-4xl md:text-6xl font-black tracking-tight mb-6 leading-tight">
                                {{ $event->name }}
                            </h1>
                            <div class="text-lg text-blue-100 leading-relaxed opacity-90 max-w-2xl">
                                {{ $event->description }}
                            </div>

                            @role('student')
                                @if($event->is_active)
                                    <div class="mt-8">
                                        <a href="{{ route('teams.create', ['event_id' => $event->id]) }}" 
                                           class="inline-flex items-center px-8 py-4 bg-ito-orange hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-600/30 transform hover:-translate-y-1 transition duration-200">
                                            <svg class="w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                            Inscribir a mi Equipo
                                        </a>
                                    </div>
                                @endif
                            @endrole
                        </div>

                        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-6 text-center">
                            <div class="mb-6">
                                <p class="text-xs text-blue-200 uppercase font-bold tracking-widest mb-1">Inicia el</p>
                                <p class="text-4xl font-black">{{ $event->start_date->format('d') }}</p>
                                <p class="text-sm font-bold uppercase text-ito-orange">{{ $event->start_date->format('M Y') }}</p>
                            </div>
                            
                            <div class="w-full h-px bg-white/10 my-4"></div>
                            
                            <div>
                                <p class="text-xs text-blue-200 uppercase font-bold tracking-widest mb-1">Termina el</p>
                                <p class="text-2xl font-bold opacity-80">{{ $event->end_date->format('d') }}</p>
                                <p class="text-xs font-bold uppercase opacity-60">{{ $event->end_date->format('M Y') }}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center mb-6">
                    <div class="h-8 w-1 bg-ito-orange rounded-full mr-3"></div>
                    <h3 class="text-2xl font-bold text-white">Equipos Participantes <span class="ml-2 text-gray-500 text-lg">({{ $event->teams->count() }})</span></h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($event->teams as $team)
                        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 hover:border-ito-orange transition duration-300 group">
                            <div class="flex justify-between items-start mb-4">
                                <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-ito-orange font-bold text-lg border border-gray-600">
                                    {{-- {{ substr($team->name, 7, 1) }} --}}
                                    {{-- <div>{{ strtoupper(substr($team->name, 7, 10)) }}</div> --}}
                                    👥
                                </div>
                                <span class="text-xs font-mono bg-black text-gray-400 border border-gray-700 px-2 py-1 rounded">
                                    {{ $team->members->count() }} / 5
                                </span>
                            </div>
                            
                            <h4 class="text-lg font-bold text-white truncate group-hover:text-ito-orange transition">
                                {{ $team->name }}
                            </h4>
                            
                            <div class="mt-3 flex items-center text-sm text-gray-400">
                                <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                                Líder: {{ Str::limit($team->leader->name, 15) }}
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-12 text-center border border-dashed border-gray-700 rounded-2xl">
                            <p class="text-gray-500">Sé el primero en registrarte en este evento.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>