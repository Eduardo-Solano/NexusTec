<nav x-data="{ open: false, notificationsOpen: false }"
    class="sticky top-0 z-40 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                        {{ __('Eventos') }}
                    </x-nav-link>
                </div>
                @can('teams.view')
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('teams.index')" :active="request()->routeIs('teams.*')">
                            {{ __('Equipos') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('criteria.view')
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('criteria.index')" :active="request()->routeIs('criteria.*')">
                            {{ __('Criterios') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('staff.view')
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('staff.index')" :active="request()->routeIs('staff.*')">
                            {{ __('Docentes') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('students.view')
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('students.index')" :active="request()->routeIs('students.*')">
                            {{ __('Alumnos') }}
                        </x-nav-link>
                    </div>
                @endcan
                @can('judges.view')
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('judges.index')" :active="request()->routeIs('judges.*')">
                            {{ __('Jueces') }}
                        </x-nav-link>
                    </div>
                @endcan
                @role('admin')
                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                        <x-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')">
                            {{ __('Actividad') }}
                        </x-nav-link>
                    </div>
                    
                    {{-- Admin Dropdown Menu --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    <span>Administrar</span>
                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('careers.index')">
                                    🎓 {{ __('Carreras') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('specialties.index')">
                                    ⚖️ {{ __('Especialidades') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('reports.index')">
                                    📊 {{ __('Reportes') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endrole
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notification Bell -->
                <div class="relative">
                    @php
                        $totalNotifications =
                            ($pendingMembers->count() ?? 0) +
                            ($pendingAdvisories->count() ?? 0) +
                            ($pendingEvaluations->count() ?? 0) +
                            ($unreadNotifications->count() ?? 0);
                    @endphp
                    <button @click="notificationsOpen = !notificationsOpen"
                        class="relative text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-100 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @if ($totalNotifications > 0)
                            <span
                                class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white ring-2 ring-white dark:ring-gray-800">
                                {{ $totalNotifications > 9 ? '9+' : $totalNotifications }}
                            </span>
                        @endif
                    </button>

                    <!-- Notifications Dropdown -->
                    <div x-show="notificationsOpen" @click.away="notificationsOpen = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden z-50 border border-gray-200 dark:border-gray-700">

                        {{-- Header --}}
                        <div
                            class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold text-gray-800 dark:text-white">Notificaciones</h3>
                                <div class="flex items-center gap-2">
                                    @if ($unreadNotifications->count() > 0)
                                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST"
                                            class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                Marcar leídas
                                            </button>
                                        </form>
                                    @endif
                                    @if ($totalNotifications > 0)
                                        <span
                                            class="px-2 py-0.5 text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full">
                                            {{ $totalNotifications }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="max-h-80 overflow-y-auto">
                            {{-- SOLICITUDES DE UNIÓN A EQUIPO --}}
                            @foreach ($unreadNotifications as $notification)
                                @if (isset($notification->data['type']) && $notification->data['type'] === 'join_request')
                                    <div
                                        class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition">
                                        <div class="flex items-start gap-3">

                                            {{-- Icono --}}
                                            <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                                                <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>

                                            {{-- Contenido --}}
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                                    {{ $notification->data['message'] }}
                                                </p>

                                                <p class="text-[10px] text-gray-500 dark:text-gray-400">
                                                    Rol solicitado: {{ $notification->data['role'] ?? 'Miembro' }}
                                                </p>

                                                <div class="flex gap-2 mt-3">

                                                    {{-- Aceptar --}}
                                                    <form action="{{ $notification->data['accept_url'] }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="px-3 py-1.5 text-xs font-bold bg-green-600 hover:bg-green-500 text-white rounded-lg transition">
                                                            Aceptar
                                                        </button>
                                                    </form>

                                                    {{-- Rechazar --}}
                                                    <form action="{{ $notification->data['reject_url'] }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit"
                                                            class="px-3 py-1.5 text-xs font-bold bg-red-600 hover:bg-red-500 text-white rounded-lg transition">
                                                            Rechazar
                                                        </button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php continue; @endphp
                                @endif
                            @endforeach

                            @if ($totalNotifications > 0)
                                {{-- Solicitudes de Asesoría Pendientes --}}
                                @if ($pendingAdvisories->count() > 0)
                                    <div
                                        class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 border-b border-indigo-100 dark:border-indigo-800">
                                        <p
                                            class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            Solicitudes de Asesoría
                                        </p>
                                    </div>
                                    @foreach ($pendingAdvisories as $advisory)
                                        <div
                                            class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition">
                                            <div class="flex items-start gap-3">
                                                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                                    <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                                                        {{ $advisory->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                        {{ $advisory->event->name ?? 'Sin evento' }}</p>
                                                    <div class="flex gap-2 mt-2">
                                                        <form
                                                            action="{{ route('teams.advisor.response', ['team' => $advisory, 'status' => 'accepted']) }}"
                                                            method="POST">
                                                            @csrf @method('PATCH')
                                                            <button type="submit"
                                                                class="px-2.5 py-1 text-xs font-bold bg-green-600 hover:bg-green-500 text-white rounded-lg transition">
                                                                Aceptar
                                                            </button>
                                                        </form>
                                                        <form
                                                            action="{{ route('teams.advisor.response', ['team' => $advisory, 'status' => 'rejected']) }}"
                                                            method="POST">
                                                            @csrf @method('PATCH')
                                                            <button type="submit"
                                                                class="px-2.5 py-1 text-xs font-bold bg-red-600 hover:bg-red-500 text-white rounded-lg transition">
                                                                Rechazar
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                {{-- Miembros Pendientes de Aceptación --}}
                                @if ($pendingMembers->count() > 0)
                                    <div
                                        class="px-4 py-2 bg-orange-50 dark:bg-orange-900/20 border-b border-orange-100 dark:border-orange-800">
                                        <p
                                            class="text-xs font-bold text-orange-600 dark:text-orange-400 uppercase tracking-wider flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                            </svg>
                                            Solicitudes de Unión
                                        </p>
                                    </div>
                                    @foreach ($pendingMembers as $pending)
                                        <a href="{{ route('teams.show', $pending->pivot->team_id) }}"
                                            class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                                                    <svg class="w-4 h-4 text-orange-600 dark:text-orange-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                                                        {{ $pending->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Quiere unirse •
                                                        {{ $pending->pivot->role ?? 'Miembro' }}</p>
                                                </div>
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif

                                {{-- Proyectos Asignados para Evaluar (Jueces) --}}
                                @if ($pendingEvaluations->count() > 0)
                                    <div
                                        class="px-4 py-2 bg-amber-50 dark:bg-amber-900/20 border-b border-amber-100 dark:border-amber-800">
                                        <p
                                            class="text-xs font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                            </svg>
                                            Proyectos por Evaluar
                                        </p>
                                    </div>
                                    @foreach ($pendingEvaluations as $project)
                                        <div
                                            class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition">
                                            <div class="flex items-center gap-3">
                                                <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p
                                                        class="text-sm font-semibold text-gray-800 dark:text-white truncate">
                                                        {{ $project->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                        {{ $project->team->name ?? 'Sin equipo' }}</p>
                                                </div>
                                                <a href="{{ route('projects.show', $project) }}"
                                                    class="px-3 py-1.5 text-xs font-bold bg-amber-600 hover:bg-amber-500 text-white rounded-lg text-center transition">
                                                    Ver
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                {{-- Notificaciones de Premios Ganados --}}
                                @if ($unreadNotifications->count() > 0)
                                    <div
                                        class="px-4 py-2 bg-yellow-50 dark:bg-yellow-900/20 border-b border-yellow-100 dark:border-yellow-800">
                                        <p
                                            class="text-xs font-bold text-yellow-600 dark:text-yellow-400 uppercase tracking-wider flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                            </svg>
                                            ¡Premios Ganados!
                                        </p>
                                    </div>
                                    @foreach ($unreadNotifications as $notification)
                                        {{-- Invitaciones a Equipos --}}
                                        @if (isset($notification->data['team_id']) && isset($notification->data['accept_url']))
                                            <div
                                                class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700 transition">
                                                <div class="flex items-start gap-3">

                                                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>

                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                                            {{ $notification->data['message'] }}
                                                        </p>

                                                        <div class="flex gap-2 mt-2">

                                                            <form action="{{ $notification->data['accept_url'] }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="notification"
                                                                    value="{{ $notification->id }}">

                                                                <button type="submit"
                                                                    class="px-3 py-1 text-xs font-bold bg-green-600 hover:bg-green-500 text-white rounded-lg transition">
                                                                    Aceptar
                                                                </button>
                                                            </form>


                                                            <form action="{{ $notification->data['reject_url'] }}"
                                                                method="POST">
                                                                @csrf
                                                                <input type="hidden" name="notification"
                                                                    value="{{ $notification->id }}">

                                                                <button type="submit"
                                                                    class="px-3 py-1 text-xs font-bold bg-red-600 hover:bg-red-500 text-white rounded-lg transition">
                                                                    Rechazar
                                                                </button>
                                                            </form>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- IMPORTANTE: No seguimos procesando esta notificación como premio --}}
                                            @continue
                                        @endif

                                        @php
                                            $medals = [
                                                '1er Lugar' => '🥇',
                                                '2do Lugar' => '🥈',
                                                '3er Lugar' => '🥉',
                                                'Mención Honorífica' => '🏅',
                                                'Mejor Innovación' => '💡',
                                                'Mejor Diseño' => '🎨',
                                                'Mejor Presentación' => '🎤',
                                                'Premio del Público' => '👥',
                                            ];
                                            $category = $notification->data['award_category'] ?? '';
                                            $medal = $medals[$category] ?? '🏆';
                                        @endphp
                                        <a href="{{ route('public.event-winners', $notification->data['event_id'] ?? 0) }}"
                                            onclick="fetch('{{ route('notifications.markAsRead', $notification->id) }}', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}})"
                                            class="block px-4 py-3 hover:bg-yellow-50 dark:hover:bg-yellow-900/10 border-b border-gray-100 dark:border-gray-700 transition bg-yellow-50/50 dark:bg-yellow-900/5">
                                            <div class="flex items-center gap-3">
                                                <div class="text-3xl">{{ $medal }}</div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-gray-800 dark:text-white">
                                                        {{ $notification->data['award_category'] ?? 'Premio' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                        {{ $notification->data['team_name'] ?? 'Tu equipo' }} •
                                                        {{ $notification->data['event_name'] ?? '' }}
                                                    </p>
                                                    <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-0.5">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                                <svg class="w-4 h-4 text-yellow-500" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            @else
                                <div class="px-4 py-8 text-center">
                                    <div class="inline-flex p-3 bg-gray-100 dark:bg-gray-700 rounded-full mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">No hay notificaciones</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Estás al día 🎉</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
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
                <x-responsive-nav-link :href="route('activity-logs.index')" :active="request()->routeIs('activity-logs.*')">
                    {{ __('Historial de Actividad') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('careers.index')" :active="request()->routeIs('careers.*')">
                    🎓 {{ __('Carreras') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('specialties.index')" :active="request()->routeIs('specialties.*')">
                    ⚖️ {{ __('Especialidades') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                    📊 {{ __('Reportes') }}
                </x-responsive-nav-link>
            @endrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
