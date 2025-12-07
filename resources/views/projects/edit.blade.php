<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Navegación --}}
            <nav class="flex items-center text-sm font-medium text-gray-400 mb-6">
                <a href="{{ route('projects.show', $project) }}" class="hover:text-white transition flex items-center group">
                    <div class="w-8 h-8 rounded-full bg-gray-800 border border-gray-700 flex items-center justify-center mr-3 group-hover:border-blue-500 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </div>
                    Volver al Proyecto
                </a>
            </nav>

            <div class="bg-gray-800 border border-gray-700 shadow-2xl rounded-lg overflow-hidden">
                
                <div class="bg-gray-800 p-8 border-b border-gray-700 flex justify-between items-center">
                    <div>
                        <h2 class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-1">Modificar Entrega</h2>
                        <h1 class="text-3xl font-bold text-white">Editar Proyecto</h1>
                    </div>
                    <div class="bg-gray-900 border border-gray-600 px-4 py-2 rounded-lg text-right">
                        <span class="text-gray-400 text-xs uppercase block">Equipo</span>
                        <span class="text-white font-bold">{{ $project->team->name }}</span>
                    </div>
                </div>

                <div class="p-8">
                    
                    {{-- Advertencia de integridad --}}
                    <div class="mb-8 bg-amber-900/20 border-l-4 border-amber-500 p-4 rounded-r flex gap-4">
                        <div class="text-amber-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-amber-200 font-bold text-sm">Edición Permitida</h3>
                            <p class="text-gray-400 text-sm mt-1">
                                Este proyecto aún no ha sido evaluado. Una vez que los jueces comiencen a calificar, no será posible realizar modificaciones para proteger la integridad de las evaluaciones.
                            </p>
                        </div>
                    </div>

                    <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Nombre del Proyecto / Prototipo')" class="text-white font-bold mb-2" />
                            <x-text-input id="name" 
                                class="block w-full py-3 px-4 bg-gray-900 border border-gray-600 text-white placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 rounded-md text-lg shadow-sm transition" 
                                type="text" name="name" :value="old('name', $project->name)" required autofocus placeholder="Ej: Sistema de Riego IoT" />
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
                                    class="block w-full pl-10 py-3 bg-gray-900 border border-gray-600 text-white placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" 
                                    type="url" name="repository_url" :value="old('repository_url', $project->repository_url)" required placeholder="https://github.com/usuario/repo" />
                            </div>
                            <x-input-error :messages="$errors->get('repository_url')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descripción General')" class="text-white font-bold mb-2" />
                            <textarea id="description" name="description" rows="5"
                                class="block w-full bg-gray-900 border border-gray-600 text-white placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                placeholder="Describe brevemente en qué consiste tu solución..." required>{{ old('description', $project->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        {{-- Sección de Archivos Adjuntos --}}
                        <div class="border-t border-gray-700 pt-8">
                            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                Archivos Adjuntos
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Documentación PDF --}}
                                <div>
                                    <x-input-label for="documentation" :value="__('📄 Documentación (PDF)')" class="text-gray-300 font-bold mb-2" />
                                    @if($project->hasDocumentation())
                                        <div class="mb-3 p-3 bg-green-900/20 border border-green-500/30 rounded-lg flex items-center justify-between">
                                            <div class="flex items-center gap-2 text-green-400">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <span class="text-sm font-bold">Archivo actual</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ $project->documentation_url }}" target="_blank" class="text-xs text-blue-400 hover:text-blue-300">Ver PDF</a>
                                                <label class="flex items-center gap-1 text-xs text-red-400 cursor-pointer">
                                                    <input type="checkbox" name="remove_documentation" value="1" class="rounded border-gray-600 bg-gray-900 text-red-500">
                                                    Eliminar
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    <input type="file" id="documentation" name="documentation" accept=".pdf"
                                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer bg-gray-900 border border-gray-600 rounded-lg p-2" />
                                    <p class="text-xs text-gray-500 mt-1">Máximo 10MB. {{ $project->hasDocumentation() ? 'Subir nuevo archivo reemplazará el actual.' : '' }}</p>
                                    <x-input-error :messages="$errors->get('documentation')" class="mt-2" />
                                </div>

                                {{-- Imagen del Proyecto --}}
                                <div>
                                    <x-input-label for="image" :value="__('🖼️ Imagen/Logo del Proyecto')" class="text-gray-300 font-bold mb-2" />
                                    @if($project->hasImage())
                                        <div class="mb-3 p-3 bg-purple-900/20 border border-purple-500/30 rounded-lg">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-purple-400 text-sm font-bold">Imagen actual</span>
                                                <label class="flex items-center gap-1 text-xs text-red-400 cursor-pointer">
                                                    <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-600 bg-gray-900 text-red-500">
                                                    Eliminar
                                                </label>
                                            </div>
                                            <img src="{{ $project->image_url }}" alt="Imagen del proyecto" class="w-full h-24 object-cover rounded-lg">
                                        </div>
                                    @endif
                                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/webp"
                                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-700 cursor-pointer bg-gray-900 border border-gray-600 rounded-lg p-2" />
                                    <p class="text-xs text-gray-500 mt-1">Máximo 5MB. JPG, PNG o WebP.</p>
                                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                                </div>
                            </div>

                            {{-- Video URL --}}
                            <div class="mt-6">
                                <x-input-label for="video_url" :value="__('🎬 Video Demostrativo (YouTube/Vimeo)')" class="text-gray-300 font-bold mb-2" />
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                                        </svg>
                                    </div>
                                    <x-text-input id="video_url" 
                                        class="block w-full pl-10 py-3 bg-gray-900 border border-gray-600 text-white placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" 
                                        type="url" name="video_url" :value="old('video_url', $project->video_url)" placeholder="https://www.youtube.com/watch?v=..." />
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Opcional. Enlace a un video de demostración.</p>
                                <x-input-error :messages="$errors->get('video_url')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-12 pt-6 border-t border-gray-700">
                            <a href="{{ route('projects.show', $project) }}" class="text-sm font-bold text-gray-500 hover:text-white transition flex items-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                Cancelar
                            </a>
                            
                            <button type="submit" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-lg rounded-xl shadow-lg hover:shadow-blue-500/20 transform hover:-translate-y-0.5 transition duration-200">
                                <svg class="w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                Guardar Cambios
                            </button>
                        </div>

                    </form>

                    {{-- Zona de Peligro: Eliminar Proyecto --}}
                    <div class="mt-12 pt-8 border-t border-red-900/30">
                        <h3 class="text-lg font-bold text-red-400 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Zona de Peligro
                        </h3>
                        
                        <div class="bg-red-900/10 border border-red-500/20 rounded-xl p-6">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h4 class="font-bold text-white">Eliminar este proyecto</h4>
                                    <p class="text-sm text-gray-400 mt-1">
                                        Esta acción es irreversible. Se eliminará el proyecto y todas sus asignaciones de jueces.
                                    </p>
                                </div>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" 
                                      onsubmit="return confirm('¿Estás seguro de eliminar este proyecto?\n\nEsta acción NO se puede deshacer.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-6 py-3 bg-red-600 hover:bg-red-500 text-white font-bold rounded-lg transition flex items-center gap-2 whitespace-nowrap">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar Proyecto
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
