<x-app-layout>
    <div class="py-12 bg-[#0B1120] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-white">Gestión de Personal</h2>
                    <p class="text-gray-400 text-sm mt-1">Administración de docentes y organizadores</p>
                </div>
                <a href="{{ route('staff.create') }}" class="bg-ito-orange hover:bg-orange-600 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-lg transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Personal
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-900/30 border border-green-500/50 rounded-lg p-4 flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-green-400 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-900/30 border border-red-500/50 rounded-lg p-4 flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <p class="text-red-400 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <table class="w-full whitespace-nowrap">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Nombre</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Tipo</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Departamento</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">No. Empleado</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($staffMembers as $staff)
                            @php
                                $hasStaff = $staff->hasRole('staff');
                                $hasAdvisor = $staff->hasRole('advisor');
                                $staffType = ($hasStaff && $hasAdvisor) ? 'both' : ($hasStaff ? 'staff' : 'advisor');
                            @endphp
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br {{ $staffType == 'both' ? 'from-ito-orange to-orange-600' : ($staffType == 'staff' ? 'from-purple-600 to-purple-800' : 'from-blue-600 to-blue-800') }} flex items-center justify-center text-white font-bold text-sm mr-3 shadow-lg">
                                            {{ strtoupper(substr($staff->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white">{{ $staff->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $staff->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($staffType == 'both')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-ito-orange/10 text-ito-orange border border-ito-orange/30">
                                            ⭐ Ambos
                                        </span>
                                    @elseif($staffType == 'staff')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-500/10 text-purple-400 border border-purple-500/30">
                                            🎯 Organizador
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/30">
                                            👨‍🏫 Docente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    {{ $staff->staffProfile->department ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-400">
                                    {{ $staff->staffProfile->employee_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('staff.edit', $staff) }}" class="p-2 text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition" title="Editar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        
                                        <form action="{{ route('staff.destroy', $staff) }}" method="POST" onsubmit="return confirm('¿Eliminar a este personal permanentemente?');">
                                            @csrf @method('DELETE')
                                            <button class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition" title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-gray-700/50 rounded-full mb-4">
                                            <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <p class="text-gray-400 font-medium">No hay personal registrado</p>
                                        <a href="{{ route('staff.create') }}" class="mt-4 text-ito-orange hover:text-orange-400 font-bold text-sm">
                                            + Agregar primer miembro
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($staffMembers->hasPages())
                    <div class="px-6 py-3 border-t border-gray-700 bg-gray-900/30">
                        {{ $staffMembers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>