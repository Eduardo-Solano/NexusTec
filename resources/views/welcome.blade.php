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

    <div class="relative bg-tecnm-blue overflow-hidden h-screen sm:h-auto">

        <div class="hidden lg:block lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full"
                src="{{ asset('img/portada-ito.jpeg') }}" alt="Banner Evento">
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 lg:grid lg:grid-cols-2">

            <div class="bg-tecnm-blue py-16 lg:py-24 lg:pr-8">
                <div class="max-w-lg mx-auto lg:mx-0">
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                        <span class="block">Gestión de Eventos</span>
                        <span class="block text-ito-orange">Académicos y Tecnológicos</span>
                    </h1>
                    <p class="mt-4 text-lg text-gray-200 sm:mt-5">
                        Plataforma centralizada para la administración de Hackathons, Congresos y Talleres del Instituto
                        Tecnológico.
                    </p>

                    <div class="mt-8 sm:flex">
                        <div class="rounded-md shadow">
                            <a href="{{ route('register') }}"
                                class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-tecnm-blue bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10 transition hover:scale-105">
                                Comenzar
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="{{ route('public.calendar') }}"
                                class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-ito-orange hover:bg-orange-600 md:py-4 md:text-lg md:px-10 transition hover:scale-105">
                                Ver Calendario
                            </a>
                            <p>Prueba</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:hidden h-64 w-full relative">
                <img class="w-full h-full object-cover" src="{{ asset('img/portada-ito.jpeg') }}" alt="Banner Evento">
            </div>
        </div>
    </div>

    <footer class="bg-white border-t mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} NexusTec - Instituto Tecnológico de Oaxaca. Todos los derechos reservados.
        </div>
    </footer>
</body>

</html>
