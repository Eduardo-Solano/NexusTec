<x-app-layout>
    {{-- Animated Background --}}
    <div class="circuit-background-app"></div>
    <div class="light-particles-app"></div>

    <div class="min-h-screen py-12 relative z-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="glass-card rounded-2xl p-8 shadow-2xl relative overflow-hidden animate-fade-in-down">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <nav class="flex items-center text-sm font-medium text-blue-400 mb-4 animate-fade-in-left" style="animation-delay: 100ms;">
                        <a href="{{ route('reports.index') }}" class="group flex items-center hover:text-white transition-colors duration-300">
                            <div class="w-8 h-8 rounded-full bg-blue-500/10 border border-blue-500/20 flex items-center justify-center mr-3 group-hover:border-blue-500/50 group-hover:bg-blue-500/20 transition-all duration-300">
                                <svg class="w-4 h-4 group-hover:text-amber-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                            </div>
                            <span>Volver a Reportes</span>
                        </a>
                    </nav>
                    
                    <h1 class="text-4xl font-black text-white flex items-center gap-3 animate-fade-in-up" style="animation-delay: 200ms;">
                        <span class="p-2 bg-blue-500/10 rounded-xl border border-blue-500/20">🎓</span>
                        Participación por Carrera
                    </h1>
                    <p class="text-gray-400 mt-2 text-sm max-w-2xl animate-fade-in-up" style="animation-delay: 300ms;">
                        Análisis detallado de la participación estudiantil desglosado por programas académicos.
                    </p>
                </div>
            </div>

            {{-- Filters --}}
            <div class="glass-card rounded-2xl p-6 shadow-lg animate-fade-in-up" style="animation-delay: 400ms;">
                <form method="GET" action="{{ route('reports.by-career') }}" class="flex flex-col md:flex-row items-end gap-4">
                    <div class="flex-1 w-full md:w-auto relative group">
                        <label for="event_id" class="block text-xs font-bold text-gray-400 mb-2 uppercase tracking-wider">Evento</label>
                        <div class="relative">
                            <select name="event_id" id="event_id" 
                                class="w-full pl-4 pr-10 py-3 bg-black/20 border border-white/10 rounded-xl text-white focus:ring-1 focus:ring-blue-500/50 focus:border-blue-500/50 transition-all duration-300 backdrop-blur-sm appearance-none cursor-pointer hover:bg-black/30">
                                <option value="" class="bg-gray-900 text-gray-400">Todos los eventos</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }} class="bg-gray-900 text-white">
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-3 w-full md:w-auto">
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-blue-500/40 w-full md:w-auto">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtrar
                        </button>
                    
                        @if(request('event_id'))
                            <a href="{{ route('reports.export-participants', ['event_id' => request('event_id')]) }}" 
                                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold transition-all duration-300 flex items-center justify-center gap-2 shadow-lg hover:shadow-emerald-500/40 w-full md:w-auto">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Exportar Excel
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Stats Summary --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-fade-in-up" style="animation-delay: 500ms;">
                <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-blue-500 group hover:scale-105 transition-transform duration-300">
                    <p class="text-3xl font-black text-white group-hover:text-blue-400 transition-colors">{{ $careers->sum('students_count') }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Total Participantes</p>
                </div>
                <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-green-500 group hover:scale-105 transition-transform duration-300">
                    <p class="text-3xl font-black text-white group-hover:text-green-400 transition-colors">{{ $careers->where('students_count', '>', 0)->count() }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Carreras Activas</p>
                </div>
                <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-purple-500 group hover:scale-105 transition-transform duration-300">
                    <p class="text-3xl font-black text-white group-hover:text-purple-400 transition-colors">{{ $careers->count() > 0 ? round($careers->sum('students_count') / $careers->count(), 1) : 0 }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Promedio por Carrera</p>
                </div>
                <div class="glass-card rounded-2xl p-6 text-center shadow-lg border-b-4 border-b-yellow-500 group hover:scale-105 transition-transform duration-300">
                    <p class="text-3xl font-black text-white group-hover:text-yellow-400 transition-colors">{{ $careers->max('students_count') ?? 0 }}</p>
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">Máx. Participantes</p>
                </div>
            </div>

            {{-- Chart --}}
            @if($careers->where('students_count', '>', 0)->count() > 0)
            <div class="glass-card rounded-2xl p-8 shadow-lg animate-fade-in-up" style="animation-delay: 600ms;">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    Distribución por Carrera
                </h3>
                <div class="h-80 relative">
                    <canvas id="careersChart"></canvas>
                </div>
            </div>
            @endif

            {{-- Table --}}
            <div class="glass-card rounded-2xl shadow-lg overflow-hidden animate-fade-in-up" style="animation-delay: 700ms;">
                <div class="p-6 border-b border-white/10 bg-white/5">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="p-1.5 bg-blue-500/10 rounded-lg border border-blue-500/20">📋</span>
                        Detalle por Carrera
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-black/20 text-left">
                                <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">#</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Código</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Carrera</th>
                                <th class="text-right py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">Estudiantes</th>
                                <th class="text-right py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider">% del Total</th>
                                <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase tracking-wider w-48">Distribución</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @php $total = $careers->sum('students_count'); @endphp
                            @forelse($careers->sortByDesc('students_count') as $index => $career)
                                <tr class="hover:bg-white/5 transition duration-200">
                                    <td class="py-4 px-6 text-gray-500 font-mono">{{ $index + 1 }}</td>
                                    <td class="py-4 px-6">
                                        <span class="px-2 py-1 bg-white/5 text-gray-300 border border-white/10 rounded text-xs font-mono font-bold tracking-wide">
                                            {{ $career->code }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-white font-bold">{{ $career->name }}</td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="px-3 py-1 {{ $career->students_count > 0 ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : 'bg-gray-700/50 text-gray-500' }} rounded-lg text-sm font-bold shadow-sm">
                                            {{ $career->students_count }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right text-gray-400 font-mono text-sm">
                                        {{ $total > 0 ? number_format(($career->students_count / $total) * 100, 1) : 0 }}%
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="h-2 bg-black/40 rounded-full overflow-hidden border border-white/5">
                                            <div class="h-full bg-gradient-to-r from-blue-600 to-indigo-500 rounded-full relative" 
                                                style="width: {{ $total > 0 ? ($career->students_count / $total) * 100 : 0 }}%">
                                                <div class="absolute inset-0 bg-white/20 animate-pulse-slow"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-gray-500">
                                         <div class="flex flex-col items-center justify-center">
                                             <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                             <p class="text-sm">No hay carreras registradas</p>
                                         </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($careers->count() > 0)
                        <tfoot>
                            <tr class="bg-black/20 border-t border-white/10">
                                <td colspan="3" class="py-4 px-6 text-white font-bold uppercase tracking-wider text-right">Total</td>
                                <td class="py-4 px-6 text-right text-blue-400 font-black text-lg">{{ $total }}</td>
                                <td class="py-4 px-6 text-right text-gray-400 font-bold">100%</td>
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
            <script>
                window.careerData = @json($careers->where('students_count', '>', 0)->sortByDesc('students_count')->take(10)->values());
            </script>
            @vite('resources/js/pages/reports-by-career.js')
        @endpush
    @endif
</x-app-layout>
