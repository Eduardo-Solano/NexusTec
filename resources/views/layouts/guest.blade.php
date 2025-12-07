@props(['hideLogo' => false, 'minimal' => false])

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
</head>

<body class="font-sans text-gray-900 antialiased">
    <div
        class="min-h-screen flex flex-col justify-center items-center {{ $minimal ? 'bg-gray-900' : 'bg-gradient-to-br from-[#0a1128] via-[#0d1b2a] to-[#1b263b] relative overflow-hidden' }}">
        @unless ($minimal)
            <!-- Fondo de circuitos animados -->
            <div class="circuit-background"></div>

            <!-- Partículas de luz flotantes -->
            <div class="light-particles"></div>

            <!-- Líneas de circuito brillantes flotantes -->
            <div class="circuit-lines-floating"></div>

            <!-- Líneas verticales animadas -->
            <div class="vertical-lines"></div>

            <!-- Puntos de energía flotantes -->
            <div class="energy-dots"></div>

            <!-- Ondas expansivas -->
            <div class="expanding-waves"></div>
        @endunless

        <!-- Logo NexusTec con efectos - Fuera del cuadro de login -->
        @if (!$hideLogo && !$minimal)
            <div class="flex justify-center -mb-16 -mt-4 relative z-20">
                <div class="logo-container-main relative">
                    <!-- Logo NexusTec SVG - Diseño de líneas con efecto neón sutil -->
                    <div class="logo-nexustec-main relative z-10" style="width: 380px; max-width: 85vw; height: auto;">
                        <svg viewBox="1000 200 5875 5360" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve"
                            style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:1.5;">
                            <defs>
                                <!-- Filtros de neón mejorados - más auténticos y realistas -->
                                <filter id="neon-cyan-main" x="-150%" y="-150%" width="400%" height="400%">
                                    <!-- Capa externa muy difusa - resplandor lejano -->
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="40" result="blur1" />
                                    <!-- Capa media - brillo principal -->
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="20" result="blur2" />
                                    <!-- Capa interna - definición -->
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur3" />
                                    <!-- Capa muy cercana - borde brillante -->
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="4" result="blur4" />
                                    <!-- Composición con múltiples capas -->
                                    <feMerge>
                                        <feMergeNode in="blur1" />
                                        <feMergeNode in="blur1" />
                                        <feMergeNode in="blur2" />
                                        <feMergeNode in="blur2" />
                                        <feMergeNode in="blur2" />
                                        <feMergeNode in="blur3" />
                                        <feMergeNode in="blur3" />
                                        <feMergeNode in="blur4" />
                                        <feMergeNode in="SourceGraphic" />
                                        <feMergeNode in="SourceGraphic" />
                                    </feMerge>
                                </filter>
                                <filter id="neon-green-main" x="-150%" y="-150%" width="400%" height="400%">
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="40" result="blur1" />
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="20" result="blur2" />
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur3" />
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="4" result="blur4" />
                                    <feMerge>
                                        <feMergeNode in="blur1" />
                                        <feMergeNode in="blur1" />
                                        <feMergeNode in="blur2" />
                                        <feMergeNode in="blur2" />
                                        <feMergeNode in="blur2" />
                                        <feMergeNode in="blur3" />
                                        <feMergeNode in="blur3" />
                                        <feMergeNode in="blur4" />
                                        <feMergeNode in="SourceGraphic" />
                                        <feMergeNode in="SourceGraphic" />
                                    </feMerge>
                                </filter>
                                <filter id="neon-circle-main" x="-150%" y="-150%" width="400%" height="400%">
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="50" result="blur1" />
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="25" result="blur2" />
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="12" result="blur3" />
                                    <feGaussianBlur in="SourceGraphic" stdDeviation="5" result="blur4" />
                                    <feMerge>
                                        <feMergeNode in="blur1" />
                                        <feMergeNode in="blur1" />
                                        <feMergeNode in="blur1" />
                                        <feMergeNode in="blur2" />
                                        <feMergeNode in="blur2" />
                                        <feMergeNode in="blur3" />
                                        <feMergeNode in="blur3" />
                                        <feMergeNode in="blur4" />
                                        <feMergeNode in="SourceGraphic" />
                                        <feMergeNode in="SourceGraphic" />
                                    </feMerge>
                                </filter>
                            </defs>
                            <g>
                                <!-- Círculo exterior con efecto neón y animación de respiración -->
                                <circle cx="3937.5" cy="2780" r="2400" class="breathing-circle-main"
                                    style="fill:none;stroke:#81cee0;stroke-width:50px;filter:url(#neon-circle-main);opacity:0.95;" />
                                <circle cx="3937.5" cy="2780" r="2480" class="breathing-circle-outer-main"
                                    style="fill:none;stroke:#b4e25c;stroke-width:40px;filter:url(#neon-circle-main);opacity:0.85;" />

                                <!-- Logo centrado -->
                                <g>
                                    <path
                                        d="M4219.78,2303.75c-52.193,-575.042 141.907,-788.482 397.887,-763.613c243.025,23.611 427.257,106.675 388.234,914.086"
                                        style="fill:none;stroke:#81cee0;stroke-width:116.67px;filter:url(#neon-cyan-main);" />
                                    <path
                                        d="M2505.92,3069.29c14.212,113.151 -122.361,902.204 351.625,911.717c150.994,3.031 484.114,-17.975 413.402,-765.71"
                                        style="fill:none;stroke:#b4e25c;stroke-width:116.67px;filter:url(#neon-green-main);" />
                                    <ellipse cx="2878.17" cy="3516.4" rx="107.567" ry="115.282"
                                        style="fill:#b4e25c;fill-opacity:0;stroke:#b4e25c;stroke-width:116.67px;filter:url(#neon-green-main);" />
                                    <path
                                        d="M2878.17,3379.99c9.512,-821.622 -50.041,-1297.49 79.536,-1344.63c120.207,-43.734 252.925,150.709 555.678,489.613"
                                        style="fill:none;stroke:#b4e25c;stroke-width:116.67px;filter:url(#neon-green-main);" />
                                    <ellipse cx="3737.01" cy="2762.82" rx="85.488" ry="91.619"
                                        style="fill:#b4e25c;stroke:#b4e25c;stroke-width:116.67px;filter:url(#neon-green-main);" />
                                    <ellipse cx="4985.36" cy="2762.82" rx="85.488" ry="91.619"
                                        style="fill:#81cee0;stroke:#81cee0;stroke-width:116.67px;filter:url(#neon-cyan-main);" />
                                    <path
                                        d="M3931.47,2969.75c362.072,382.068 497.994,559.345 614.323,481.022c139.761,-94.1 78.073,-505.158 60.185,-1251.31"
                                        style="fill:none;stroke:#81cee0;stroke-width:116.67px;filter:url(#neon-cyan-main);" />
                                    <ellipse cx="4617.66" cy="2006.55" rx="139.136" ry="149.114"
                                        style="fill:#81cee0;fill-opacity:0;stroke:#81cee0;stroke-width:116.67px;filter:url(#neon-cyan-main);" />
                                    <path
                                        d="M3276.75,2784.46c591.418,640.319 958.519,1176.65 1237.16,1194.26c328.275,20.742 564.786,-114.472 471.444,-1124.28"
                                        style="fill:none;stroke:#81cee0;stroke-width:116.67px;filter:url(#neon-cyan-main);" />
                                    <ellipse cx="2494.32" cy="2762.82" rx="138.139" ry="148.046"
                                        style="fill:#b4e25c;stroke:#b4e25c;stroke-width:116.67px;filter:url(#neon-green-main);" />
                                    <path
                                        d="M2494.32,2762.82c12.591,-715.081 -94.553,-1004.06 242.708,-1185.51c96.522,-51.929 260.876,-79.678 376.612,5.724c279.554,206.283 551.186,588.021 1117.61,1179.78"
                                        style="fill:none;stroke:#b4e25c;stroke-width:116.67px;filter:url(#neon-green-main);" />
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        @unless ($minimal)
            <style>
                /* Fondo de circuitos */
                .circuit-background {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    opacity: 0.4;
                    background-image:
                        linear-gradient(90deg, transparent 48%, rgba(6, 182, 212, 0.1) 49%, rgba(6, 182, 212, 0.4) 50%, rgba(6, 182, 212, 0.1) 51%, transparent 52%),
                        linear-gradient(0deg, transparent 48%, rgba(6, 182, 212, 0.1) 49%, rgba(6, 182, 212, 0.4) 50%, rgba(6, 182, 212, 0.1) 51%, transparent 52%);
                    background-size: 80px 80px;
                    animation: circuit-flow 6s linear infinite;
                }

                @keyframes circuit-flow {
                    0% {
                        background-position: 0 0;
                    }

                    100% {
                        background-position: 80px 80px;
                    }
                }

                /* Partículas de luz flotantes */
                .light-particles {
                    position: absolute;
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
                    animation: particles-pulse 1.5s ease-in-out infinite, particles-move 8s linear infinite;
                }

                @keyframes particles-pulse {

                    0%,
                    100% {
                        opacity: 0.4;
                    }

                    50% {
                        opacity: 1;
                    }
                }

                @keyframes particles-move {
                    0% {
                        transform: translateY(0) translateX(0);
                    }

                    50% {
                        transform: translateY(-20px) translateX(20px);
                    }

                    100% {
                        transform: translateY(0) translateX(0);
                    }
                }

                /* Líneas de circuito flotantes brillantes */
                .circuit-lines-floating {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    opacity: 0.6;
                    pointer-events: none;
                }

                .circuit-lines-floating::before {
                    content: '';
                    position: absolute;
                    top: 20%;
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
                            transparent 100%);
                    background-size: 200% 100%;
                    box-shadow: 0 0 10px rgba(6, 182, 212, 0.8), 0 0 20px rgba(6, 182, 212, 0.5);
                    animation: line-flow-1 2s linear infinite;
                }

                .circuit-lines-floating::after {
                    content: '';
                    position: absolute;
                    bottom: 25%;
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
                            transparent 100%);
                    background-size: 200% 100%;
                    box-shadow: 0 0 10px rgba(59, 130, 246, 0.8), 0 0 20px rgba(59, 130, 246, 0.5);
                    animation: line-flow-2 2.5s linear infinite reverse;
                }

                @keyframes line-flow-1 {
                    0% {
                        background-position: -200% 0;
                    }

                    100% {
                        background-position: 200% 0;
                    }
                }

                @keyframes line-flow-2 {
                    0% {
                        background-position: 200% 0;
                    }

                    100% {
                        background-position: -200% 0;
                    }
                }

                .logo-container-main {
                    transition: transform 0.3s ease-out, filter 0.3s ease;
                    animation: gentle-pulse 3s ease-in-out infinite, neon-flicker 6s ease-in-out infinite;
                }

                .logo-container-main:hover {
                    transform: scale(1.08);
                    filter: drop-shadow(0 0 40px rgba(129, 206, 224, 1)) drop-shadow(0 0 60px rgba(180, 226, 92, 0.8)) drop-shadow(0 0 80px rgba(129, 206, 224, 0.6));
                }

                /* Efecto de parpadeo sutil del neón - como neón real */
                @keyframes neon-flicker {

                    0%,
                    100% {
                        opacity: 1;
                    }

                    2%,
                    8%,
                    10%,
                    15%,
                    18%,
                    22%,
                    38%,
                    40% {
                        opacity: 0.98;
                    }

                    4%,
                    12%,
                    20%,
                    42% {
                        opacity: 0.95;
                    }

                    6%,
                    14%,
                    25%,
                    45% {
                        opacity: 1;
                    }
                }

                /* Pulso suave y constante del resplandor */
                @keyframes gentle-pulse {

                    0%,
                    100% {
                        filter: drop-shadow(0 0 25px rgba(129, 206, 224, 0.7)) drop-shadow(0 0 50px rgba(180, 226, 92, 0.4)) drop-shadow(0 0 75px rgba(129, 206, 224, 0.3));
                    }

                    50% {
                        filter: drop-shadow(0 0 35px rgba(129, 206, 224, 0.9)) drop-shadow(0 0 70px rgba(180, 226, 92, 0.6)) drop-shadow(0 0 100px rgba(129, 206, 224, 0.4));
                    }
                }

                /* Animación de respiración de los círculos - más intensa */
                @keyframes breathing-main {

                    0%,
                    100% {
                        r: 2400px;
                        opacity: 0.95;
                        stroke-width: 50px;
                    }

                    50% {
                        r: 2450px;
                        opacity: 1;
                        stroke-width: 60px;
                    }
                }

                @keyframes breathing-outer-main {

                    0%,
                    100% {
                        r: 2480px;
                        opacity: 0.85;
                        stroke-width: 40px;
                    }

                    50% {
                        r: 2530px;
                        opacity: 1;
                        stroke-width: 50px;
                    }
                }

                .breathing-circle-main {
                    animation: breathing-main 3s ease-in-out infinite;
                }

                .breathing-circle-outer-main {
                    animation: breathing-outer-main 3s ease-in-out infinite 0.5s;
                }

                /* Líneas verticales animadas */
                .vertical-lines {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    opacity: 0.3;
                    pointer-events: none;
                }

                .vertical-lines::before {
                    content: '';
                    position: absolute;
                    left: 30%;
                    top: 0;
                    width: 2px;
                    height: 100%;
                    background: linear-gradient(180deg,
                            transparent 0%,
                            rgba(6, 182, 212, 0) 20%,
                            rgba(6, 182, 212, 0.8) 40%,
                            rgba(6, 182, 212, 1) 50%,
                            rgba(6, 182, 212, 0.8) 60%,
                            rgba(6, 182, 212, 0) 80%,
                            transparent 100%);
                    background-size: 100% 200%;
                    box-shadow: 0 0 10px rgba(6, 182, 212, 0.8);
                    animation: vertical-flow 3s linear infinite;
                }

                .vertical-lines::after {
                    content: '';
                    position: absolute;
                    right: 25%;
                    top: 0;
                    width: 2px;
                    height: 100%;
                    background: linear-gradient(180deg,
                            transparent 0%,
                            rgba(59, 130, 246, 0) 20%,
                            rgba(59, 130, 246, 0.8) 40%,
                            rgba(59, 130, 246, 1) 50%,
                            rgba(59, 130, 246, 0.8) 60%,
                            rgba(59, 130, 246, 0) 80%,
                            transparent 100%);
                    background-size: 100% 200%;
                    box-shadow: 0 0 10px rgba(59, 130, 246, 0.8);
                    animation: vertical-flow 3.5s linear infinite reverse;
                }

                @keyframes vertical-flow {
                    0% {
                        background-position: 0 -200%;
                    }

                    100% {
                        background-position: 0 200%;
                    }
                }

                /* Puntos de energía flotantes */
                .energy-dots {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    pointer-events: none;
                    background-image:
                        radial-gradient(circle at 20% 35%, rgba(6, 182, 212, 0.6) 4px, transparent 4px),
                        radial-gradient(circle at 75% 25%, rgba(59, 130, 246, 0.7) 5px, transparent 5px),
                        radial-gradient(circle at 50% 50%, rgba(6, 182, 212, 0.5) 3px, transparent 3px),
                        radial-gradient(circle at 80% 65%, rgba(59, 130, 246, 0.6) 4px, transparent 4px),
                        radial-gradient(circle at 25% 75%, rgba(6, 182, 212, 0.7) 5px, transparent 5px),
                        radial-gradient(circle at 60% 85%, rgba(59, 130, 246, 0.5) 3px, transparent 3px),
                        radial-gradient(circle at 40% 15%, rgba(6, 182, 212, 0.6) 4px, transparent 4px),
                        radial-gradient(circle at 95% 45%, rgba(59, 130, 246, 0.7) 5px, transparent 5px),
                        radial-gradient(circle at 10% 55%, rgba(6, 182, 212, 0.5) 4px, transparent 4px);
                    background-size: 100% 100%;
                    animation: energy-pulse 2s ease-in-out infinite, energy-float 10s ease-in-out infinite;
                }

                @keyframes energy-pulse {

                    0%,
                    100% {
                        opacity: 0.3;
                        transform: scale(1);
                    }

                    50% {
                        opacity: 0.8;
                        transform: scale(1.1);
                    }
                }

                @keyframes energy-float {

                    0%,
                    100% {
                        transform: translate(0, 0) scale(1);
                    }

                    25% {
                        transform: translate(30px, -30px) scale(1.05);
                    }

                    50% {
                        transform: translate(-20px, -40px) scale(1.1);
                    }

                    75% {
                        transform: translate(-30px, 20px) scale(1.05);
                    }
                }

                /* Ondas expansivas */
                .expanding-waves {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 100%;
                    height: 100%;
                    pointer-events: none;
                }

                .expanding-waves::before,
                .expanding-waves::after {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 200px;
                    height: 200px;
                    border: 2px solid rgba(6, 182, 212, 0.3);
                    border-radius: 50%;
                    transform: translate(-50%, -50%);
                    animation: wave-expand 4s ease-out infinite;
                }

                .expanding-waves::after {
                    border-color: rgba(59, 130, 246, 0.3);
                    animation-delay: 2s;
                }

                @keyframes wave-expand {
                    0% {
                        width: 200px;
                        height: 200px;
                        opacity: 0.6;
                    }

                    100% {
                        width: 800px;
                        height: 800px;
                        opacity: 0;
                    }
                }
            </style>
        @endunless

        <div class="w-full sm:max-w-md {{ $hideLogo ? 'mt-6' : 'mt-20' }} px-6 py-8 {{ $minimal ? 'bg-gray-900 border border-gray-700 shadow-xl sm:rounded-xl' : 'bg-white/[0.02] backdrop-blur-3xl border border-white/40 shadow-[0_8px_32px_0_rgba(31,38,135,0.37)] overflow-hidden sm:rounded-2xl relative z-10' }}"
            @unless ($minimal) style="box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37), 0 0 0 1.5px rgba(255, 255, 255, 0.25) inset, 0 4px 16px 0 rgba(255, 255, 255, 0.1) inset;" @endunless>
            {{ $slot }}
        </div>
    </div>
</body>

</html>
