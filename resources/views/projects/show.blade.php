<x-app-layout>
    <div class="min-h-screen bg-[#0f172a] py-12"> <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('events.show', $project->team->event_id) }}" class="inline-flex items-center text-gray-400 hover:text-white transition group">
                    <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center mr-3 border border-gray-700 group-hover:border-ito-orange">
                        <svg class="w-4 h-4 group-hover:text-ito-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </div>
                    <span class="text-sm font-medium">Volver al Evento</span>
                </a>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-gray-800 rounded-2xl p-8 border border-gray-700 shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-ito-orange to-orange-600"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-3 py-1 rounded-md bg-blue-500/10 text-blue-400 text-xs font-bold uppercase tracking-wider border border-blue-500/20">
                                    Proyecto Final
                                </span>
                                <span class="text-gray-500 text-xs">Entregado {{ $project->created_at->format('d M, Y') }}</span>
                            </div>

                            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                                {{ $project->name }}
                            </h1>

                            <div class="flex items-center gap-3 pt-2">
                                <p class="text-gray-400 text-sm uppercase font-bold tracking-widest">Desarrollado por:</p>
                                <div class="flex items-center px-4 py-1.5 bg-gray-900 rounded-full border border-gray-600">
                                    <span class="w-2 h-2 rounded-full bg-ito-orange mr-2 animate-pulse"></span>
                                    <span class="text-white font-bold text-sm">{{ $project->team->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-2xl p-8 border border-gray-700 shadow-lg">
                        <h3 class="text-lg font-bold text-white mb-6 flex items-center border-b border-gray-700 pb-4">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            Sobre la Solución
                        </h3>
                        
                        <div class="prose prose-invert max-w-none text-gray-300 leading-relaxed text-base">
                            {{ $project->description }}
                        </div>
                    </div>

                </div>

                <div class="space-y-6">
                    
                    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 shadow-lg">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Código Fuente</h3>
                        
                        <a href="{{ $project->repository_url }}" target="_blank" class="flex items-center justify-center w-full py-4 bg-white hover:bg-gray-200 text-gray-900 font-bold rounded-xl transition duration-200 shadow-lg group">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                            Ver en GitHub
                            <svg class="w-4 h-4 ml-2 text-gray-400 group-hover:text-gray-900 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        
                        <p class="text-center text-xs text-gray-500 mt-3">El repositorio debe ser público para su evaluación.</p>
                    </div>

                    <div class="bg-gray-800 rounded-2xl p-6 border border-gray-700 shadow-lg">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-700">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">INTEGRANTES</h3>
                            <span class="bg-gray-700 text-gray-300 text-xs font-bold px-2 py-0.5 rounded">{{ $project->team->members->count() }}</span>
                        </div>
                        
                        <ul class="space-y-4">
                            @php 
                                $leader = $project->team->members->find($project->team->leader_id);
                            @endphp
                            
                            @if($leader)
                            <li class="flex items-center gap-4 bg-blue-900/20 p-3 rounded-xl border border-blue-500/20">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white font-bold text-sm shadow-lg ring-1 ring-blue-500/50">
                                    {{ substr($leader->name, 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-white text-sm font-bold truncate">{{ $leader->name }}</p>
                                    <p class="text-blue-400 text-[10px] font-black uppercase tracking-wider truncate">
                                        {{ $leader->pivot->role ?? 'Líder del Equipo' }}
                                    </p>
                                </div>
                            </li>
                            @endif

                            @foreach($project->team->members as $member)
                                @if($member->id !== $project->team->leader_id)
                                    <li class="flex items-center gap-4 px-3 py-1 group opacity-80 hover:opacity-100 transition">
                                        <div class="w-9 h-9 rounded-lg bg-gray-700 flex items-center justify-center text-gray-300 font-bold text-xs border border-gray-600 group-hover:border-gray-500 transition">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="text-gray-200 text-sm font-medium truncate">{{ $member->name }}</p>
                                            <p class="text-gray-500 text-[10px] uppercase tracking-wider group-hover:text-gray-400 transition truncate">
                                                {{ $member->pivot->role ?? 'Colaborador' }}
                                            </p>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>