<x-app-layout>
    {{-- Animated Background --}}
    <div class="circuit-background-app"></div>
    <div class="light-particles-app"></div>
    
    <div class="min-h-screen py-12 relative z-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Navegación --}}
            <nav class="flex items-center text-sm font-medium text-gray-400 mb-6">
                <a href="{{ route('projects.show', $project) }}" class="group flex items-center hover:text-white transition-colors duration-300">
                    <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mr-3 group-hover:border-blue-500/50 group-hover:bg-blue-500/20 transition-all duration-300">
                        <svg class="w-4 h-4 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </div>
                    <span>Volver al Proyecto</span>
                </a>
            </nav>

            <div class="glass-card rounded-2xl overflow-hidden shadow-2xl animate-fade-in-up">
                
                <div class="p-8 border-b border-white/10 flex justify-between items-center bg-white/5">
                    <div>
                        <h2 class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-1">Modificar Entrega</h2>
                        <h1 class="text-3xl font-bold text-white bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">
                            Editar Proyecto
                        </h1>
                    </div>
                    <div class="px-4 py-2 rounded-lg bg-black/20 border border-white/5 backdrop-blur-sm text-right">
                        <span class="text-gray-400 text-[10px] uppercase font-bold block tracking-wider">Equipo</span>
                        <span class="text-white font-bold text-sm">{{ $project->team->name }}</span>
                    </div>
                </div>

                <div class="p-8">
                    
                    {{-- Advertencia de integridad --}}
                    <div class="mb-8 bg-amber-500/10 border-l-4 border-amber-500/50 p-4 rounded-r flex gap-4 backdrop-blur-sm">
                        <div class="text-amber-400 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-amber-200 font-bold text-sm">Edición Permitida</h3>
                            <p class="text-gray-400 text-sm mt-1 leading-relaxed">
                                Este proyecto aún no ha sido evaluado. Una vez que los jueces comiencen a calificar, no será posible realizar modificaciones para proteger la integridad de las evaluaciones.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Nombre del Proyecto / Prototipo')" class="text-white font-bold mb-2 text-sm uppercase tracking-wider" />
                            <input id="name" 
                                class="block w-full py-3 px-4 bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl text-lg shadow-inner transition-all duration-300 hover:bg-black/30 backdrop-blur-sm" 
                                type="text" name="name" :value="old('name', $project->name)" required autofocus placeholder="Ej: Sistema de Riego IoT" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="repository_url" :value="__('Enlace al Repositorio (GitHub/GitLab)')" class="text-white font-bold mb-2 text-sm uppercase tracking-wider" />
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                </div>
                                <input id="repository_url" 
                                    class="block w-full pl-10 py-3 bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl shadow-inner transition-all duration-300 hover:bg-black/30 backdrop-blur-sm" 
                                    type="url" name="repository_url" :value="old('repository_url', $project->repository_url)" required placeholder="https://github.com/usuario/repo" />
                            </div>
                            <x-input-error :messages="$errors->get('repository_url')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descripción General')" class="text-white font-bold mb-2 text-sm uppercase tracking-wider" />
                            <textarea id="description" name="description" rows="5"
                                class="block w-full bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl shadow-inner transition-all duration-300 hover:bg-black/30 backdrop-blur-sm resize-none"
                                placeholder="Describe brevemente en qué consiste tu solución..." required>{{ old('description', $project->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- Sección de Archivos Adjuntos --}}
                        <div class="border-t border-white/10 pt-8">
                            <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                                <span class="p-1.5 rounded-lg bg-blue-500/10 text-blue-400">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </span>
                                Archivos Adjuntos
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Documentación PDF --}}
                                <div class="bg-white/5 p-4 rounded-xl border border-white/5 hover:border-white/10 transition-colors">
                                    <x-input-label for="documentation" :value="__('📄 Documentación (PDF)')" class="text-gray-300 font-bold mb-3 block" />
                                    @if($project->hasDocumentation())
                                        <div class="mb-3 p-3 bg-green-500/10 border border-green-500/20 rounded-lg flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-green-400">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <span class="text-xs font-bold uppercase">Actual</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <a href="{{ $project->documentation_url }}" target="_blank" class="text-xs font-medium text-white hover:text-blue-400 transition-colors">Ver</a>
                                                <label class="flex items-center gap-1 text-xs text-red-400 cursor-pointer hover:text-red-300 transition-colors">
                                                    <input type="checkbox" name="remove_documentation" value="1" class="rounded border-gray-600 bg-gray-900 text-red-500 focus:ring-red-500/50">
                                                    Borrar
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" id="documentation" name="documentation" accept=".pdf"
                                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer bg-black/20 border border-white/10 rounded-lg transition-all" />
                                    <p class="text-[10px] text-gray-500 mt-2">Máximo 10MB.</p>
                                    <x-input-error :messages="$errors->get('documentation')" class="mt-2" />
                                </div>

                                {{-- Imagen del Proyecto --}}
                                <div class="bg-white/5 p-4 rounded-xl border border-white/5 hover:border-white/10 transition-colors">
                                    <x-input-label for="image" :value="__('🖼️ Imagen/Logo')" class="text-gray-300 font-bold mb-3 block" />
                                    @if($project->hasImage())
                                        <div class="mb-3 p-3 bg-purple-500/10 border border-purple-500/20 rounded-lg">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-purple-400 text-xs font-bold uppercase">Actual</span>
                                                <label class="flex items-center gap-1 text-xs text-red-400 cursor-pointer hover:text-red-300 transition-colors">
                                                    <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-600 bg-gray-900 text-red-500 focus:ring-red-500/50">
                                                    Borrar
                                                </label>
                                            </div>
                                            <div class="w-full h-24 rounded-lg overflow-hidden bg-black/20">
                                                <img src="{{ $project->image_url }}" alt="Imagen del proyecto" class="w-full h-full object-cover opacity-80 hover:opacity-100 transition-opacity">
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 cursor-pointer bg-black/20 border border-white/10 rounded-lg transition-all" />
                                    <p class="text-[10px] text-gray-500 mt-2">Máx 5MB (JPG, PNG, WebP).</p>
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>
                            </div>

                            {{-- Video URL --}}
                            <div class="mt-6">
                                <x-input-label for="video_url" :value="__('🎬 Video Demostrativo (YouTube/Vimeo)')" class="text-gray-300 font-bold mb-2 text-sm uppercase tracking-wider" />
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-red-500/70 group-focus-within:text-red-500 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                                        </svg>
                                    </div>
                                    <input id="video_url" 
                                        class="block w-full pl-10 py-3 bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl shadow-inner transition-all duration-300 hover:bg-black/30 backdrop-blur-sm" 
                                        type="url" name="video_url" :value="old('video_url', $project->video_url)" placeholder="https://www.youtube.com/watch?v=..." />
                                </div>
                                <p class="text-[10px] text-gray-500 mt-2 pl-1">Opcional. Enlace directo al video.</p>
                                <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-12 pt-6 border-t border-white/10">
                            <a href="{{ route('projects.show', $project) }}" class="text-sm font-bold text-gray-400 hover:text-white transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                Cancelar
                            </a>
                            
                            <button type="submit" class="relative inline-flex items-center justify-center px-8 py-3 overflow-hidden font-bold text-white transition-all duration-300 bg-blue-600 rounded-xl group hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:ring-offset-gray-900">
                                <span class="absolute inset-0 w-full h-full -mt-10 transition-all duration-700 opacity-0 bg-gradient-to-b from-transparent via-transparent to-black group-hover:opacity-20"></span>
                                <span class="relative flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                    Guardar Cambios
                                </span>
                            </button>
                        </div>

                    </form>

                    {{-- Zona de Peligro: Eliminar Proyecto --}}
                    <div class="mt-12 pt-8 border-t border-red-500/20">
                        <h3 class="text-lg font-bold text-red-400 mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Zona de Peligro
                        </h3>
                        
                        <div class="glass-card bg-red-500/5 border border-red-500/20 rounded-xl p-6 hover:border-red-500/40 transition-colors">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div>
                                    <h4 class="font-bold text-white text-base">Eliminar este proyecto</h4>
                                    <p class="text-sm text-gray-400 mt-1">
                                        Esta acción no se puede deshacer. Se perderán todas las calificaciones.
                                    </p>
                                </div>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" 
                                      onsubmit="return confirm('¿Estás SEGURO?\n\nEsta acción es irreversible y eliminará el proyecto permanentemente.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-5 py-2.5 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white font-bold rounded-lg transition-all duration-300 border border-red-500/50 flex items-center gap-2 whitespace-nowrap group">
                                        <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar Definitivamente
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
