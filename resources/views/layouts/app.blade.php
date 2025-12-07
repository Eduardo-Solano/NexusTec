<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            /* Fondo de circuitos animados */
            .circuit-background-app {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0.4;
                background-image: 
                    linear-gradient(90deg, transparent 48%, rgba(6, 182, 212, 0.1) 49%, rgba(6, 182, 212, 0.4) 50%, rgba(6, 182, 212, 0.1) 51%, transparent 52%),
                    linear-gradient(0deg, transparent 48%, rgba(6, 182, 212, 0.1) 49%, rgba(6, 182, 212, 0.4) 50%, rgba(6, 182, 212, 0.1) 51%, transparent 52%);
                background-size: 80px 80px;
                animation: circuit-flow-app 8s linear infinite;
                pointer-events: none;
                z-index: 0;
            }

            @keyframes circuit-flow-app {
                0% {
                    background-position: 0 0;
                }
                100% {
                    background-position: 80px 80px;
                }
            }

            /* Partículas de luz flotantes */
            .light-particles-app {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-image: 
                    radial-gradient(circle at 15% 20%, rgba(6, 182, 212, 0.4) 2px, transparent 2px),
                    radial-gradient(circle at 85% 30%, rgba(59, 130, 246, 0.5) 3px, transparent 3px),
                    radial-gradient(circle at 45% 70%, rgba(6, 182, 212, 0.3) 2px, transparent 2px),
                    radial-gradient(circle at 70% 80%, rgba(59, 130, 246, 0.4) 2px, transparent 2px),
                    radial-gradient(circle at 30% 60%, rgba(6, 182, 212, 0.5) 3px, transparent 3px),
                    radial-gradient(circle at 90% 50%, rgba(59, 130, 246, 0.3) 2px, transparent 2px),
                    radial-gradient(circle at 10% 90%, rgba(6, 182, 212, 0.4) 3px, transparent 3px);
                background-size: 100% 100%;
                animation: particles-pulse-app 2s ease-in-out infinite, particles-move-app 12s linear infinite;
                pointer-events: none;
                z-index: 0;
            }

            @keyframes particles-pulse-app {
                0%, 100% {
                    opacity: 0.4;
                }
                50% {
                    opacity: 1;
                }
            }

            @keyframes particles-move-app {
                0% {
                    transform: translateY(0) translateX(0);
                }
                50% {
                    transform: translateY(-15px) translateX(15px);
                }
                100% {
                    transform: translateY(0) translateX(0);
                }
            }

            /* Líneas de circuito flotantes */
            .circuit-lines-app {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0.5;
                pointer-events: none;
                z-index: 0;
            }

            .circuit-lines-app::before {
                content: '';
                position: absolute;
                top: 25%;
                left: 0;
                width: 100%;
                height: 2px;
                background: linear-gradient(90deg, 
                    transparent 0%, 
                    rgba(6, 182, 212, 0) 20%,
                    rgba(6, 182, 212, 0.8) 40%,
                    rgba(6, 182, 212, 1) 50%,
                    rgba(6, 182, 212, 0.8) 60%,
                    rgba(6, 182, 212, 0) 80%,
                    transparent 100%
                );
                background-size: 200% 100%;
                box-shadow: 0 0 10px rgba(6, 182, 212, 0.8);
                animation: line-flow-app-1 3s linear infinite;
            }

            .circuit-lines-app::after {
                content: '';
                position: absolute;
                bottom: 30%;
                left: 0;
                width: 100%;
                height: 2px;
                background: linear-gradient(90deg, 
                    transparent 0%, 
                    rgba(59, 130, 246, 0) 20%,
                    rgba(59, 130, 246, 0.8) 40%,
                    rgba(59, 130, 246, 1) 50%,
                    rgba(59, 130, 246, 0.8) 60%,
                    rgba(59, 130, 246, 0) 80%,
                    transparent 100%
                );
                background-size: 200% 100%;
                box-shadow: 0 0 10px rgba(59, 130, 246, 0.8);
                animation: line-flow-app-2 3.5s linear infinite reverse;
            }

            @keyframes line-flow-app-1 {
                0% {
                    background-position: -200% 0;
                }
                100% {
                    background-position: 200% 0;
                }
            }

            @keyframes line-flow-app-2 {
                0% {
                    background-position: 200% 0;
                }
                100% {
                    background-position: -200% 0;
                }
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-[#0a1128] via-[#0d1b2a] to-[#1b263b] relative">
            <!-- Fondo animado -->
            <div class="circuit-background-app"></div>
            <div class="light-particles-app"></div>
            <div class="circuit-lines-app"></div>
            
            <div class="relative z-10">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white/[0.02] backdrop-blur-xl border-b border-white/10 shadow-lg">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
