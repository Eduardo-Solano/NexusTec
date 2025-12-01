<x-app-layout>
    <div class="py-12 bg-[#0B1120] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-white tracking-tight">Equipos</h2>
                    <p class="text-gray-400 text-sm mt-1">Listado general de participantes</p>
                </div>
                <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-full text-xs font-bold border border-gray-700">
                    {{ $teams->total() }} Registrados
                </span>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead class="bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[35%]">
                                    Nombre del Equipo
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[20%]">
                                    Evento
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-[20%]">
                                    Líder
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider w-24">
                                    Miembros
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">
                                    
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @foreach ($teams as $team)
                                <tr class="hover:bg-gray-700/30 transition duration-150 group">
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 rounded bg-gray-700 flex items-center justify-center text-white font-bold text-xs mr-3 border border-gray-600">
                                                {{ substr($team->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-white group-hover:text-ito-orange transition">
                                                    {{ $team->name }}
                                                </div>
                                                <div class="text-[10px] text-gray-500 font-mono">ID: {{ str_pad($team->id, 3, '0', STR_PAD_LEFT) }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-300 font-medium">
                                            {{ Str::limit($team->event->name, 20) }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="h-2 w-2 rounded-full bg-blue-500"></div>
                                            <span class="text-sm text-gray-400">{{ Str::limit($team->leader->name, 15) }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="text-xs font-mono text-gray-400 bg-gray-900 border border-gray-700 px-2 py-1 rounded">
                                            {{ $team->members->count() }}/5
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('teams.show', $team) }}" class="text-gray-500 hover:text-white transition" title="Ver Expediente">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            </a>

                                            @role('admin')
                                                <form action="{{ route('teams.destroy', $team) }}" method="POST" onsubmit="return confirm('¿ELIMINAR EQUIPO?\n\nSe borrará el proyecto entregado y los miembros quedarán libres.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500/70 hover:text-red-500 transition" title="Eliminar Equipo">
                                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </form>
                                            @endrole
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-3 border-t border-gray-700 bg-gray-900/30">
                    {{ $teams->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>