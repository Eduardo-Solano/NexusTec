<x-app-layout>
    <div class="py-12 bg-[#0B1120] min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8">
                <a href="{{ route('criteria.index') }}" class="inline-flex items-center text-gray-400 hover:text-white transition mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a criterios
                </a>
                <h2 class="text-3xl font-bold text-white">Editar Criterio</h2>
                <p class="text-gray-400 text-sm mt-1">Modifica la información del criterio de evaluación</p>
            </div>

            {{-- Formulario --}}
            <div class="bg-white/[0.02] backdrop-blur-xl border border-white/20 rounded-2xl overflow-hidden shadow-xl hover:border-white/30 transition-colors">
                <div class="px-6 py-5 border-b border-gray-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-ito-orange to-orange-600 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            {{ strtoupper(substr($criterion->name, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-white">{{ $criterion->name }}</h3>
                            <p class="text-xs text-gray-500">ID: #{{ str_pad($criterion->id, 3, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Eventos asignados</p>
                        <p class="text-lg font-bold text-white">{{ $criterion->events->count() }}</p>
                    </div>
                </div>

                <form action="{{ route('criteria.update', $criterion) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Nombre del Criterio <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $criterion->name) }}"
                            class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-ito-orange focus:ring focus:ring-ito-orange/20 transition @error('name') border-red-500 @enderror"
                            placeholder="Ej: Innovación, Presentación, Documentación...">
                        @error('name')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Puntos máximos --}}
                    <div>
                        <label for="max_points" class="block text-sm font-medium text-gray-300 mb-2">
                            Puntos Máximos <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="max_points" id="max_points" value="{{ old('max_points', $criterion->max_points) }}" min="1" max="100"
                                class="w-full bg-gray-900 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-ito-orange focus:ring focus:ring-ito-orange/20 transition @error('max_points') border-red-500 @enderror"
                                placeholder="10">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">pts</span>
                            </div>
                        </div>
                        <p class="text-gray-500 text-xs mt-2">Define la puntuación máxima que se puede otorgar en este criterio (1-100)</p>
                        @error('max_points')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Advertencia si tiene evaluaciones --}}
                    @if($criterion->evaluations()->exists())
                        <div class="bg-yellow-900/20 border border-yellow-500/30 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-yellow-500/10 rounded-lg">
                                    <svg class="w-5 h-5 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-yellow-400 font-semibold text-sm">Este criterio tiene evaluaciones</h4>
                                    <p class="text-yellow-300/70 text-xs mt-1">
                                        Modificar los puntos máximos puede afectar las evaluaciones existentes. 
                                        Las puntuaciones ya registradas no se modificarán automáticamente.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Información de uso --}}
                    <div class="bg-gray-900/50 border border-gray-700 rounded-xl p-4">
                        <h4 class="text-gray-300 font-semibold text-sm mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Estadísticas de uso
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/[0.02] backdrop-blur-xl border border-white/10 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Eventos que lo usan</p>
                                <p class="text-xl font-bold text-white">{{ $criterion->events->count() }}</p>
                            </div>
                            <div class="bg-white/[0.02] backdrop-blur-xl border border-white/10 rounded-lg p-3">
                                <p class="text-xs text-gray-500">Evaluaciones realizadas</p>
                                <p class="text-xl font-bold text-white">{{ $criterion->evaluations->count() }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-700">
                        <a href="{{ route('criteria.index') }}" 
                            class="px-5 py-2.5 text-gray-400 hover:text-white font-medium rounded-lg transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                            class="bg-ito-orange hover:bg-orange-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>

            {{-- Zona de peligro --}}
            <div class="mt-8 bg-red-900/10 border border-red-500/30 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-red-500/30 bg-red-900/20">
                    <h3 class="font-bold text-red-400 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Zona de peligro
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-white font-semibold">Eliminar este criterio</h4>
                            <p class="text-gray-500 text-sm">Una vez eliminado, no podrás recuperar este criterio ni sus datos asociados.</p>
                        </div>
                        <form action="{{ route('criteria.destroy', $criterion) }}" method="POST" 
                            onsubmit="return confirm('¿Estás seguro de eliminar este criterio? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                class="bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/30 hover:border-red-500 font-bold py-2 px-4 rounded-lg transition"
                                {{ $criterion->evaluations()->exists() ? 'disabled' : '' }}>
                                Eliminar Criterio
                            </button>
                        </form>
                    </div>
                    @if($criterion->evaluations()->exists())
                        <p class="text-yellow-400 text-xs mt-3 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            No puedes eliminar este criterio porque tiene evaluaciones asociadas.
                        </p>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
