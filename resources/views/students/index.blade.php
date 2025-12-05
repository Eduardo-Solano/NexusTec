<x-app-layout>
    <div class="py-12 bg-[#0B1120] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-white">Gestión de Alumnos</h2>
                    <p class="text-gray-400 text-sm mt-1">Administración de alumnos</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('students.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition">
                        Agregar Alumno
                    </a>

                    <a href="#" onclick="document.getElementById('csv_file_input').click();"
                        class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500 transition ms-2">

                        <!-- Ícono tipo Excel -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M4 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0013.414 6L9 1.586A2 2 0 007.586 1H4z" />
                        </svg>

                        importar lista CSV
                    </a>


                </div>
                <!-- Formulario oculto para cargar CSV -->
                <form id="csvUploadForm" action="{{ route('students.importCsv') }}" method="POST"
                    enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" name="csv_file" id="csv_file_input" accept=".csv" class="hidden" />
                </form>
                <script>
                    let csvData = []; // Filas completas del CSV
                    let csvHeaders = []; // Encabezados
                    let currentPage = 1; // Página actual
                    let rowsPerPage = 10; // Filas por página

                    function openCsvModal() {
                        document.getElementById('csv_modal').classList.remove('hidden');
                    }

                    function closeCsvModal() {
                        document.getElementById('csv_modal').classList.add('hidden');
                        document.getElementById('csv_file_input').value = ""; // limpiar selección
                        // limpiar tabla
                        document.getElementById('csv_modal_head').innerHTML = "";
                        document.getElementById('csv_modal_body').innerHTML = "";
                        document.getElementById('csv_pagination').style.display = "none";
                    }

                    // Renderizar tabla por página
                    function renderCsvPage() {
                        const start = (currentPage - 1) * rowsPerPage;
                        const end = start + rowsPerPage;
                        const pageRows = csvData.slice(start, end);

                        // Encabezados
                        let headHtml = '<tr>';
                        csvHeaders.forEach(h => headHtml += `<th class="px-3 py-2 border-b font-semibold">${h}</th>`);
                        headHtml += '</tr>';
                        document.getElementById('csv_modal_head').innerHTML = headHtml;

                        // Filas
                        let bodyHtml = '';
                        pageRows.forEach(row => {
                            bodyHtml += '<tr>';
                            row.forEach(col => {
                                bodyHtml += `<td class="px-3 py-2 border-b">${col}</td>`;
                            });
                            bodyHtml += '</tr>';
                        });

                        document.getElementById('csv_modal_body').innerHTML = bodyHtml;

                        // Paginación
                        const totalPages = Math.ceil(csvData.length / rowsPerPage);

                        document.getElementById('csv_pagination').style.display = "flex";
                        document.getElementById('csv_page_info').innerText = `Página ${currentPage} de ${totalPages}`;

                        document.getElementById('csv_prev_btn').disabled = currentPage === 1;
                        document.getElementById('csv_next_btn').disabled = currentPage === totalPages;
                    }

                    function prevCsvPage() {
                        if (currentPage > 1) {
                            currentPage--;
                            renderCsvPage();
                        }
                    }

                    function nextCsvPage() {
                        const totalPages = Math.ceil(csvData.length / rowsPerPage);
                        if (currentPage < totalPages) {
                            currentPage++;
                            renderCsvPage();
                        }
                    }

                    // Leer CSV al seleccionar archivo
                    document.getElementById('csv_file_input').addEventListener('change', function() {

                        if (this.files.length === 0) return;

                        const file = this.files[0];
                        const reader = new FileReader();

                        reader.onload = function(event) {
                            const text = event.target.result;

                            // Convertir archivo CSV en array
                            const rows = text.trim().split('\n').map(r => r.split(','));

                            csvHeaders = rows[0]; // encabezados
                            csvData = rows.slice(1); // solo datos (sin headers)

                            currentPage = 1; // reset paginación

                            renderCsvPage(); // pintar primera página
                            openCsvModal(); // abrir modal
                        };

                        reader.readAsText(file);
                    });
                </script>



                <!-- MODAL DE PREVISUALIZACIÓN CSV -->
                <div id="csv_modal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl p-6">

                        <!-- Título -->
                        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">
                            Vista previa del archivo CSV
                        </h2>

                        <!-- Contenedor de la tabla -->
                        <div class="max-h-80 overflow-auto border border-gray-300 dark:border-gray-700 rounded-md">
                            <table class="min-w-full text-sm text-gray-700 dark:text-gray-200">
                                <thead id="csv_modal_head" class="bg-gray-100 dark:bg-gray-700"></thead>
                                <tbody id="csv_modal_body"></tbody>
                            </table>
                        </div>

                        <!-- Controles de paginación -->
                        <div class="flex justify-between items-center mt-3" id="csv_pagination" style="display:none;">
                            <button id="csv_prev_btn"
                                class="px-3 py-1 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded disabled:opacity-40"
                                onclick="prevCsvPage()">
                                Anterior
                            </button>

                            <span id="csv_page_info" class="text-sm text-gray-700 dark:text-gray-300"></span>

                            <button id="csv_next_btn"
                                class="px-3 py-1 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-white rounded disabled:opacity-40"
                                onclick="nextCsvPage()">
                                Siguiente
                            </button>
                        </div>

                        <!-- Botones -->
                        <div class="mt-4 flex justify-end gap-2">
                            <button onclick="closeCsvModal()"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-400">
                                Cancelar
                            </button>

                            <button onclick="confirmCsvImport()"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500">
                                Importar
                            </button>
                        </div>

                    </div>
                </div>
                <!-- MODAL DE RESULTADOS CSV -->
                <div id="csv_result_modal"
                    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl p-6 relative">

                        <!-- Título dinámico -->
                        <h2 id="csv_result_title" class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4">
                            Resultados de Importación
                        </h2>

                        <!-- CONTENEDOR GENERAL -->
                        <div id="csv_result_content" class="max-h-96 overflow-auto"></div>

                        <!-- BOTONES DE NAVEGACIÓN -->
                        <div class="mt-4 flex justify-between">

                            <button id="csv_result_back_btn"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-400 hidden">
                                Anterior
                            </button>

                            <button id="csv_result_next_btn"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-500 hidden">
                                Siguiente
                            </button>

                            <button id="csv_result_close_btn" onclick="closeCsvResultModal()"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500 hidden">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
                <script>
                    let csvResultStep = 1; // 1: correctos, 2: incorrectos, 3: resumen
                    let csvImported = [];
                    let csvFailed = [];
                    let csvDefaultPassword = "password";

                    function openCsvResultModal() {
                        document.getElementById("csv_result_modal").classList.remove("hidden");
                    }

                    function closeCsvResultModal() {
                        document.getElementById("csv_result_modal").classList.add("hidden");
                        csvResultStep = 1;
                    }

                    // -----------------------------
                    //     FLUJO DE 3 PANTALLAS
                    // -----------------------------
                    function renderCsvResultScreen() {
                        const title = document.getElementById("csv_result_title");
                        const content = document.getElementById("csv_result_content");
                        const backBtn = document.getElementById("csv_result_back_btn");
                        const nextBtn = document.getElementById("csv_result_next_btn");
                        const closeBtn = document.getElementById("csv_result_close_btn");

                        // Reset botones
                        backBtn.classList.add("hidden");
                        nextBtn.classList.add("hidden");
                        closeBtn.classList.add("hidden");

                        // Render según pantalla
                        if (csvResultStep === 1) {
                            // ------------------------------
                            // PANTALLA 1 → DATOS CORRECTOS
                            // ------------------------------
                            title.innerText = "Datos Correctos";

                            let html = `
            <p class="mb-2 text-gray-700 dark:text-gray-300">
                Estos alumnos fueron importados correctamente:
            </p>
            <table class="w-full text-sm border">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-2 py-1 border">Nombre</th>
                        <th class="px-2 py-1 border">Correo</th>
                        <th class="px-2 py-1 border">Matrícula</th>
                        <th class="px-2 py-1 border">Carrera ID</th>
                    </tr>
                </thead>
                <tbody>
        `;

                            csvImported.forEach(item => {
                                html += `
                <tr>
                    <td class="border px-2 py-1">${item.name}</td>
                    <td class="border px-2 py-1">${item.email}</td>
                    <td class="border px-2 py-1">${item.control_number}</td>
                    <td class="border px-2 py-1">${item.career_id}</td>
                </tr>
            `;
                            });

                            html += "</tbody></table>";
                            content.innerHTML = html;

                            // Botones
                            nextBtn.classList.remove("hidden");

                        } else if (csvResultStep === 2) {
                            // ------------------------------
                            // PANTALLA 2 → DATOS INCORRECTOS
                            // ------------------------------
                            title.innerText = "Datos Incorrectos";

                            let html = `
            <p class="mb-2 text-gray-700 dark:text-gray-300">
                Estas filas tuvieron errores y no fueron importadas:
            </p>
            <table class="w-full text-sm border">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-2 py-1 border">Fila</th>
                        <th class="px-2 py-1 border">Nombre</th>
                        <th class="px-2 py-1 border">Correo</th>
                        <th class="px-2 py-1 border">Matrícula</th>
                        <th class="px-2 py-1 border">Error</th>
                    </tr>
                </thead>
                <tbody>
        `;

                            csvFailed.forEach(item => {
                                html += `
                <tr>
                    <td class="border px-2 py-1">${item.row}</td>
                    <td class="border px-2 py-1">${item.name}</td>
                    <td class="border px-2 py-1">${item.email}</td>
                    <td class="border px-2 py-1">${item.control_number}</td>
                    <td class="border px-2 py-1 text-red-600">${item.errors.join(", ")}</td>
                </tr>
            `;
                            });

                            html += "</tbody></table>";
                            content.innerHTML = html;

                            // Botones
                            backBtn.classList.remove("hidden");
                            nextBtn.classList.remove("hidden");

                        } else {
                            // ------------------------------
                            // PANTALLA 3 → RESUMEN FINAL
                            // ------------------------------
                            title.innerText = "Resumen de Importación";

                            // Mensaje dinámico según si hubo importados o no
                            let passwordMessage = "";

                            if (csvImported.length > 0) {
                                passwordMessage = `
            <p class="text-gray-800 dark:text-gray-200 mt-4 font-semibold">
                Los alumnos que han sido importados correctamente tienen la contraseña por defecto:
                <span class="text-blue-500">"${csvDefaultPassword}"</span>
            </p>
        `;
                            } else {
                                passwordMessage = `
            <p class="text-red-400 dark:text-red-300 mt-4 font-semibold">
                No se importó ningún alumno. No se asignaron contraseñas.
            </p>
        `;
                            }

                            content.innerHTML = `
        <p class="text-gray-800 dark:text-gray-200 text-md mb-3">
            Proceso completado.
        </p>

        <p class="text-gray-700 dark:text-gray-300 mb-1">
            ✔ Alumnos importados: <strong>${csvImported.length}</strong>
        </p>

        <p class="text-gray-700 dark:text-gray-300 mb-1">
            ❌ Filas con errores: <strong>${csvFailed.length}</strong>
        </p>

        ${passwordMessage}
    `;

                            backBtn.classList.remove("hidden");
                            closeBtn.classList.remove("hidden");
                        }

                    }

                    // Navegación
                    document.getElementById("csv_result_next_btn").addEventListener("click", () => {
                        if (csvResultStep < 3) csvResultStep++;
                        renderCsvResultScreen();
                    });

                    document.getElementById("csv_result_back_btn").addEventListener("click", () => {
                        if (csvResultStep > 1) csvResultStep--;
                        renderCsvResultScreen();
                    });
                </script>
                <script>
                    function confirmCsvImport() {
                        const fileInput = document.getElementById("csv_file_input");
                        const formData = new FormData();

                        if (fileInput.files.length === 0) {
                            alert("No se seleccionó archivo.");
                            return;
                        }

                        formData.append("csv_file", fileInput.files[0]);

                        // CSRF token
                        formData.append("_token", document.querySelector('meta[name="csrf-token"]').getAttribute("content"));

                        // Enviar al backend
                        fetch("{{ route('students.importCsv') }}", {
                                method: "POST",
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {

                                // Cerrar modal de previsualización
                                document.getElementById("csv_modal").classList.add("hidden");

                                // Guardar datos recibidos
                                csvImported = data.imported;
                                csvFailed = data.failed;
                                csvDefaultPassword = data.default_password;

                                // Comenzar en pantalla 1
                                csvResultStep = 1;

                                // Abrir modal de resultados
                                openCsvResultModal();

                                // Mostrar pantalla 1
                                renderCsvResultScreen();
                            })
                            .catch(error => {
                                console.error("Error en importación:", error);
                                alert("Ocurrió un error inesperado.");
                            });
                    }
                </script>





            </div>

            <!-- Barra de Búsqueda y Filtros -->
            <div class="mb-6 bg-gray-800 p-4 rounded-xl shadow-lg border border-gray-700">
                <form method="GET" action="{{ route('students.index') }}" class="flex flex-col md:flex-row gap-4">
                    <!-- Búsqueda por texto -->
                    <div class="flex-1">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar por nombre, email o número de control..."
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-600 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:ring-2 focus:ring-ito-orange focus:border-ito-orange transition">
                        </div>
                    </div>

                    <!-- Filtro por carrera -->
                    <div class="w-full md:w-56">
                        <select name="career_id"
                            class="block w-full py-2.5 px-3 border border-gray-600 rounded-lg bg-gray-700 text-white focus:ring-2 focus:ring-ito-orange focus:border-ito-orange transition">
                            <option value="">🎓 Todas las carreras</option>
                            @foreach ($careers as $career)
                                <option value="{{ $career->id }}"
                                    {{ request('career_id') == $career->id ? 'selected' : '' }}>
                                    {{ Str::limit($career->name, 35) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-2">
                        <button type="submit"
                            class="px-4 py-2.5 bg-tecnm-blue hover:bg-blue-700 text-white font-bold rounded-lg transition flex items-center gap-2">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrar
                        </button>
                        @if (request('search') || request('career_id'))
                            <a href="{{ route('students.index') }}"
                                class="px-4 py-2.5 bg-gray-600 hover:bg-gray-500 text-white font-bold rounded-lg transition flex items-center gap-2">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Limpiar
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Indicador de filtros activos -->
                @if (request('search') || request('career_id'))
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-sm text-gray-400">
                        <span class="font-medium">Filtros activos:</span>
                        @if (request('search'))
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900 text-blue-200">
                                Búsqueda: "{{ request('search') }}"
                            </span>
                        @endif
                        @if (request('career_id'))
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-900 text-purple-200">
                                Carrera: {{ $careers->find(request('career_id'))->name ?? 'N/A' }}
                            </span>
                        @endif
                        <span class="text-gray-500">— {{ $students->total() }} resultado(s)</span>
                    </div>
                @endif
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <table class="w-full whitespace-nowrap">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase w-1/3">Nombre</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase w-1/4">No. Control
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase w-1/4">Carrera
                            </th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($students as $student)
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="h-8 w-8 rounded-full bg-purple-900/50 flex items-center justify-center text-purple-300 font-bold text-xs mr-3 border border-purple-500/30">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-white">{{ $student->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $student->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    {{ $student->studentProfile->control_number ?? 'S/N' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-400">
                                    {{ $student->studentProfile->career->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('students.edit', $student) }}"
                                            class="text-blue-400 hover:text-blue-300"><svg class="w-5 h-5"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg></a>

                                        <form action="{{ route('students.destroy', $student) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar a este estudiante permanentemente?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-400 hover:text-red-300"><svg class="w-5 h-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-12 w-12 text-gray-600 mb-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-gray-400 font-medium">No se encontraron estudiantes</p>
                                        @if (request('search') || request('career_id'))
                                            <p class="text-gray-500 text-sm mt-1">Intenta modificar los filtros de
                                                búsqueda</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-6 py-3 border-t border-gray-700 bg-gray-900/30">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
