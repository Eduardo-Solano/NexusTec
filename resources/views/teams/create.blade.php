<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-gray-800 border border-gray-700 shadow-xl rounded-lg overflow-hidden">
                
                <div class="bg-gray-800 p-8 border-b border-gray-700 flex justify-between items-center">
                    <div>
                        <h2 class="text-xs font-bold text-ito-orange uppercase tracking-widest mb-1">Nueva Inscripción</h2>
                        <h1 class="text-3xl font-bold text-white">Crear Equipo</h1>
                    </div>
                    <div class="bg-gray-900 border border-gray-600 px-4 py-2 rounded-lg">
                        <span class="text-gray-400 text-xs uppercase block">Evento</span>
                        <span class="text-white font-bold">{{ $event->name }}</span>
                    </div>
                </div>

                <div class="p-8">
                    
                    <div class="mb-8 bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded-r flex gap-4">
                        <div class="text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-blue-200 font-bold text-sm">Rol de Capitán</h3>
                            <p class="text-gray-400 text-sm mt-1">
                                Al crear este equipo, serás asignado automáticamente como líder. Asegúrate de escribir correctamente los correos institucionales de tus compañeros.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('teams.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div>
                            <x-input-label for="name" :value="__('Nombre del Equipo')" class="text-white font-bold mb-2 text-base" />
                            <x-text-input id="name" 
                                class="block w-full py-3 px-4 bg-gray-900 border border-gray-600 text-white placeholder-gray-500 focus:border-ito-orange focus:ring-ito-orange rounded-md text-lg shadow-sm transition" 
                                type="text" name="name" :value="old('name')" required autofocus placeholder="Ej: Los Innovadores" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="border-t border-gray-700 pt-6">
                            <h3 class="text-lg font-bold text-white mb-1">Integrantes</h3>
                            <p class="text-gray-500 text-sm mb-6">Agrega los correos de tus compañeros (Opcional).</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @for ($i = 1; $i <= 4; $i++)
                                    <div>
                                        <label for="member_{{ $i }}" class="block text-xs font-bold text-gray-400 uppercase mb-2">
                                            Miembro #{{ $i }}
                                        </label>
                                        <x-text-input id="member_{{ $i }}" 
                                            class="block w-full bg-gray-900 border border-gray-600 text-white placeholder-gray-600 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" 
                                            type="email" name="members[]" placeholder="correo@institucional.com" />
                                    </div>
                                @endfor
                            </div>
                            <x-input-error :messages="$errors->get('members')" class="mt-4" />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-700">
                            <a href="{{ route('events.show', $event) }}" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white transition">
                                Cancelar
                            </a>
                            
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-ito-orange hover:bg-orange-700 text-white font-bold rounded-md shadow-lg transition transform hover:-translate-y-0.5">
                                Finalizar Inscripción
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>