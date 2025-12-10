<nav x-data="{ open: false, notificationsOpen: false }"
    class="sticky top-0 z-40 bg-[#0B1120]/80 backdrop-blur-md border-b border-white/10 shadow-lg transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">

            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group relative">
                        <div
                            class="absolute inset-0 bg-blue-500/20 blur-xl rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <img src="{{ asset('img/logo-ito.png') }}" alt="Logo ITO"
                            class="block h-10 w-auto relative z-10 transition-all duration-500 ease-out group-hover:scale-110 group-hover:rotate-3 group-hover:drop-shadow-[0_0_15px_rgba(56,189,248,0.6)]" />
                    </a>
                </div>

                <div class="hidden space-x-8 xl:-my-px xl:ms-10 xl:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="text-gray-300 hover:text-white transition-colors duration-300">
                        {{ __('Inicio') }}
                    </x-nav-link>

                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')"
                        class="text-gray-300 hover:text-white transition-colors duration-300">
                        {{ __('Eventos') }}
                    </x-nav-link>

                    @can('teams.edit')
                        <x-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')"
                            class="text-gray-300 hover:text-white transition-colors duration-300">
                            {{ __('Equipos') }}
                        </x-nav-link>
                    @endcan

                    @can('projects.edit')
                        <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')"
                            class="text-gray-300 hover:text-white transition-colors duration-300">
                            {{ __('Proyectos') }}
                        </x-nav-link>
                    @endcan

                    @can('criteria.view')
                        <x-nav-link :href="route('criteria.index')" :active="request()->routeIs('criteria.*')"
                            class="text-gray-300 hover:text-white transition-colors duration-300">
                            {{ __('Criterios') }}
                        </x-nav-link>
                    @endcan

                    @if (auth()->user()?->can('students.view') || auth()->user()?->can('staff.view') || auth()->user()?->can('judges.view'))
                        <div class="hidden xl:flex xl:items-center xl:ms-2">
                            <x-dropdown align="left" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium border border-transparent rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition ease-in-out duration-200">
                                        <span>Usuarios</span>
                                        <svg class="ms-2 -me-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    @can('students.view')
                                        <x-dropdown-link :href="route('students.index')">Alumnos</x-dropdown-link>
                                    @endcan
                                    @can('staff.view')
                                        <x-dropdown-link :href="route('staff.index')">Docentes</x-dropdown-link>
                                    @endcan
                                    @can('judges.view')
                                        <x-dropdown-link :href="route('judges.index')">Jueces</x-dropdown-link>
                                    @endcan
                                </x-slot>
                            </x-dropdown>
                        </div>
                    @endif

                    @role('admin')
                        <x-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')"
                            class="text-gray-300 hover:text-white transition-colors duration-300">
                            {{ __('Actividad') }}
                        </x-nav-link>

                        <div class="hidden xl:flex xl:items-center xl:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium border border-transparent rounded-lg text-purple-400 hover:text-purple-300 hover:bg-purple-500/10 transition ease-in-out duration-200 ring-1 ring-purple-500/20">
                                        <span>Administrar</span>
                                        <svg class="ms-2 -me-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
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

            <div class="flex items-center">


                
                @auth
                    <div class="relative me-3 ms-6 group" x-data="{
                        // Conteo real (para el dropdown)
                        totalNotifications: {{ $totalNotifications ?? 0 }},
                        // Conteo visual (solo badge de la campana)
                        badgeCount: {{ $totalNotifications ?? 0 }},
                        polling: null,
                        async fetchNotifications() {
                            try {
                                const response = await fetch('{{ route('notifications.poll') }}');
                                if (response.ok) {
                                    const data = await response.json();
                                    this.totalNotifications = data.total;
                                    this.badgeCount = data.total; // actualizamos también el badge
                                }
                            } catch (e) {
                                console.error('Error polling notifications:', e);
                            }
                        },
                        startPolling() {
                            this.fetchNotifications();
                            this.polling = setInterval(() => this.fetchNotifications(), 15000);
                        },
                        stopPolling() {
                            if (this.polling) clearInterval(this.polling);
                        }
                    }" x-init="startPolling()"
                        @beforeunload.window="stopPolling()">
                        @php
                            $hasJoinRequests = $unreadNotifications->where('data.type', 'join_request')->count() > 0;
                            $totalNotifications =
                                ($pendingAdvisories->count() ?? 0) +
                                ($pendingEvaluations->count() ?? 0) +
                                ($unreadNotifications->count() ?? 0);
                        @endphp

                        <button
                            @click="
        notificationsOpen = !notificationsOpen;
        if (notificationsOpen) {
            badgeCount = 0; //
        }
    "
                            class="relative p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-full transition-all duration-200 focus:outline-none">

                            <svg class="h-6 w-6 transform group-hover:scale-110 transition-transform duration-200"
                                fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V4a2 2 0 10-4 0v1.3A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                            </svg>
                            <template x-if="badgeCount > 0">
                                <span>
                                    <span
                                        class="absolute top-1 right-1 h-2.5 w-2.5 bg-red-500 rounded-full animate-pulse ring-2 ring-[#0B1120]"></span>
                                    <span
                                        class="absolute -top-1 -right-1 h-5 w-5 flex items-center justify-center bg-red-600 text-white rounded-full text-[10px] font-bold shadow-lg shadow-red-500/30"
                                        x-text="badgeCount > 9 ? '9+' : badgeCount"></span>
                                </span>
                            </template>

                        </button>

                        <!-- NOTIFICATIONS PANEL -->
                        <div x-show="notificationsOpen" @click.away="notificationsOpen = false" x-transition
                            class="fixed left-2 right-2 top-16 sm:absolute sm:left-auto sm:right-0 sm:top-auto sm:mt-2 w-auto sm:w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl z-50 overflow-hidden divide-y divide-gray-200 dark:divide-gray-700"
                            style="display: none;">

                            <!-- Header -->
                            <div
                                class="px-5 py-4 bg-white/5 border-b border-white/10 flex justify-between items-center backdrop-blur-md">
                                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V4a2 2 0 10-4 0v1.3A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                                    </svg>
                                    Notificaciones
                                </h3>
                                @if ($unreadNotifications->count() > 0)
                                    <button
                                        x-on:click.prevent="
            fetch('{{ route('notifications.markAllAsRead') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(() => {
                // Dejamos el dropdown sin notificaciones
                totalNotifications = 0;
                // Quitamos el numerito de la campana
                badgeCount = 0;
            })
            .catch(e => console.error('Error marcando todas como leídas:', e));
        "
                                        class="text-xs font-medium text-blue-400 hover:text-blue-300 hover:underline transition-colors">
                                        Marcar leídas
                                    </button>
                                @endif


                            </div>

                            <!-- Body with Logic -->
                            <div class="max-h-[28rem] overflow-y-auto custom-scrollbar" x-show="totalNotifications > 0">

                                {{-- 1. SOLICITUDES DE UNIÓN A EQUIPOS --}}
                                @foreach ($unreadNotifications as $notification)
                                    @if (($notification->data['type'] ?? null) === 'join_request')
                                        <div
                                            class="px-5 py-4 hover:bg-white/5 border-b border-white/5 transition-colors group">
                                            <div class="flex items-start gap-4">
                                                <div
                                                    class="p-2.5 bg-indigo-500/20 rounded-xl group-hover:bg-indigo-500/30 transition-colors">
                                                    <svg class="w-5 h-5 text-indigo-400" viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 19a4 4 0 014-4h8a4 4 0 014 4"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-semibold text-gray-200 leading-snug">
                                                        {{ $notification->data['message'] ?? ($notification->data['user_name'] ?? 'Un usuario') . ' quiere unirse al equipo "' . ($notification->data['team_name'] ?? 'Sin nombre') . '"' }}
                                                    </p>
                                                    <p class="text-xs text-indigo-400/80 mt-1 font-medium">Equipo:
                                                        {{ $notification->data['team_name'] ?? 'Sin nombre' }}</p>
                                                    <p class="text-[10px] text-gray-500 mt-2">
                                                        {{ $notification->created_at->diffForHumans() }}</p>
                                                    <div class="flex gap-2 mt-3">
                                                        <form method="POST"
                                                            action="{{ route('teams.accept', ['team' => $notification->data['team_id'], 'user' => $notification->data['user_id']]) }}">
                                                            @csrf <input type="hidden" name="notification"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit"
                                                                class="px-4 py-1.5 text-xs font-bold rounded-lg bg-green-500/20 text-green-400 border border-green-500/20 hover:bg-green-500/30 transition-colors">Aceptar</button>
                                                        </form>
                                                        <form method="POST"
                                                            action="{{ route('teams.reject', ['team' => $notification->data['team_id'], 'user' => $notification->data['user_id']]) }}">
                                                            @csrf <input type="hidden" name="notification"
                                                                value="{{ $notification->id }}">
                                                            <button type="submit"
                                                                class="px-4 py-1.5 text-xs font-bold rounded-lg bg-red-500/20 text-red-400 border border-red-500/20 hover:bg-red-500/30 transition-colors">Rechazar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                {{-- 2. INVITACIONES A EQUIPO --}}
                                @foreach ($unreadNotifications as $notification)
                                    @if (($notification->data['type'] ?? null) === 'team_invitation')
                                        <div class="px-5 py-4 hover:bg-white/5 border-b border-white/5 transition-colors">
                                            <div class="flex items-start gap-4">
                                                <div class="p-2.5 bg-blue-500/20 rounded-xl">
                                                    <svg class="w-5 h-5 text-blue-400" fill="none"
                                                        viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM4 19a4 4 0 014-4h8a4 4 0 014 4" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-semibold text-gray-200">
                                                        {{ $notification->data['message'] ?? 'Has sido invitado a un equipo' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">Equipo: <span
                                                            class="text-gray-300 font-medium">{{ $notification->data['team_name'] ?? 'Sin nombre' }}</span>
                                                    </p>
                                                    <div class="flex gap-2 mt-3">
                                                        <form action="{{ $notification->data['accept_url'] ?? '#' }}"
                                                            method="POST">
                                                            @csrf <button type="submit"
                                                                class="px-4 py-1.5 text-xs font-bold bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500/30 border border-green-500/20 transition">Aceptar</button>
                                                        </form>
                                                        <form action="{{ $notification->data['reject_url'] ?? '#' }}"
                                                            method="POST">
                                                            @csrf <button type="submit"
                                                                class="px-4 py-1.5 text-xs font-bold bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 border border-red-500/20 transition">Rechazar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                {{-- 3. RESPUESTAS Y ASESORÍAS (Simplificado para brevedad, usando estilos oscuros) --}}
                                @foreach ($unreadNotifications as $notification)
                                    @if (($notification->data['type'] ?? null) === 'team_join_response')
                                        @php $accepted = ($notification->data['status'] ?? 'accepted') === 'accepted'; @endphp
                                        <a href="{{ $notification->data['team_url'] ?? '#' }}"
                                            onclick="fetch('{{ route('notifications.markAsRead', $notification->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })"
                                            class="block px-5 py-4 hover:bg-white/5 border-b border-white/5 transition-colors">
                                            <div class="flex items-start gap-4">
                                                <div
                                                    class="p-2.5 rounded-xl {{ $accepted ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                                        <path
                                                            d="{{ $accepted ? 'M5 13l4 4L19 7' : 'M6 6l12 12M6 18L18 6' }}"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-semibold text-gray-200">
                                                        {{ $notification->data['message'] ?? 'Actualización sobre tu solicitud.' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ $notification->created_at->diffForHumans() }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach

                                @if ($pendingAdvisories->count() > 0)
                                    <div class="px-5 py-2 bg-indigo-500/10 border-b border-white/5">
                                        <p class="text-xs font-bold text-indigo-400 uppercase tracking-wider">Solicitudes
                                            de Asesoría</p>
                                    </div>
                                    {{-- Loop de asesorías similar a los anteriores --}}
                                    @foreach ($pendingAdvisories as $advisory)
                                        <div class="px-5 py-4 hover:bg-white/5 border-b border-white/5">
                                            <div class="flex text-gray-300 justify-between items-center">
                                                <span>{{ $advisory->name }}</span>
                                                <div class="flex gap-2">
                                                    <form
                                                        action="{{ route('teams.advisor.response', ['team' => $advisory, 'status' => 'accepted']) }}"
                                                        method="POST">@csrf @method('PATCH')<button
                                                            class="text-green-400 hover:text-green-300 font-bold text-xs">✓</button>
                                                    </form>
                                                    <form
                                                        action="{{ route('teams.advisor.response', ['team' => $advisory, 'status' => 'rejected']) }}"
                                                        method="POST">@csrf @method('PATCH')<button
                                                            class="text-red-400 hover:text-red-300 font-bold text-xs">✕</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @if ($pendingEvaluations->count() > 0)
                                    <div class="px-5 py-2 bg-amber-500/10 border-b border-white/5">
                                        <p class="text-xs font-bold text-amber-500 uppercase tracking-wider">Por Evaluar
                                        </p>
                                    </div>
                                    @foreach ($pendingEvaluations as $project)
                                        <div
                                            class="px-5 py-3 hover:bg-white/5 border-b border-white/5 flex justify-between items-center">
                                            <div class="text-sm text-gray-300">
                                                <p class="font-bold">{{ $project->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $project->team->name }}</p>
                                            </div>
                                            <a href="{{ route('projects.show', $project) }}"
                                                class="px-3 py-1 bg-amber-500/20 text-amber-400 text-xs rounded hover:bg-amber-500/30">Ver</a>
                                        </div>
                                    @endforeach
                                @endif

                                @foreach ($unreadNotifications as $notification)
                                    @php $type = $notification->data['type'] ?? null; @endphp
                                    @if ($type !== 'join_request' && $type !== 'team_invitation' && $type !== 'team_join_response')
                                        <a href="{{ route('public.event-winners', $notification->data['event_id'] ?? 0) }}"
                                            onclick="fetch('{{ route('notifications.markAsRead', $notification->id) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })"
                                            class="block px-5 py-4 hover:bg-white/5 border-b border-white/5 transition-colors">
                                            <div class="flex items-center gap-4">
                                                <div class="text-2xl animate-bounce">🏆</div>
                                                <div class="flex-1">
                                                    <p class="text-sm font-bold text-yellow-100">
                                                        {{ $notification->data['award_category'] ?? 'Premio' }}</p>
                                                    <p class="text-xs text-yellow-500/70">
                                                        {{ $notification->data['team_name'] ?? 'Tu equipo' }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach

                                <div class="px-4 py-12 text-center" x-show="totalNotifications === 0">
                                    <div class="inline-flex p-4 bg-white/5 rounded-full mb-4">
                                        <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-width="2"
                                                d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4-5.7V4a2 2 0 10-4 0v1.3A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-400">Sin notificaciones nuevas</p>
                                    <p class="text-xs text-gray-600 mt-1">¡Estás al día!</p>
                                </div>

                            </div>
                        </div>
                    </div>
                @endauth

                <!-- User Dropdown (Visible on XL) -->
                <div class="hidden xl:flex xl:items-center xl:ms-6">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-300 bg-white/5 border border-white/10 hover:text-white hover:bg-white/10 hover:border-white/20 transition-all duration-200">
                                    <div class="max-w-[150px] truncate">{{ Auth::user()->name }}</div>
                                    <svg class="ms-2 h-4 w-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="text-red-400 hover:text-red-300 hover:bg-red-500/10">
                                        Cerrar Sesión
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <!-- (Login/Register Buttons preserved with glass style) -->
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}"
                                class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Iniciar
                                Sesión</a>
                            <a href="{{ route('register') }}"
                                class="text-sm font-bold px-5 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white rounded-lg shadow-lg shadow-cyan-500/20 transition-all hover:-translate-y-0.5">Registrarse</a>
                        </div>
                    @endauth
                </div>

                <!-- HAMBURGER BUTTON (Visible on screens smaller than XL) -->
                <div class="-me-2 flex items-center xl:hidden">
                    <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 focus:outline-none transition duration-200">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path class="inline-flex" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- OFF-CANVAS SIDEBAR (MOBILE MENU)           -->
    <!-- ============================================== -->
    <template x-teleport="body">
        <div x-show="open" class="relative z-50">
            <!-- Backdrop -->
            <div x-show="open" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-[#000000]/80 backdrop-blur-sm" @click="open = false">
            </div>

            <!-- Sidebar Panel -->
            <div x-show="open" x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                class="fixed inset-y-0 left-0 w-[80%] max-w-sm bg-[#0B1120]/95 backdrop-blur-2xl border-r border-white/10 shadow-2xl overflow-y-auto elegant-scrollbar">

                <!-- Sidebar Header -->
                <div
                    class="flex items-center justify-between px-6 h-20 bg-gradient-to-r from-blue-900/20 to-purple-900/20 border-b border-white/10">
                    <span
                        class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">NEXUS</span>
                    <button @click="open = false"
                        class="text-gray-400 hover:text-white p-2 hover:bg-white/10 rounded-lg transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- User Info Mobile -->
                @auth
                    <div class="px-6 py-6 border-b border-white/5 bg-white/[0.02]">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-base text-gray-100 truncate">{{ Auth::user()->name }}</div>
                                <div class="font-medium text-sm text-gray-500 truncate">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                    </div>
                @endauth

                <!-- Mobile Navigation Links -->
                <div class="py-6 px-4 space-y-2">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl">
                        {{ __('Inicio') }}
                    </x-responsive-nav-link>

                    <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')" class="rounded-xl">
                        {{ __('Eventos') }}
                    </x-responsive-nav-link>

                    @can('teams.edit')
                        <x-responsive-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')" class="rounded-xl">
                            {{ __('Equipos') }}
                        </x-responsive-nav-link>
                    @endcan

                    @can('projects.edit')
                        <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')" class="rounded-xl">
                            {{ __('Proyectos') }}
                        </x-responsive-nav-link>
                    @endcan

                    @can('criteria.view')
                        <x-responsive-nav-link :href="route('criteria.index')" :active="request()->routeIs('criteria.*')" class="rounded-xl">
                            {{ __('Criterios') }}
                        </x-responsive-nav-link>
                    @endcan

                    @if (auth()->user()?->can('students.view') || auth()->user()?->can('staff.view') || auth()->user()?->can('judges.view'))
                        <div class="py-4 mt-4 mb-2">
                            <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Directorios</p>
                        </div>

                        @can('students.view')
                            <x-responsive-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')" class="rounded-xl pl-8 text-sm">
                                {{ __('Alumnos') }}
                            </x-responsive-nav-link>
                        @endcan
                        @can('staff.view')
                            <x-responsive-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.*')" class="rounded-xl pl-8 text-sm">
                                {{ __('Docentes') }}
                            </x-responsive-nav-link>
                        @endcan
                        @can('judges.view')
                            <x-responsive-nav-link :href="route('judges.index')" :active="request()->routeIs('judges.*')" class="rounded-xl pl-8 text-sm">
                                {{ __('Jueces') }}
                            </x-responsive-nav-link>
                        @endcan
                    @endif

                    @role('admin')
                        <div class="py-4 mt-4 mb-2 bg-purple-900/10 -mx-4 px-8 border-y border-purple-500/10">
                            <p class="text-xs font-bold text-purple-400 uppercase tracking-widest">Administración</p>
                        </div>
                        <x-responsive-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')" class="rounded-xl">
                            Historial
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('careers.index')" :active="request()->routeIs('careers.*')" class="rounded-xl text-sm">
                            🎓 Carreras
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('specialties.index')" :active="request()->routeIs('specialties.*')" class="rounded-xl text-sm">
                            ⚖️ Especialidades
                        </x-responsive-nav-link>
                        <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')" class="rounded-xl text-sm">
                            📊 Reportes
                        </x-responsive-nav-link>
                    @endrole
                </div>

                <!-- Mobile Logout -->
                <div class="p-4 border-t border-white/10 bg-black/20">
                    <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl mb-2">
                        {{ __('Perfil Config') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl">
                            {{ __('Cerrar Sesión') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </template>
</nav>
