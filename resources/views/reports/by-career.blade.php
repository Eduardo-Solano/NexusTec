<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <a href="{{ route('reports.index') }}" class="text-sm text-gray-400 hover:text-indigo-400 transition flex items-center gap-1 mb-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver a Reportes
                    </a>
                    <h1 class="text-3xl font-black text-white flex items-center gap-3">
                        <span class="p-2 bg-blue-500/10 rounded-xl">🎓</span>
                        Participación por Carrera
                    </h1>
                    <p class="text-gray-400 mt-2">Análisis de participación de estudiantes por carrera</p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                <form method="GET" action="{{ route('reports.by-career') }}" class="flex flex-wrap items-end gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <label for="event_id" class="block text-sm font-medium text-gray-400 mb-2">Evento</label>
                        <select name="event_id" id="event_id" 
                            class="w-full px-4 py-2.5 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todos los eventos</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" 
                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            Filtrar
                        </button>
                    </div>
                    @if(request('event_id'))
                        <div>
                            <a href="{{ route('reports.export-participants', ['event_id' => request('event_id')]) }}" 
                                class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Exportar Excel
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Stats Summary --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
                    <p class="text-2xl font-black text-white">{{ $careers->sum('students_count') }}</p>
                    <p class="text-sm text-gray-400">Total Participantes</p>
                </div>
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
                    <p class="text-2xl font-black text-white">{{ $careers->where('students_count', '>', 0)->count() }}</p>
                    <p class="text-sm text-gray-400">Carreras Activas</p>
                </div>
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
                    <p class="text-2xl font-black text-white">{{ $careers->count() > 0 ? round($careers->sum('students_count') / $careers->count(), 1) : 0 }}</p>
                    <p class="text-sm text-gray-400">Promedio por Carrera</p>
                </div>
                <div class="bg-gray-800 border border-gray-700 rounded-xl p-4 text-center">
                    <p class="text-2xl font-black text-white">{{ $careers->max('students_count') ?? 0 }}</p>
                    <p class="text-sm text-gray-400">Máx. Participantes</p>
                </div>
            </div>

            {{-- Chart --}}
            @if($careers->where('students_count', '>', 0)->count() > 0)
            <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <span class="p-1.5 bg-blue-500/10 rounded-lg">📊</span>
                    Distribución por Carrera
                </h3>
                <div class="h-80">
                    <canvas id="careersChart"></canvas>
                </div>
            </div>
            @endif

            {{-- Table --}}
            <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-700">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="p-1.5 bg-blue-500/10 rounded-lg">📋</span>
                        Detalle por Carrera
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-900/50">
                                <th class="text-left py-3 px-6 text-xs font-bold text-gray-400 uppercase">#</th>
                                <th class="text-left py-3 px-6 text-xs font-bold text-gray-400 uppercase">Código</th>
                                <th class="text-left py-3 px-6 text-xs font-bold text-gray-400 uppercase">Carrera</th>
                                <th class="text-right py-3 px-6 text-xs font-bold text-gray-400 uppercase">Estudiantes</th>
                                <th class="text-right py-3 px-6 text-xs font-bold text-gray-400 uppercase">% del Total</th>
                                <th class="py-3 px-6 text-xs font-bold text-gray-400 uppercase">Distribución</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @php $total = $careers->sum('students_count'); @endphp
                            @forelse($careers->sortByDesc('students_count') as $index => $career)
                                <tr class="hover:bg-gray-700/30 transition">
                                    <td class="py-3 px-6 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="py-3 px-6">
                                        <span class="px-2 py-1 bg-gray-700 text-gray-300 rounded text-xs font-mono">
                                            {{ $career->code }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-white font-medium">{{ $career->name }}</td>
                                    <td class="py-3 px-6 text-right">
                                        <span class="px-2 py-1 {{ $career->students_count > 0 ? 'bg-blue-500/10 text-blue-400' : 'bg-gray-700 text-gray-500' }} rounded-lg text-sm font-bold">
                                            {{ $career->students_count }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-right text-gray-400">
                                        {{ $total > 0 ? number_format(($career->students_count / $total) * 100, 1) : 0 }}%
                                    </td>
                                    <td class="py-3 px-6 w-48">
                                        <div class="h-2 bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full" 
                                                style="width: {{ $total > 0 ? ($career->students_count / $total) * 100 : 0 }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-500">
                                        No hay carreras registradas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($careers->count() > 0)
                        <tfoot>
                            <tr class="bg-gray-900/50 border-t border-gray-600">
                                <td colspan="3" class="py-3 px-6 text-white font-bold">Total</td>
                                <td class="py-3 px-6 text-right text-white font-bold">{{ $total }}</td>
                                <td class="py-3 px-6 text-right text-white font-bold">100%</td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </div>

    @if($careers->where('students_count', '>', 0)->count() > 0)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('careersChart').getContext('2d');
            const careers = @json($careers->where('students_count', '>', 0)->sortByDesc('students_count')->take(10)->values());
            
            const colors = [
                'rgba(59, 130, 246, 0.8)', 'rgba(139, 92, 246, 0.8)', 'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)', 'rgba(239, 68, 68, 0.8)', 'rgba(236, 72, 153, 0.8)',
                'rgba(14, 165, 233, 0.8)', 'rgba(34, 197, 94, 0.8)', 'rgba(168, 85, 247, 0.8)',
                'rgba(251, 146, 60, 0.8)'
            ];
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: careers.map(c => c.name),
                    datasets: [{
                        data: careers.map(c => c.students_count),
                        backgroundColor: colors,
                        borderColor: 'rgba(31, 41, 55, 1)',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { color: '#9CA3AF', padding: 15, usePointStyle: true }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
    @endif
</x-app-layout>
