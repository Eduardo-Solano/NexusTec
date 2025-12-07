<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ganadores - NexusTec</title>
        <meta name="description" content="Conoce a los ganadores de los eventos académicos del Instituto Tecnológico de Oaxaca">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-[#0B1120] text-white min-h-screen">
        
        {{-- Navbar --}}
        <nav class="bg-gray-900/80 backdrop-blur-lg border-b border-gray-800 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <a href="/" class="flex items-center gap-3 text-white font-bold text-xl group">
                        <img src="{{ asset('img/logo-ito.png') }}" alt="Logo ITO" 
                             class="h-10 w-auto transition-all duration-500 ease-out group-hover:scale-110 group-hover:rotate-6 group-hover:drop-shadow-[0_0_15px_rgba(255,255,255,0.8)] hover:brightness-110" />
                        <span class="hidden sm:block">NexusTec</span>
                    </a>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('public.calendar') }}" class="text-sm font-medium text-gray-400 hover:text-white transition">
                            Calendario
                        </a>
                        <a href="{{ route('login') }}" class="text-sm font-medium px-4 py-2 bg-ito-orange hover:bg-orange-600 rounded-lg transition">
                            Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Hero Section --}}
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-yellow-500/10 via-transparent to-transparent"></div>
            <div class="absolute top-20 left-1/4 w-72 h-72 bg-yellow-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-40 right-1/4 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 py-16 sm:py-24 text-center">
                <div class="text-6xl mb-6">🏆</div>
                <h1 class="text-4xl sm:text-5xl font-black mb-4">
                    <span class="bg-gradient-to-r from-yellow-400 via-orange-500 to-yellow-400 bg-clip-text text-transparent">
                        Salón de la Fama
                    </span>
                </h1>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">
                    Conoce a los equipos ganadores de nuestros eventos académicos. 
                    Proyectos innovadores que destacaron por su excelencia.
                </p>
            </div>
        </div>

        {{-- Events with Awards --}}
        <div class="max-w-7xl mx-auto px-4 pb-20">
            
            @forelse($eventsWithAwards as $event)
                <div class="mb-16">
                    {{-- Event Header --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="px-3 py-1 bg-gray-800 border border-gray-700 rounded-full text-xs font-bold text-gray-400">
                                    {{ $event->end_date->format('d M, Y') }}
                                </span>
                                <span class="px-3 py-1 bg-green-500/10 border border-green-500/20 rounded-full text-xs font-bold text-green-400">
                                    Finalizado
                                </span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-bold text-white">{{ $event->name }}</h2>
                            <p class="text-gray-500 mt-1">{{ $event->awards->count() }} premios otorgados</p>
                        </div>
                        <a href="{{ route('public.event-winners', $event) }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800 hover:bg-gray-700 border border-gray-700 rounded-xl text-sm font-bold text-white transition group">
                            Ver todos los detalles
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Awards Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @php
                            $displayAwards = $event->awards->sortBy('position')->take(3);
                        @endphp

                        @foreach($displayAwards as $index => $award)
                            @php
                                $medals = [1 => '🥇', 2 => '🥈', 3 => '🥉'];
                                $medal = $medals[$award->position] ?? '🏆';
                                $gradients = [
                                    1 => 'from-yellow-500/20 via-yellow-600/10 to-transparent border-yellow-500/30',
                                    2 => 'from-gray-400/20 via-gray-500/10 to-transparent border-gray-400/30',
                                    3 => 'from-amber-700/20 via-amber-800/10 to-transparent border-amber-600/30',
                                ];
                                $gradient = $gradients[$award->position] ?? 'from-purple-500/20 via-purple-600/10 to-transparent border-purple-500/30';
                                $positionLabel = \App\Models\Award::POSITIONS[$award->position] ?? 'Premio';
                            @endphp

                            <div class="relative group">
                                <div class="absolute inset-0 bg-gradient-to-br {{ $gradient }} rounded-2xl opacity-50 group-hover:opacity-100 transition"></div>
                                <div class="relative bg-gray-800/80 backdrop-blur border border-gray-700 rounded-2xl p-6 hover:border-gray-600 transition-all duration-300 hover:-translate-y-1">
                                    
                                    {{-- Medal & Position --}}
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="text-4xl">{{ $medal }}</span>
                                        <span class="px-3 py-1 bg-gray-900/50 rounded-lg text-xs font-bold text-gray-300">
                                            {{ $positionLabel }}
                                        </span>
                                    </div>

                                    {{-- Team Name --}}
                                    <h3 class="text-xl font-bold text-white mb-2 line-clamp-1">
                                        {{ $award->team->name ?? 'Equipo no disponible' }}
                                    </h3>

                                    {{-- Project Name --}}
                                    @if($award->team?->project)
                                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                                            {{ $award->team->project->title }}
                                        </p>
                                    @endif

                                    {{-- Team Members --}}
                                    <div class="flex items-center gap-2">
                                        <div class="flex -space-x-2">
                                            @foreach($award->team->members->take(4) as $member)
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-600 to-gray-700 border-2 border-gray-800 flex items-center justify-center text-xs font-bold text-white" title="{{ $member->name }}">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                            @endforeach
                                            @if($award->team->members->count() > 4)
                                                <div class="w-8 h-8 rounded-full bg-gray-700 border-2 border-gray-800 flex items-center justify-center text-xs font-bold text-gray-400">
                                                    +{{ $award->team->members->count() - 4 }}
                                                </div>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $award->team->members->count() }} integrantes</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- More awards indicator --}}
                    @if($event->awards->count() > 3)
                        <div class="mt-6 text-center">
                            <a href="{{ route('public.event-winners', $event) }}" class="text-sm text-gray-500 hover:text-ito-orange transition">
                                + {{ $event->awards->count() - 3 }} premios más →
                            </a>
                        </div>
                    @endif
                </div>

                @if(!$loop->last)
                    <div class="border-t border-gray-800 mb-16"></div>
                @endif
            @empty
                {{-- No winners yet --}}
                <div class="text-center py-20">
                    <div class="text-6xl mb-6 opacity-50">🏆</div>
                    <h3 class="text-xl font-bold text-gray-400 mb-2">Próximamente</h3>
                    <p class="text-gray-600 max-w-md mx-auto">
                        Aún no hay ganadores registrados. Los resultados se publicarán al finalizar los eventos.
                    </p>
                    <a href="{{ route('public.calendar') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-ito-orange hover:bg-orange-600 rounded-xl text-sm font-bold text-white transition">
                        Ver eventos próximos
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <footer class="border-t border-gray-800 bg-gray-900/50">
            <div class="max-w-7xl mx-auto px-4 py-12">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('img/logo-ito.png') }}" class="h-10 w-auto bg-white rounded-full p-0.5" alt="Logo ITO">
                        <div>
                            <p class="font-bold text-white">NexusTec</p>
                            <p class="text-xs text-gray-500">Instituto Tecnológico de Oaxaca</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-6 text-sm text-gray-500">
                        <a href="{{ route('public.calendar') }}" class="hover:text-white transition">Calendario</a>
                        <a href="{{ route('public.winners') }}" class="hover:text-white transition">Ganadores</a>
                        <a href="{{ route('login') }}" class="hover:text-white transition">Acceso</a>
                    </div>
                    <p class="text-xs text-gray-600">
                        © {{ date('Y') }} NexusTec. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
