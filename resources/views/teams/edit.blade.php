<x-app-layout>
    <!-- Fondo animado (Igual que Dashboard) -->
    <div class="fixed inset-0 bg-gradient-to-br from-[#0a1128] via-[#0d1b2a] to-[#1b263b] -z-10">
        <!-- Grid de circuitos -->
        <div class="absolute inset-0 opacity-40">
            <div class="absolute inset-0" style="
                background-image: 
                    linear-gradient(rgba(6, 182, 212, 0.1) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(6, 182, 212, 0.1) 1px, transparent 1px);
                background-size: 80px 80px;
                animation: circuit-flow-app 8s linear infinite;
            "></div>
        </div>

        <!-- Líneas de circuito flotantes -->
        <div class="circuit-lines-app"></div>

        <!-- Partículas de luz -->
        <div class="light-particles-app"></div>

        <!-- Blob de fondo para ambiente extra -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl animate-pulse animation-delay-4000"></div>
    </div>

    <div class="relative z-10 py-12 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Breadcrumb Moderno --}}
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 bg-white/5 backdrop-blur-md rounded-lg px-4 py-2 border border-white/10">
                    <li class="inline-flex items-center">
                        <a href="{{ route('teams.index') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-white transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                            Equipos
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-sm font-medium text-purple-400 md:ml-2">Editar Equipo</span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Header con Glassmorphism --}}
            <div class="glass-card rounded-3xl p-8 mb-8 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400 tracking-tight mb-2">
                        Editar Equipo
                    </h1>
                    <p class="text-lg text-gray-400">
                        Actualizando información de <span class="text-white font-bold">{{ $team->name }}</span>
                    </p>
                </div>
            </div>

            {{-- Formulario Glass Card --}}
            <div class="glass-card rounded-3xl overflow-hidden border border-white/10 shadow-2xl relative">
                <!-- Barra superior decorativa -->
                <div class="h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
                
                <div class="p-8 border-b border-white/5 bg-white/[0.02]">
                    <h3 class="text-xl font-bold text-white flex items-center gap-3">
                        <div class="p-2 bg-blue-500/20 rounded-lg text-blue-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </div>
                        Detalles del Equipo
                    </h3>
                </div>

                <form action="{{ route('teams.update', $team) }}" method="POST" class="p-8 space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Nombre del Equipo --}}
                        <div class="space-y-2 group">
                            <label for="name" class="block text-sm font-bold text-gray-400 group-hover:text-blue-400 transition-colors uppercase tracking-wider">
                                Nombre del Equipo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $team->name) }}"
                                   class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all hover:bg-black/30 text-lg"
                                   placeholder="Ej: Los Innovadores">
                            @error('name')
                                <p class="text-red-400 text-xs font-bold flex items-center gap-1 mt-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Evento --}}
                        <div class="space-y-2 group">
                            <label for="event_id" class="block text-sm font-bold text-gray-400 group-hover:text-purple-400 transition-colors uppercase tracking-wider">
                                Evento Asignado <span class="text-red-500">*</span>
                            </label>
                            
                            @if(isset($isLeader) && $isLeader && !auth()->user()->can('teams.edit'))
                                {{-- Líder NO puede cambiar evento (Read Only Moderno) --}}
                                <input type="hidden" name="event_id" value="{{ $team->event_id }}">
                                <div class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-300 flex justify-between items-center cursor-not-allowed">
                                    <span>{{ $team->event->name }}</span>
                                    <svg class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                </div>
                                <p class="text-xs text-gray-600 italic">No puedes cambiar el evento una vez registrado.</p>
                            @else
                                <div class="relative">
                                    <select name="event_id" id="event_id" 
                                            class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all hover:bg-black/30 appearance-none">
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" class="bg-gray-900 text-white" {{ old('event_id', $team->event_id) == $event->id ? 'selected' : '' }}>
                                                {{ $event->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                </div>
                                @error('event_id')
                                    <p class="text-red-400 text-xs font-bold mt-1">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>
                    </div>

                    {{-- Líder del Equipo --}}
                    <div class="space-y-2 group">
                        <label for="leader_id" class="block text-sm font-bold text-gray-400 group-hover:text-yellow-400 transition-colors uppercase tracking-wider">
                            Líder del Equipo <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="leader_id" id="leader_id" 
                                    class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all hover:bg-black/30 appearance-none">
                                @foreach($team->members as $member)
                                    <option value="{{ $member->id }}" class="bg-gray-900 text-white" {{ old('leader_id', $team->leader_id) == $member->id ? 'selected' : '' }}>
                                        ★ {{ $member->name }} - {{ $member->pivot->role ?? 'Miembro' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                        <p class="text-gray-500 text-xs">El líder tiene permisos de administración sobre el equipo.</p>
                        @error('leader_id')
                            <p class="text-red-400 text-xs font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Miembros del Equipo (solo lectura) --}}
                    <div class="pt-8 border-t border-white/10">
                        <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center justify-between">
                            <span>Miembros Actuales</span>
                            <span class="bg-white/10 text-white px-2 py-1 rounded text-xs">{{ $team->members->count() }}/5</span>
                        </h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($team->members as $member)
                                <div class="flex items-center gap-4 bg-white/[0.03] border border-white/5 rounded-xl p-4 hover:bg-white/[0.05] transition-colors group">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-lg shadow-lg {{ $member->id === $team->leader_id ? 'bg-gradient-to-br from-yellow-400 to-orange-500 text-white' : 'bg-gradient-to-br from-gray-700 to-gray-600 text-gray-300' }}">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white text-base font-bold truncate flex items-center gap-2 group-hover:text-blue-300 transition-colors">
                                            {{ $member->name }}
                                            @if($member->id === $team->leader_id)
                                                <span class="text-[10px] bg-yellow-500/20 text-yellow-300 px-2 py-0.5 rounded border border-yellow-500/30">LÍDER</span>
                                            @endif
                                        </p>
                                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                            <span>{{ $member->pivot->role ?? 'Miembro' }}</span>
                                            <span class="text-gray-700">•</span>
                                            <span class="{{ $member->pivot->is_accepted ? 'text-green-400' : 'text-yellow-400' }} font-bold">
                                                {{ $member->pivot->is_accepted ? 'ACTIVO' : 'PENDIENTE' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-4 pt-8 border-t border-white/10">
                        <a href="{{ route('teams.show', $team) }}" 
                           class="px-6 py-3 bg-white/5 hover:bg-white/10 text-gray-300 hover:text-white font-bold rounded-xl transition border border-white/10">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 hover:shadow-blue-600/40 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <span>Guardar Cambios</span>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Info adicional --}}
            <div class="mt-8 glass-card rounded-2xl p-6 border border-blue-500/20 bg-blue-500/5">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-blue-500/20 rounded-xl text-blue-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-300 text-lg mb-1">Información Importante</h4>
                        <p class="text-blue-200/70 text-sm leading-relaxed">
                            Para mantener la integridad de la competencia, la gestión de miembros se realiza de forma individual. 
                            Los estudiantes deben solicitar unirse o salir del equipo desde sus propios perfiles.
                            Como administrador/líder, solo puedes modificar los datos principales del equipo.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
