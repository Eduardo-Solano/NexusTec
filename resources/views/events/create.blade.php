<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nuevo Evento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('events.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nombre del Evento')" class="text-gray-300" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-gray-900 border-gray-600 text-white focus:border-ito-orange focus:ring-ito-orange" :value="old('name')" required autofocus placeholder="Ej: HackTec 2025" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Descripción')" class="text-gray-300" />
                            <textarea id="description" name="description" rows="4" 
                                class="mt-1 block w-full bg-gray-900 border-gray-600 text-white focus:border-ito-orange focus:ring-ito-orange rounded-md shadow-sm"
                                placeholder="Describe el objetivo, las reglas básicas, etc.">{{ old('description') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
                            <div>
                                <x-input-label for="start_date" :value="__('Fecha y Hora de Inicio')" class="text-gray-300" />
                                
                                <x-text-input id="start_date" name="start_date" type="datetime-local" 
                                    style="color-scheme: dark;"
                                    class="mt-1 block w-full bg-gray-900 border-gray-600 text-white focus:border-ito-orange focus:ring-ito-orange" 
                                    :value="old('start_date')" required />
                                    
                                <x-input-error class="mt-2" :messages="$errors->get('start_date')" />
                            </div>

                            <div>
                                <x-input-label for="end_date" :value="__('Fecha y Hora de Cierre')" class="text-gray-300" />
                                
                                <x-text-input id="end_date" name="end_date" type="datetime-local" 
                                    style="color-scheme: dark;"
                                    class="mt-1 block w-full bg-gray-900 border-gray-600 text-white focus:border-ito-orange focus:ring-ito-orange" 
                                    :value="old('end_date')" required />
                                    
                                <x-input-error class="mt-2" :messages="$errors->get('end_date')" />
                            </div>
                        </div>
                        <div class="mt-8 pt-6 border-t border-gray-700">
                            <h3 class="text-lg font-bold text-white mb-2">Rúbrica de Evaluación</h3>
                            <p class="text-gray-400 text-sm mb-6">Selecciona los criterios que los jueces utilizarán para calificar este evento.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($criteria as $criterion)
                                    <label class="relative flex items-start p-4 rounded-xl border border-gray-700 bg-gray-800/50 cursor-pointer hover:bg-gray-800 hover:border-ito-orange transition group">
                                        <div class="flex items-center h-5">
                                            <input type="checkbox" name="criteria[]" value="{{ $criterion->id }}" 
                                                class="w-5 h-5 rounded border-gray-600 bg-gray-900 text-ito-orange focus:ring-ito-orange focus:ring-offset-gray-900">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <span class="font-bold text-white group-hover:text-ito-orange transition">{{ $criterion->name }}</span>
                                            <p class="text-gray-500 text-xs mt-1">Valor Máximo: <span class="text-gray-300">{{ $criterion->max_points }} pts</span></p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('criteria')" />
                        </div>
                        <div class="flex items-center justify-end gap-4 border-t border-gray-700 pt-6">
                            <a href="{{ route('events.index') }}" class="text-sm text-gray-400 hover:text-white transition">
                                Cancelar
                            </a>
                            
                            <button type="submit" class="bg-ito-orange hover:bg-orange-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
                                Guardar Evento
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>