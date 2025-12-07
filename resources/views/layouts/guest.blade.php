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
        <div class="min-h-screen flex flex-col justify-center items-center bg-gradient-to-br from-[#0a1128] via-[#0d1b2a] to-[#1b263b] relative overflow-hidden">
            
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
            
            <!-- Logo NexusTec con efectos - Fuera del cuadro de login -->
            <div class="flex justify-center -mb-32 -mt-16 relative z-20">
                <div class="logo-container-main relative">
                    <!-- Logo NexusTec SVG -->
                    <div class="logo-nexustec-main relative z-10" style="width: 800px; max-width: 90vw; height: auto;">
                        <svg viewBox="0 0 7875 5560" xmlns="http://www.w3.org/2000/svg" style="filter: drop-shadow(0 0 30px rgba(59, 130, 246, 0.6));">
                            <path d="M2470.75,3152.2l-106.709,-282.465l-24.062,-0l0,-88.924l34.524,-0l106.709,282.465l13.077,-0l-0,88.924l-23.539,-0Zm-166.341,-0l0,-371.389l54.401,-0l0,371.389l-54.401,-0Zm182.033,-0l0,-371.389l54.401,-0l0,371.389l-54.401,-0Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <path d="M2760.53,3157.43c-47.252,-0 -83.737,-12.424 -109.455,-37.27c-25.718,-24.847 -38.577,-60.547 -38.577,-107.101c-0,-45.334 10.897,-80.555 32.692,-105.663c21.795,-25.108 52.57,-37.662 92.324,-37.662c37.488,-0 66.476,11.333 86.963,34c20.487,22.667 30.731,55.621 30.731,98.863c0,12.728 -0.523,24.585 -1.569,35.57l-194.587,-0l-0,-42.37l145.94,-0c0,-24.934 -5.928,-44.07 -17.785,-57.409c-11.856,-13.338 -28.246,-20.007 -49.169,-20.007c-23.539,-0 -41.804,8.02 -54.793,24.061c-12.99,16.042 -19.485,38.709 -19.485,68.001c-0,32.083 8.936,56.493 26.808,73.232c17.872,16.739 43.285,25.108 76.239,25.108c11.508,-0 22.841,-0.654 34.001,-1.962c11.159,-1.307 22.492,-3.095 34,-5.361l6.8,48.646c-16.39,3.139 -31.385,5.144 -44.985,6.016c-13.6,0.872 -25.631,1.308 -36.093,1.308Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <path d="M2921.12,3152.2l110.37,-152.741l85.263,-124.493l62.77,-0l-114.555,157.448l-81.078,119.786l-62.77,-0Zm195.633,-0l-84.217,-119.786l-111.416,-157.448l62.77,-0l85.262,124.493l110.371,152.741l-62.77,-0Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <path d="M3336.97,3157.43c-29.118,-0 -51.655,-8.675 -67.609,-26.024c-15.954,-17.349 -23.931,-41.803 -23.931,-73.362l0,-183.079l55.447,-0l0,183.079c0,15.867 4.534,28.115 13.6,36.746c9.067,8.631 21.795,12.947 38.185,12.947c20.226,-0 35.483,-6.408 45.77,-19.224c10.288,-12.815 15.431,-34.305 15.431,-64.47l14.123,74.801l-18.307,0c-4.36,18.831 -12.903,33.303 -25.632,43.416c-12.728,10.113 -28.42,15.17 -47.077,15.17Zm81.601,-2.616l-4.708,-66.431l0,-48.647l55.447,-0l0,62.247l38.185,5.754l0,44.462l-88.924,2.615Zm-4.708,-81.078l0,-198.771l55.447,-0l0,183.079l-55.447,15.692Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <path d="M3656.58,3157.43c-20.924,-0 -38.839,-0.611 -53.747,-1.831c-14.908,-1.221 -28.552,-3.226 -40.931,-6.016l6.277,-50.216c18.308,2.79 34.48,4.882 48.516,6.277c14.036,1.395 27.331,2.093 39.885,2.093c28.072,-0 48.429,-2.747 61.07,-8.239c12.641,-5.492 18.962,-14.254 18.962,-26.285c-0,-8.369 -2.224,-14.603 -6.67,-18.7c-4.446,-4.098 -11.29,-7.28 -20.531,-9.546l-63.816,-16.216c-27.375,-6.974 -47.47,-15.605 -60.285,-25.893c-12.816,-10.287 -19.224,-25.892 -19.224,-46.815c0,-30.339 11.639,-52.309 34.916,-65.909c23.277,-13.6 60.809,-20.4 112.594,-20.4c13.426,-0 25.936,0.392 37.531,1.177c11.595,0.784 23.495,2.136 35.701,4.054l-5.754,48.123c-14.124,-1.395 -26.59,-2.354 -37.401,-2.877c-10.81,-0.523 -21.359,-0.784 -31.646,-0.784c-31.037,-0 -53.529,2.659 -67.478,7.977c-13.949,5.318 -20.923,13.992 -20.923,26.023c-0,8.021 2.877,13.818 8.63,17.393c5.754,3.574 14.56,6.843 26.416,9.807l51.262,12.554c29.119,6.975 50.434,16.303 63.947,27.985c13.513,11.683 20.27,28.683 20.27,51.001c-0,29.816 -10.898,51.48 -32.693,64.993c-21.795,13.513 -56.755,20.27 -104.878,20.27Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <path d="M3964.67,3152.2l-0,-371.389l54.401,-0l-0,371.389l-54.401,-0Zm-108.801,-321.696l-0,-49.693l272.003,-0l-0,49.693l-272.003,-0Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <path d="M4329.78,3157.43c-47.252,-0 -83.737,-12.424 -109.455,-37.27c-25.718,-24.847 -38.578,-60.547 -38.578,-107.101c0,-45.334 10.898,-80.555 32.693,-105.663c21.795,-25.108 52.57,-37.662 92.324,-37.662c37.488,-0 66.476,11.333 86.963,34c20.487,22.667 30.731,55.621 30.731,98.863c0,12.728 -0.523,24.585 -1.569,35.57l-194.587,-0l-0,-42.37l145.94,-0c0,-24.934 -5.928,-44.07 -17.785,-57.409c-11.856,-13.338 -28.246,-20.007 -49.17,-20.007c-23.538,-0 -41.803,8.02 -54.793,24.061c-12.989,16.042 -19.484,38.709 -19.484,68.001c-0,32.083 8.936,56.493 26.808,73.232c17.872,16.739 43.285,25.108 76.239,25.108c11.508,-0 22.841,-0.654 34,-1.962c11.16,-1.307 22.493,-3.095 34.001,-5.361l6.8,48.646c-16.39,3.139 -31.385,5.144 -44.985,6.016c-13.6,0.872 -25.631,1.308 -36.093,1.308Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <path d="M4646.25,3157.43c-47.251,-0 -83.736,-12.424 -109.455,-37.27c-25.718,-24.847 -38.577,-60.547 -38.577,-107.101c-0,-45.334 12.162,-80.555 36.485,-105.663c24.323,-25.108 59.065,-37.662 104.224,-37.662c27.026,-0 50.173,5.013 69.44,15.038c19.266,10.026 32.997,24.193 41.192,42.501l-42.631,32.17c-7.846,-12.729 -17.872,-22.58 -30.077,-29.555c-12.205,-6.974 -25.544,-10.461 -40.016,-10.461c-26.154,-0 -46.467,7.89 -60.939,23.669c-14.472,15.78 -21.708,38.229 -21.708,67.347c-0,31.56 8.5,55.665 25.5,72.316c17,16.652 41.28,24.978 72.839,24.978c12.903,-0 25.806,-0.611 38.709,-1.831c12.902,-1.221 25.456,-2.877 37.662,-4.969l6.277,48.646c-14.298,3.139 -29.075,5.231 -44.332,6.277c-15.256,1.047 -30.121,1.57 -44.593,1.57Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <path d="M4933.42,3157.43c-10.636,-0 -19.746,-3.793 -27.331,-11.377c-7.585,-7.585 -11.377,-16.696 -11.377,-27.332c0,-10.81 3.792,-19.964 11.377,-27.461c7.585,-7.498 16.695,-11.247 27.331,-11.247c10.811,0 19.965,3.749 27.462,11.247c7.498,7.497 11.246,16.651 11.246,27.461c0,10.636 -3.748,19.747 -11.246,27.332c-7.497,7.584 -16.651,11.377 -27.462,11.377Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <path d="M5237.86,3152.2l-0,-261.542l55.446,0l0,261.542l-55.446,-0Zm-105.663,-0l-0,-48.647l110.893,0l0,48.647l-110.893,-0Zm155.879,-0l-0,-48.647l90.493,0l0,48.647l-90.493,-0Zm-140.187,-228.588l0,-48.646l145.417,-0l0,48.646l-145.417,0Zm115.863,-92.585c-10.636,-0 -19.746,-3.793 -27.331,-11.377c-7.585,-7.585 -11.377,-16.696 -11.377,-27.332c0,-10.81 3.792,-19.964 11.377,-27.461c7.585,-7.498 16.695,-11.247 27.331,-11.247c10.811,0 19.965,3.749 27.462,11.247c7.498,7.497 11.246,16.651 11.246,27.461c0,10.636 -3.748,19.747 -11.246,27.332c-7.497,7.584 -16.651,11.377 -27.462,11.377Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <path d="M5618.66,3152.2l0,-180.464c0,-16.739 -4.359,-29.641 -13.077,-38.708c-8.718,-9.067 -20.923,-13.6 -36.616,-13.6c-43.59,-0 -65.385,25.631 -65.385,76.893l-16.216,-71.139l21.447,-0c1.569,-18.657 8.369,-32.562 20.4,-41.716c12.031,-9.154 29.641,-13.731 52.831,-13.731c29.293,-0 51.96,8.892 68.001,26.677c16.042,17.785 24.062,42.893 24.062,75.324l0,180.464l-55.447,-0Zm-170.525,-0l0,-277.234l50.216,-0l5.231,58.585l-0,218.649l-55.447,-0Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <path d="M5901.65,3157.43c-47.251,-0 -83.737,-12.424 -109.455,-37.27c-25.718,-24.847 -38.577,-60.547 -38.577,-107.101c-0,-45.334 12.161,-80.555 36.485,-105.663c24.323,-25.108 59.065,-37.662 104.224,-37.662c27.026,-0 50.173,5.013 69.44,15.038c19.266,10.026 32.997,24.193 41.192,42.501l-42.631,32.17c-7.846,-12.729 -17.872,-22.58 -30.077,-29.555c-12.206,-6.974 -25.544,-10.461 -40.016,-10.461c-26.154,-0 -46.467,7.89 -60.939,23.669c-14.472,15.78 -21.708,38.229 -21.708,67.347c-0,31.56 8.5,55.665 25.5,72.316c17,16.652 41.28,24.978 72.839,24.978c12.903,-0 25.806,-0.611 38.709,-1.831c12.902,-1.221 25.456,-2.877 37.662,-4.969l6.277,48.646c-14.298,3.139 -29.075,5.231 -44.332,6.277c-15.256,1.047 -30.121,1.57 -44.593,1.57Z" style="fill:#cfe64d;fill-rule:nonzero;"/>
                            <rect x="1637.68" y="2416.88" width="4598.65" height="54.98" style="fill:#3c8cc1;"/>
                            <rect x="2131.56" y="2634.76" width="1492.88" height="38.605" style="fill:#3c8cc1;"/>
                            <rect x="1908.04" y="2326.11" width="484.88" height="242.955" style="fill:#fbfbf9;"/>
                            <rect x="3354.36" y="2326.11" width="512.527" height="242.955" style="fill:#fbfbf9;"/>
                            <rect x="2747.5" y="2322.89" width="499.96" height="242.955" style="fill:#cfe64d;"/>
                            <rect x="2131.56" y="2603.2" width="42.98" height="70.172" style="fill:#3c8cc1;"/>
                            <rect x="3581.47" y="2603.2" width="42.98" height="70.172" style="fill:#3c8cc1;"/>
                        </svg>
                    </div>
                </div>
            </div>

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
                    0%, 100% {
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
                        transparent 100%
                    );
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
                        transparent 100%
                    );
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
                    animation: pulse-glow-main 2s ease-in-out infinite;
                    transition: transform 0.6s ease-in-out;
                }

                .logo-container-main:hover {
                    transform: rotate(360deg);
                }

                @keyframes pulse-glow-main {
                    0%, 100% {
                        filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.6)) drop-shadow(0 0 40px rgba(59, 130, 246, 0.4));
                    }
                    50% {
                        filter: drop-shadow(0 0 40px rgba(59, 130, 246, 0.9)) drop-shadow(0 0 60px rgba(59, 130, 246, 0.7)) drop-shadow(0 0 80px rgba(59, 130, 246, 0.5));
                    }
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
                        transparent 100%
                    );
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
                        transparent 100%
                    );
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
                    0%, 100% {
                        opacity: 0.3;
                        transform: scale(1);
                    }
                    50% {
                        opacity: 0.8;
                        transform: scale(1.1);
                    }
                }

                @keyframes energy-float {
                    0%, 100% {
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
            
            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-900/95 backdrop-blur-md shadow-2xl overflow-hidden sm:rounded-lg relative z-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
