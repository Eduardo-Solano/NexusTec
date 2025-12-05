<nav x-data="{ open: false, notificationsOpen: false }" class="sticky top-0 z-40 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    
    <!-- PRIMARY NAVIGATION BAR -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- LEFT SIDE: Logo & Desktop Menu -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Desktop Navigation Links (Visible only on XL screens and up) -->
                <!-- CAMBIO: De 'hidden sm:flex' a 'hidden xl:flex' para evitar saturación -->
                <div class="hidden space-x-8 xl:-my-px xl:ms-10 xl:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                        {{ __('Eventos') }}
                    </x-nav-link>

                    @can('teams.edit')
                        <x-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')">
                            {{ __('Equipos') }}
                        </x-nav-link>
                    @endcan

                    @can('projects.edit')
                        <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                            {{ __('Proyectos') }}
                        </x-nav-link>
                    @endcan

                    @can('criteria.view')
                        <x-nav-link :href="route('criteria.index')" :active="request()->routeIs('criteria.*')">
                            {{ __('Criterios') }}
                        </x-nav-link>
                    @endcan

                    @can('staff.view')
                        <x-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.*')">
                            {{ __('Docentes') }}
                        </x-nav-link>
                    @endcan

                    @can('students.view')
                        <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                            {{ __('Alumnos') }}
                        </x-nav-link>
                    @endcan

                    @can('judges.view')
                        <x-nav-link :href="route('judges.index')" :active="request()->routeIs('judges.*')">
                            {{ __('Jueces') }}
                        </x-nav-link>
                    @endcan

                    @role('admin')
                        <x-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')">
                            {{ __('Actividad') }}
                        </x-nav-link>

                        {{-- Admin Dropdown (Desktop) --}}
                        <div class="hidden xl:flex xl:items-center xl:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 text-sm border border-transparent rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 transition ease-in-out duration-150">
                                        <span>Administrar</span>
                                        <svg class="ms-2 -me-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('careers.index')">🎓 Carreras</x-dropdown-link>
                                    <x-dropdown-link :href="route('specialties.index')">⚖️ Especialidades</x-dropdown-link>
                                    <x-dropdown-link :href="route('reports.index')">📊 Reportes</x-dropdown-link>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endrole
                </div>
            </div>

            <!-- RIGHT SIDE: Notifications & User Menu -->
            <div class="flex items-center">
                
                <!-- 🔔 Notification Bell (Visible Always) -->
                <!-- SE AGREGÓ 'ms-6' PARA SEPARARLO DEL ADMINISTRADOR -->
                <div class="relative me-3 ms-6">
                     @php
                        $hasJoinRequests = $unreadNotifications->where('data.type', 'join_request')->count() > 0;
                        $totalNotifications = ($pendingAdvisories->count() ?? 0) + ($pendingEvaluations->count() ?? 0) + ($unreadNotifications->count() ?? 0);
                    @endphp

                    <button @click="notificationsOpen = !notificationsOpen" class="relative text-gray-500 hover:text-gray-700 dark:text-gray-300 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V4a2 2 0 10-4 0v1.3A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                        </svg>
                        @if ($totalNotifications > 0)
                            <span class="absolute -top-1 -right-1 h-5 w-5 flex items-center justify-center bg-red-600 text-white rounded-full text-[10px] font-bold">
                                {{ $totalNotifications > 9 ? '9+' : $totalNotifications }}
                            </span>
                        @endif
                    </button>
                    
                     <!-- NOTIFICATIONS DROPDOWN PANEL (Desktop & Mobile view logic handled by absolute positioning) -->
                     <div x-show="notificationsOpen" @click.away="notificationsOpen = false" x-transition
                         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl border z-50 overflow-hidden divide-y divide-gray-200 dark:divide-gray-700"
                         style="display: none;">
                        
                        <!-- Header Notificaciones -->
                        <div class="px-4 py-3 bg-white dark:bg-gray-800 flex justify-between">
                             <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Notificaciones</h3>
                             @if ($unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                    @csrf
                                    <button class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Marcar leídas</button>
                                </form>
                             @endif
                        </div>

                        <!-- Body Notificaciones -->
                        <div class="max-h-80 overflow-y-auto bg-white dark:bg-gray-800">
                             {{-- COPIAR AQUÍ TU LÓGICA DE NOTIFICACIONES EXISTENTE --}}
                             {{-- He resumido esta parte para no hacer el código infinito, 
                                  pero aquí va exactamente el mismo bloque @foreach que tenías --}}
                             @if ($totalNotifications === 0)
                                <div class="px-4 py-8 text-center">
                                    <p class="text-sm text-gray-500">No hay notificaciones</p>
                                </div>
                             @else
                                <div class="px-4 py-2 text-xs text-gray-400 text-center">
                                    Ver todas las notificaciones...
                                </div>
                             @endif
                        </div>
                     </div>
                </div>

                <!-- User Dropdown (Visible on XL) -->
                <div class="hidden xl:flex xl:items-center xl:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 rounded-md text-sm text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <svg class="ms-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    Cerrar Sesión
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- HAMBURGER BUTTON (Visible on screens smaller than XL) -->
                <!-- CAMBIO: 'sm:hidden' a 'xl:hidden' -->
                <div class="-me-2 flex items-center xl:hidden">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- OFF-CANVAS SIDEBAR (SLIDE-OVER MENU) -->
    <!-- ============================================== -->
    
    <!-- Backdrop (Fondo oscuro) -->
    <div x-show="open" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/80 z-40 xl:hidden backdrop-blur-sm"
         style="display: none;"
         @click="open = false">
    </div>

    <!-- Sidebar Panel -->
    <div x-show="open"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-gray-800 shadow-2xl overflow-y-auto xl:hidden"
         style="display: none;">

         <!-- Sidebar Header -->
         <div class="flex items-center justify-between px-4 h-16 border-b border-gray-200 dark:border-gray-700">
            <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Menú</span>
            <button @click="open = false" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
         </div>

         <!-- User Info Mobile -->
         <div class="px-4 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
            <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
         </div>

         <!-- Mobile Navigation Links -->
         <div class="py-2 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                {{ __('Eventos') }}
            </x-responsive-nav-link>

            @can('teams.edit')
                <x-responsive-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')">
                    {{ __('Equipos') }}
                </x-responsive-nav-link>
            @endcan

            @can('projects.edit')
                <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                    {{ __('Proyectos') }}
                </x-responsive-nav-link>
            @endcan

            @can('criteria.view')
                <x-responsive-nav-link :href="route('criteria.index')" :active="request()->routeIs('criteria.*')">
                    {{ __('Criterios') }}
                </x-responsive-nav-link>
            @endcan

            @can('staff.view')
                <x-responsive-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.*')">
                    {{ __('Docentes') }}
                </x-responsive-nav-link>
            @endcan

            @can('students.view')
                <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                    {{ __('Alumnos') }}
                </x-responsive-nav-link>
            @endcan

            @can('judges.view')
                <x-responsive-nav-link :href="route('judges.index')" :active="request()->routeIs('judges.*')">
                    {{ __('Jueces') }}
                </x-responsive-nav-link>
            @endcan

            @role('admin')
                <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
                <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Administración
                </div>
                <x-responsive-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')">
                    Historial de Actividad
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('careers.index')" :active="request()->routeIs('careers.*')">
                    🎓 Carreras
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('specialties.index')" :active="request()->routeIs('specialties.*')">
                    ⚖️ Especialidades
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                    📊 Reportes
                </x-responsive-nav-link>
            @endrole
         </div>

         <!-- Mobile Logout -->
         <div class="border-t border-gray-200 dark:border-gray-700 pt-2 pb-4">
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Perfil') }}
            </x-responsive-nav-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-600 dark:text-red-400">
                    {{ __('Cerrar Sesión') }}
                </x-responsive-nav-link>
            </form>
         </div>
    </div>

</nav>