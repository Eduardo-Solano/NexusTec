<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-amber-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-amber-400 font-bold uppercase tracking-wider mb-1">Catálogo</p>
                        <h1 class="text-3xl font-black text-white flex items-center gap-3">
                            <span class="p-2 bg-amber-500/10 rounded-xl">⚖️</span>
                            Gestión de Especialidades
                        </h1>
                        <p class="text-gray-400 mt-2">Administra las especialidades de los jueces</p>
                    </div>
                    
                    <a href="{{ route('specialties.create') }}" 
                        class="inline-flex items-center px-5 py-3 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-xl transition shadow-lg hover:shadow-amber-500/25">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nueva Especialidad
                    </a>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                <form method="GET" action="{{ route('specialties.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Buscar por nombre..."
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition">
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" 
                            class="px-6 py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl transition text-sm font-bold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Buscar
                        </button>
                        <a href="{{ route('specialties.index') }}" class="px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-xl transition text-sm font-bold">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Grid de especialidades --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($specialties as $specialty)
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg hover:border-amber-500/50 transition group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="p-3 bg-amber-500/10 rounded-xl group-hover:bg-amber-500/20 transition">
                                <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('specialties.edit', $specialty) }}" 
                                    class="p-2 text-gray-400 hover:text-amber-400 hover:bg-amber-500/10 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('specialties.destroy', $specialty) }}" method="POST" class="inline"
                                    onsubmit="return confirm('¿Eliminar esta especialidad?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-white mb-2 group-hover:text-amber-400 transition">
                            {{ $specialty->name }}
                        </h3>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-sm text-gray-400">{{ $specialty->judge_profiles_count }} jueces</span>
                            </div>
                            <a href="{{ route('specialties.show', $specialty) }}" 
                                class="text-sm text-amber-400 hover:text-amber-300 font-bold">
                                Ver detalles →
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-gray-800 border border-gray-700 rounded-2xl p-12 text-center">
                        <div class="inline-flex p-4 rounded-full bg-gray-700/50 mb-4">
                            <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">No hay especialidades registradas</p>
                        <a href="{{ route('specialties.create') }}" class="text-amber-400 hover:text-amber-300 text-sm mt-2 inline-block">
                            Crear primera especialidad →
                        </a>
                    </div>
                @endforelse
            </div>

            @if($specialties->hasPages())
                <div class="mt-6">
                    {{ $specialties->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
