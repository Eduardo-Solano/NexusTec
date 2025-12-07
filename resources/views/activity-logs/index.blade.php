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

    <div class="relative z-10 min-h-screen py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-purple-400 font-bold uppercase tracking-wider mb-1">Administración</p>
                        <h1 class="text-3xl font-black text-white flex items-center gap-3">
                            <span class="p-2 bg-purple-500/10 rounded-xl">📋</span>
                            Historial de Actividades
                        </h1>
                        <p class="text-gray-400 mt-2">Registro de todas las acciones realizadas en el sistema</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500">Total de registros:</span>
                        <span class="px-3 py-1.5 bg-purple-500/10 text-purple-400 border border-purple-500/20 rounded-lg font-bold">
                            {{ $activities->total() }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Filtros --}}
            <div class="bg-white/[0.02] backdrop-blur-xl border border-white/20 rounded-2xl p-6 shadow-lg hover:border-white/30 transition-colors">
                <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    
                    {{-- Búsqueda --}}
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Buscar en descripción..."
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    </div>

                    {{-- Acción --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Acción</label>
                        <select name="action" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            <option value="">Todas</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tipo de Modelo --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Entidad</label>
                        <select name="model_type" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            <option value="">Todas</option>
                            @foreach($modelTypes as $type)
                                <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Fecha Desde --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Desde</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    </div>

                    {{-- Fecha Hasta --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Hasta</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    </div>

                    {{-- Botones --}}
                    <div class="lg:col-span-6 flex justify-end gap-3 pt-2">
                        <a href="{{ route('activity-logs.index') }}" 
                            class="px-4 py-2.5 bg-gray-700 hover:bg-gray-600 text-gray-300 rounded-xl transition text-sm font-bold">
                            Limpiar
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-tecnm-blue hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            {{-- Lista de Actividades --}}
            <div class="bg-white/[0.02] backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg overflow-hidden hover:border-white/30 transition-colors">
                @forelse($activities as $activity)
                    <div class="p-4 border-b border-gray-700 hover:bg-gray-700/30 transition group {{ $loop->last ? 'border-b-0' : '' }}">
                        <div class="flex items-start gap-4">
                            {{-- Icono --}}
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-{{ $activity->color }}-500/10 border border-{{ $activity->color }}-500/20 flex items-center justify-center text-lg">
                                {{ $activity->icon }}
                            </div>

                            {{-- Contenido --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded bg-{{ $activity->color }}-500/10 text-{{ $activity->color }}-400 border border-{{ $activity->color }}-500/20">
                                        {{ $activity->action }}
                                    </span>
                                    @if($activity->model_type)
                                        <span class="text-xs text-gray-500">
                                            {{ class_basename($activity->model_type) }}
                                            @if($activity->model_id)
                                                #{{ $activity->model_id }}
                                            @endif
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-white font-medium">{{ $activity->description }}</p>
                                
                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                    @if($activity->user)
                                        <span class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $activity->user->name }}
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1.5 text-gray-600">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Sistema
                                        </span>
                                    @endif
                                    
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $activity->created_at->diffForHumans() }}
                                    </span>

                                    @if($activity->ip_address)
                                        <span class="flex items-center gap-1.5 hidden md:flex">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                            {{ $activity->ip_address }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Propiedades adicionales --}}
                                @if($activity->properties && count($activity->properties) > 0)
                                    <div class="mt-3 p-3 bg-gray-900/50 rounded-lg border border-gray-700">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($activity->properties as $key => $value)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs bg-white/[0.02] backdrop-blur-xl border border-white/20">
                                                    <span class="text-gray-500 mr-1.5">{{ str_replace('_', ' ', ucfirst($key)) }}:</span>
                                                    <span class="text-gray-300 font-medium">
                                                        @if(is_array($value))
                                                            {{ implode(', ', $value) }}
                                                        @else
                                                            {{ $value }}
                                                        @endif
                                                    </span>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Timestamp --}}
                            <div class="flex-shrink-0 text-right hidden lg:block">
                                <p class="text-xs text-gray-500">{{ $activity->created_at->format('d/m/Y') }}</p>
                                <p class="text-sm font-mono text-gray-400">{{ $activity->created_at->format('H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="inline-flex p-4 rounded-full bg-gray-700/50 mb-4">
                            <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">No hay registros de actividad</p>
                        <p class="text-gray-600 text-sm mt-1">Las actividades aparecerán aquí conforme se realicen acciones en el sistema</p>
                    </div>
                @endforelse

                {{-- Paginación --}}
                @if($activities->hasPages())
                    <div class="p-4 bg-gray-900/30 border-t border-gray-700">
                        {{ $activities->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
