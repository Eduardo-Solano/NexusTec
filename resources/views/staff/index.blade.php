<x-app-layout>
    <div class="py-12 bg-[#0B1120] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-white">Claustro Docente</h2>
                    <p class="text-gray-400 text-sm mt-1">Administración de asesores y staff</p>
                </div>
                <a href="{{ route('staff.create') }}" class="bg-ito-orange hover:bg-orange-600 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-lg transition">
                    + Nuevo Docente
                </a>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <table class="w-full whitespace-nowrap">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase w-1/3">Nombre</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase w-1/4">Departamento</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase w-1/4">No. Empleado</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach ($staffMembers as $staff)
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-purple-900/50 flex items-center justify-center text-purple-300 font-bold text-xs mr-3 border border-purple-500/30">
                                            {{ substr($staff->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white">{{ $staff->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $staff->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    {{ $staff->staffProfile->department ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-400">
                                    {{ $staff->staffProfile->employee_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('staff.edit', $staff) }}" class="text-indigo-400 hover:text-white font-bold transition flex items-center justify-end gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-3 border-t border-gray-700 bg-gray-900/30">
                    {{ $staffMembers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>