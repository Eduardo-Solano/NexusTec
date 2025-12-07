<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Asignar Premio
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $event->name }}
                </p>
            </div>
            <a href="{{ route('awards.index', ['event_id' => $event->id]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                
                <form action="{{ route('awards.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                    {{-- Posición del Premio --}}
                    <div>
                        <label for="position" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Posición del Premio
                        </label>
                        <select name="position" id="position" required
                                class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                onchange="updatePreview()">
                            <option value="">-- Selecciona la posición --</option>
                            @foreach($positions as $key => $label)
                                <option value="{{ $label }}" {{ old('position') === $label ? 'selected' : '' }}>
                                    {{ $key === 1 ? '🥇' : ($key === 2 ? '🥈' : '🥉') }} {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('position')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Equipo Ganador --}}
                    <div>
                        <label for="team_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Equipo Ganador
                        </label>
                        @if($teams->count() > 0)
                            <select name="team_id" id="team_id" required
                                    class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                    onchange="updatePreview()">
                                <option value="">-- Selecciona el equipo ganador --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                        {{ $team->name }} - {{ $team->project->name ?? 'Sin proyecto' }}
                                        ({{ $team->leader->name ?? 'Sin líder' }})
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-300 dark:border-amber-700 rounded-lg text-amber-700 dark:text-amber-400 text-sm">
                                No hay equipos con proyectos entregados en este evento.
                            </div>
                        @endif
                        @error('team_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Vista previa --}}
                    <div id="preview" class="hidden p-4 bg-gradient-to-r from-yellow-500/10 to-yellow-600/5 border border-yellow-500/30 rounded-xl">
                        <p class="text-xs text-yellow-600 dark:text-yellow-400 uppercase font-bold tracking-wider mb-2">Vista Previa</p>
                        <div class="flex items-center gap-3">
                            <span id="preview-emoji" class="text-4xl">🏆</span>
                            <div>
                                <p id="preview-position" class="font-bold text-gray-800 dark:text-white">-</p>
                                <p id="preview-team" class="text-sm text-gray-600 dark:text-gray-400">-</p>
                            </div>
                        </div>
                    </div>

                    {{-- Botón Guardar --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('awards.index', ['event_id' => $event->id]) }}" 
                           class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg font-bold transition flex items-center gap-2"
                                {{ $teams->count() === 0 ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                            Asignar Premio
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        const emojis = {
            '1er Lugar': '🥇',
            '2do Lugar': '🥈',
            '3er Lugar': '🥉'
        };

        function updatePreview() {
            const position = document.getElementById('position').value;
            const teamSelect = document.getElementById('team_id');
            const teamText = teamSelect?.options[teamSelect?.selectedIndex]?.text || '-';
            const preview = document.getElementById('preview');
            
            if (position) {
                preview.classList.remove('hidden');
                document.getElementById('preview-emoji').textContent = emojis[position] || '🏆';
                document.getElementById('preview-position').textContent = position;
                document.getElementById('preview-team').textContent = teamText !== '-- Selecciona el equipo ganador --' ? teamText : '-';
            } else {
                preview.classList.add('hidden');
            }
        }

        // Escuchar cambios en el select de equipo
        document.getElementById('team_id')?.addEventListener('change', updatePreview);
        
        // Inicializar preview si hay valores seleccionados
        document.addEventListener('DOMContentLoaded', updatePreview);
    </script>
</x-app-layout>
