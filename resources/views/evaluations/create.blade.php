<x-app-layout>
    <div class="min-h-screen bg-[#0B1120] py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('projects.show', $project) }}" class="text-gray-400 hover:text-white transition flex items-center gap-2 text-sm font-bold">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Cancelar Evaluación
                </a>
            </div>

            <div class="grid lg:grid-cols-3 gap-8" 
                 x-data="{ 
                    scores: {}, 
                    criteria: {{ $criteria->toJson() }},
                    
                    get total() {
                        let sum = 0;
                        for (let key in this.scores) {
                            sum += parseInt(this.scores[key]) || 0;
                        }
                        return sum;
                    },
                    
                    get maxTotal() {
                        return this.criteria.reduce((sum, c) => sum + c.max_points, 0);
                    }
                 }">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-8 shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-500 to-orange-600"></div>
                        
                        <div class="mb-8">
                            <h2 class="text-2xl font-black text-white">Rúbrica de Evaluación</h2>
                            <p class="text-gray-400 text-sm mt-1">
                                Evaluando a: <strong class="text-white">{{ $project->name }}</strong>
                            </p>
                        </div>

                        <form action="{{ route('evaluations.store') }}" method="POST" id="evalForm">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">

                            <div class="space-y-6">
                                @foreach($criteria as $criterion)
                                    <div class="bg-gray-900/50 rounded-xl p-6 border border-gray-700 hover:border-gray-500 transition">
                                        <div class="flex justify-between items-end mb-4">
                                            <div>
                                                <h3 class="text-lg font-bold text-white">{{ $criterion->name }}</h3>
                                                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">
                                                    Puntaje Máximo: <span class="text-ito-orange">{{ $criterion->max_points }}</span>
                                                </p>
                                            </div>
                                            <div class="w-24">
                                                <input type="number" 
                                                       name="scores[{{ $criterion->id }}]" 
                                                       x-model.number="scores[{{ $criterion->id }}]"
                                                       min="0" 
                                                       max="{{ $criterion->max_points }}" 
                                                       class="w-full bg-gray-800 border border-gray-600 text-white text-center font-bold rounded-lg focus:ring-orange-500 focus:border-orange-500 p-2 text-lg"
                                                       required>
                                            </div>
                                        </div>

                                        <input type="range" 
                                               x-model.number="scores[{{ $criterion->id }}]" 
                                               min="0" 
                                               max="{{ $criterion->max_points }}" 
                                               step="1"
                                               class="w-full h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer accent-orange-500 hover:accent-orange-400">
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-8 pt-8 border-t border-gray-700">
                                <label class="block text-sm font-bold text-gray-300 mb-2">Retroalimentación para el equipo (Opcional)</label>
                                <textarea name="feedback" rows="4" 
                                    class="w-full bg-gray-900 border-gray-600 text-white rounded-xl focus:ring-orange-500 focus:border-orange-500 placeholder-gray-600 p-4"
                                    placeholder="Escribe tus comentarios, fortalezas y áreas de oportunidad..."></textarea>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit" onclick="return confirm('¿Estás seguro de enviar esta evaluación? No podrás editarla después.')"
                                        class="px-8 py-4 bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-500 hover:to-orange-500 text-white font-black rounded-xl shadow-lg shadow-orange-900/20 transform hover:-translate-y-1 transition-all flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Enviar Evaluación Final
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                <div class="lg:col-span-1 space-y-6">
                    
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-xl sticky top-8">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-6 text-center">Puntaje Total</h3>
                        
                        <div class="flex items-center justify-center mb-6">
                            <div class="relative w-40 h-40 flex items-center justify-center rounded-full border-8 border-gray-700 bg-gray-900 shadow-inner">
                                <div class="text-center">
                                    <span class="text-5xl font-black text-white block" x-text="total">0</span>
                                    <span class="text-xs text-gray-400 font-bold uppercase block mt-1">de <span x-text="maxTotal"></span> pts</span>
                                </div>
                            </div>
                        </div>

                        <div class="text-center space-y-2">
                            <div class="h-2 w-full bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full transition-all duration-500 ease-out"
                                     :class="total >= (maxTotal * 0.7) ? 'bg-green-500' : (total >= (maxTotal * 0.5) ? 'bg-yellow-500' : 'bg-red-500')"
                                     :style="'width: ' + ((total / maxTotal) * 100) + '%'"></div>
                            </div>
                            <p class="text-xs text-gray-500 pt-2">Calificación Final</p>
                        </div>

                        <div class="border-t border-gray-700 mt-6 pt-6">
                            <div class="bg-blue-900/20 p-4 rounded-xl border border-blue-500/20">
                                <p class="text-blue-300 text-xs font-bold uppercase mb-1">Recordatorio</p>
                                <p class="text-blue-100 text-xs">
                                    Asegúrate de ser objetivo. Una vez enviada, la calificación es definitiva.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>