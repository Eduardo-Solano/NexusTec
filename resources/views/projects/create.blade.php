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

                        <div class="mt-10 pt-8 border-t border-gray-700/50">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                        <div class="p-1.5 bg-blue-500/20 rounded-lg text-blue-400">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        Asesor Académico
                                    </h3>
                                    <p class="text-gray-500 text-xs mt-1 ml-1">Selecciona al docente que supervisará este proyecto.</p>
                                </div>
                                <span class="text-[10px] font-bold tracking-wider text-blue-400 bg-blue-900/30 px-3 py-1 rounded-full border border-blue-500/30">REQUERIDO</span>
                            </div>

                            <div class="relative group">
                                <select name="advisor_id" id="advisor_id" required
                                    style="color-scheme: dark;"
                                    class="block w-full pl-5 pr-12 py-4 bg-gray-900 border border-gray-600 text-white rounded-xl focus:border-blue-500 focus:ring-blue-500 appearance-none text-base transition shadow-sm hover:border-gray-500 cursor-pointer">
                                    <option value="" disabled selected class="bg-gray-900 text-gray-500">
                                        Click para seleccionar docente...
                                    </option>
                                    
                                    @foreach(\App\Models\User::role(['advisor', 'staff'])->get() as $advisor)
                                        <option value="{{ $advisor->id }}" class="bg-gray-900 text-gray-200 py-2">
                                            {{ $advisor->name }} &mdash; {{ $advisor->staffProfile->department ?? 'Docente' }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400 group-hover:text-white transition">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-12 pt-6 border-t border-gray-700">
                            <a href="{{ route('events.show', $team->event_id) }}" class="text-sm font-bold text-gray-500 hover:text-white transition flex items-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                Cancelar
                            </a>
                            
                            <button type="submit" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-green-500/20 transform hover:-translate-y-0.5 transition duration-200">
                                <svg class="w-6 h-6 mr-3 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Entregar Proyecto
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>