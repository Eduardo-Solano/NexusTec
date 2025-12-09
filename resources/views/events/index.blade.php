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
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Eventos NexusTec') }}
            </h2>

            @role('admin|staff')
                <a href="{{ route('events.create') }}"
                    class="bg-ito-orange/70 hover:bg-ito-orange text-white font-bold py-2 px-4 rounded-lg shadow-md transition transform hover:scale-105 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Nuevo Evento
                </a>
            @endrole
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"
                    role="alert">
                    <p class="font-bold">¡Éxito!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- Barra de Búsqueda y Filtros -->
            <div class="mb-6 bg-white/[0.02] backdrop-blur-xl p-6 rounded-xl shadow-lg border border-white/20">
                <form method="GET" action="{{ route('events.index') }}" class="space-y-4">
                    <!-- Fila 1: Búsqueda -->
                    <div class="w-full">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-all duration-300 group-focus-within:scale-110">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-ito-orange transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Buscar por nombre o descripción..."
                                class="block w-full pl-10 pr-3 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:ring-2 focus:ring-ito-orange focus:border-ito-orange focus:bg-white dark:focus:bg-gray-800 transition-all duration-300 hover:border-gray-400 dark:hover:border-gray-500">
                        </div>
                    </div>

                    <!-- Fila 2: Fecha específica, Mes, Año, Estado -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Fecha específica -->
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2 group-focus-within:text-ito-orange transition-colors">
                                <span class="text-lg">📅</span>
                                <span>Fecha específica</span>
                            </label>
                            <input type="date" name="date" value="{{ request('date') }}" 
                                class="block w-full py-3 px-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-ito-orange focus:border-ito-orange focus:bg-white dark:focus:bg-gray-800 transition-all duration-300 hover:border-gray-400 dark:hover:border-gray-500 cursor-pointer font-medium">
                        </div>

                        <!-- Mes -->
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2 group-focus-within:text-ito-orange transition-colors">
                                <span class="text-lg">📆</span>
                                <span>Mes</span>
                            </label>
                            <div class="relative">
                                <select name="month" 
                                    class="block w-full py-3 pl-4 pr-10 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-ito-orange focus:border-ito-orange focus:bg-white dark:focus:bg-gray-800 transition-all duration-300 hover:border-gray-400 dark:hover:border-gray-500 cursor-pointer font-medium appearance-none">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('month') == '1' ? 'selected' : '' }}>🌟 Enero</option>
                                    <option value="2" {{ request('month') == '2' ? 'selected' : '' }}>💝 Febrero</option>
                                    <option value="3" {{ request('month') == '3' ? 'selected' : '' }}>🌸 Marzo</option>
                                    <option value="4" {{ request('month') == '4' ? 'selected' : '' }}>🌷 Abril</option>
                                    <option value="5" {{ request('month') == '5' ? 'selected' : '' }}>🌺 Mayo</option>
                                    <option value="6" {{ request('month') == '6' ? 'selected' : '' }}>☀️ Junio</option>
                                    <option value="7" {{ request('month') == '7' ? 'selected' : '' }}>🏖️ Julio</option>
                                    <option value="8" {{ request('month') == '8' ? 'selected' : '' }}>🌊 Agosto</option>
                                    <option value="9" {{ request('month') == '9' ? 'selected' : '' }}>🍂 Septiembre</option>
                                    <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>🎃 Octubre</option>
                                    <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>🦃 Noviembre</option>
                                    <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>🎄 Diciembre</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-ito-orange transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Año -->
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2 group-focus-within:text-ito-orange transition-colors">
                                <span class="text-lg">🗓️</span>
                                <span>Año</span>
                            </label>
                            <div class="relative">
                                <select name="year" 
                                    class="block w-full py-3 pl-4 pr-10 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-ito-orange focus:border-ito-orange focus:bg-white dark:focus:bg-gray-800 transition-all duration-300 hover:border-gray-400 dark:hover:border-gray-500 cursor-pointer font-medium appearance-none">
                                    <option value="">Todos</option>
                                    @for ($y = date('Y') + 1; $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-ito-orange transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2 group-focus-within:text-ito-orange transition-colors">
                                <span class="text-lg">📋</span>
                                <span>Estado</span>
                            </label>
                            <div class="relative">
                                <select name="status" 
                                    class="block w-full py-3 pl-4 pr-10 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-ito-orange focus:border-ito-orange focus:bg-white dark:focus:bg-gray-800 transition-all duration-300 hover:border-gray-400 dark:hover:border-gray-500 cursor-pointer font-medium appearance-none">
                                    <option value="">Todos</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>🟢 Activos</option>
                                    <option value="finished" {{ request('status') === 'finished' ? 'selected' : '' }}>🔴 Finalizados</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-ito-orange transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fila 3: Botones -->
                    <div class="flex gap-3 justify-end pt-2">
                        <button type="submit"
                            class="px-4 py-2 bg-tecnm-blue hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrar
                        </button>
                        @if(request('search') || request('status') || request('date') || request('month') || request('year'))
                            <a href="{{ route('events.index') }}" 
                                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold rounded-lg transition flex items-center gap-2 border border-gray-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Limpiar
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Indicador de filtros activos -->
                @if(request('search') || request('status') || request('date') || request('month') || request('year'))
                    <div class="mt-4 flex flex-wrap items-center gap-2 text-sm text-gray-600 dark:text-gray-400 animate-fade-in">
                        <span class="font-bold text-gray-700 dark:text-gray-300">🎯 Filtros activos:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 dark:from-blue-900 dark:to-blue-800 dark:text-blue-200 border border-blue-300 dark:border-blue-700 shadow-sm animate-slide-in">
                                🔍 Búsqueda: "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('status'))
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800 dark:from-purple-900 dark:to-purple-800 dark:text-purple-200 border border-purple-300 dark:border-purple-700 shadow-sm animate-slide-in">
                                {{ request('status') === 'active' ? '🟢 Activos' : '🔴 Finalizados' }}
                            </span>
                        @endif
                        @if(request('date'))
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-green-100 to-green-200 text-green-800 dark:from-green-900 dark:to-green-800 dark:text-green-200 border border-green-300 dark:border-green-700 shadow-sm animate-slide-in">
                                📅 {{ \Carbon\Carbon::parse(request('date'))->format('d/m/Y') }}
                            </span>
                        @endif
                        @if(request('month') || request('year'))
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-gradient-to-r from-orange-100 to-orange-200 text-orange-800 dark:from-orange-900 dark:to-orange-800 dark:text-orange-200 border border-orange-300 dark:border-orange-700 shadow-sm animate-slide-in">
                                🗓️ Período: 
                                @if(request('month'))
                                    {{ ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][request('month') - 1] }}
                                @endif
                                @if(request('year'))
                                    {{ request('year') }}
                                @endif
                            </span>
                        @endif
                        <span class="inline-flex items-center text-gray-500 dark:text-gray-400 font-semibold">
                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            {{ $events->total() }} resultado(s)
                        </span>
                    </div>
                @endif
            </div>

            <style>
                @keyframes fade-in {
                    from {
                        opacity: 0;
                    }
                    to {
                        opacity: 1;
                    }
                }
                
                @keyframes slide-in {
                    from {
                        transform: translateY(-10px);
                        opacity: 0;
                    }
                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }
                
                .animate-fade-in {
                    animation: fade-in 0.3s ease-out;
                }
                
                .animate-slide-in {
                    animation: slide-in 0.4s ease-out;
                }
                
                /* Eliminar flecha nativa de los selects */
                select::-ms-expand {
                    display: none;
                }
            </style>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($events as $event)
                    <div
                        class="bg-white/[0.02] backdrop-blur-xl overflow-hidden shadow-lg rounded-xl border border-white/20 hover:shadow-2xl hover:border-white/30 transition duration-300 group">

                        <div class="h-2 bg-gradient-to-r from-tecnm-blue to-ito-orange"></div>

                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                @php
                                    $statusClasses = match($event->status) {
                                        'registration' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        'active' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        'closed' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $statusClasses }}">
                                    {{ $event->status_icon ?? '' }} {{ $event->status_label }}
                                </span>

                                <div class="text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </div>
                            </div>

                            <h3
                                class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-ito-orange transition">
                                {{ $event->name }}
                            </h3>

                            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 h-12 overflow-hidden">
                                {{ Str::limit($event->description ?? 'Sin descripción disponible para este evento.', 90) }}
                            </p>

                            <div
                                class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6 bg-gray-50 dark:bg-gray-700 p-2 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 mr-2 text-tecnm-blue dark:text-blue-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                @if ($event->start_date->isSameDay($event->end_date))
                                    <span>{{ $event->start_date->format('d M') }} 
                                        <span class="text-xs font-bold bg-gray-200 dark:bg-gray-600 px-1.5 py-0.5 rounded ml-1">
                                            {{ $event->start_date->format('g:i A') }} - {{ $event->end_date->format('g:i A') }}
                                        </span>
                                    </span>
                                @else
                                    <span>{{ $event->start_date->format('d M') }} - {{ $event->end_date->format('d M, Y') }}</span>
                                @endif
                            </div>

                            <div
                                class="flex justify-between items-center border-t border-gray-100 dark:border-gray-700 pt-4 mt-auto">

                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ $event->teams_count ?? 0 }} Equipos
                                </div>

                                <div class="flex items-center gap-3">
                                    <a href="{{ route('events.show', $event) }}"
                                        class="text-sm font-bold text-ito-orange hover:text-orange-500 flex items-center transition">
                                        Ver Detalles
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    @can('events.edit')
                                        @if(!$event->isClosed())
                                            <a href="{{ route('events.edit', $event) }}"
                                                class="text-gray-400 hover:text-blue-500 transition" title="Editar Evento">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                        @endif
                                    @endcan

                                    @can('events.delete')
                                        <form action="{{ route('events.destroy', $event) }}" method="POST"
                                            onsubmit="return confirm('¿Estás seguro de eliminar este evento?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-500 transition"
                                                title="Eliminar Evento">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan

                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        
                        @php
                            $hasFilters = request('search') || request('status') || request('date') || request('month') || request('year');
                        @endphp

                        @if($hasFilters)
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                                No existen eventos para los filtros seleccionados
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                @if(request('date'))
                                    No hay eventos programados para el {{ \Carbon\Carbon::parse(request('date'))->format('d/m/Y') }}
                                @elseif(request('month') && request('year'))
                                    No hay eventos en {{ ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'][request('month') - 1] }} de {{ request('year') }}
                                @elseif(request('year'))
                                    No hay eventos en el año {{ request('year') }}
                                @elseif(request('status') === 'active')
                                    No hay eventos activos en este momento
                                @elseif(request('status') === 'finished')
                                    No hay eventos finalizados
                                @else
                                    Intenta modificar los criterios de búsqueda
                                @endif
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('events.index') }}" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-500 hover:bg-gray-600">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Limpiar Filtros
                                </a>
                            </div>
                        @else
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay eventos</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comienza creando el primer evento académico.</p>
                            @can('events.create')
                                <div class="mt-6">
                                    <a href="{{ route('events.create') }}"
                                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-ito-orange hover:bg-orange-700">
                                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Crear Evento
                                    </a>
                                </div>
                            @endcan
                        @endif
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
