<nav x-data="{ open: false, notificationsOpen: false }" class="sticky top-0 z-40 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-200/50 dark:border-gray-700/50 shadow-sm">
    
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
                <div class="hidden space-x-8 xl:-my-px xl:ms-10 xl:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Inicio') }}
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
                
                <!-- 🔔 Notification Bell -->
                <div class="relative me-3 ms-6 group">
                     @php
                        $hasJoinRequests = $unreadNotifications->where('data.type', 'join_request')->count() > 0;
                        $totalNotifications = ($pendingAdvisories->count() ?? 0) + ($pendingEvaluations->count() ?? 0) + ($unreadNotifications->count() ?? 0);
                    @endphp

                    <button @click="notificationsOpen = !notificationsOpen" class="relative text-gray-500 hover:text-gray-700 dark:text-gray-300 focus:outline-none transition-all duration-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V4a2 2 0 10-4 0v1.3A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                        </svg>
                        @if ($totalNotifications > 0)
                            <span class="absolute -top-1 -right-1 h-5 w-5 flex items-center justify-center bg-red-600 text-white rounded-full text-[10px] font-bold group-hover:opacity-70 transition-opacity duration-200">
                                {{ $totalNotifications > 9 ? '9+' : $totalNotifications }}
                            </span>
                        @endif
                    </button>
                    
                     <!-- NOTIFICATIONS PANEL -->
                     <div x-show="notificationsOpen" @click.away="notificationsOpen = false" x-transition
                         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-gray-200 dark:divide-gray-700"
                         style="display: none;">
                        
                        <!-- Header -->
                        <div class="px-4 py-3 bg-white dark:bg-gray-800 flex justify-between">
                             <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Notificaciones</h3>
                             @if ($unreadNotifications->count() > 0)
                                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                                    @csrf
                                    <button class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Marcar leídas</button>
                                </form>
                             @endif
                        </div>

                        <!-- Body with Logic -->
                        <div class="max-h-80 overflow-y-auto bg-white dark:bg-gray-800">
                            
                            {{-- 1. SOLICITUDES DE UNIÓN --}}
                            @foreach ($unreadNotifications as $notification)
                                @if (($notification->data['type'] ?? null) === 'join_request')
                                    <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b dark:border-gray-700">
                                        <div class="flex items-start gap-3">
                                            <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                                                <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $notification->data['message'] }}</p>
                                                <p class="text-[10px] text-gray-500">Rol solicitado: {{ $notification->data['role'] ?? 'Miembro' }}</p>
                                                <div class="flex gap-2 mt-3">
                                                    <form action="{{ $notification->data['accept_url'] }}" method="POST">
                                                        @csrf
                                                        <button class="px-3 py-1.5 text-xs font-bold bg-green-600 hover:bg-green-500 text-white rounded-lg">Aceptar</button>
                                                    </form>
                                                    <form action="{{ $notification->data['reject_url'] }}" method="POST">
                                                        @csrf
                                                        <button class="px-3 py-1.5 text-xs font-bold bg-red-600 hover:bg-red-500 text-white rounded-lg">Rechazar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            {{-- 2. SOLICITUDES DE ASESORÍA --}}
                            @if ($pendingAdvisories->count() > 0)
                                <div class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 border-b dark:border-gray-700">
                                    <p class="text-xs font-bold text-indigo-600 uppercase">Solicitudes de Asesoría</p>
                                </div>
                                @foreach ($pendingAdvisories as $advisory)
                                    <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b dark:border-gray-700">
                                        <div class="flex items-start gap-3">
                                            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                                <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2M7 20H2v-2a3 3 0 015.356-1.857" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $advisory->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $advisory->event->name ?? '' }}</p>
                                                <div class="flex gap-2 mt-2">
                                                    <form action="{{ route('teams.advisor.response', ['team' => $advisory, 'status' => 'accepted']) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <button class="px-2.5 py-1 text-xs bg-green-600 text-white rounded-lg">Aceptar</button>
                                                    </form>
                                                    <form action="{{ route('teams.advisor.response', ['team' => $advisory, 'status' => 'rejected']) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <button class="px-2.5 py-1 text-xs bg-red-600 text-white rounded-lg">Rechazar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- 3. PROYECTOS POR EVALUAR --}}
                            @if ($pendingEvaluations->count() > 0)
                                <div class="px-4 py-2 bg-amber-50 dark:bg-amber-900/20 border-b dark:border-gray-700">
                                    <p class="text-xs font-bold text-amber-600 uppercase">Proyectos por Evaluar</p>
                                </div>
                                @foreach ($pendingEvaluations as $project)
                                    <div class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b dark:border-gray-700">
                                        <div class="flex items-center gap-3">
                                            <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                                                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $project->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $project->team->name }}</p>
                                            </div>
                                            <a href="{{ route('projects.show', $project) }}" class="px-3 py-1.5 text-xs font-bold bg-amber-600 text-white rounded-lg">Ver</a>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- 4. PREMIOS Y OTRAS NOTIFICACIONES --}}
                            @foreach ($unreadNotifications as $notification)
                                @if (($notification->data['type'] ?? null) !== 'join_request')
                                    <a href="{{ route('public.event-winners', $notification->data['event_id'] ?? 0) }}"
                                       onclick="fetch('{{ route('notifications.markAsRead', $notification->id) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})"
                                       class="block px-4 py-3 hover:bg-yellow-50 dark:hover:bg-yellow-900/10 bg-yellow-50/50 dark:bg-yellow-900/5 border-b dark:border-gray-700">
                                        <div class="flex items-center gap-3">
                                            <div class="text-2xl">🏆</div>
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $notification->data['award_category'] ?? 'Premio' }}</p>
                                                <p class="text-xs text-gray-500 truncate">
                                                    {{ $notification->data['team_name'] ?? 'Tu equipo' }} · {{ $notification->data['event_name'] ?? '' }}
                                                </p>
                                                <p class="text-xs text-yellow-600 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach

                            {{-- ESTADO VACÍO --}}
                            @if ($totalNotifications === 0)
                                <div class="px-4 py-8 text-center">
                                    <div class="inline-flex p-3 bg-gray-100 dark:bg-gray-700 rounded-full mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V4a2 2 0 10-4 0v1.3A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-500">No hay notificaciones</p>
                                    <p class="text-xs text-gray-400 mt-1">Estás al día 🎉</p>
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
    <template x-teleport="body">
        <div x-show="open" class="relative z-50">
             <!-- Backdrop (Fondo oscuro) -->
            <div x-show="open" 
                x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"
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
                class="fixed inset-y-0 left-0 w-72 bg-white dark:bg-gray-800 shadow-2xl overflow-y-auto">

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
        </div>
    </template>

</nav>