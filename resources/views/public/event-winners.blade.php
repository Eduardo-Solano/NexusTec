<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ganadores: {{ $event->name }} - NexusTec</title>
        <meta name="description" content="Ganadores del evento {{ $event->name }} en el Instituto Tecnológico de Oaxaca">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-[#0B1120] text-white min-h-screen">
        
        {{-- Navbar --}}
        <nav class="bg-gray-900/80 backdrop-blur-lg border-b border-gray-800 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <a href="/" class="flex items-center gap-3 text-white font-bold text-xl group">
                        <img src="{{ asset('img/logo-ito.png') }}" class="h-10 w-auto bg-white rounded-full p-0.5 group-hover:scale-105 transition" alt="Logo ITO">
                        <span class="hidden sm:block">NexusTec</span>
                    </a>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('public.winners') }}" class="text-sm font-medium text-gray-400 hover:text-white transition">
                            ← Todos los ganadores
                        </a>
                        <a href="{{ route('login') }}" class="text-sm font-medium px-4 py-2 bg-ito-orange hover:bg-orange-600 rounded-lg transition">
                            Iniciar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Hero Section --}}
        <div class="relative overflow-hidden border-b border-gray-800">
            <div class="absolute inset-0 bg-gradient-to-b from-yellow-500/10 via-transparent to-transparent"></div>
            <div class="absolute top-10 left-1/4 w-72 h-72 bg-yellow-500/20 rounded-full blur-3xl"></div>
            <div class="absolute top-20 right-1/3 w-96 h-96 bg-orange-500/10 rounded-full blur-3xl"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 py-12 sm:py-16">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 bg-green-500/10 border border-green-500/20 rounded-full text-xs font-bold text-green-400">
                                Evento Finalizado
                            </span>
                            <span class="text-gray-500 text-sm">
                                {{ $event->start_date->format('d M') }} - {{ $event->end_date->format('d M, Y') }}
                            </span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black mb-4">
                            {{ $event->name }}
                        </h1>
                        <p class="text-gray-400 max-w-2xl">
                            {{ $event->description }}
                        </p>
                    </div>

                    {{-- Stats --}}
                    <div class="flex gap-6">
                        <div class="text-center px-6 py-4 bg-gray-800/50 border border-gray-700 rounded-xl">
                            <p class="text-3xl font-black text-yellow-400">{{ $awards->count() }}</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Premios</p>
                        </div>
                        <div class="text-center px-6 py-4 bg-gray-800/50 border border-gray-700 rounded-xl">
                            <p class="text-3xl font-black text-blue-400">{{ $event->teams->count() }}</p>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Equipos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Awards Section --}}
        <div class="max-w-7xl mx-auto px-4 py-12">
            
            {{-- Podium for top 3 --}}
            @php
                $topAwards = $awards->whereIn('position', [1, 2, 3])->values();
            @endphp

            @if($topAwards->count() > 0)
                <div class="mb-16">
                    <h2 class="text-2xl font-bold text-center mb-10">
                        <span class="bg-gradient-to-r from-yellow-400 via-orange-500 to-yellow-400 bg-clip-text text-transparent">
                            🏆 Podio de Ganadores
                        </span>
                    </h2>

                    {{-- Podium Display (responsive) --}}
                    <div class="flex flex-col md:flex-row items-end justify-center gap-4 md:gap-6">
                        
                        {{-- 2nd Place --}}
                        @php $second = $topAwards->firstWhere('position', 2); @endphp
                        @if($second)
                            <div class="order-2 md:order-1 w-full md:w-80">
                                <div class="bg-gradient-to-b from-gray-400/20 to-gray-800/50 border border-gray-600 rounded-t-2xl p-6 text-center">
                                    <span class="text-5xl mb-4 block">🥈</span>
                                    <h3 class="text-xl font-bold text-white mb-1">{{ $second->team->name ?? 'N/A' }}</h3>
                                    @if($second->team?->project)
                                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $second->team->project->title }}</p>
                                    @endif
                                    <div class="flex justify-center -space-x-2 mb-2">
                                        @foreach($second->team->members->take(5) as $member)
                                            <div class="w-8 h-8 rounded-full bg-gray-600 border-2 border-gray-800 flex items-center justify-center text-xs font-bold" title="{{ $member->name }}">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $second->team->members->count() }} integrantes</p>
                                </div>
                                <div class="bg-gray-500 h-24 md:h-32 rounded-b-lg flex items-center justify-center">
                                    <span class="text-4xl font-black text-white/80">2°</span>
                                </div>
                            </div>
                        @endif

                        {{-- 1st Place --}}
                        @php $first = $topAwards->firstWhere('position', 1); @endphp
                        @if($first)
                            <div class="order-1 md:order-2 w-full md:w-80">
                                <div class="bg-gradient-to-b from-yellow-500/30 to-gray-800/50 border border-yellow-500/40 rounded-t-2xl p-6 text-center relative overflow-hidden">
                                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-yellow-400 via-yellow-300 to-yellow-400"></div>
                                    <span class="text-6xl mb-4 block">🥇</span>
                                    <h3 class="text-2xl font-bold text-white mb-1">{{ $first->team->name ?? 'N/A' }}</h3>
                                    @if($first->team?->project)
                                        <p class="text-gray-300 text-sm mb-4 line-clamp-2">{{ $first->team->project->title }}</p>
                                    @endif
                                    <div class="flex justify-center -space-x-2 mb-2">
                                        @foreach($first->team->members->take(5) as $member)
                                            <div class="w-9 h-9 rounded-full bg-yellow-600/50 border-2 border-yellow-500/30 flex items-center justify-center text-xs font-bold" title="{{ $member->name }}">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-400">{{ $first->team->members->count() }} integrantes</p>
                                </div>
                                <div class="bg-gradient-to-b from-yellow-500 to-yellow-600 h-32 md:h-44 rounded-b-lg flex items-center justify-center shadow-lg shadow-yellow-500/20">
                                    <span class="text-5xl font-black text-white">1°</span>
                                </div>
                            </div>
                        @endif

                        {{-- 3rd Place --}}
                        @php $third = $topAwards->firstWhere('position', 3); @endphp
                        @if($third)
                            <div class="order-3 w-full md:w-80">
                                <div class="bg-gradient-to-b from-amber-700/20 to-gray-800/50 border border-amber-700/30 rounded-t-2xl p-6 text-center">
                                    <span class="text-5xl mb-4 block">🥉</span>
                                    <h3 class="text-xl font-bold text-white mb-1">{{ $third->team->name ?? 'N/A' }}</h3>
                                    @if($third->team?->project)
                                        <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $third->team->project->title }}</p>
                                    @endif
                                    <div class="flex justify-center -space-x-2 mb-2">
                                        @foreach($third->team->members->take(5) as $member)
                                            <div class="w-8 h-8 rounded-full bg-amber-800/50 border-2 border-gray-800 flex items-center justify-center text-xs font-bold" title="{{ $member->name }}">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $third->team->members->count() }} integrantes</p>
                                </div>
                                <div class="bg-amber-700 h-20 md:h-24 rounded-b-lg flex items-center justify-center">
                                    <span class="text-4xl font-black text-white/80">3°</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- No awards message --}}
            @if($awards->count() === 0)
                <div class="text-center py-20">
                    <div class="text-6xl mb-6 opacity-50">🏆</div>
                    <h3 class="text-xl font-bold text-gray-400 mb-2">Sin premios registrados</h3>
                    <p class="text-gray-600">
                        Aún no se han asignado premios para este evento.
                    </p>
                </div>
            @endif
        </div>

        {{-- Team Details Section --}}
        @if($awards->count() > 0)
            <div class="border-t border-gray-800 bg-gray-900/30">
                <div class="max-w-7xl mx-auto px-4 py-12">
                    <h2 class="text-xl font-bold mb-8 flex items-center gap-3">
                        <span class="text-2xl">👥</span>
                        Equipos Premiados - Detalle
                    </h2>

                    <div class="space-y-6">
                        @foreach($awards as $award)
                            <div class="bg-gray-800/50 border border-gray-700 rounded-xl overflow-hidden">
                                <div class="p-6">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                                        <div class="flex items-center gap-4">
                                            @php
                                                $medals = [1 => '🥇', 2 => '🥈', 3 => '🥉'];
                                                $medal = $medals[$award->position] ?? '🏆';
                                                $positionLabel = \App\Models\Award::POSITIONS[$award->position] ?? 'Premio';
                                            @endphp
                                            <span class="text-4xl">{{ $medal }}</span>
                                            <div>
                                                <h3 class="text-xl font-bold text-white">{{ $award->team->name ?? 'N/A' }}</h3>
                                                <p class="text-sm text-gray-500">{{ $positionLabel }}</p>
                                            </div>
                                        </div>
                                        @if($award->team?->project)
                                            <div class="px-4 py-2 bg-gray-900 rounded-lg">
                                                <p class="text-xs text-gray-500 uppercase">Proyecto</p>
                                                <p class="text-white font-medium">{{ $award->team->project->title }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Team Members --}}
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                        @if($award->team?->leader)
                                            <div class="flex items-center gap-3 p-3 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                                                <div class="w-10 h-10 rounded-full bg-yellow-600/30 border border-yellow-500/30 flex items-center justify-center text-sm font-bold text-yellow-400">
                                                    {{ strtoupper(substr($award->team->leader->name, 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-white font-medium text-sm truncate">{{ $award->team->leader->name }}</p>
                                                    <p class="text-yellow-500 text-xs">Líder</p>
                                                </div>
                                            </div>
                                        @endif

                                        @foreach($award->team->members->where('id', '!=', $award->team->leader_id) as $member)
                                            <div class="flex items-center gap-3 p-3 bg-gray-900/50 border border-gray-700 rounded-lg">
                                                <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-sm font-bold text-gray-300">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-white font-medium text-sm truncate">{{ $member->name }}</p>
                                                    <p class="text-gray-500 text-xs">Integrante</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- Advisor if exists --}}
                                    @if($award->team?->advisor)
                                        <div class="mt-4 pt-4 border-t border-gray-700">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-xs font-bold text-blue-400">
                                                    {{ strtoupper(substr($award->team->advisor->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-white text-sm">{{ $award->team->advisor->name }}</p>
                                                    <p class="text-blue-400 text-xs">Asesor</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

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
