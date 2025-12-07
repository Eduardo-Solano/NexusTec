<x-app-layout>
    <div class="py-12 bg-[#0B1120] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-white tracking-tight">Proyectos</h2>
                    <p class="text-gray-400 text-sm mt-1">Listado de proyectos entregados</p>
                </div>
                <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-full text-xs font-bold border border-gray-700">
                    {{ $projects->total() }} Registrados
                </span>
            </div>

            <!-- Barra de Búsqueda y Filtros -->
            <div class="mb-6 bg-gray-800 p-4 rounded-xl shadow-lg border border-gray-700">
                <form method="GET" action="{{ route('projects.index') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Búsqueda por texto -->
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Buscar por nombre de proyecto o equipo..."
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-600 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:ring-2 focus:ring-ito-orange focus:border-ito-orange transition">
                        </div>
                    </div>

                    <!-- Filtro por evento -->
                    <div class="w-full md:w-56">
                        <select name="event_id" 
                            class="block w-full py-2.5 px-3 border border-gray-600 rounded-lg bg-gray-700 text-white focus:ring-2 focus:ring-ito-orange focus:border-ito-orange transition">
                            <option value="">🎯 Todos los eventos</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ Str::limit($event->name, 30) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-tecnm-blue hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrar
                        </button>
                        @if(request('search') || request('event_id'))
                            <a href="{{ route('projects.index') }}" 
                                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-bold rounded-lg transition flex items-center gap-2 border border-gray-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Limpiar
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Indicador de filtros activos -->
                @if(request('search') || request('event_id'))
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-gray-400">
                        <span class="font-medium">Filtros activos:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-200">
                                Búsqueda: "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('event_id'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-900 text-purple-200">
                                Evento: {{ $events->find(request('event_id'))->name ?? 'N/A' }}
                            </span>
                        @endif
                        <span class="text-gray-500">— {{ $projects->total() }} resultado(s)</span>
                    </div>
                @endif
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[30%]">
                                    Proyecto
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[20%]">
                                    Equipo
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[20%]">
                                    Evento
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider w-24">
                                    Jueces
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse ($projects as $project)
                                <tr class="hover:bg-gray-700/30 transition duration-150 group">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded bg-gradient-to-br from-ito-orange to-amber-600 flex items-center justify-center text-white font-bold text-xs mr-3">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-white group-hover:text-ito-orange transition">
                                                    {{ Str::limit($project->name, 30) }}
                                                </div>
                                                <div class="text-[10px] text-gray-500 font-mono">ID: {{ str_pad($project->id, 3, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-2 rounded-full bg-blue-500"></div>
                                            <span class="text-sm text-gray-300 font-medium">{{ Str::limit($project->team->name ?? 'Sin equipo', 20) }}</span>
                                        </div>
                                        @if($project->team && $project->team->leader)
                                            <div class="text-xs text-gray-500 mt-1">
                                                Líder: {{ Str::limit($project->team->leader->name, 15) }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-300 font-medium">
                                            {{ Str::limit($project->team->event->name ?? 'N/A', 20) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xs font-mono text-gray-400 bg-gray-900 border border-gray-700 px-2 py-1 rounded">
                                            {{ $project->judges->count() }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('projects.show', $project) }}" class="text-gray-500 hover:text-white transition" title="Ver Proyecto">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </a>

                                            @can('projects.edit')
                                                <a href="{{ route('projects.edit', $project) }}" class="text-blue-500/70 hover:text-blue-500 transition" title="Editar Proyecto">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                </a>
                                            @elseif($project->team && auth()->id() === $project->team->leader_id)
                                                <a href="{{ route('projects.edit', $project) }}" class="text-blue-500/70 hover:text-blue-500 transition" title="Editar mi Proyecto">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                </a>
                                            @endcan

                                            @can('projects.delete')
                                                <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('¿ELIMINAR PROYECTO?\n\nEsta acción no se puede deshacer.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500/70 hover:text-red-500 transition" title="Eliminar Proyecto">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-12 w-12 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-gray-400 font-medium">No se encontraron proyectos</p>
                                            @if(request('search') || request('event_id'))
                                                <p class="text-gray-500 text-sm mt-1">Intenta modificar los filtros de búsqueda</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-3 border-t border-gray-700 bg-gray-900/30">
                    {{ $projects->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
