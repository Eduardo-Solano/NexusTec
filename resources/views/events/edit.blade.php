<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Evento') }}: <span class="text-ito-orange">{{ $event->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('events.update', $event) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT') <div>
                            <x-input-label for="name" :value="__('Nombre del Evento')" class="text-gray-300" />
                            <x-text-input id="name" name="name" type="text" 
                                class="mt-1 block w-full bg-gray-900 border-gray-600 text-white focus:border-ito-orange focus:ring-ito-orange" 
                                :value="old('name', $event->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descripción')" class="text-gray-300" />
                            <textarea id="description" name="description" rows="4" 
                                class="mt-1 block w-full bg-gray-900 border-gray-600 text-white focus:border-ito-orange focus:ring-ito-orange rounded-md shadow-sm"
                                >{{ old('description', $event->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="start_date" :value="__('Fecha de Inicio')" class="text-gray-300" />
                                <x-text-input id="start_date" name="start_date" type="datetime-local" style="color-scheme: dark;"
                                    class="mt-1 block w-full bg-gray-900 border-gray-600 text-white focus:border-ito-orange focus:ring-ito-orange" 
                                    :value="old('start_date', $event->start_date->format('Y-m-d\TH:i'))" required />
                                <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                            </div>

                            <div>
                                <x-input-label for="registration_deadline" :value="__('Cierre de Inscripciones')" class="text-gray-300" />
                                <x-text-input id="registration_deadline" name="registration_deadline" type="datetime-local" style="color-scheme: dark;"
                                    class="mt-1 block w-full bg-gray-900 border-gray-600 text-white focus:border-ito-orange focus:ring-ito-orange" 
                                    :value="old('registration_deadline', $event->registration_deadline?->format('Y-m-d\TH:i'))" required />
                                <p class="text-xs text-gray-500 mt-1">Después de esta fecha → "En Curso"</p>
                                <x-input-error class="mt-2" :messages="$errors->get('registration_deadline')" />
                            </div>

                            <div>
                                <x-input-label for="end_date" :value="__('Fecha de Cierre')" class="text-gray-300" />
                                <x-text-input id="end_date" name="end_date" type="datetime-local" style="color-scheme: dark;"
                                    class="mt-1 block w-full bg-gray-900 border-gray-600 text-white focus:border-ito-orange focus:ring-ito-orange" 
                                    :value="old('end_date', $event->end_date->format('Y-m-d\TH:i'))" required />
                                <p class="text-xs text-gray-500 mt-1">Después de esta fecha → "Finalizado"</p>
                                <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                            </div>
                        </div>
                        
                        <div class="block">
                            <x-input-label for="status" :value="__('Estado del Evento (Manual)')" class="text-gray-300 mb-2" />
                            <select id="status" name="status" class="mt-1 block w-full rounded-md bg-gray-900 border-gray-600 text-white focus:border-ito-orange focus:ring-ito-orange">
                                <option value="registration" {{ $event->status === 'registration' ? 'selected' : '' }}>📝 Inscripciones Abiertas</option>
                                <option value="active" {{ $event->status === 'active' ? 'selected' : '' }}>🚀 En Curso</option>
                                <option value="closed" {{ $event->status === 'closed' ? 'selected' : '' }}>🏆 Finalizado</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                El estado cambia automáticamente según las fechas. Usa esta opción solo para forzar un cambio manual.
                            </p>
                        </div>

                        <div class="block">
                            <label for="show_feedback_to_students" class="inline-flex items-center">
                                <input id="show_feedback_to_students" type="checkbox" class="rounded border-gray-700 bg-gray-900 text-green-500 shadow-sm focus:ring-green-500" name="show_feedback_to_students" value="1" {{ $event->show_feedback_to_students ? 'checked' : '' }}>
                                <span class="ms-2 text-sm text-gray-300">{{ __('Mostrar retroalimentación a estudiantes') }}</span>
                            </label>
                            <p class="mt-1 text-xs text-gray-500 ml-7">Cuando está activado, los estudiantes pueden ver los comentarios de los jueces en sus proyectos evaluados.</p>
                        </div>
                        <div class="mt-8 pt-6 border-t border-gray-700">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-white mb-2">Rúbrica de Evaluación</h3>
                                <div class="bg-gray-800 border border-gray-600 px-3 py-1 rounded-lg">
                                    <span class="text-sm text-gray-400">Total Puntos:</span>
                                    <span id="total_points_counter" class="font-bold text-lg text-white">0</span>
                                    <span class="text-sm text-gray-500">/ 100</span>
                                </div>
                            </div>
                            <p class="text-gray-400 text-sm mb-6">Selecciona los criterios que los jueces utilizarán para calificar este evento.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($criteria as $criterion)
                                    <label class="relative flex items-start p-4 rounded-xl border border-gray-700 bg-gray-800/50 cursor-pointer hover:bg-gray-800 hover:border-ito-orange transition group">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="criteria[]" value="{{ $criterion->id }}" data-points="{{ $criterion->max_points }}" @checked($event->criteria->contains($criterion->id))
                                                class="criterion-checkbox w-5 h-5 rounded border-gray-600 bg-gray-900 text-ito-orange focus:ring-ito-orange focus:ring-offset-gray-900">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <span class="font-bold text-white group-hover:text-ito-orange transition">{{ $criterion->name }}</span>
                                            <p class="text-gray-500 text-xs mt-1">Valor Máximo: <span class="text-gray-300">{{ $criterion->max_points }} pts</span></p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <p id="points_error" class="hidden text-red-400 text-sm mt-2 font-bold max-w-full">
                                ⚠️ Has excedido el límite de 100 puntos. Por favor desmarca otros criterios.
                            </p>
                            <x-input-error class="mt-2" :messages="$errors->get('criteria')" />

                             <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const checkboxes = document.querySelectorAll('.criterion-checkbox');
                                    const counterDisplay = document.getElementById('total_points_counter');
                                    const errorMsg = document.getElementById('points_error');
                                    const submitBtn = document.querySelector('button[type="submit"]');
                                    const maxPoints = 100;

                                    function updateTotal() {
                                        let currentTotal = 0;
                                        checkboxes.forEach(cb => {
                                            if (cb.checked) {
                                                currentTotal += parseInt(cb.dataset.points || 0);
                                            }
                                        });

                                        counterDisplay.textContent = currentTotal;
                                        
                                        // Reset clases base
                                        counterDisplay.classList.remove('text-white', 'text-green-400', 'text-red-500', 'text-yellow-400');
                                        errorMsg.classList.add('hidden');
                                        
                                        if (currentTotal > maxPoints) {
                                            counterDisplay.classList.add('text-red-500');
                                            errorMsg.textContent = `⚠️ Excede el límite de 100 puntos (Actual: ${currentTotal}). Desmarca criterios.`;
                                            errorMsg.classList.remove('hidden');
                                            submitBtn.disabled = true;
                                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                                        } else if (currentTotal < maxPoints) {
                                            counterDisplay.classList.add('text-yellow-400');
                                            submitBtn.disabled = true;
                                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                                        } else { // Exactamente 100
                                            counterDisplay.classList.add('text-green-400');
                                            submitBtn.disabled = false;
                                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                                        }
                                        return currentTotal;
                                    }

                                    checkboxes.forEach(checkbox => {
                                        checkbox.addEventListener('change', function(e) {
                                            let currentTotal = 0;
                                            checkboxes.forEach(cb => {
                                                if (cb.checked) {
                                                    currentTotal += parseInt(cb.dataset.points || 0);
                                                }
                                            });

                                            if (currentTotal > maxPoints) {
                                                e.preventDefault();
                                                this.checked = false; // Revertir cambio
                                                
                                                // Mostrar mensaje ERROR en la vista, NO alert
                                                errorMsg.textContent = `⚠️ No puedes agregar ese criterio. Excedería los 100 puntos.`;
                                                errorMsg.classList.remove('hidden');
                                                
                                                // Solución para mantener el mensaje visible temporalmente
                                                setTimeout(() => {
                                                    errorMsg.textContent = `⚠️ No puedes agregar ese criterio. Excedería los 100 puntos.`;
                                                    errorMsg.classList.remove('hidden');
                                                }, 50);
                                                
                                                updateTotal(); 
                                            } else {
                                                updateTotal();
                                            }
                                        });
                                    });

                                    // Init
                                    updateTotal();
                                });
                            </script>
                            <x-input-error class="mt-2" :messages="$errors->get('criteria')" />
                        </div>
                        <div class="flex items-center justify-end gap-4 border-t border-gray-700 pt-6">
                            <a href="{{ route('events.index') }}" class="text-sm text-gray-400 hover:text-white transition">Cancelar</a>
                            <button type="submit" class="bg-ito-orange hover:bg-orange-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
                                Actualizar Evento
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>