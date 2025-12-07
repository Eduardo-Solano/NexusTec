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
                <h2 class="text-3xl font-bold text-white">Nuevo Criterio</h2>
                <p class="text-gray-400 text-sm mt-1">Define un nuevo criterio de evaluación para los proyectos</p>
            </div>

            {{-- Formulario --}}
            <div class="bg-white/[0.02] backdrop-blur-xl border border-white/20 rounded-2xl overflow-hidden shadow-xl hover:border-white/30 transition-colors">
                <div class="px-6 py-5 border-b border-gray-700 flex items-center gap-3">
                    <div class="p-2 bg-ito-orange/10 rounded-lg">
                        <svg class="w-5 h-5 text-ito-orange" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white">Información del Criterio</h3>
                        <p class="text-xs text-gray-500">Completa los campos requeridos</p>
                    </div>
                </div>

                <form action="{{ route('criteria.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    {{-- Nombre --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            Nombre del Criterio <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
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
                            <input type="number" name="max_points" id="max_points" value="{{ old('max_points', 10) }}" min="1" max="100"
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

                    {{-- Información adicional --}}
                    <div class="bg-blue-900/20 border border-blue-500/30 rounded-xl p-4">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-blue-500/10 rounded-lg">
                                <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-blue-400 font-semibold text-sm">¿Cómo funcionan los criterios?</h4>
                                <p class="text-blue-300/70 text-xs mt-1">
                                    Los criterios se asignan a eventos y los jueces usarán estos criterios para evaluar cada proyecto.
                                    La puntuación final de un proyecto es la suma de todos los criterios evaluados.
                                </p>
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
                            Crear Criterio
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
