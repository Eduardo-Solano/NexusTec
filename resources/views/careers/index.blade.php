<x-app-layout>
    {{-- Animated Background --}}
    <div class="circuit-background-app"></div>
    <div class="light-particles-app"></div>

    <div class="min-h-screen py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="glass-card rounded-2xl p-8 shadow-2xl relative overflow-hidden animate-fade-in-down">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-1">Catálogo</p>
                        <h1 class="text-4xl font-black text-white flex items-center gap-3">
                            <span class="p-2 bg-blue-500/10 rounded-xl border border-blue-500/20">🎓</span>
                            Gestión de Carreras
                        </h1>
                        <p class="text-gray-400 mt-2 text-sm">Administra las carreras académicas activas en la plataforma.</p>
                    </div>
                    
                    <a href="{{ route('careers.create') }}" 
                        class="group relative inline-flex items-center justify-center px-6 py-3 overflow-hidden font-bold text-white transition-all duration-300 bg-blue-600 rounded-xl hover:bg-blue-500 hover:scale-105 shadow-lg hover:shadow-blue-500/40">
                        <span class="absolute inset-0 w-full h-full -mt-10 transition-all duration-700 opacity-0 bg-gradient-to-b from-transparent via-transparent to-black group-hover:opacity-20"></span>
                        <span class="relative flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nueva Carrera
                        </span>
                    </a>
                </div>
            </div>

            {{-- ALERTAS FLASH --}}
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/30 text-green-300 px-6 py-4 rounded-xl flex items-center gap-3 animate-fade-in-up" 
                     x-data="{ show: true }" x-show="show" x-transition>
                    <svg class="w-6 h-6 text-green-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-green-400 hover:text-green-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/30 text-red-300 px-6 py-4 rounded-xl flex items-center gap-3 animate-fade-in-up"
                     x-data="{ show: true }" x-show="show" x-transition>
                    <svg class="w-6 h-6 text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto text-red-400 hover:text-red-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif

            {{-- Filtros --}}
            <div class="glass-card rounded-2xl p-6 shadow-lg animate-fade-in-up" style="animation-delay: 100ms;">
                <form method="GET" action="{{ route('careers.index') }}" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Buscar por nombre o código..."
                            class="w-full bg-black/20 border border-white/10 rounded-xl pl-10 pr-4 py-3 text-white placeholder-gray-500 focus:ring-1 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-300 backdrop-blur-sm hover:bg-black/30">
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" 
                            class="px-6 py-3 bg-blue-600/80 hover:bg-blue-500 text-white rounded-xl transition-all duration-300 text-sm font-bold flex items-center gap-2 shadow-lg hover:shadow-blue-500/20 backdrop-blur-md">
                            Buscar
                        </button>
                        @if(request('search'))
                            <a href="{{ route('careers.index') }}" class="px-4 py-3 bg-gray-700/50 hover:bg-gray-600/50 text-gray-300 hover:text-white rounded-xl transition-all duration-300 text-sm font-bold backdrop-blur-md border border-white/5 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Limpiar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabla --}}
            <div class="glass-card rounded-2xl shadow-2xl overflow-hidden border border-white/5 animate-fade-in-up" style="animation-delay: 200ms;">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/10 bg-white/5">
                                <th class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Código</th>
                                <th class="px-6 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Nombre de la Carrera</th>
                                <th class="px-6 py-5 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Estudiantes</th>
                                <th class="px-6 py-5 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($careers as $career)
                                <tr class="hover:bg-white/5 transition duration-200 group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <span class="px-3 py-1 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-lg text-sm font-mono font-bold group-hover:bg-blue-500/20 group-hover:border-blue-500/40 transition-colors shadow-[0_0_10px_rgba(59,130,246,0.1)]">
                                                {{ $career->code }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-white font-bold text-sm tracking-wide group-hover:text-blue-200 transition-colors">{{ $career->name }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-gray-800/50 border border-gray-700/50 text-gray-300 text-xs font-bold backdrop-blur-sm group-hover:border-gray-500/50 transition-colors">
                                            <svg class="w-3 h-3 mr-1.5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                            {{ $career->student_profiles_count }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity">
                                            {{-- <a href="{{ route('careers.show', $career) }}" 
                                                class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition-all duration-300" title="Ver Detalles">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a> --}}
                                            <a href="{{ route('careers.edit', $career) }}" 
                                                class="p-2 text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition-all duration-300" title="Editar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            <form action="{{ route('careers.destroy', $career) }}" method="POST" class="inline"
                                                onsubmit="return confirm('¿Estás seguro de eliminar esta carrera? Esta acción no se puede deshacer.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-all duration-300" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-24 text-center">
                                        <div class="inline-flex p-6 rounded-full bg-white/5 mb-6 border border-white/10 animate-pulse-slow">
                                            <svg class="w-16 h-16 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-bold text-white mb-2">No se encontraron carreras</h3>
                                        <p class="text-gray-500 mb-6 max-w-sm mx-auto">No hay carreras registradas en el sistema o no coinciden con tu búsqueda.</p>
                                        <a href="{{ route('careers.create') }}" class="inline-flex items-center px-6 py-2 bg-blue-600/20 border border-blue-500/50 text-blue-400 font-bold rounded-lg hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300">
                                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Crear primera carrera
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($careers->hasPages())
                    <div class="px-6 py-4 bg-white/5 border-t border-white/10">
                        {{ $careers->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
