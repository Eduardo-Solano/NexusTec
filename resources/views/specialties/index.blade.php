<x-app-layout>
    {{-- Animated Background --}}
    <div class="circuit-background-app"></div>
    <div class="light-particles-app"></div>

    <div class="min-h-screen py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="glass-card rounded-2xl p-8 shadow-2xl relative overflow-hidden animate-fade-in-down">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-amber-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold text-amber-400 uppercase tracking-widest mb-1">Catálogo</p>
                        <h1 class="text-4xl font-black text-white flex items-center gap-3">
                            <span class="p-2 bg-amber-500/10 rounded-xl border border-amber-500/20">⚖️</span>
                            Gestión de Especialidades
                        </h1>
                        <p class="text-gray-400 mt-2 text-sm">Administra las áreas de experiencia para la evaluación de proyectos.</p>
                    </div>
                    
                    <a href="{{ route('specialties.create') }}" 
                        class="group relative inline-flex items-center justify-center px-6 py-3 overflow-hidden font-bold text-white transition-all duration-300 bg-amber-600 rounded-xl hover:bg-amber-500 hover:scale-105 shadow-lg hover:shadow-amber-500/40">
                        <span class="absolute inset-0 w-full h-full -mt-10 transition-all duration-700 opacity-0 bg-gradient-to-b from-transparent via-transparent to-black group-hover:opacity-20"></span>
                        <span class="relative flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nueva Especialidad
                        </span>
                    </a>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="glass-card rounded-2xl p-6 shadow-lg animate-fade-in-up" style="animation-delay: 100ms;">
                <form method="GET" action="{{ route('specialties.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-amber-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Buscar por nombre..."
                            class="w-full bg-black/20 border border-white/10 rounded-xl pl-10 pr-4 py-3 text-white placeholder-gray-500 focus:ring-1 focus:ring-amber-500/50 focus:border-amber-500/50 transition-all duration-300 backdrop-blur-sm hover:bg-black/30">
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" 
                            class="px-6 py-3 bg-amber-600/80 hover:bg-amber-500 text-white rounded-xl transition-all duration-300 text-sm font-bold flex items-center gap-2 shadow-lg hover:shadow-amber-500/20 backdrop-blur-md">
                            Buscar
                        </button>
                        @if(request('search'))
                            <a href="{{ route('specialties.index') }}" class="px-4 py-3 bg-gray-700/50 hover:bg-gray-600/50 text-gray-300 hover:text-white rounded-xl transition-all duration-300 text-sm font-bold backdrop-blur-md border border-white/5 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Limpiar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Grid de Especialidades --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 animate-fade-in-up" style="animation-delay: 200ms;">
                @forelse($specialties as $specialty)
                    <div class="glass-card rounded-2xl p-6 shadow-lg hover:border-amber-500/50 transition duration-300 group relative overflow-hidden">
                         <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-400 to-orange-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
                        
                        <div class="flex items-start justify-between mb-4 relative z-10">
                            <div class="p-3 bg-amber-500/10 rounded-xl group-hover:bg-amber-500/20 transition-colors border border-amber-500/10">
                                <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="{{ route('specialties.edit', $specialty) }}" 
                                    class="p-2 text-gray-400 hover:text-amber-400 hover:bg-amber-500/10 rounded-lg transition" title="Editar">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('specialties.destroy', $specialty) }}" method="POST" class="inline"
                                    onsubmit="return confirm('¿Eliminar esta especialidad? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition" title="Eliminar">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-amber-400 transition-colors relative z-10">
                            {{ $specialty->name }}
                        </h3>

                        <div class="flex items-center justify-between pt-4 border-t border-white/5 relative z-10">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-sm text-gray-400 font-medium">{{ $specialty->judge_profiles_count }} jueces</span>
                            </div>
                            
                            {{-- <a href="{{ route('specialties.show', $specialty) }}" 
                                class="text-xs text-amber-400 hover:text-amber-300 font-bold uppercase tracking-wider flex items-center gap-1 group-hover:gap-2 transition-all">
                                Detalles <span class="text-lg">→</span>
                            </a> --}}
                        </div>
                    </div>
                @empty
                    <div class="col-span-full glass-card rounded-2xl p-12 text-center border border-white/10">
                        <div class="inline-flex p-6 rounded-full bg-white/5 mb-6 border border-white/10 animate-pulse-slow">
                            <svg class="w-16 h-16 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">No se encontraron especialidades</h3>
                        <p class="text-gray-500 mb-6 max-w-sm mx-auto">No hay especialidades registradas en el sistema o no coinciden con tu búsqueda.</p>
                        <a href="{{ route('specialties.create') }}" class="inline-flex items-center px-6 py-2 bg-amber-600/20 border border-amber-500/50 text-amber-400 font-bold rounded-lg hover:bg-amber-600 hover:text-white hover:border-amber-600 transition-all duration-300">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Crear primera especialidad
                        </a>
                    </div>
                @endforelse
            </div>

            @if($specialties->hasPages())
                <div class="mt-6 px-4">
                    {{ $specialties->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
