<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Calendario de Eventos - NexusTec</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="antialiased font-sans text-gray-100 bg-[#0a1128]">
        
        <!-- Fondo Animado Compartido -->
        <div class="fixed inset-0 bg-gradient-to-br from-[#0a1128] via-[#0d1b2a] to-[#1b263b] -z-20"></div>
        <div class="circuit-background-app"></div>
        
        <!-- Navbar Estilo Glass -->
        <nav class="glass-nav fixed w-full z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <a href="/" class="flex items-center gap-4 group">
                        <div class="relative">
                            <div class="absolute inset-0 bg-blue-500 blur-lg opacity-20 group-hover:opacity-40 transition-opacity"></div>
                            <img src="{{ asset('img/logo-ito.png') }}" alt="Logo ITO" 
                                 class="h-10 w-auto relative z-10 transition-transform duration-500 group-hover:rotate-12" />
                        </div>
                        <span class="text-xl font-bold tracking-tight text-white group-hover:text-blue-400 transition-colors">
                            Nexus<span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">Tec</span>
                        </span>
                    </a>
                    <div class="flex items-center gap-6">
                        <a href="{{ route('public.winners') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors flex items-center gap-2">
                            <span class="text-xl">🏆</span> 
                            <span class="hidden sm:inline">Ganadores</span>
                        </a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold shadow-lg shadow-blue-500/20 transition-all transform hover:-translate-y-0.5">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-white hover:text-blue-400 transition-colors">
                                Iniciar Sesión <span aria-hidden="true">&rarr;</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Header Hero -->
        <div class="relative pt-32 pb-12 overflow-hidden">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[400px] bg-blue-500/20 rounded-full blur-[120px] -z-10 pointer-events-none"></div>
            <div class="max-w-4xl mx-auto px-4 text-center">
                <h1 class="text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-gray-400 mb-4 tracking-tight">
                    Calendario Académico
                </h1>
                <p class="text-lg text-blue-200/80 font-medium max-w-2xl mx-auto leading-relaxed">
                    Cronograma oficial de eventos, competencias y actividades tecnológicas.
                </p>
            </div>
        </div>

        <!-- Timeline -->
        <div class="max-w-5xl mx-auto px-4 pb-24 relative z-10">
            
            @forelse($events as $month => $monthEvents)
                <div class="relative flex items-center justify-center mb-12">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/10"></div>
                    </div>
                    <div class="relative z-10 bg-[#0a1128] px-6">
                        <span class="inline-block px-6 py-2 rounded-full border border-blue-500/30 bg-blue-900/20 text-blue-300 font-bold uppercase tracking-widest text-sm shadow-[0_0_15px_rgba(59,130,246,0.2)]">
                            {{ \Carbon\Carbon::parse($month)->locale('es')->translatedFormat('F Y') }}
                        </span>
                    </div>
                </div>

                <div class="space-y-6 mb-16">
                    @foreach($monthEvents as $event)
                        @php
                            $statusConfig = match($event->status) {
                                'registration' => ['color' => 'blue', 'label' => 'Registro Abierto', 'icon' => '📝'],
                                'active' => ['color' => 'green', 'label' => 'En Curso', 'icon' => '🟢'],
                                'closed' => ['color' => 'gray', 'label' => 'Finalizado', 'icon' => '🔒'],
                                default => ['color' => 'gray', 'label' => 'Estado Desconocido', 'icon' => '❓']
                            };
                            $color = $statusConfig['color'];
                        @endphp
                        
                        <div class="glass-card rounded-2xl p-1 relative group transition-all duration-300 hover:scale-[1.01] hover:bg-white/[0.05]">
                            <!-- Borde brillante en hover -->
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-transparent via-{{ $color }}-500/0 to-transparent group-hover:via-{{ $color }}-500/20 transition-all duration-500 opacity-0 group-hover:opacity-100 -z-10"></div>

                            <div class="p-6 sm:p-8 flex flex-col md:flex-row gap-8 items-center md:items-stretch">
                                
                                <!-- Fecha Badge -->
                                <div class="flex flex-col items-center justify-center min-w-[100px] bg-white/[0.03] border border-white/10 rounded-2xl p-4 text-center group-hover:border-{{ $color }}-500/30 transition-colors">
                                    <span class="text-xs font-bold text-{{ $color }}-400 uppercase tracking-wider mb-1">{{ $event->start_date->format('M') }}</span>
                                    <span class="text-4xl font-black text-white leading-none mb-1">{{ $event->start_date->format('d') }}</span>
                                    <span class="text-xs font-medium text-gray-500">{{ $event->start_date->format('l') }}</span>
                                </div>

                                <!-- Contenido -->
                                <div class="flex-1 text-center md:text-left space-y-4">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                        <h3 class="text-2xl font-bold text-white group-hover:text-blue-300 transition-colors">
                                            {{ $event->name }}
                                        </h3>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-{{ $color }}-500/10 text-{{ $color }}-400 border border-{{ $color }}-500/20 shadow-[0_0_10px_rgba(0,0,0,0.2)]">
                                            {{ $statusConfig['icon'] }} {{ $event->status_label }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-400 leading-relaxed text-sm line-clamp-2">
                                        {{ $event->description }}
                                    </p>

                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-6 text-sm text-gray-400 pt-2">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-{{ $color }}-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span class="font-medium text-gray-300">{{ $event->start_date->format('H:i') }} - {{ $event->end_date->format('H:i') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-{{ $color }}-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span class="font-medium text-gray-300">{{ $event->teams->count() }} Equipos</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón de Acción -->
                                <div class="flex items-center justify-end">
                                    <a href="{{ route('login') }}" class="w-full md:w-auto px-6 py-3 rounded-xl bg-white/[0.05] hover:bg-white/[0.1] border border-white/10 text-white font-bold transition-all shadow-lg hover:shadow-{{ $color }}-500/20 group-hover:border-{{ $color }}-500/30 flex items-center justify-center gap-2">
                                        Detalles
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="glass-card rounded-[2rem] p-16 text-center border border-dashed border-white/10">
                    <div class="inline-flex p-4 bg-white/[0.03] rounded-full mb-6 animate-pulse">
                        <svg class="w-12 h-12 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Sin Eventos Programados</h3>
                    <p class="text-gray-400 max-w-md mx-auto">Actualmente no hay eventos en el calendario. Revisa más tarde para nuevas convocatorias.</p>
                </div>
            @endforelse

        </div>

    </body>
</html>