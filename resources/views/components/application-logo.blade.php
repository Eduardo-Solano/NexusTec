<div class="logo-circle-wrapper" style="position: relative; display: inline-block;">
    <svg {{ $attributes }} viewBox="1000 200 5875 5360" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:1.5; shape-rendering: geometricPrecision;">
        <defs>
            <linearGradient id="cyan-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#06B6D4;stop-opacity:1" />
                <stop offset="50%" style="stop-color:#22D3EE;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#67E8F9;stop-opacity:1" />
            </linearGradient>
            <linearGradient id="green-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" style="stop-color:#84CC16;stop-opacity:1" />
                <stop offset="50%" style="stop-color:#A3E635;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#BEF264;stop-opacity:1" />
            </linearGradient>
            
            <filter id="neon-cyan" x="-100%" y="-100%" width="300%" height="300%">
                <feGaussianBlur in="SourceGraphic" stdDeviation="2" result="blur1"/>
                <feGaussianBlur in="SourceGraphic" stdDeviation="6" result="blur2"/>
                <feGaussianBlur in="SourceGraphic" stdDeviation="12" result="blur3"/>
                <feMerge>
                    <feMergeNode in="blur3"/>
                    <feMergeNode in="blur2"/>
                    <feMergeNode in="blur1"/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>
            <filter id="neon-green" x="-100%" y="-100%" width="300%" height="300%">
                <feGaussianBlur in="SourceGraphic" stdDeviation="2" result="blur1"/>
                <feGaussianBlur in="SourceGraphic" stdDeviation="6" result="blur2"/>
                <feGaussianBlur in="SourceGraphic" stdDeviation="12" result="blur3"/>
                <feMerge>
                    <feMergeNode in="blur3"/>
                    <feMergeNode in="blur2"/>
                    <feMergeNode in="blur1"/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>
            <filter id="neon-circle" x="-100%" y="-100%" width="300%" height="300%">
                <feGaussianBlur in="SourceGraphic" stdDeviation="3" result="blur1"/>
                <feGaussianBlur in="SourceGraphic" stdDeviation="8" result="blur2"/>
                <feGaussianBlur in="SourceGraphic" stdDeviation="15" result="blur3"/>
                <feMerge>
                    <feMergeNode in="blur3"/>
                    <feMergeNode in="blur2"/>
                    <feMergeNode in="blur1"/>
                    <feMergeNode in="SourceGraphic"/>
                </feMerge>
            </filter>
            
            <filter id="inner-shadow">
                <feGaussianBlur in="SourceAlpha" stdDeviation="4" result="blur"/>
                <feOffset in="blur" dx="2" dy="2" result="offsetBlur"/>
                <feComposite in="offsetBlur" in2="SourceAlpha" operator="arithmetic" k2="-1" k3="1" result="shadowDiff"/>
                <feFlood flood-color="#000000" flood-opacity="0.3"/>
                <feComposite in2="shadowDiff" operator="in"/>
                <feComposite in2="SourceGraphic" operator="over"/>
            </filter>
        </defs>
        <g>
            <circle cx="3937.5" cy="2780" r="2350" class="breathing-circle-base" style="fill:none;stroke:url(#cyan-gradient);stroke-width:25px;opacity:0.2;"/>
            <circle cx="3937.5" cy="2780" r="2400" class="breathing-circle" style="fill:none;stroke:url(#cyan-gradient);stroke-width:50px;filter:url(#neon-circle);opacity:0.7;"/>
            <circle cx="3937.5" cy="2780" r="2440" class="breathing-circle-mid" style="fill:none;stroke:url(#green-gradient);stroke-width:35px;filter:url(#neon-circle);opacity:0.5;"/>
            <circle cx="3937.5" cy="2780" r="2480" class="breathing-circle-outer" style="fill:none;stroke:url(#green-gradient);stroke-width:40px;filter:url(#neon-circle);opacity:0.6;"/>
            
            <g transform="translate(80, 0)" class="logo-n-group">
                <path class="path-cyan-1" d="M4219.78,2303.75c-52.193,-575.042 141.907,-788.482 397.887,-763.613c243.025,23.611 427.257,106.675 388.234,914.086" style="fill:none;stroke:url(#cyan-gradient);stroke-width:130px;filter:url(#neon-cyan);stroke-linecap:round;"/>
                <path class="path-green-1" d="M2505.92,3069.29c14.212,113.151 -122.361,902.204 351.625,911.717c150.994,3.031 484.114,-17.975 413.402,-765.71" style="fill:none;stroke:url(#green-gradient);stroke-width:130px;filter:url(#neon-green);stroke-linecap:round;"/>
                
                <ellipse class="ellipse-green-1" cx="2878.17" cy="3516.4" rx="107.567" ry="115.282" style="fill:none;stroke:url(#green-gradient);stroke-width:130px;filter:url(#neon-green);"/>
                <ellipse class="ellipse-green-fill-1" cx="2878.17" cy="3516.4" rx="80" ry="85" style="fill:#A3E635;opacity:0.3;"/>
                
                <path class="path-green-2" d="M2878.17,3379.99c9.512,-821.622 -50.041,-1297.49 79.536,-1344.63c120.207,-43.734 252.925,150.709 555.678,489.613" style="fill:none;stroke:url(#green-gradient);stroke-width:130px;filter:url(#neon-green);stroke-linecap:round;"/>
                
                <ellipse class="ellipse-green-2" cx="3737.01" cy="2762.82" rx="95" ry="102" style="fill:url(#green-gradient);filter:url(#neon-green);"/>
                <ellipse class="ellipse-green-fill-2" cx="3737.01" cy="2762.82" rx="70" ry="75" style="fill:#BEF264;opacity:0.8;"/>
                
                <ellipse class="ellipse-cyan-1" cx="4985.36" cy="2762.82" rx="95" ry="102" style="fill:url(#cyan-gradient);filter:url(#neon-cyan);"/>
                <ellipse class="ellipse-cyan-fill-1" cx="4985.36" cy="2762.82" rx="70" ry="75" style="fill:#67E8F9;opacity:0.8;"/>
                
                <path class="path-cyan-2" d="M3931.47,2969.75c362.072,382.068 497.994,559.345 614.323,481.022c139.761,-94.1 78.073,-505.158 60.185,-1251.31" style="fill:none;stroke:url(#cyan-gradient);stroke-width:130px;filter:url(#neon-cyan);stroke-linecap:round;"/>
                
                <ellipse class="ellipse-cyan-2" cx="4617.66" cy="2006.55" rx="149" ry="159" style="fill:none;stroke:url(#cyan-gradient);stroke-width:130px;filter:url(#neon-cyan);"/>
                <ellipse class="ellipse-cyan-fill-2" cx="4617.66" cy="2006.55" rx="110" ry="117" style="fill:#22D3EE;opacity:0.3;"/>
                
                <path class="path-cyan-3" d="M3276.75,2784.46c591.418,640.319 958.519,1176.65 1237.16,1194.26c328.275,20.742 564.786,-114.472 471.444,-1124.28" style="fill:none;stroke:url(#cyan-gradient);stroke-width:130px;filter:url(#neon-cyan);stroke-linecap:round;"/>
                
                <ellipse class="ellipse-green-3" cx="2494.32" cy="2762.82" rx="148" ry="158" style="fill:url(#green-gradient);filter:url(#neon-green);"/>
                <ellipse class="ellipse-green-fill-3" cx="2494.32" cy="2762.82" rx="110" ry="117" style="fill:#BEF264;opacity:0.8;"/>
                
                <path class="path-green-3" d="M2494.32,2762.82c12.591,-715.081 -94.553,-1004.06 242.708,-1185.51c96.522,-51.929 260.876,-79.678 376.612,5.724c279.554,206.283 551.186,588.021 1117.61,1179.78" style="fill:none;stroke:url(#green-gradient);stroke-width:130px;filter:url(#neon-green);stroke-linecap:round;"/>
            </g>
        </g>
    </svg>
    <style>
        @keyframes breathing {
            0%, 100% {
                r: 2400px;
                opacity: 0.7;
                stroke-width: 50px;
            }
            50% {
                r: 2420px;
                opacity: 0.9;
                stroke-width: 55px;
            }
        }
        
        @keyframes breathing-mid {
            0%, 100% {
                r: 2440px;
                opacity: 0.5;
                stroke-width: 35px;
            }
            50% {
                r: 2460px;
                opacity: 0.7;
                stroke-width: 40px;
            }
        }
        
        @keyframes breathing-outer {
            0%, 100% {
                r: 2480px;
                opacity: 0.6;
                stroke-width: 40px;
            }
            50% {
                r: 2500px;
                opacity: 0.8;
                stroke-width: 45px;
            }
        }
        
        @keyframes breathing-base {
            0%, 100% {
                r: 2350px;
                opacity: 0.2;
            }
            50% {
                r: 2370px;
                opacity: 0.4;
            }
        }
        
        @keyframes pulse-scale {
            0%, 100% {
                transform: scale(1);
                opacity: 0.8;
            }
            50% {
                transform: scale(1.2);
                opacity: 1;
            }
        }
        
        @keyframes pulse-fill {
            0%, 100% {
                opacity: 0.3;
                transform: scale(1);
            }
            50% {
                opacity: 0.6;
                transform: scale(1.1);
            }
        }
        
        @keyframes draw-path {
            0% {
                stroke-dashoffset: 3000;
                opacity: 0.5;
            }
            100% {
                stroke-dashoffset: 0;
                opacity: 1;
            }
        }
        
        @keyframes glow-pulse {
            0%, 100% {
                filter: url(#neon-cyan) drop-shadow(0 0 5px currentColor);
            }
            50% {
                filter: url(#neon-cyan) drop-shadow(0 0 20px currentColor);
            }
        }
        
        @keyframes fadeInScale {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .breathing-circle-base {
            animation: breathing-base 3s ease-in-out infinite;
            transition: all 0.3s ease;
        }
        
        .breathing-circle {
            animation: breathing 3s ease-in-out infinite 0.2s;
            transition: all 0.3s ease;
        }
        
        .breathing-circle-mid {
            animation: breathing-mid 3s ease-in-out infinite 0.4s;
            transition: all 0.3s ease;
        }
        
        .breathing-circle-outer {
            animation: breathing-outer 3s ease-in-out infinite 0.6s;
            transition: all 0.3s ease;
        }
        
        svg {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
        
        svg path[class*="path-"] {
            stroke-dasharray: 3000;
            stroke-dashoffset: 3000;
            animation: draw-path 2.5s ease-out forwards;
            transform-origin: center;
            transition: all 0.3s ease;
        }
        
        .path-cyan-1 { animation-delay: 0s; }
        .path-green-1 { animation-delay: 0.2s; }
        .path-green-2 { animation-delay: 0.4s; }
        .path-cyan-2 { animation-delay: 0.6s; }
        .path-cyan-3 { animation-delay: 0.8s; }
        .path-green-3 { animation-delay: 1s; }
        
        svg ellipse[class*="ellipse-"] {
            transform-origin: center;
            animation: pulse-scale 2.5s ease-in-out infinite;
            transition: all 0.3s ease;
        }
        
        .ellipse-green-1 { animation-delay: 0s; }
        .ellipse-green-fill-1 { animation: pulse-fill 2.5s ease-in-out infinite 0.1s; }
        .ellipse-green-2 { animation-delay: 0.3s; }
        .ellipse-green-fill-2 { animation: pulse-fill 2.5s ease-in-out infinite 0.4s; }
        .ellipse-cyan-1 { animation-delay: 0.6s; }
        .ellipse-cyan-fill-1 { animation: pulse-fill 2.5s ease-in-out infinite 0.7s; }
        .ellipse-cyan-2 { animation-delay: 0.9s; }
        .ellipse-cyan-fill-2 { animation: pulse-fill 2.5s ease-in-out infinite 1s; }
        .ellipse-green-3 { animation-delay: 1.2s; }
        .ellipse-green-fill-3 { animation: pulse-fill 2.5s ease-in-out infinite 1.3s; }
        
        .logo-circle-wrapper {
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.3s ease;
            cursor: pointer;
            animation: fadeInScale 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }
        
        .logo-circle-wrapper:hover {
            transform: scale(1.08);
            filter: drop-shadow(0 0 25px rgba(6, 182, 212, 0.6)) 
                    drop-shadow(0 0 40px rgba(163, 230, 53, 0.4));
        }
        
        .logo-circle-wrapper:hover .breathing-circle-base {
            r: 2380px;
            opacity: 0.6;
            stroke-width: 35px;
            animation-duration: 1.5s;
        }
        
        .logo-circle-wrapper:hover .breathing-circle {
            animation-duration: 1.5s;
            opacity: 1;
            stroke-width: 65px;
        }
        
        .logo-circle-wrapper:hover .breathing-circle-mid {
            animation-duration: 1.5s;
            opacity: 0.95;
            stroke-width: 45px;
        }
        
        .logo-circle-wrapper:hover .breathing-circle-outer {
            animation-duration: 1.5s;
            opacity: 1;
            stroke-width: 50px;
        }
        
        .logo-circle-wrapper:hover svg path[class*="path-"] {
            stroke-width: 145px;
            animation: draw-path 1.5s ease-in-out infinite;
            filter: brightness(1.3) drop-shadow(0 0 15px currentColor);
        }
        
        .logo-circle-wrapper:hover svg ellipse[class*="ellipse-"] {
            animation-duration: 1s;
            filter: brightness(1.5) drop-shadow(0 0 15px currentColor);
        }
        
        .logo-circle-wrapper:hover svg ellipse[class*="ellipse-fill"] {
            animation-duration: 1s;
            opacity: 0.8 !important;
        }
        
        .logo-circle-wrapper:active {
            transform: scale(1.02);
            transition: transform 0.1s ease;
        }
        
        .logo-n-group {
            transform-origin: center;
            transition: transform 0.3s ease;
        }
        
        .logo-circle-wrapper:hover .logo-n-group {
            transform: translate(80px, 0) scale(1.02);
        }
        
        .logo-circle-wrapper:focus {
            outline: none;
            filter: drop-shadow(0 0 30px rgba(6, 182, 212, 0.8));
        }
        
        @keyframes gradient-shift {
            0%, 100% {
                stop-color: #06B6D4;
            }
            50% {
                stop-color: #22D3EE;
            }
        }
    </style>
</div>