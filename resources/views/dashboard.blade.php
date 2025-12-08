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

    <x-slot name="header">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
             <div class="flex items-center gap-2 group">
                <div class="p-2 bg-white/[0.02] backdrop-blur-xl rounded-xl shadow-sm ring-1 border border-white/20 group-hover:scale-110 group-hover:border-white/30 transition-all duration-300">
                     <svg class="w-6 h-6 text-purple-600 animate-float" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </div>
                 <h2 class="text-3xl font-black leading-tight tracking-tight text-gray-900 dark:text-gray-100">
                    {{ __('Dashboard') }}
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 via-pink-500 to-red-500 animate-text-shimmer relative">
                        {{ __('Nexus') }}
                        <svg class="absolute -top-1 -right-4 w-4 h-4 text-yellow-400 animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    </span>
                </h2>
            </div>
             <nav class="flex px-5 py-3 text-gray-300 border border-white/20 rounded-lg bg-white/[0.02] backdrop-blur-xl hover:border-white/30 transition-colors" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <span class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            Home
                        </span>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <!-- Main Container -->
    <div class="relative z-10 min-h-screen selection:bg-purple-500 selection:text-white pb-20 overflow-hidden">
        
        <!-- Animated Background Blobs -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-0">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-yellow-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative z-10 py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">
                
                <!-- Hero Section: Welcome & Status -->
                <div class="relative overflow-hidden group rounded-[2.5rem] bg-white/[0.02] backdrop-blur-3xl border border-white/20 hover:border-white/30 shadow-2xl p-8 sm:p-12 transition-all">
                    <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-purple-100/50 dark:from-purple-900/20 to-transparent"></div>
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                        <div>
                             <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100/50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-widest mb-4">
                                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                Panel de Control
                            </div>
                            <h1 class="text-5xl font-black text-gray-900 dark:text-white tracking-tight mb-4">
                                Hola, <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-pink-500">{{ explode(' ', Auth::user()->name)[0] }}</span>! 👋
                            </h1>
                            <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl leading-relaxed">
                                Bienvenido a tu centro de mando en <span class="font-bold text-gray-900 dark:text-gray-100">NexusTec</span>.
                                @if(isset($data['upcoming_events']) && count($data['upcoming_events']) > 0)
                                    El próximo evento es <span class="text-purple-600 dark:text-purple-400 font-bold">{{ $data['upcoming_events'][0]->name }}</span> en {{ $data['upcoming_events'][0]->start_date->diffForHumans() }}.
                                @else
                                    Todo está tranquilo por ahora.
                                @endif
                            </p>
                            <!-- Action Buttons -->
                             <div class="flex flex-wrap gap-4 mt-8">
                                <a href="{{ route('profile.edit') }}" class="px-6 py-3 rounded-xl bg-white/[0.05] backdrop-blur-xl border border-white/30 text-white font-bold hover:scale-105 hover:border-white/40 transition-all shadow-lg flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    Mi Perfil
                                </a>
                                @role('student')
                                    <a href="{{ route('events.index') }}" class="px-6 py-3 rounded-xl bg-white/[0.02] backdrop-blur-xl text-white border border-white/20 font-bold hover:border-white/30 transition-all flex items-center gap-2 shadow-sm">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        Explorar Eventos
                                    </a>
                                @endrole
                            </div>
                        </div>
                        <!-- Hero Illustration / Icon -->
                        <div class="relative hidden md:block group-hover:scale-110 transition-transform duration-700 ease-in-out">
                            <div class="w-48 h-48 bg-gradient-to-tr from-purple-500 to-pink-500 rounded-[2rem] rotate-6 shadow-2xl opacity-80 blur-sm absolute top-4 left-4"></div>
                            <div class="w-48 h-48 bg-white/[0.02] backdrop-blur-xl rounded-[2rem] relative z-10 flex items-center justify-center border border-white/20 shadow-2xl">
                                <span class="text-7xl">🚀</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ADMIN / STAFF / ADVISOR DASHBOARD -->
                @can('teams.advise')
                    <!-- KPIs Grid -->
                     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @php
                            $stats = [
                                ['label' => 'Estudiantes', 'value' => $data['total_students'], 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'blue'],
                                ['label' => 'Eventos Activos', 'value' => $data['active_events'], 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'green'],
                                ['label' => 'Total Equipos', 'value' => $data['total_teams'], 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'color' => 'purple'],
                                ['label' => 'Proyectos', 'value' => $data['projects_delivered'], 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'amber'],
                            ];
                        @endphp

                        @foreach($stats as $stat)
                            <div class="glass-card rounded-2xl p-6 relative group overflow-hidden hover:-translate-y-1 transition-all duration-300">
                                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                                    <svg class="w-24 h-24 transform rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $stat['icon'] }}"/></svg>
                                </div>
                                <div class="relative z-10">
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</span>
                                        <div class="p-2 rounded-lg bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-500">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
                                        </div>
                                    </div>
                                    <p class="text-4xl font-black text-gray-900 dark:text-white">{{ $stat['value'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endcan

                @if(isset($data['event_progress']))
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white p-8 shadow-2xl border border-gray-700/50">
                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100"></div>
                        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-600/30 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
                        
                        <div class="relative z-10">
                            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
                                <div>
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-500/20 text-green-300 border border-green-500/30 text-xs font-bold uppercase tracking-wider mb-2">
                                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Evento Activo
                                    </div>
                                    <h2 class="text-3xl font-black">{{ $data['event_progress']['event']->name }}</h2>
                                    <p class="text-gray-400 mt-1">Sigue el progreso en tiempo real de la competencia.</p>
                                </div>

                                <!-- Countdown -->
                                <div class="flex gap-4">
                                    <div class="text-center p-4 bg-white/5 rounded-2xl backdrop-blur-sm border border-white/10">
                                        <span class="block text-3xl font-black text-purple-400">{{ round($data['event_progress']['days_remaining']) }}</span>
                                        <span class="text-xs text-gray-400 uppercase font-bold">Días</span>
                                    </div>
                                    <div class="text-center p-4 bg-white/5 rounded-2xl backdrop-blur-sm border border-white/10">
                                        <span class="block text-3xl font-black text-pink-400">{{ floor($data['event_progress']['hours_remaining']) }}</span>
                                        <span class="text-xs text-gray-400 uppercase font-bold">Horas</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bars Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                                @php
                                    $progressItems = [
                                        ['label' => 'Equipos', 'count' => $data['event_progress']['teams_count'], 'total' => null, 'percent' => 100, 'color' => 'blue', 'icon' => '👥'],
                                        ['label' => 'Proyectos', 'count' => $data['event_progress']['projects_count'], 'total' => null, 'percent' => $data['event_progress']['projects_percent'], 'color' => 'purple', 'icon' => '📦'],
                                        ['label' => 'Evaluados', 'count' => $data['event_progress']['total_evaluations'], 'total' => $data['event_progress']['required_evaluations'], 'percent' => ($data['event_progress']['required_evaluations'] > 0 ? round(($data['event_progress']['total_evaluations'] / $data['event_progress']['required_evaluations']) * 100) : 0), 'color' => 'amber', 'icon' => '⭐'],
                                        ['label' => 'Premios', 'count' => $data['event_progress']['awards_count'], 'total' => null, 'percent' => ($data['event_progress']['awards_count'] > 0 ? 100 : 0), 'color' => 'green', 'icon' => '🏆']
                                    ];
                                @endphp

                                @foreach ($progressItems as $item)
                                    <div class="bg-white/5 border border-white/10 rounded-2xl p-5 hover:bg-white/10 transition">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="p-2 bg-{{ $item['color'] }}-500/20 rounded-lg text-{{ $item['color'] }}-400 text-xl">
                                                {{ $item['icon'] }}
                                            </div>
                                            <span class="text-xs font-bold text-gray-500 uppercase">{{ $item['percent'] }}%</span>
                                        </div>
                                        <h3 class="text-2xl font-black">{{ $item['count'] }} <span class="text-sm font-normal text-gray-500">{{ $item['total'] ? '/' . $item['total'] : '' }}</span></h3>
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">{{ $item['label'] }}</p>
                                        <div class="w-full bg-gray-700/50 rounded-full h-1.5 mt-3 overflow-hidden">
                                            <div class="bg-{{ $item['color'] }}-500 h-full rounded-full transition-all duration-1000" style="width: {{ $item['percent'] }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Acciones Rápidas (Botones) -->
                            <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-700/50">
                                <a href="{{ route('events.show', $data['event_progress']['event']) }}" 
                                    class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl transition shadow-lg shadow-blue-500/20 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Ver Detalles
                                </a>
                                <a href="{{ route('events.rankings', $data['event_progress']['event']) }}" 
                                    class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl transition border border-white/10 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    Rankings
                                </a>
                                <a href="{{ route('export.diplomas', $data['event_progress']['event']) }}" 
                                    class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl transition border border-white/10 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Diplomas
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- SECCIÓN PARA DOCENTES/ASESORES: Equipos que asesoran --}}
                @role('advisor')
                    @if(isset($data['advised_teams']) && $data['advised_teams']->count() > 0)
                        <div class="mt-8">
                            <div class="glass-card rounded-3xl overflow-hidden border border-green-500/20">
                                <div class="p-6 border-b border-gray-200/10 dark:border-gray-700/50 bg-gradient-to-r from-green-500/10 to-transparent">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        <span class="p-2 bg-green-500/20 text-green-500 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                            </svg>
                                        </span>
                                        Mis Equipos Asesorados
                                        <span class="ml-2 px-2 py-0.5 bg-green-500/20 text-green-400 text-xs font-bold rounded-full">{{ $data['advised_teams']->count() }}</span>
                                    </h3>
                                </div>
                                <div class="divide-y divide-gray-200/10 dark:divide-gray-700/50">
                                    @foreach ($data['advised_teams'] as $team)
                                        <div class="p-5 hover:bg-white/5 transition">
                                            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                                        {{ strtoupper(substr($team->name, 0, 2)) }}
                                                    </div>
                                                    <div>
                                                        <h4 class="font-bold text-gray-900 dark:text-white text-lg">{{ $team->name }}</h4>
                                                        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mt-1">
                                                            <span class="inline-flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                                {{ $team->event->name }}
                                                            </span>
                                                            <span>•</span>
                                                            <span class="inline-flex items-center gap-1">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                                                {{ $team->members->count() }} miembros
                                                            </span>
                                                            <span>•</span>
                                                            <span class="inline-flex items-center gap-1">
                                                                👑 {{ $team->leader->name ?? 'Sin líder' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-3">
                                                    {{-- Estado del Proyecto --}}
                                                    @if($team->project)
                                                        <span class="px-3 py-1.5 bg-blue-500/20 text-blue-400 text-xs font-bold rounded-lg flex items-center gap-1">
                                                            📋 Proyecto: {{ Str::limit($team->project->name, 20) }}
                                                        </span>
                                                        @if($team->project->judges->count() > 0)
                                                            @php
                                                                $completedEvals = $team->project->judges->where('pivot.is_completed', true)->count();
                                                                $totalJudges = $team->project->judges->count();
                                                            @endphp
                                                            <span class="px-3 py-1.5 {{ $completedEvals === $totalJudges ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }} text-xs font-bold rounded-lg">
                                                                ⚖️ {{ $completedEvals }}/{{ $totalJudges }} Evaluado
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="px-3 py-1.5 bg-gray-500/20 text-gray-400 text-xs font-bold rounded-lg">
                                                            📋 Sin proyecto
                                                        </span>
                                                    @endif
                                                    {{-- Estado del Evento --}}
                                                    <span class="px-3 py-1.5 text-xs font-bold rounded-lg
                                                        @if($team->event->status === 'registration') bg-blue-500/20 text-blue-400
                                                        @elseif($team->event->status === 'active') bg-green-500/20 text-green-400
                                                        @else bg-red-500/20 text-red-400
                                                        @endif">
                                                        {{ $team->event->status_icon }} {{ $team->event->status_label }}
                                                    </span>
                                                    <a href="{{ route('teams.show', $team) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        Ver Equipo
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @elseif(isset($data['pending_advisories']) && $data['pending_advisories']->count() > 0)
                        {{-- Mostrar solicitudes pendientes si no tiene equipos aceptados --}}
                        <div class="mt-8">
                            <div class="glass-card rounded-3xl overflow-hidden border border-yellow-500/20">
                                <div class="p-6 border-b border-gray-200/10 dark:border-gray-700/50 bg-gradient-to-r from-yellow-500/10 to-transparent">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                        <span class="p-2 bg-yellow-500/20 text-yellow-500 rounded-lg">🔔</span>
                                        Solicitudes de Asesoría Pendientes
                                        <span class="ml-2 px-2 py-0.5 bg-yellow-500/20 text-yellow-400 text-xs font-bold rounded-full animate-pulse">{{ $data['pending_advisories']->count() }}</span>
                                    </h3>
                                </div>
                                <div class="p-6">
                                    <p class="text-gray-400 mb-4">Tienes equipos esperando tu confirmación como asesor.</p>
                                    <a href="{{ route('teams.index') }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-bold rounded-lg transition">
                                        Ver Solicitudes →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- No tiene equipos ni solicitudes --}}
                        <div class="mt-8">
                            <div class="glass-card rounded-3xl p-8 text-center border border-gray-500/20">
                                <div class="text-6xl mb-4">📚</div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Sin equipos asesorados</h3>
                                <p class="text-gray-500">Aún no tienes equipos bajo tu asesoría. Los estudiantes pueden solicitarte como asesor al crear sus equipos.</p>
                            </div>
                        </div>
                    @endif
                @endrole

                    <!-- Charts Section -->
                @if(isset($data['event_progress']))
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12">
                        <!-- Equipos Chart -->
                        <div class="glass-card p-6 rounded-3xl relative overflow-hidden">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                <span class="p-2 bg-blue-500/10 text-blue-500 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></span>
                                Equipos Registrados (14 días)
                            </h3>
                            <div class="h-64 w-full">
                                <canvas id="teamsChart"></canvas>
                            </div>
                        </div>

                        <!-- Carreras Chart -->
                        <div class="glass-card p-6 rounded-3xl relative overflow-hidden">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                <span class="p-2 bg-purple-500/10 text-purple-500 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg></span>
                                Estudiantes por Carrera
                            </h3>
                            <div class="h-64 w-full">
                                <canvas id="careersChart"></canvas>
                            </div>
                        </div>

                        <!-- Eventos Chart -->
                        <div class="glass-card p-6 rounded-3xl relative overflow-hidden lg:col-span-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                                <span class="p-2 bg-green-500/10 text-green-500 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></span>
                                Participación por Evento
                            </h3>
                            <div class="h-80 w-full">
                                <canvas id="eventsChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Teams List -->
                    <div class="mt-12">
                        <div class="glass-card rounded-3xl overflow-hidden">
                            <div class="p-6 border-b border-gray-200/10 dark:border-gray-700/50 flex items-center justify-between">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <span class="p-2 bg-pink-500/10 text-pink-500 rounded-lg"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></span>
                                    Equipos Recientes
                                </h3>
                                <a href="{{ route('teams.index') }}" class="text-sm font-bold text-purple-500 hover:text-purple-400 transition">Ver todos &rarr;</a>
                            </div>
                            <div class="divide-y divide-gray-200/10 dark:divide-gray-700/50">
                                @forelse ($data['recent_teams'] as $team)
                                    <div class="p-4 hover:bg-white/5 transition flex items-center justify-between group">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform">
                                                {{ strtoupper(substr($team->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 dark:text-white group-hover:text-purple-400 transition">{{ $team->name }}</h4>
                                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                                    <span>{{ $team->members->count() }} Miembros</span>
                                                    <span>•</span>
                                                    <span>{{ $team->event->name }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs font-mono text-gray-400">{{ $team->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-8 text-center text-gray-500">No hay equipos recientes.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- STUDENT ROLE -->
                @role('student')
                    @if (isset($data['my_teams']) && $data['my_teams']->count() > 0)
                        <div class="space-y-8">
                            @foreach($data['my_teams'] as $team)
                                @php
                                    $teamProgress = $data['teams_progress'][$team->id] ?? null;
                                @endphp
                                <div class="grid lg:grid-cols-3 gap-8">
                                    <!-- Team Card -->
                                    <div class="lg:col-span-2 glass-card rounded-[2rem] p-8 relative overflow-hidden group">
                                        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-purple-500/10 to-transparent -mr-16 -mt-16 rounded-full blur-3xl"></div>
                                        <div class="relative z-10">
                                            <div class="flex justify-between items-start mb-6">
                                                <div>
                                                    <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-300 rounded-full text-xs font-bold uppercase tracking-wider border border-purple-200 dark:border-purple-700">Mi Equipo</span>
                                                    <h2 class="text-3xl font-black text-gray-900 dark:text-white mt-4">{{ $team->name }}</h2>
                                                    <p class="text-gray-500 font-medium">Evento: {{ $team->event->name }}</p>
                                                </div>
                                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center text-3xl shadow-inner">
                                                    🛡️
                                                </div>
                                            </div>

                                            @if($teamProgress)
                                                <div class="space-y-6">
                                                    <!-- Steps -->
                                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                                         @foreach($teamProgress['steps'] as $key => $step)
                                                            <div class="flex flex-col items-center text-center p-4 rounded-2xl border {{ $step['completed'] ? 'bg-green-500/10 backdrop-blur-xl border-green-500/30' : 'bg-white/[0.02] backdrop-blur-xl border-white/20' }} transition-all">
                                                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg mb-2 {{ $step['completed'] ? 'bg-green-100 text-green-600' : 'bg-gray-200 text-gray-400' }}">
                                                                    {{ $step['icon'] }}
                                                                </div>
                                                                <p class="text-xs font-bold {{ $step['completed'] ? 'text-green-700 dark:text-green-400' : 'text-gray-400' }}">
                                                                    {{ $step['label'] }}
                                                                </p>
                                                            </div>
                                                         @endforeach
                                                    </div>

                                                    <div class="bg-white/[0.02] backdrop-blur-xl rounded-2xl p-6 border border-white/20 hover:border-white/30 transition-colors flex items-center justify-between">
                                                        <div>
                                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Progreso General</p>
                                                            <p class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600">{{ $teamProgress['percent'] }}%</p>
                                                        </div>
                                                        <div class="w-1/2">
                                                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                                                <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full" style="width: {{ $teamProgress['percent'] }}%"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="grid grid-cols-2 gap-4">
                                                        @if ($team->leader_id === Auth::id())
                                                            @if($team->project)
                                                                <a href="{{ route('projects.edit', $team->project) }}" class="flex justify-center items-center py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all">
                                                                    Editar Proyecto
                                                                </a>
                                                            @else
                                                                <a href="{{ route('projects.create', ['team_id' => $team->id]) }}" class="flex justify-center items-center py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transform hover:-translate-y-0.5 transition-all">
                                                                    Entregar Proyecto
                                                                </a>
                                                            @endif
                                                        @endif
                                                        <a href="{{ route('events.show', $team->event_id) }}" class="flex justify-center items-center py-3 px-4 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors {{ $team->leader_id !== Auth::id() ? 'col-span-2' : '' }}">
                                                            Ver Evento
                                                        </a>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Score Card (if available) -->
                                    @if(isset($teamProgress['score']) && $teamProgress['score'] > 0)
                                        <div class="glass-card rounded-[2rem] p-8 flex flex-col items-center justify-center text-center relative overflow-hidden">
                                             <div class="absolute inset-0 bg-gradient-to-b from-amber-500/10 to-transparent"></div>
                                             <div class="relative z-10">
                                                 <p class="text-sm font-bold text-amber-500 uppercase tracking-widest mb-4">Puntaje Final</p>
                                                 <div class="text-6xl font-black text-gray-900 dark:text-white mb-2">{{ $teamProgress['score'] }}</div>
                                                 <div class="flex items-center justify-center gap-1 text-yellow-400">
                                                     <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                     <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                     <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                 </div>
                                             </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white/[0.02] backdrop-blur-xl rounded-[2rem] p-12 text-center border-2 border-dashed border-white/20 hover:border-white/30 transition-colors">
                            <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-6">
                                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No estás en ningún equipo</h3>
                            <p class="text-gray-500 mb-8 max-w-md mx-auto">Únete a un evento activo para comenzar a competir.</p>
                            <a href="{{ route('events.index') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all">Explorar Eventos</a>
                        </div>
                    @endif
                @endrole

                <!-- JUDGE ROLE -->
                @role('judge')
                     @include('dashboard.partials.judge-view')
                @endrole

            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuración Global Premium
            Chart.defaults.color = '#94a3b8';
            Chart.defaults.font.family = "'Plus Jakarta Sans', 'Inter', sans-serif";
            Chart.defaults.font.weight = '600';
            
            // Función para crear gradientes
            function createGradient(ctx, colorStart, colorEnd) {
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, colorStart);
                gradient.addColorStop(1, colorEnd);
                return gradient;
            }

            // 1. Gráfica de Equipos Registrados (Smart Timeline)
            const teamsCtx = document.getElementById('teamsChart');
            if (teamsCtx) {
                const ctx = teamsCtx.getContext('2d');
                
                // --- PROCESAMIENTO DE DATOS INTELIGENTE ---
                // Obtener datos crudos del backend
                const rawData = {!! json_encode($data['teams_by_day'] ?? []) !!};
                
                // Generar los últimos 14 días para llenar huecos
                const labels = [];
                const dailyData = [];
                const accumulatedData = [];
                let currentSum = 0; // Podrías inicializar esto con un total histórico si lo tuvieras

                for (let i = 13; i >= 0; i--) {
                    const d = new Date();
                    d.setDate(d.getDate() - i);
                    const dateStr = d.toISOString().split('T')[0]; // YYYY-MM-DD
                    
                    // Formato legible para el eje X (e.g., "05 Dic")
                    const labelStr = d.toLocaleDateString('es-ES', { day: '2-digit', month: 'short' });
                    labels.push(labelStr);

                    // Buscar si hay datos para este día
                    const dayRecord = rawData.find(r => r.date === dateStr);
                    const count = dayRecord ? parseInt(dayRecord.count) : 0;
                    
                    dailyData.push(count);
                    
                    // Calcular acumulado
                    currentSum += count;
                    accumulatedData.push(currentSum);
                }

                // --- ESTILOS ---
                const barGradient = ctx.createLinearGradient(0, 0, 0, 300);
                barGradient.addColorStop(0, '#8B5CF6'); // Purple
                barGradient.addColorStop(1, 'rgba(139, 92, 246, 0.2)');

                new Chart(teamsCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                type: 'line',
                                label: 'Total Acumulado',
                                data: accumulatedData,
                                borderColor: '#38bdf8', // Sky Blue Neon
                                borderWidth: 3,
                                tension: 0.4,
                                pointBackgroundColor: '#0ea5e9',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                yAxisID: 'y1',
                                fill: {
                                    target: 'origin',
                                    below: 'rgba(56, 189, 248, 0.05)' // Sutil brillo bajo la línea
                                }
                            },
                            {
                                type: 'bar',
                                label: 'Registros Diarios',
                                data: dailyData,
                                backgroundColor: barGradient,
                                borderRadius: 4,
                                barPercentage: 0.6,
                                categoryPercentage: 0.8,
                                yAxisID: 'y',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { 
                                display: true,
                                position: 'top',
                                align: 'end',
                                labels: { color: '#94a3b8', usePointStyle: true, boxWidth: 8 } 
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.95)',
                                titleColor: '#fff',
                                bodyColor: '#cbd5e1',
                                borderColor: 'rgba(255, 255, 255, 0.1)',
                                borderWidth: 1,
                                cornerRadius: 8,
                                padding: 12,
                                callbacks: {
                                    title: (items) => `📅 ${items[0].label}`,
                                    label: (context) => {
                                        const val = context.parsed.y;
                                        return context.dataset.type === 'line' 
                                            ? `📈 Total: ${val} equipos`
                                            : `🆕 Nuevos: +${val}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#64748b', font: { size: 10 }, maxRotation: 0 }
                            },
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: { display: true, text: 'Diarios', color: '#8B5CF6', font: { size: 9 } },
                                grid: { color: 'rgba(255, 255, 255, 0.05)', borderDash: [5, 5] },
                                suggestedMax: Math.max(...dailyData) + 2 // Dar aire arriba
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                grid: { drawOnChartArea: false },
                                ticks: { display: false } // Ocultar números del acumulado para limpieza
                            }
                        }
                    }
                });
            }

            // 2. Gráfica de Estudiantes por Carrera (Doughnut Neon)
            const careersCtx = document.getElementById('careersChart');
            if (careersCtx) {
                new Chart(careersCtx, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode(($data['students_by_career'] ?? collect())->pluck('name')) !!},
                        datasets: [{
                            data: {!! json_encode(($data['students_by_career'] ?? collect())->pluck('count')) !!},
                            backgroundColor: [
                                '#8B5CF6', '#EC4899', '#3B82F6', '#10B981', '#F59E0B'
                            ],
                            borderWidth: 0,
                            hoverOffset: 15,
                            borderRadius: 20, // Bordes redondeados en los segmentos
                            spacing: 5
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: { 
                                    boxWidth: 8, 
                                    usePointStyle: true, 
                                    pointStyle: 'circle',
                                    padding: 25,
                                    font: { size: 12 }
                                }
                            }
                        },
                        cutout: '75%',
                        layout: { padding: 20 },
                        animation: { animateScale: true, animateRotate: true }
                    }
                });
            }

            // 3. Gráfica de Eventos (Bar Chart Gradient)
            const eventsCtx = document.getElementById('eventsChart');
            if (eventsCtx) {
                const ctx = eventsCtx.getContext('2d');
                const gradientBar = ctx.createLinearGradient(0, 0, 0, 400);
                gradientBar.addColorStop(0, '#34D399'); // Emerald 400
                gradientBar.addColorStop(1, 'rgba(16, 185, 129, 0.2)'); // Emerald 500 transparent

                new Chart(eventsCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode(($data['projects_by_event'] ?? collect())->pluck('name')) !!},
                        datasets: [{
                            label: 'Equipos',
                            data: {!! json_encode(($data['projects_by_event'] ?? collect())->pluck('teams_count')) !!},
                            backgroundColor: gradientBar,
                            borderRadius: { topLeft: 20, topRight: 20 },
                            barThickness: 50,
                            maxBarThickness: 60,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { 
                            legend: { display: false },
                               tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#E2E8F0',
                                padding: 12,
                                cornerRadius: 12
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(255, 255, 255, 0.05)', borderDash: [5, 5] },
                                border: { display: false }
                            },
                            x: {
                                grid: { display: false },
                                border: { display: false }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>