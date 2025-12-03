<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar Premio
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
                
                <form action="{{ route('awards.update', $award) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Categoría del Premio --}}
                    <div>
                        <label for="category" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Categoría del Premio
                        </label>
                        <select name="category" id="category" required
                                class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                onchange="toggleCustomName(this.value)">
                            <option value="">-- Selecciona una categoría --</option>
                            @foreach($categories as $value => $label)
                                <option value="{{ $value }}" {{ old('category', $award->category) === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nombre personalizado (solo si categoría es "Otro") --}}
                    <div id="custom-name-wrapper" class="{{ $award->category === 'Otro' ? '' : 'hidden' }}">
                        <label for="name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Nombre del Premio
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $award->name) }}"
                               placeholder="Ej: Mejor Prototipo Funcional"
                               class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Equipo Ganador --}}
                    <div>
                        <label for="team_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                            Equipo Ganador
                        </label>
                        <select name="team_id" id="team_id" required
                                class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="">-- Selecciona el equipo ganador --</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ old('team_id', $award->team_id) == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }} - {{ $team->project->name ?? 'Sin proyecto' }}
                                    ({{ $team->leader->name ?? 'Sin líder' }})
                                </option>
                            @endforeach
                        </select>
                        @error('team_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info actual --}}
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-lg text-sm text-gray-600 dark:text-gray-400">
                        <p><strong>Otorgado:</strong> {{ $award->awarded_at->format('d/m/Y') }}</p>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('awards.index', ['event_id' => $event->id]) }}" 
                           class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg font-bold transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function toggleCustomName(category) {
            const wrapper = document.getElementById('custom-name-wrapper');
            wrapper.classList.toggle('hidden', category !== 'Otro');
        }
    </script>
</x-app-layout>
