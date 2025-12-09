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
                                <filter id="neon-cyan-main" x="-150%" y="-150%" width="400%" height="400%">
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

        @endunless

        <div class="w-full sm:max-w-md {{ $hideLogo ? 'mt-6' : 'mt-20' }} px-6 py-8 {{ $minimal ? 'bg-gray-900 border border-gray-700 shadow-xl sm:rounded-xl' : 'bg-white/[0.02] backdrop-blur-3xl border border-white/40 shadow-[0_8px_32px_0_rgba(31,38,135,0.37)] overflow-hidden sm:rounded-2xl relative z-10' }}"
            @unless ($minimal) style="box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37), 0 0 0 1.5px rgba(255, 255, 255, 0.25) inset, 0 4px 16px 0 rgba(255, 255, 255, 0.1) inset;" @endunless>
            {{ $slot }}
        </div>
    </div>
</body>

</html>
