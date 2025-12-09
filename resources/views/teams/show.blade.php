<x-app-layout>
    <div class="min-h-screen bg-[#0B1120] py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <a href="{{ route('dashboard') }}"
                    class="group inline-flex items-center text-sm font-medium text-gray-400 hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver al Dashboard
                </a>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">

                {{-- COLUMNA IZQUIERDA --}}
                <div class="lg:col-span-1 space-y-6">

                    <div
                        class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-xl text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-purple-600">
                        </div>

                        <div
                            class="w-24 h-24 mx-auto bg-gray-900 rounded-full flex items-center justify-center border-4 border-gray-700 mb-4 shadow-lg">
                            <span
                                class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-br from-blue-400 to-purple-500">
                                {{ substr($team->name, 0, 1) }}
                            </span>
                        </div>

                        <h1 class="text-2xl font-black text-white mb-1">{{ $team->name }}</h1>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-widest mb-6">
                            ID: TM-{{ str_pad($team->id, 4, '0', STR_PAD_LEFT) }}
                        </p>

                        <div class="bg-gray-900/50 rounded-xl p-4 border border-gray-700 text-left">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Evento</p>
                            @php
                                $eventDotColor = match ($team->event->status) {
                                    'registration' => 'bg-blue-500',
                                    'active' => 'bg-green-500',
                                    'closed' => 'bg-red-500',
                                    default => 'bg-gray-500',
                                };
                            @endphp
                            <p class="text-white font-bold text-sm flex items-center">
                                <span class="w-2 h-2 rounded-full {{ $eventDotColor }} mr-2"></span>
                                {{ $team->event->name }}
                            </p>
                        </div>
                    </div>

                    {{-- Sección del Asesor --}}
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Asesor Asignado</h3>

                        @if ($team->advisor)
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm bg-purple-600 text-white shadow-lg shadow-purple-500/30">
                                    {{ substr($team->advisor->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">{{ $team->advisor->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $team->advisor->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($team->advisor_status === 'accepted')
                                    <span
                                        class="inline-flex items-center gap-1 text-xs bg-green-500/20 text-green-400 px-3 py-1 rounded-full border border-green-500/30">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Aceptado
                                    </span>
                                @elseif($team->advisor_status === 'rejected')
                                    <span
                                        class="inline-flex items-center gap-1 text-xs bg-red-500/20 text-red-400 px-3 py-1 rounded-full border border-red-500/30">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Rechazado
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 text-xs bg-yellow-500/20 text-yellow-400 px-3 py-1 rounded-full border border-yellow-500/30">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Pendiente
                                    </span>
                                @endif
                            </div>

                            {{-- Botones para que el asesor responda --}}
                            @if (auth()->id() === $team->advisor_id && $team->advisor_status === 'pending')
                                <div class="mt-4 flex gap-2">
                                    <form action="{{ route('teams.advisor.response', [$team, 'accepted']) }}"
                                        method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold uppercase rounded-lg transition">
                                            Aceptar
                                        </button>
                                    </form>
                                    <form action="{{ route('teams.advisor.response', [$team, 'rejected']) }}"
                                        method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="w-full py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold uppercase rounded-lg transition">
                                            Rechazar
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-700/50 text-gray-400 rounded-lg border border-gray-600">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-gray-400 font-bold text-sm">Sin asesor</p>
                                    <p class="text-xs text-gray-500">No se ha asignado un asesor</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Estado del Proyecto
                        </h3>

                        @if ($team->project)
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-green-500/10 text-green-400 rounded-lg border border-green-500/20">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">Entregado</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $team->project->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('projects.show', $team->project) }}"
                                class="block w-full py-2 bg-gray-700 hover:bg-gray-600 text-white text-xs font-bold uppercase rounded-lg text-center transition">
                                Ver Proyecto
                            </a>
                        @else
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="p-2 bg-yellow-500/10 text-yellow-400 rounded-lg border border-yellow-500/20">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">Pendiente</p>
                                    <p class="text-xs text-gray-500">Aún no han subido su repositorio</p>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>

                {{-- COLUMNA DERECHA: Detalles del equipo --}}
                <div class="lg:col-span-2">
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden shadow-xl"
                        x-data="{ showInvite: {{ $errors->has('email') || session()->has('invite_check_success') ? 'true' : 'false' }} }">


                        {{-- Header --}}
                        <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                Detalles del Equipo
                            </h3>

                            @php
                                $maxMembers = $team->event->max_team_members ?? 5;
                                $currentMembers = $team->members->count();
                            @endphp

                            <div class="flex items-center gap-3">
                                <span
                                    class="bg-gray-900 text-gray-300 text-xs px-3 py-1 rounded-full border border-gray-600">
                                    {{ $currentMembers }} / {{ $maxMembers }}
                                </span>

                                {{-- Botón Invitar más miembros (solo líder, evento abierto y si aún hay espacio) --}}
                                @if ($team->event->isOpen() && auth()->id() === $team->leader_id && $currentMembers < $maxMembers)
                                    <button type="button" @click="showInvite = !showInvite"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Invitar más miembros
                                    </button>
                                @endif

                                @can('teams.edit')
                                    <a href="{{ route('teams.edit', $team) }}"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar
                                    </a>
                                @elseif(auth()->id() === $team->leader_id)
                                    <a href="{{ route('teams.edit', $team) }}"
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar mi Equipo
                                    </a>
                                @endcan
                            </div>
                        </div>

                        {{-- CUADRO: input de correo (solo UI, sin enviar aún) --}}
                        <div class="px-6 pt-0 pb-4 border-b border-gray-700" x-show="showInvite" x-transition>
                            <div class="mt-4">

                                {{-- Mensaje de éxito de la validación --}}
                                @if (session('invite_check_success'))
                                    <div class="mb-3 text-xs text-green-400">
                                        {{ session('invite_check_success') }}
                                    </div>
                                @endif

                                {{-- Mensaje de error específico del campo email --}}
                                @error('email')
                                    <div class="mb-2 text-xs text-red-400">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <form method="POST" action="{{ route('teams.invitations.check', $team) }}"
                                    class="space-y-3">
                                    @csrf

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">
                                            Correo electrónico del miembro a invitar
                                        </label>
                                        <input type="email" name="email" value="{{ old('email') }}" required
                                            class="w-full bg-gray-900 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-100
                           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                            placeholder="nombre.apellido@ejemplo.com">
                                        <p class="text-[11px] text-gray-500 mt-1">
                                            Debe ser un correo de un usuario ya registrado en el sistema.
                                        </p>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit"
                                            class="px-4 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                                            Enviar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>


                        {{-- LISTA DE MIEMBROS --}}
                        <div class="divide-y divide-gray-700">
                            @foreach ($team->members as $member)
                                <div class="p-4 flex items-center justify-between hover:bg-gray-700/30 transition">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $member->id === $team->leader_id ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-700 text-gray-300' }}">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>

                                        <div>
                                            <p class="text-white font-bold text-sm flex items-center gap-2">
                                                {{ $member->name }}
                                                @if ($member->id === $team->leader_id)
                                                    <span
                                                        class="text-[10px] bg-blue-500/20 text-blue-300 px-2 py-0.5 rounded border border-blue-500/30">
                                                        LÍDER
                                                    </span>
                                                @endif
                                            </p>
                                            <p class="text-gray-500 text-xs">{{ $member->email }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <div class="text-right">
                                            <p class="text-gray-300 text-xs font-bold uppercase tracking-wider mb-1">
                                                {{ $member->pivot->role ?? 'Miembro' }}
                                            </p>
                                            <span
                                                class="text-[10px] {{ $member->pivot->is_accepted ? 'text-green-400' : 'text-yellow-400' }}">
                                                {{ $member->pivot->is_accepted ? '● Activo' : '● Pendiente' }}
                                            </span>
                                        </div>

                                        {{-- Acciones de gestión de miembros --}}
                                        @if ($team->event->isOpen())
                                            <div class="flex items-center gap-1">
                                                {{-- Si soy el líder y este NO es el líder --}}
                                                @if (auth()->id() === $team->leader_id && $member->id !== $team->leader_id)
                                                    @if ($member->pivot->is_accepted)
                                                        {{-- Transferir liderazgo --}}
                                                        <form action="{{ route('teams.transfer', [$team, $member]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('¿Transferir el liderazgo a {{ $member->name }}?\n\nDejarás de ser el líder del equipo.')">
                                                            @csrf
                                                            <button type="submit"
                                                                class="p-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition"
                                                                title="Transferir liderazgo">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                                </svg>
                                                            </button>
                                                        </form>

                                                        {{-- Expulsar miembro --}}
                                                        <form action="{{ route('teams.kick', [$team, $member]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('¿Expulsar a {{ $member->name }} del equipo?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="p-1.5 bg-red-600 hover:bg-red-500 text-white rounded-lg transition"
                                                                title="Expulsar del equipo">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @else
                                                        {{-- Cancelar invitación pendiente --}}
                                                        <form
                                                            action="{{ route('teams.cancel-invitation', [$team, $member]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('¿Cancelar la invitación a {{ $member->name }}?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="p-1.5 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg transition"
                                                                title="Cancelar invitación">
                                                                <svg class="w-4 h-4" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif

                                                {{-- Si soy yo mismo y NO soy líder, puedo abandonar --}}
                                                @if (auth()->id() === $member->id && $member->id !== $team->leader_id && $member->pivot->is_accepted)
                                                    <form action="{{ route('teams.leave', $team) }}" method="POST"
                                                        onsubmit="return confirm('¿Estás seguro de abandonar el equipo {{ $team->name }}?\n\nEsta acción no se puede deshacer.')">
                                                        @csrf
                                                        <button type="submit"
                                                            class="p-1.5 bg-orange-600 hover:bg-orange-500 text-white rounded-lg transition"
                                                            title="Abandonar equipo">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Leyenda de acciones --}}
                        @if ($team->event->isOpen() && (auth()->id() === $team->leader_id || $team->members->contains('id', auth()->id())))
                            <div class="p-4 bg-gray-900/50 border-t border-gray-700">
                                <p class="text-xs text-gray-500 mb-2 font-bold uppercase">Acciones disponibles:</p>
                                <div class="flex flex-wrap gap-3 text-xs text-gray-400">
                                    @if (auth()->id() === $team->leader_id)
                                        <span class="flex items-center gap-1">
                                            <span class="w-5 h-5 bg-blue-600 rounded flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                            </span>
                                            Transferir liderazgo
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span class="w-5 h-5 bg-red-600 rounded flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6" />
                                                </svg>
                                            </span>
                                            Expulsar miembro
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <span
                                                class="w-5 h-5 bg-yellow-600 rounded flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </span>
                                            Cancelar invitación
                                        </span>
                                    @else
                                        <span class="flex items-center gap-1">
                                            <span
                                                class="w-5 h-5 bg-orange-600 rounded flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                            </span>
                                            Abandonar equipo
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
