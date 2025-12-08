<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 shadow-2xl rounded-2xl p-8">
                
                <h1 class="text-2xl font-bold text-white mb-6">Registrar Nuevo Docente</h1>

                @if ($errors->any())
                    <div class="mb-4 bg-red-900/30 border border-red-500/50 rounded-lg p-4">
                        <p class="text-red-400 font-bold mb-2">Errores de validación:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-red-300 text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('staff.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <x-input-label for="name" :value="__('Nombre Completo')" class="text-white" />
                        <x-text-input id="name" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('name') border-red-500 @enderror" type="text" name="name" :value="old('name')" required maxlength="255" />
                        @error('name')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Correo Institucional')" class="text-white" />
                        <x-text-input id="email" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('email') border-red-500 @enderror" type="email" name="email" :value="old('email')" required maxlength="255" autocomplete="email" />
                        @error('email')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="employee_number" :value="__('No. Empleado')" class="text-white" />
                            <x-text-input id="employee_number" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('employee_number') border-red-500 @enderror" type="text" name="employee_number" :value="old('employee_number')" required maxlength="255" placeholder="Ej: EMP-005"/>
                            @error('employee_number')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <x-input-label for="department" :value="__('Departamento')" class="text-white" />
                            <select name="department" required class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-md shadow-sm focus:border-ito-orange focus:ring-ito-orange @error('department') border-red-500 @enderror">
                                <option value="">Seleccionar departamento</option>
                                <option value="Sistemas y Computación" @if(old('department') == 'Sistemas y Computación') selected @endif>Sistemas y Computación</option>
                                <option value="Ciencias Básicas" @if(old('department') == 'Ciencias Básicas') selected @endif>Ciencias Básicas</option>
                                <option value="Industrial" @if(old('department') == 'Industrial') selected @endif>Industrial</option>
                                <option value="Económico-Administrativo" @if(old('department') == 'Económico-Administrativo') selected @endif>Económico-Administrativo</option>
                            </select>
                            @error('department')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Tipo de Staff --}}
                    <div>
                        <x-input-label :value="__('Tipo de Personal')" class="text-white mb-3" />
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="staff_type" value="advisor" class="peer sr-only" {{ old('staff_type', 'advisor') == 'advisor' ? 'checked' : '' }} required>
                                <div class="p-4 bg-gray-900 border-2 border-gray-600 rounded-xl text-center transition peer-checked:border-blue-500 peer-checked:bg-blue-500/10 hover:border-gray-500">
                                    <div class="text-2xl mb-2">👨‍🏫</div>
                                    <p class="text-white font-bold text-sm">Docente</p>
                                    <p class="text-gray-500 text-xs mt-1">Solo asesoría</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="staff_type" value="staff" class="peer sr-only" {{ old('staff_type') == 'staff' ? 'checked' : '' }}>
                                <div class="p-4 bg-gray-900 border-2 border-gray-600 rounded-xl text-center transition peer-checked:border-purple-500 peer-checked:bg-purple-500/10 hover:border-gray-500">
                                    <div class="text-2xl mb-2">🎯</div>
                                    <p class="text-white font-bold text-sm">Organizador</p>
                                    <p class="text-gray-500 text-xs mt-1">Gestión de eventos</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="staff_type" value="both" class="peer sr-only" {{ old('staff_type') == 'both' ? 'checked' : '' }}>
                                <div class="p-4 bg-gray-900 border-2 border-gray-600 rounded-xl text-center transition peer-checked:border-ito-orange peer-checked:bg-ito-orange/10 hover:border-gray-500">
                                    <div class="text-2xl mb-2">⭐</div>
                                    <p class="text-white font-bold text-sm">Ambos</p>
                                    <p class="text-gray-500 text-xs mt-1">Todos los permisos</p>
                                </div>
                            </label>
                        </div>
                        @error('staff_type')<p class="text-red-400 text-sm mt-2">{{ $message }}</p>@enderror
                        <p class="text-gray-500 text-xs mt-3">
                            <strong>Docente:</strong> Puede asesorar equipos y ver proyectos asignados.<br>
                            <strong>Organizador:</strong> Puede crear y gestionar eventos, equipos y criterios.<br>
                            <strong>Ambos:</strong> Tiene todos los permisos de docente y organizador.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="password" :value="__('Contraseña')" class="text-white" />
                            <x-text-input id="password" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('password') border-red-500 @enderror" type="password" name="password" minlength="8" autocomplete="new-password" />
                            @error('password')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-white" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('password_confirmation') border-red-500 @enderror" type="password" name="password_confirmation" minlength="8" autocomplete="new-password" />
                            @error('password_confirmation')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <a href="{{ route('staff.index') }}" class="px-4 py-2 text-gray-400 hover:text-white">Cancelar</a>
                        <button type="submit" class="bg-ito-orange hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            Guardar Docente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
