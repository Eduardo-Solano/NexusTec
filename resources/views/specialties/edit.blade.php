<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-xl overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-amber-600 to-amber-700 px-8 py-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('specialties.index') }}" 
                            class="p-2 bg-white/10 hover:bg-white/20 rounded-lg transition">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Editar Especialidad</h1>
                            <p class="text-amber-100 text-sm">Modifica los datos de la especialidad</p>
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <form action="{{ route('specialties.update', $specialty) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-300 mb-2">Nombre de la Especialidad *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $specialty->name) }}" required
                            placeholder="Ej: Desarrollo de Software, Inteligencia Artificial..."
                            class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition"
                            maxlength="100">
                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-700">
                        <a href="{{ route('specialties.index') }}" 
                            class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-gray-300 font-bold rounded-xl transition">
                            Cancelar
                        </a>
                        <button type="submit" 
                            class="px-6 py-3 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-xl transition shadow-lg hover:shadow-amber-500/25">
                            Actualizar Especialidad
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
