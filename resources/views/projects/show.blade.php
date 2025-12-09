<x-app-layout>
    {{-- Animated Background --}}
    <div class="circuit-background-app"></div>
    <div class="light-particles-app"></div>

    <div class="min-h-screen py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 flex items-center justify-between">
                <a href="{{ route('events.show', $project->team->event_id) }}" class="inline-flex items-center text-gray-400 hover:text-white transition group">
                    <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mr-3 group-hover:border-blue-500/50 group-hover:bg-blue-500/20 transition-all duration-300">
                        <svg class="w-4 h-4 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </div>
                    <span class="text-sm font-medium">Volver al Evento</span>
                </a>

                {{-- Botones de Editar/Eliminar (solo si tiene permiso y no ha sido evaluado) --}}
                @php
                    $isLeader = $project->team->leader_id === Auth::id();
                    $isAdminOrStaff = Auth::user()->hasAnyRole(['admin', 'staff']);
                    $canModify = ($isLeader || $isAdminOrStaff) && !$project->evaluations()->exists();
                    $hasEvaluations = $project->evaluations()->exists();
                @endphp

                @if($isLeader || $isAdminOrStaff)
                    <div class="flex items-center gap-3">
                        @if($canModify)
                            <a href="{{ route('projects.edit', $project) }}" 
                               class="inline-flex items-center gap-2 px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl shadow-lg hover:shadow-blue-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar
                            </a>
                        @else
                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800/50 border border-gray-700 text-gray-400 text-sm font-bold rounded-xl cursor-not-allowed backdrop-blur-sm" title="No se puede editar porque ya fue evaluado">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Bloqueado
                            </span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Mensajes Flash --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 rounded-xl text-green-400 text-sm backdrop-blur-sm shadow-lg animate-fade-in-down">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-sm backdrop-blur-sm shadow-lg animate-fade-in-down">
                    {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="mb-6 p-4 bg-blue-500/10 border border-blue-500/30 rounded-xl text-blue-400 text-sm backdrop-blur-sm shadow-lg animate-fade-in-down">
                    {{ session('info') }}
                </div>
            @endif

            {{-- Banner de Estado de Evaluación --}}
            @if($hasEvaluations && ($isLeader || $isAdminOrStaff))
                <div class="mb-6 p-4 bg-amber-500/10 border border-amber-500/30 rounded-xl flex items-center gap-4 backdrop-blur-sm shadow-lg animate-pulse-slow">
                    <div class="p-2 bg-amber-500/20 rounded-lg">
                        <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-amber-300 font-bold text-sm">Proyecto en Evaluación</p>
                        <p class="text-amber-400/70 text-xs">Este proyecto ya ha recibido evaluaciones. La edición y eliminación están bloqueadas para proteger la integridad de las calificaciones.</p>
                    </div>
                </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="glass-card rounded-2xl p-8 shadow-2xl relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-purple-500 group-hover:h-1.5 transition-all duration-500"></div>
                        <div class="absolute -top-20 -right-20 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="px-3 py-1 rounded-md bg-white/5 text-blue-400 text-[10px] font-bold uppercase tracking-wider border border-white/10 shadow-sm">
                                    Proyecto Final
                                </span>
                                <span class="text-gray-500 text-xs">Entregado {{ $project->created_at->format('d M, Y') }}</span>
                            </div>

                            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                                {{ $project->name }}
                            </h1>

                            <div class="flex items-center gap-3 pt-2">
                                <p class="text-gray-400 text-xs uppercase font-bold tracking-widest">Desarrollado por:</p>
                                <div class="flex items-center px-4 py-1.5 bg-black/30 rounded-full border border-white/10 backdrop-blur-md">
                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 animate-pulse shadow-[0_0_10px_rgba(96,165,250,0.8)]"></span>
                                    <span class="text-white font-bold text-sm">{{ $project->team->name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card rounded-2xl p-8 shadow-lg">
                        <h3 class="text-lg font-bold text-white mb-6 flex items-center border-b border-white/5 pb-4">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            Sobre la Solución
                        </h3>
                        
                        <div class="prose prose-invert max-w-none text-gray-300 leading-relaxed text-base">
                            {{ $project->description }}
                        </div>
                    </div>

                    {{-- SECCIÓN DE ARCHIVOS ADJUNTOS --}}
                    @if($project->hasDocumentation() || $project->hasImage() || $project->hasVideo())
                        <div class="glass-card rounded-2xl p-8 shadow-lg">
                            <h3 class="text-lg font-bold text-white mb-6 flex items-center border-b border-white/5 pb-4">
                                <svg class="w-5 h-5 mr-3 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                                Material del Proyecto
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Documentación PDF --}}
                                @if($project->hasDocumentation())
                                    <a href="{{ $project->documentation_url }}" target="_blank" 
                                       class="group flex items-center gap-4 p-4 bg-white/5 border border-white/5 rounded-xl hover:border-blue-500/50 hover:bg-blue-500/10 transition-all duration-300">
                                        <div class="w-14 h-14 rounded-xl bg-red-500/10 border border-red-500/20 flex items-center justify-center group-hover:scale-110 transition-transform shadow-lg">
                                            <svg class="w-8 h-8 text-red-400 drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-white font-bold group-hover:text-blue-400 transition-colors">Documentación</p>
                                            <p class="text-gray-500 text-xs text-shadow-sm">Archivo PDF</p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-500 group-hover:text-blue-400 transition-colors group-hover:translate-x-1 duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @endif

                                {{-- Imagen del Proyecto / Logo --}}
                                <a href="{{ $project->hasImage() ? $project->image_url : '#' }}" 
                                   target="{{ $project->hasImage() ? '_blank' : '_self' }}"
                                   class="group flex items-center gap-4 p-4 bg-white/5 border border-white/5 rounded-xl hover:border-purple-500/50 hover:bg-purple-500/10 transition-all duration-300 {{ !$project->hasImage() ? 'cursor-default' : '' }}">
                                    
                                    <div class="w-14 h-14 rounded-xl overflow-hidden border border-white/10 group-hover:border-purple-500/30 transition-colors shadow-lg relative bg-black/40 flex items-center justify-center">
                                        @if($project->hasImage())
                                            <img src="{{ $project->image_url }}" 
                                                 alt="Imagen del proyecto" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($project->name) }}&background=6366f1&color=fff&size=128'; this.parentElement.classList.add('flex', 'items-center', 'justify-center');">
                                        @else
                                            {{-- Icono por defecto si no hay imagen --}}
                                            <svg class="w-8 h-8 text-gray-500 group-hover:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1">
                                        <p class="text-white font-bold group-hover:text-purple-400 transition-colors">Imagen/Logo</p>
                                        <p class="text-gray-500 text-xs text-shadow-sm">
                                            {{ $project->hasImage() ? 'Ver imagen completa' : 'No asignada' }}
                                        </p>
                                    </div>

                                    @if($project->hasImage())
                                        <svg class="w-5 h-5 text-gray-500 group-hover:text-purple-400 transition-colors group-hover:translate-x-1 duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    @endif
                                </a>
                            </div>

                            {{-- Video Embebido --}}
                            @if($project->hasVideo())
                                <div class="mt-6 pt-6 border-t border-white/5">
                                    <p class="text-gray-300 font-bold mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-red-500 drop-shadow-[0_0_5px_rgba(239,68,68,0.5)]" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                                        </svg>
                                        Video Demostrativo
                                    </p>
                                    <div class="aspect-video rounded-xl overflow-hidden border border-white/10 bg-black shadow-2xl relative group">
                                        <div class="absolute inset-0 bg-blue-500/5 group-hover:bg-transparent transition-colors pointer-events-none z-10"></div>
                                        @if($project->embed_video_url)
                                            <iframe 
                                                src="{{ $project->embed_video_url }}" 
                                                class="w-full h-full"
                                                frameborder="0" 
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                allowfullscreen>
                                            </iframe>
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-500 p-8 glass-card">
                                                <svg class="w-12 h-12 mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                                </svg>
                                                <p class="text-sm font-medium">Video externo</p>
                                                <a href="{{ $project->video_url }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm mt-1 flex items-center gap-1 hover:underline">
                                                    Ver en sitio original 
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- SECCIÓN DE JUECES ASIGNADOS --}}
                    <div class="glass-card rounded-2xl border border-white/10 shadow-lg overflow-hidden">
                        <div class="p-6 border-b border-white/10 flex items-center justify-between bg-white/5">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <svg class="w-5 h-5 mr-3 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Panel de Evaluación
                            </h3>
                            <span class="bg-amber-500/10 text-amber-400 text-xs font-bold px-3 py-1 rounded-full border border-amber-500/20 backdrop-blur-sm shadow-sm">
                                {{ $project->judges->count() }} Jueces Asignados
                            </span>
                        </div>

                        <div class="p-6">
                            @if($project->judges->count() > 0)
                                <div class="space-y-3 mb-6">
                                    @foreach($project->judges as $judge)
                                        <div class="flex items-center justify-between bg-black/20 border border-white/5 rounded-xl p-4 hover:border-white/10 transition backdrop-blur-sm">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white font-bold text-lg shadow-lg border border-white/10">
                                                    {{ substr($judge->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-white font-bold">{{ $judge->name }}</p>
                                                    <p class="text-gray-500 text-xs">{{ $judge->judgeProfile->specialty->name ?? 'Sin especialidad' }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-3">
                                                @if($judge->pivot->is_completed)
                                                    <span class="px-3 py-1.5 bg-green-500/10 text-green-400 text-xs font-bold rounded-lg border border-green-500/20 flex items-center gap-1 backdrop-blur-sm">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        Evaluado
                                                    </span>
                                                @else
                                                    {{-- Botón para que el juez asignado pueda evaluar --}}
                                                    @if(Auth::id() === $judge->id)
                                                        <a href="{{ route('evaluations.create', ['project_id' => $project->id]) }}" 
                                                           class="px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white text-xs font-bold rounded-lg shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                            Evaluar Ahora
                                                        </a>
                                                    @else
                                                        <span class="px-3 py-1.5 bg-yellow-500/10 text-yellow-400 text-xs font-bold rounded-lg border border-yellow-500/20 flex items-center gap-1 backdrop-blur-sm">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            Pendiente
                                                        </span>
                                                    @endif
                                                @endif

                                                @can('projects.edit')
                                                    @if(!$judge->pivot->is_completed)
                                                        <form action="{{ route('projects.remove-judge', [$project, $judge]) }}" method="POST" 
                                                              onsubmit="return confirm('¿Remover a {{ $judge->name }} de este proyecto?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition" title="Remover juez">
                                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 bg-white/5 rounded-xl border border-dashed border-white/10 mb-6 backdrop-blur-sm">
                                    <svg class="w-12 h-12 mx-auto text-gray-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <p class="text-gray-400 font-medium">Sin jueces asignados</p>
                                    <p class="text-gray-500 text-xs mt-1">Este proyecto aún no tiene evaluadores</p>
                                </div>
                            @endif

                            {{-- Formulario para asignar juez (solo admin/staff) --}}
                            @can('projects.edit')
                                @if(isset($availableJudges) && $availableJudges->count() > 0)
                                    <div class="pt-4 border-t border-white/5">
                                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mb-3">Asignar Nuevo Juez</p>
                                        
                                        {{-- Barra de búsqueda --}}
                                        <div class="mb-3">
                                            <div class="relative">
                                                <input type="text" id="project_judge_search" 
                                                    class="w-full bg-black/20 border border-white/10 text-white text-sm rounded-lg px-4 py-2.5 pl-10 focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 placeholder-gray-500 backdrop-blur-sm transition-all"
                                                    placeholder="🔍 Buscar juez por nombre o especialidad...">
                                                <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        
                                        <form action="{{ route('projects.assign-judge', $project) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <select name="judge_id" id="project_judge_select" required size="6"
                                                    class="custom-scrollbar w-full bg-black/20 border border-white/10 text-white text-sm rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-500/50 focus:border-amber-500/50 leading-relaxed backdrop-blur-sm"
                                                    style="min-height: 220px;">
                                                    @foreach($availableJudges as $judge)
                                                        <option value="{{ $judge->id }}" class="py-2 px-2 hover:bg-white/5 rounded transition-colors">
                                                            {{ $judge->name }}
                                                            @if($judge->judgeProfile && $judge->judgeProfile->specialty)
                                                                - {{ $judge->judgeProfile->specialty->name }}
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" class="w-full px-5 py-2.5 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-bold text-sm rounded-lg transition flex items-center justify-center gap-2 shadow-lg hover:shadow-amber-500/20">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                Asignar Juez Seleccionado
                                            </button>
                                        </form>
                                        <p id="project_no_results" class="hidden text-xs text-amber-400 mt-2 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            No se encontraron jueces con ese criterio
                                        </p>
                                    </div>
                                @elseif($project->judges->count() > 0)
                                    <div class="pt-4 border-t border-white/5 text-center">
                                        <p class="text-gray-400 text-xs">✓ Todos los jueces disponibles ya están asignados</p>
                                    </div>
                                @endif
                            @endcan
                        </div>
                    </div>

                    {{-- SECCIÓN DE RETROALIMENTACIÓN --}}
                    @php
                        $isMember = $project->team->members->contains('id', Auth::id());
                        $event = $project->team->event;
                        $canSeeFeedback = $isMember && $event->show_feedback_to_students && $hasEvaluations;
                        $isAdminOrStaff = Auth::user()->hasAnyRole(['admin', 'staff']);
                    @endphp

                    @if($canSeeFeedback || $isAdminOrStaff)
                        @php
                            $evaluationsByJudge = $project->evaluations()->with(['judge', 'criterion'])->get()->groupBy('judge_id');
                        @endphp

                        @if($evaluationsByJudge->count() > 0)
                            <div class="glass-card rounded-2xl border border-white/10 shadow-lg overflow-hidden">
                                <div class="p-6 border-b border-white/10 bg-green-500/10">
                                    <h3 class="text-lg font-bold text-white flex items-center">
                                        <svg class="w-5 h-5 mr-3 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        Retroalimentación de Jueces
                                    </h3>
                                    <p class="text-gray-400 text-sm mt-1">Comentarios y calificaciones recibidas por tu proyecto.</p>
                                </div>

                                <div class="p-6 space-y-6">
                                    @foreach($evaluationsByJudge as $judgeId => $evaluations)
                                        @php
                                            $judge = $evaluations->first()->judge;
                                            $totalScore = $evaluations->sum('score');
                                            $maxPossible = $evaluations->sum(fn($e) => $e->criterion->max_points ?? 10);
                                            $percentage = $maxPossible > 0 ? round(($totalScore / $maxPossible) * 100) : 0;
                                            $feedback = $evaluations->first()->feedback;
                                        @endphp

                                        <div class="bg-black/20 rounded-xl border border-white/5 overflow-hidden backdrop-blur-sm">
                                            {{-- Header del Juez --}}
                                            <div class="p-4 border-b border-white/5 flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-green-600 to-emerald-700 flex items-center justify-center text-white font-bold shadow-lg border border-white/10">
                                                        {{ substr($judge->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <p class="text-white font-bold text-sm">{{ $judge->name }}</p>
                                                        <p class="text-gray-500 text-xs">{{ $judge->judgeProfile->specialty->name ?? 'Juez' }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-2xl font-black {{ $percentage >= 70 ? 'text-green-400' : ($percentage >= 50 ? 'text-yellow-400' : 'text-red-400') }} drop-shadow-sm">
                                                        {{ $percentage }}%
                                                    </p>
                                                    <p class="text-gray-500 text-xs">{{ $totalScore }}/{{ $maxPossible }} pts</p>
                                                </div>
                                            </div>

                                            {{-- Calificaciones por Criterio --}}
                                            <div class="p-4 space-y-3">
                                                @foreach($evaluations as $evaluation)
                                                    <div class="flex items-center justify-between group">
                                                        <span class="text-gray-300 text-sm group-hover:text-white transition-colors">{{ $evaluation->criterion->name }}</span>
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-24 h-2 bg-gray-700 rounded-full overflow-hidden">
                                                                @php
                                                                    $criterionPercentage = $evaluation->criterion->max_points > 0 
                                                                        ? ($evaluation->score / $evaluation->criterion->max_points) * 100 
                                                                        : 0;
                                                                @endphp
                                                                <div class="h-full rounded-full {{ $criterionPercentage >= 70 ? 'bg-green-500' : ($criterionPercentage >= 50 ? 'bg-yellow-500' : 'bg-red-500') }} shadow-[0_0_8px_rgba(255,255,255,0.2)]" 
                                                                     style="width: {{ $criterionPercentage }}%"></div>
                                                            </div>
                                                            <span class="text-white text-sm font-bold w-12 text-right">
                                                                {{ $evaluation->score }}/<span class="text-gray-500 text-xs">{{ $evaluation->criterion->max_points }}</span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            {{-- Comentarios del Juez --}}
                                            @if($feedback)
                                                <div class="p-4 border-t border-white/5 bg-white/5">
                                                    <p class="text-[10px] text-gray-400 uppercase tracking-wider font-bold mb-2 flex items-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                                        </svg>
                                                        Comentarios
                                                    </p>
                                                    <p class="text-gray-300 text-sm leading-relaxed italic">"{{ $feedback }}"</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    {{-- Resumen Total --}}
                                    @php
                                        $allEvaluations = $project->evaluations()->with('criterion')->get();
                                        $grandTotal = $allEvaluations->sum('score');
                                        $grandMax = $allEvaluations->sum(fn($e) => $e->criterion->max_points ?? 10);
                                        $grandPercentage = $grandMax > 0 ? round(($grandTotal / $grandMax) * 100) : 0;
                                        $judgeCount = $evaluationsByJudge->count();
                                    @endphp

                                    <div class="mt-4 p-4 bg-gradient-to-r from-gray-900 to-black rounded-xl border border-gray-600 shadow-inner">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-gray-400 text-xs uppercase tracking-wider font-bold">Promedio General</p>
                                                <p class="text-gray-500 text-xs mt-1">Basado en {{ $judgeCount }} evaluación(es)</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-4xl font-black {{ $grandPercentage >= 70 ? 'text-green-400' : ($grandPercentage >= 50 ? 'text-yellow-400' : 'text-red-400') }} drop-shadow-md">
                                                    {{ $grandPercentage }}%
                                                </p>
                                                <p class="text-gray-400 text-sm">{{ $grandTotal }}/{{ $grandMax }} pts totales</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @elseif($isMember && $hasEvaluations && !$event->show_feedback_to_students)
                        <div class="glass-card rounded-2xl border border-white/10 p-6">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-blue-500/10 rounded-xl">
                                    <svg class="w-8 h-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold">Tu proyecto ha sido evaluado</p>
                                    <p class="text-gray-400 text-sm mt-1">Los comentarios de los jueces estarán disponibles cuando el administrador habilite la visualización de retroalimentación.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>

                <div class="space-y-6">
                    
                    <div class="glass-card rounded-2xl p-6 border border-white/10 shadow-lg group hover:border-blue-500/30 transition-all duration-300">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Código Fuente</h3>
                        
                        <a href="{{ $project->repository_url }}" target="_blank" class="flex items-center justify-center w-full py-4 bg-white hover:bg-gray-200 text-gray-900 font-bold rounded-xl transition duration-200 shadow-lg group relative overflow-hidden">
                            <span class="relative z-10 flex items-center">
                                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                Ver en GitHub
                                <svg class="w-4 h-4 ml-2 text-gray-500 group-hover:text-gray-900 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </span>
                        </a>
                        
                        <p class="text-center text-[10px] text-gray-500 mt-3 uppercase tracking-wider">El repositorio debe ser público para su evaluación.</p>
                    </div>

                    <div class="glass-card rounded-2xl p-6 border border-white/10 shadow-lg">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/5">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">INTEGRANTES</h3>
                            <span class="bg-gray-700/50 border border-gray-600/50 text-gray-300 text-xs font-bold px-2 py-0.5 rounded">{{ $project->team->members->count() }}</span>
                        </div>
                        
                        <ul class="space-y-4">
                            @php 
                                $leader = $project->team->members->find($project->team->leader_id);
                            @endphp
                            
                            @if($leader)
                            <li class="flex items-center gap-4 bg-blue-900/20 p-3 rounded-xl border border-blue-500/20 backdrop-blur-sm">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white font-bold text-sm shadow-lg ring-1 ring-blue-500/50">
                                    {{ substr($leader->name, 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-white text-sm font-bold truncate">{{ $leader->name }}</p>
                                    <p class="text-blue-400 text-[10px] font-black uppercase tracking-wider truncate">
                                        {{ $leader->pivot->role ?? 'Líder del Equipo' }}
                                    </p>
                                </div>
                            </li>
                            @endif
                            <div class="bg-black/20 border border-white/5 rounded-2xl p-4 shadow-inner mb-6 backdrop-blur-sm">
                                <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">Asesor Académico</h3>
                                
                                @if($project->team->advisor)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-900/50 flex items-center justify-center text-indigo-400 font-bold border border-indigo-700 text-xs">
                                                {{ substr($project->team->advisor->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-white text-sm font-bold leading-tight">{{ $project->team->advisor->name }}</p>
                                                <p class="text-gray-500 text-[10px]">{{ $project->team->advisor->email }}</p>
                                            </div>
                                        </div>

                                        @if($project->team->advisor_status === 'accepted')
                                            <span class="px-2 py-0.5 bg-green-500/10 text-green-400 text-[9px] font-bold uppercase rounded border border-green-500/20">
                                                Verificado
                                            </span>
                                        @elseif($project->team->advisor_status === 'rejected')
                                            <span class="px-2 py-0.5 bg-red-500/10 text-red-400 text-[9px] font-bold uppercase rounded border border-red-500/20">
                                                Rechazado
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 bg-yellow-500/10 text-yellow-400 text-[9px] font-bold uppercase rounded border border-yellow-500/20 animate-pulse">
                                                Pendiente
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-gray-500 text-xs italic opacity-70">Sin asesor asignado.</p>
                                @endif
                            </div>
                            @foreach($project->team->members as $member)
                                @if($member->id !== $project->team->leader_id)
                                    <li class="flex items-center gap-4 px-3 py-1 group opacity-80 hover:opacity-100 transition">
                                        <div class="w-9 h-9 rounded-lg bg-gray-800/80 flex items-center justify-center text-gray-300 font-bold text-xs border border-gray-700 group-hover:border-gray-500 transition">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="text-gray-200 text-sm font-medium truncate">{{ $member->name }}</p>
                                            <p class="text-gray-500 text-[10px] uppercase tracking-wider group-hover:text-gray-400 transition truncate">
                                                {{ $member->pivot->role ?? 'Colaborador' }}
                                            </p>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        @vite('resources/js/pages/projects-show.js')
    @endpush
</x-app-layout>