<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NexusTec - Gestión de Eventos</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-50 text-gray-800">

    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">

                <div class="flex items-center gap-3">
                    <x-application-logo class="h-10 w-auto" />
                    <span class="font-bold text-xl text-tecnm-blue tracking-tight">NexusTec</span>
                </div>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="text-sm font-semibold text-gray-600 hover:text-ito-orange">
                                Ir al Panel
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="text-sm font-semibold text-gray-600 hover:text-ito-orange transition">
                                Iniciar Sesión
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 bg-ito-orange text-white text-sm font-bold rounded-lg hover:bg-orange-600 transition shadow-md">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative bg-tecnm-blue overflow-hidden min-h-[calc(100vh-4rem)]">

        {{-- Imagen de fondo para desktop --}}
        <div class="hidden lg:block lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-full w-full object-cover" src="{{ asset('img/portada-ito.jpeg') }}" alt="Banner Evento">
            <div class="absolute inset-0 bg-gradient-to-r from-tecnm-blue via-tecnm-blue/50 to-transparent"></div>
        </div>

        {{-- Decoración de fondo --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -left-40 w-80 h-80 bg-ito-orange/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-1/4 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 lg:grid lg:grid-cols-2 lg:gap-8">

            <div class="py-16 lg:py-32 lg:pr-8 flex flex-col justify-center">
                <div class="max-w-xl mx-auto lg:mx-0">
                    {{-- Badge --}}
                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-white/10 border border-white/20 text-sm text-white/80 mb-6">
                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                        Inscripciones Abiertas
                    </div>
                    
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl lg:text-6xl leading-tight">
                        <span class="block">Gestión de Eventos</span>
                        <span class="block text-ito-orange mt-2">Académicos y Tecnológicos</span>
                    </h1>
                    <p class="mt-6 text-lg text-gray-200 leading-relaxed">
                        Plataforma centralizada para la administración de Hackathons, Congresos y Talleres del Instituto
                        Tecnológico.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-base font-bold rounded-xl text-tecnm-blue bg-white hover:bg-gray-100 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Comenzar
                        </a>
                        <a href="{{ route('public.calendar') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-base font-bold rounded-xl text-white bg-ito-orange hover:bg-orange-600 transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Ver Calendario
                        </a>
                        <a href="{{ route('public.winners') }}"
                            class="inline-flex items-center justify-center px-6 py-3 text-base font-bold rounded-xl text-white border-2 border-white/40 hover:bg-white/10 hover:border-white/60 transition-all duration-300 hover:scale-105">
                            <span class="mr-2">🏆</span>
                            Ganadores
                        </a>
                    </div>

                    {{-- Mini estadísticas --}}
                    <div class="mt-12 grid grid-cols-3 gap-6 pt-8 border-t border-white/10">
                        @php
                            $totalEvents = \App\Models\Event::count();
                            $totalTeams = \App\Models\Team::count();
                            // Cuenta estudiantes de forma segura sin depender del rol
                            $totalStudents = \App\Models\StudentProfile::count();
                        @endphp
                        <div>
                            <p class="text-3xl font-black text-white">{{ $totalEvents }}+</p>
                            <p class="text-sm text-gray-300">Eventos</p>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-white">{{ $totalTeams }}+</p>
                            <p class="text-sm text-gray-300">Equipos</p>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-white">{{ $totalStudents }}+</p>
                            <p class="text-sm text-gray-300">Participantes</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Imagen móvil --}}
            <div class="lg:hidden h-64 w-full relative">
                <img class="w-full h-full object-cover" src="{{ asset('img/portada-ito.jpeg') }}" alt="Banner Evento">
                <div class="absolute inset-0 bg-gradient-to-t from-tecnm-blue to-transparent"></div>
            </div>
        </div>
    </div>

    {{-- Sección de características --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900">¿Por qué NexusTec?</h2>
                <p class="mt-4 text-lg text-gray-600">Todo lo que necesitas para gestionar eventos académicos</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-2xl bg-gray-50 hover:bg-gray-100 transition">
                    <div class="w-14 h-14 mx-auto mb-4 bg-tecnm-blue/10 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-tecnm-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Gestión de Equipos</h3>
                    <p class="text-gray-600 text-sm">Forma equipos, invita compañeros y colabora en proyectos innovadores.</p>
                </div>
                
                <div class="text-center p-6 rounded-2xl bg-gray-50 hover:bg-gray-100 transition">
                    <div class="w-14 h-14 mx-auto mb-4 bg-ito-orange/10 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-ito-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Evaluación Profesional</h3>
                    <p class="text-gray-600 text-sm">Sistema de rúbricas y jueces para evaluaciones justas y transparentes.</p>
                </div>
                
                <div class="text-center p-6 rounded-2xl bg-gray-50 hover:bg-gray-100 transition">
                    <div class="w-14 h-14 mx-auto mb-4 bg-green-500/10 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Diplomas Automáticos</h3>
                    <p class="text-gray-600 text-sm">Genera diplomas personalizados para participantes y ganadores.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} NexusTec - Instituto Tecnológico de Oaxaca. Todos los derechos reservados.
        </div>
    </footer>
</body>

</html>
