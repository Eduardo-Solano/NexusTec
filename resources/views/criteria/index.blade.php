<x-app-layout>
    <!-- Fondo animado -->
    <div class="fixed inset-0 bg-gradient-to-br from-[#0a1128] via-[#0d1b2a] to-[#1b263b] -z-10">
        <!-- Grid de circuitos -->
        <div class="absolute inset-0 opacity-40">
            <div class="absolute inset-0" style="
                background-image: 
                    linear-gradient(rgba(6, 182, 212, 0.1) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(6, 182, 212, 0.1) 1px, transparent 1px);
                background-size: 80px 80px;
                animation: circuit-flow-app 8s linear infinite;
            "></div>
        </div>

        <!-- Partículas de luz -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute inset-0">
                <svg class="absolute w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <filter id="glow-app">
                            <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                            <feMerge>
                                <feMergeNode in="coloredBlur"/>
                                <feMergeNode in="SourceGraphic"/>
                            </feMerge>
                        </filter>
                    </defs>
                    @for ($i = 0; $i < 7; $i++)
                        <circle 
                            cx="{{ rand(0, 100) }}%" 
                            cy="{{ rand(0, 100) }}%" 
                            r="{{ rand(2, 4) }}" 
                            fill="#06B6D4" 
                            opacity="0.6"
                            filter="url(#glow-app)"
                            style="animation: particles-pulse-app 2s ease-in-out infinite {{ $i * 0.3 }}s, particles-move-app 12s ease-in-out infinite {{ $i * 1.5 }}s;"
                        />
                    @endfor
                </svg>
            </div>
        </div>

        <!-- Líneas de circuito flotantes horizontales -->
        <div class="absolute inset-0 overflow-hidden opacity-30">
            <div class="absolute h-px bg-gradient-to-r from-transparent via-cyan-400 to-transparent w-full top-1/4" 
                 style="animation: line-flow-1-app 3s ease-in-out infinite;"></div>
            <div class="absolute h-px bg-gradient-to-r from-transparent via-blue-400 to-transparent w-full top-2/4" 
                 style="animation: line-flow-2-app 3.5s ease-in-out infinite 0.5s;"></div>
            <div class="absolute h-px bg-gradient-to-r from-transparent via-cyan-400 to-transparent w-full top-3/4" 
                 style="animation: line-flow-1-app 4s ease-in-out infinite 1s;"></div>
        </div>
    </div>

    <style>
        @keyframes circuit-flow-app {
            0% { transform: translateX(0) translateY(0); }
            100% { transform: translateX(80px) translateY(80px); }
        }
        
        @keyframes particles-pulse-app {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.5); }
        }
        
        @keyframes particles-move-app {
            0%, 100% { transform: translate(0, 0); }
            25% { transform: translate(100px, -100px); }
            50% { transform: translate(-50px, -150px); }
            75% { transform: translate(-100px, 50px); }
        }
        
        @keyframes line-flow-1-app {
            0%, 100% { transform: translateX(-100%); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateX(100%); }
        }
        
        @keyframes line-flow-2-app {
            0%, 100% { transform: translateX(100%); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateX(-100%); }
        }
    </style>

    <div class="relative z-10 py-12 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-white">Criterios de Evaluación</h2>
                    <p class="text-gray-400 text-sm mt-1">Gestiona los criterios que se usarán para evaluar los proyectos</p>
                </div>
                <a href="{{ route('criteria.create') }}" 
                    class="bg-ito-orange/70 hover:bg-ito-orange text-white text-sm font-bold py-2.5 px-5 rounded-lg shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Criterio
                </a>
            </div>

            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="mb-6 bg-green-900/30 border border-green-500/50 rounded-lg p-4 flex items-center gap-3">
                    <div class="p-2 bg-green-500/20 rounded-full">
                        <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-green-400 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Mensaje de error --}}
            @if (session('error'))
                <div class="mb-6 bg-red-900/30 border border-red-500/50 rounded-lg p-4 flex items-center gap-3">
                    <div class="p-2 bg-red-500/20 rounded-full">
                        <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </div>
                    <p class="text-red-400 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Estadísticas rápidas --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white/[0.02] backdrop-blur-xl border border-white/20 rounded-xl p-5 hover:border-white/30 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Criterios</p>
                            <p class="text-2xl font-black text-white mt-1">{{ $criteria->total() }}</p>
                        </div>
                        <div class="p-3 bg-blue-500/10 rounded-xl">
                            <svg class="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white/[0.02] backdrop-blur-xl border border-white/20 rounded-xl p-5 hover:border-white/30 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Puntos Totales</p>
                            <p class="text-2xl font-black text-white mt-1">{{ $criteria->sum('max_points') }}</p>
                        </div>
                        <div class="p-3 bg-green-500/10 rounded-xl">
                            <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white/[0.02] backdrop-blur-xl border border-white/20 rounded-xl p-5 hover:border-white/30 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Promedio por Criterio</p>
                            <p class="text-2xl font-black text-white mt-1">{{ $criteria->count() > 0 ? round($criteria->sum('max_points') / $criteria->count(), 1) : 0 }} pts</p>
                        </div>
                        <div class="p-3 bg-purple-500/10 rounded-xl">
                            <svg class="w-6 h-6 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla de criterios --}}
            <div class="bg-white/[0.02] backdrop-blur-xl border border-white/20 rounded-2xl overflow-hidden shadow-xl hover:border-white/30 transition-colors">
                <div class="px-6 py-5 border-b border-gray-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-ito-orange/10 rounded-lg">
                            <svg class="w-5 h-5 text-ito-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white">Lista de Criterios</h3>
                            <p class="text-xs text-gray-500">Criterios disponibles para asignar a eventos</p>
                        </div>
                    </div>
                </div>

                <table class="w-full">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Criterio</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Puntos Máximos</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Eventos Asignados</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @forelse ($criteria as $criterion)
                            <tr class="hover:bg-gray-700/30 transition group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-ito-orange to-orange-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                            {{ strtoupper(substr($criterion->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-white group-hover:text-ito-orange transition">{{ $criterion->name }}</p>
                                            <p class="text-xs text-gray-500">ID: #{{ str_pad($criterion->id, 3, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-500/10 border border-blue-500/20">
                                        <svg class="w-4 h-4 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        <span class="text-blue-400 font-bold">{{ $criterion->max_points }}</span>
                                        <span class="text-blue-400/60 ml-1">pts</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-700/50 border border-gray-600">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-gray-300 font-medium">{{ $criterion->events_count ?? $criterion->events->count() }}</span>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('criteria.edit', $criterion) }}" 
                                            class="p-2 text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition" title="Editar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('criteria.destroy', $criterion) }}" method="POST" 
                                            onsubmit="return confirm('¿Estás seguro de eliminar este criterio? Esta acción no se puede deshacer.');">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition" title="Eliminar">
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
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-gray-700/50 rounded-full mb-4">
                                            <svg class="w-10 h-10 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-white mb-1">No hay criterios registrados</h3>
                                        <p class="text-gray-500 text-sm mb-6">Comienza creando el primer criterio de evaluación</p>
                                        <a href="{{ route('criteria.create') }}" 
                                            class="inline-flex items-center px-5 py-2.5 bg-ito-orange hover:bg-orange-600 text-white font-bold rounded-lg transition">
                                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Crear Primer Criterio
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($criteria->hasPages())
                    <div class="px-6 py-4 border-t border-gray-700 bg-gray-900/30">
                        {{ $criteria->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
