<x-app-layout>
    <div class="py-12 bg-[#0B1120] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-white">Gestión de Jueces</h2>
                    <p class="text-gray-400 text-sm mt-1">Administración de perfiles de jueces externos</p>
                </div>
                <div class="flex gap-3">
                    <!-- Botón Importar CSV -->
                    <button type="button" onclick="document.getElementById('csv-modal').classList.remove('hidden')" 
                        class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-lg transition flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Importar CSV
                    </button>
                    <a href="{{ route('judges.create') }}"
                        class="bg-ito-orange hover:bg-orange-600 text-white text-sm font-bold py-2 px-4 rounded-lg shadow-lg transition">
                        + Nuevo Juez
                    </a>
                </div>
            </div>

            <!-- Barra de Búsqueda y Filtros -->
            <div class="mb-6 bg-gray-800 p-4 rounded-xl shadow-lg border border-gray-700">
                <form method="GET" action="{{ route('judges.index') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Búsqueda por texto -->
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Buscar por nombre, email o empresa..."
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-600 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:ring-2 focus:ring-ito-orange focus:border-ito-orange transition">
                        </div>
                    </div>

                    <!-- Filtro por especialidad -->
                    <div class="w-full md:w-56">
                        <select name="specialty_id" 
                            class="block w-full py-2.5 px-3 border border-gray-600 rounded-lg bg-gray-700 text-white focus:ring-2 focus:ring-ito-orange focus:border-ito-orange transition">
                            <option value="">🔬 Todas las especialidades</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}" {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-2">
                        <button type="submit" 
                            class="px-4 py-2.5 bg-tecnm-blue hover:bg-blue-700 text-white font-bold rounded-lg transition flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrar
                        </button>
                        @if(request('search') || request('specialty_id'))
                            <a href="{{ route('judges.index') }}" 
                                class="px-4 py-2.5 bg-gray-600 hover:bg-gray-500 text-white font-bold rounded-lg transition flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Limpiar
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Indicador de filtros activos -->
                @if(request('search') || request('specialty_id'))
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-gray-400">
                        <span class="font-medium">Filtros activos:</span>
                        @if(request('search'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-200">
                                Búsqueda: "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('specialty_id'))
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-900 text-purple-200">
                                Especialidad: {{ $specialties->find(request('specialty_id'))->name ?? 'N/A' }}
                            </span>
                        @endif
                        <span class="text-gray-500">— {{ $judges->total() }} resultado(s)</span>
                    </div>
                @endif
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <table class="w-full whitespace-nowrap">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Nombre</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Empresa</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Especialidad</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase">Asignados</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase">Evaluados</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($judges as $judge)
                            @php
                                $assignedCount = $judge->assignedProjects()->count();
                                $completedCount = $judge->assignedProjects()->wherePivot('is_completed', true)->count();
                                $pendingCount = $assignedCount - $completedCount;
                            @endphp
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="h-8 w-8 rounded-full bg-purple-900/50 flex items-center justify-center text-purple-300 font-bold text-xs mr-3 border border-purple-500/30">
                                            {{ substr($judge->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white">{{ $judge->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $judge->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    {{ $judge->judgeProfile->company ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-400">
                                    {{ $judge->judgeProfile->specialty->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 text-xs font-bold rounded-full {{ $assignedCount > 0 ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'bg-gray-700 text-gray-500' }}">
                                        {{ $assignedCount }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1">
                                        <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 text-xs font-bold rounded-full bg-green-500/10 text-green-400 border border-green-500/20">
                                            {{ $completedCount }}
                                        </span>
                                        @if($pendingCount > 0)
                                            <span class="text-gray-600">/</span>
                                            <span class="inline-flex items-center justify-center min-w-[2rem] px-2 py-1 text-xs font-bold rounded-full bg-yellow-500/10 text-yellow-400 border border-yellow-500/20" title="Pendientes">
                                                {{ $pendingCount }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('judges.edit', $judge) }}" 
                                            class="p-2 text-blue-400 hover:text-blue-300 hover:bg-blue-500/10 rounded-lg transition" title="Editar">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('judges.destroy', $judge) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar a este juez?');">
                                            @csrf @method('DELETE')
                                            <button class="p-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-lg transition" title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-12 w-12 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <p class="text-gray-400 font-medium">No se encontraron jueces</p>
                                        @if(request('search') || request('specialty_id'))
                                            <p class="text-gray-500 text-sm mt-1">Intenta modificar los filtros de búsqueda</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-3 border-t border-gray-700 bg-gray-900/30">
                    {{ $judges->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para importar CSV -->
    <div id="csv-modal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-gradient-to-b from-gray-800 to-gray-900 rounded-2xl shadow-2xl border border-gray-700 max-w-2xl w-full max-h-[90vh] overflow-hidden">
            
            <!-- Header del Modal -->
            <div class="p-6 border-b border-gray-700 bg-gradient-to-r from-green-600/20 to-emerald-600/10">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-500/20 rounded-xl">
                            <svg class="w-6 h-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white">Importar Jueces</h3>
                            <p class="text-sm text-gray-400">Carga masiva desde archivo CSV</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeImportModal()" class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Contenido del Modal -->
            <div id="import-form-section" class="p-6 overflow-y-auto max-h-[60vh]">
                <form id="csv-import-form" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Área de Drag & Drop -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-300 mb-3">📁 Selecciona tu archivo</label>
                        <div id="drop-zone" class="relative border-2 border-dashed border-gray-600 rounded-xl p-8 text-center hover:border-green-500 hover:bg-green-500/5 transition-all cursor-pointer">
                            <input type="file" name="csv_file" id="csv-file-input" accept=".csv,.txt" required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div id="upload-placeholder">
                                <svg class="w-12 h-12 mx-auto text-gray-500 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="text-gray-400 font-medium">Arrastra tu archivo aquí o haz clic para seleccionar</p>
                                <p class="text-xs text-gray-500 mt-1">Formatos aceptados: CSV, TXT</p>
                            </div>
                            <div id="file-selected" class="hidden">
                                <svg class="w-12 h-12 mx-auto text-green-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-green-400 font-medium" id="selected-file-name">archivo.csv</p>
                                <p class="text-xs text-gray-500 mt-1">Listo para importar</p>
                            </div>
                        </div>
                    </div>

                    <!-- Formato requerido -->
                    <div class="bg-gray-900/70 rounded-xl p-5 mb-6 border border-gray-700">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-bold text-gray-300">Formato del archivo CSV</span>
                        </div>
                        
                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                            <div class="flex items-start gap-2 p-3 bg-gray-800 rounded-lg">
                                <span class="text-lg">👤</span>
                                <div>
                                    <p class="text-xs font-bold text-white">Nombre</p>
                                    <p class="text-[10px] text-gray-500">Nombre completo</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-2 p-3 bg-gray-800 rounded-lg">
                                <span class="text-lg">📧</span>
                                <div>
                                    <p class="text-xs font-bold text-white">Email</p>
                                    <p class="text-[10px] text-gray-500">Correo electrónico</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-2 p-3 bg-gray-800 rounded-lg">
                                <span class="text-lg">📱</span>
                                <div>
                                    <p class="text-xs font-bold text-white">Teléfono</p>
                                    <p class="text-[10px] text-gray-500">Opcional</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-2 p-3 bg-gray-800 rounded-lg">
                                <span class="text-lg">🏢</span>
                                <div>
                                    <p class="text-xs font-bold text-white">Empresa</p>
                                    <p class="text-[10px] text-gray-500">Opcional</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-2 p-3 bg-gray-800 rounded-lg col-span-2 lg:col-span-1">
                                <span class="text-lg">🔬</span>
                                <div>
                                    <p class="text-xs font-bold text-white">Especialidad</p>
                                    <p class="text-[10px] text-gray-500">Nombre (detección automática)</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-800 rounded-lg p-3 font-mono text-xs text-gray-400 overflow-x-auto">
                            <span class="text-gray-500"># Ejemplo de contenido:</span><br>
                            <span class="text-blue-400">Nombre,Email,Telefono,Empresa,Especialidad</span><br>
                            <span class="text-gray-300">Carlos García,carlos@empresa.com,3311234567,TechCorp,Desarrollo de Software</span><br>
                            <span class="text-gray-300">Ana Martínez,ana@consulting.mx,,Freelance,Inteligencia Artificial</span>
                        </div>
                    </div>

                    <!-- Nota importante -->
                    <div class="flex items-start gap-3 p-4 bg-amber-500/10 border border-amber-500/30 rounded-xl mb-6">
                        <svg class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-amber-300">Información importante</p>
                            <ul class="text-xs text-amber-200/70 mt-1 space-y-1">
                                <li>• La primera fila debe ser el encabezado (se ignora)</li>
                                <li>• La especialidad se detecta automáticamente por similitud</li>
                                <li>• Teléfono y Empresa son campos opcionales</li>
                                <li>• Contraseña temporal: <code class="bg-amber-500/20 px-1.5 py-0.5 rounded font-bold">password</code></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeImportModal()" 
                            class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-xl transition">
                            Cancelar
                        </button>
                        <button type="submit" id="import-btn"
                            class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-bold rounded-xl transition flex items-center gap-2 shadow-lg shadow-green-500/25">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Importar Jueces
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sección de Resultados -->
            <div id="import-results-section" class="hidden p-6 overflow-y-auto max-h-[60vh]">
            </div>
        </div>
    </div>

    <script>
        document.getElementById('csv-file-input').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('upload-placeholder').classList.add('hidden');
                document.getElementById('file-selected').classList.remove('hidden');
                document.getElementById('selected-file-name').textContent = fileName;
            }
        });

        function closeImportModal() {
            document.getElementById('csv-modal').classList.add('hidden');
            document.getElementById('csv-import-form').reset();
            document.getElementById('upload-placeholder').classList.remove('hidden');
            document.getElementById('file-selected').classList.add('hidden');
            document.getElementById('import-form-section').classList.remove('hidden');
            document.getElementById('import-results-section').classList.add('hidden');
        }

        document.getElementById('csv-import-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = document.getElementById('import-btn');
            
            btn.disabled = true;
            btn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Procesando...`;

            try {
                const response = await fetch('{{ route("judges.importCsv") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await response.json();
                showImportResults(data);
            } catch (error) {
                alert('Error al procesar el archivo: ' + error.message);
                btn.disabled = false;
                btn.innerHTML = `<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg> Importar Jueces`;
            }
        });

        function showImportResults(data) {
            document.getElementById('import-form-section').classList.add('hidden');
            const resultsSection = document.getElementById('import-results-section');
            resultsSection.classList.remove('hidden');

            let html = `
                <div class="text-center mb-6">
                    <div class="inline-flex p-4 rounded-full ${data.total_imported > 0 ? 'bg-green-500/20' : 'bg-red-500/20'} mb-4">
                        ${data.total_imported > 0 
                            ? '<svg class="w-12 h-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                            : '<svg class="w-12 h-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                        }
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Importación ${data.total_imported > 0 ? 'Completada' : 'Finalizada'}</h3>
                    <div class="flex justify-center gap-6 text-sm">
                        <div class="text-center"><p class="text-2xl font-bold text-green-400">${data.total_imported}</p><p class="text-gray-500">Importados</p></div>
                        <div class="text-center"><p class="text-2xl font-bold text-red-400">${data.total_failed}</p><p class="text-gray-500">Errores</p></div>
                    </div>
                </div>
            `;

            if (data.imported && data.imported.length > 0) {
                html += `<div class="mb-4"><h4 class="text-sm font-bold text-green-400 mb-2">✓ Jueces importados</h4>
                    <div class="bg-gray-900 rounded-lg border border-gray-700 max-h-40 overflow-y-auto">
                        <table class="w-full text-xs"><thead class="bg-gray-800 sticky top-0"><tr>
                            <th class="px-3 py-2 text-left text-gray-400">Nombre</th>
                            <th class="px-3 py-2 text-left text-gray-400">Email</th>
                            <th class="px-3 py-2 text-left text-gray-400">Empresa</th>
                        </tr></thead><tbody class="divide-y divide-gray-800">
                            ${data.imported.map(s => `<tr class="hover:bg-gray-800/50"><td class="px-3 py-2 text-white">${s.name}</td><td class="px-3 py-2 text-gray-400">${s.email}</td><td class="px-3 py-2 text-gray-400">${s.company || '-'}</td></tr>`).join('')}
                        </tbody></table></div></div>`;
            }

            if (data.failed && data.failed.length > 0) {
                html += `<div class="mb-4"><h4 class="text-sm font-bold text-red-400 mb-2">✗ Registros con errores</h4>
                    <div class="bg-gray-900 rounded-lg border border-red-900/50 max-h-40 overflow-y-auto">
                        <table class="w-full text-xs"><thead class="bg-red-900/20 sticky top-0"><tr>
                            <th class="px-3 py-2 text-left text-gray-400">Fila</th>
                            <th class="px-3 py-2 text-left text-gray-400">Datos</th>
                            <th class="px-3 py-2 text-left text-gray-400">Errores</th>
                        </tr></thead><tbody class="divide-y divide-gray-800">
                            ${data.failed.map(f => `<tr class="hover:bg-red-900/10"><td class="px-3 py-2 text-white">#${f.row}</td><td class="px-3 py-2 text-gray-400">${f.name || '-'}</td><td class="px-3 py-2 text-red-400 text-[10px]">${f.errors.join(', ')}</td></tr>`).join('')}
                        </tbody></table></div></div>`;
            }

            if (data.total_imported > 0) {
                html += `<div class="flex items-start gap-3 p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg mb-4">
                    <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-xs text-blue-300">Contraseña temporal: <code class="bg-blue-500/20 px-1.5 py-0.5 rounded font-bold">${data.default_password}</code></p>
                </div>`;
            }

            html += `<div class="flex justify-end gap-3">
                <button onclick="closeImportModal()" class="px-5 py-2.5 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-xl transition">Cerrar</button>
                ${data.total_imported > 0 ? '<button onclick="location.reload()" class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-xl transition">Ver Jueces</button>' : ''}
            </div>`;

            resultsSection.innerHTML = html;
        }
    </script>
</x-app-layout>
