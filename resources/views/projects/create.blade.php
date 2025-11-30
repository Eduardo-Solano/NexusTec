<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-gray-800 border border-gray-700 shadow-2xl rounded-lg overflow-hidden">
                
                <div class="bg-gray-800 p-8 border-b border-gray-700 flex justify-between items-center">
                    <div>
                        <h2 class="text-xs font-bold text-green-400 uppercase tracking-widest mb-1">Entrega Final</h2>
                        <h1 class="text-3xl font-bold text-white">Subir Proyecto</h1>
                    </div>
                    <div class="bg-gray-900 border border-gray-600 px-4 py-2 rounded-lg text-right">
                        <span class="text-gray-400 text-xs uppercase block">Equipo</span>
                        <span class="text-white font-bold">{{ $team->name }}</span>
                    </div>
                </div>

                <div class="p-8">
                    
                    <div class="mb-8 bg-purple-900/20 border-l-4 border-purple-500 p-4 rounded-r flex gap-4">
                        <div class="text-purple-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-purple-200 font-bold text-sm">Fase de Evaluación</h3>
                            <p class="text-gray-400 text-sm mt-1">
                                Asegúrate de que tu repositorio sea público o accesible para los jueces. Una vez enviado, no podrás cambiar el enlace.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('projects.store') }}" method="POST" class="space-y-8">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $team->id }}">

                        <div>
                            <x-input-label for="name" :value="__('Nombre del Proyecto / Prototipo')" class="text-white font-bold mb-2" />
                            <x-text-input id="name" 
                                class="block w-full py-3 px-4 bg-gray-900 border border-gray-600 text-white placeholder-gray-500 focus:border-green-500 focus:ring-green-500 rounded-md text-lg shadow-sm transition" 
                                type="text" name="name" :value="old('name')" required autofocus placeholder="Ej: Sistema de Riego IoT" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="repository_url" :value="__('Enlace al Repositorio (GitHub/GitLab)')" class="text-white font-bold mb-2" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                </div>
                                <x-text-input id="repository_url" 
                                    class="block w-full pl-10 py-3 bg-gray-900 border border-gray-600 text-white placeholder-gray-500 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm" 
                                    type="url" name="repository_url" :value="old('repository_url')" required placeholder="https://github.com/usuario/repo" />
                            </div>
                            <x-input-error :messages="$errors->get('repository_url')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descripción General')" class="text-white font-bold mb-2" />
                            <textarea id="description" name="description" rows="5"
                                class="block w-full bg-gray-900 border border-gray-600 text-white placeholder-gray-500 focus:border-green-500 focus:ring-green-500 rounded-md shadow-sm"
                                placeholder="Describe brevemente en qué consiste tu solución..." required>{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-700">
                            <a href="{{ route('events.show', $team->event_id) }}" class="px-4 py-2 text-sm font-medium text-gray-400 hover:text-white transition">
                                Cancelar
                            </a>
                            
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-md shadow-lg transition transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Enviar Proyecto
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>