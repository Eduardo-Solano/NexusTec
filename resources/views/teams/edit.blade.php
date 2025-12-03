<x-app-layout>
    <div class="min-h-screen bg-[#0B1120] py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Breadcrumb --}}
            <div class="mb-8">
                <a href="{{ route('teams.index') }}" class="group inline-flex items-center text-sm font-medium text-gray-400 hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Volver a Equipos
                </a>
            </div>

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white tracking-tight">Editar Equipo</h1>
                <p class="text-gray-400 text-sm mt-1">Modifica la información del equipo "{{ $team->name }}"</p>
            </div>

            {{-- Formulario --}}
            <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Información del Equipo
                    </h3>
                </div>

                <form action="{{ route('teams.update', $team) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nombre del Equipo --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                Nombre del Equipo <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $team->name) }}"
                                   class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                   placeholder="Ej: Los Innovadores">
                            @error('name')
                                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Evento --}}
                        <div>
                            <label for="event_id" class="block text-sm font-medium text-gray-300 mb-2">
                                Evento <span class="text-red-400">*</span>
                            </label>
                            @if(isset($isLeader) && $isLeader && !auth()->user()->can('teams.edit'))
                                {{-- Líder NO puede cambiar evento --}}
                                <input type="hidden" name="event_id" value="{{ $team->event_id }}">
                                <div class="w-full bg-gray-900/50 border border-gray-600 rounded-lg px-4 py-3 text-gray-400">
                                    {{ $team->event->name }}
                                    <span class="text-xs text-gray-500 ml-2">(No modificable)</span>
                                </div>
                            @else
                                <select name="event_id" id="event_id" 
                                        class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" {{ old('event_id', $team->event_id) == $event->id ? 'selected' : '' }}>
                                            {{ $event->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            @error('event_id')
                                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Líder del Equipo --}}
                    <div>
                        <label for="leader_id" class="block text-sm font-medium text-gray-300 mb-2">
                            Líder del Equipo <span class="text-red-400">*</span>
                        </label>
                        <select name="leader_id" id="leader_id" 
                                class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            @foreach($team->members as $member)
                                <option value="{{ $member->id }}" {{ old('leader_id', $team->leader_id) == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }} - {{ $member->pivot->role ?? 'Miembro' }}
                                    @if($member->id === $team->leader_id)
                                        (Actual)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('leader_id')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-2">Solo los miembros actuales del equipo pueden ser líder.</p>
                    </div>

                    {{-- Miembros del Equipo (solo lectura) --}}
                    <div class="pt-4 border-t border-gray-700">
                        <h4 class="text-sm font-medium text-gray-300 mb-4">Miembros Actuales ({{ $team->members->count() }}/5)</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($team->members as $member)
                                <div class="flex items-center gap-3 bg-gray-900/50 border border-gray-700 rounded-lg p-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $member->id === $team->leader_id ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-300' }}">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white text-sm font-medium truncate flex items-center gap-2">
                                            {{ $member->name }}
                                            @if($member->id === $team->leader_id)
                                                <span class="text-[10px] bg-blue-500/20 text-blue-300 px-2 py-0.5 rounded">LÍDER</span>
                                            @endif
                                        </p>
                                        <p class="text-gray-500 text-xs truncate">{{ $member->pivot->role ?? 'Miembro' }}</p>
                                    </div>
                                    <span class="text-xs {{ $member->pivot->is_accepted ? 'text-green-400' : 'text-yellow-400' }}">
                                        {{ $member->pivot->is_accepted ? '● Activo' : '● Pendiente' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-700">
                        <a href="{{ route('teams.show', $team) }}" 
                           class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white text-sm font-semibold rounded-lg transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            {{-- Info adicional --}}
            <div class="mt-6 bg-gray-800/50 border border-gray-700 rounded-xl p-4">
                <div class="flex gap-3">
                    <div class="text-blue-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="text-sm text-gray-400">
                        <p class="font-medium text-gray-300 mb-1">Nota:</p>
                        <p>Para agregar o quitar miembros del equipo, los estudiantes deben hacerlo desde su cuenta. Solo puedes cambiar el nombre, evento asignado y el líder del equipo.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
