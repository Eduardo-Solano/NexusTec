<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 shadow-2xl rounded-2xl p-8">
                
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-white">Editar Alumno</h1>
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $student->is_active ? 'bg-green-500/20 text-green-400 border border-green-500/30' : 'bg-red-500/20 text-red-400 border border-red-500/30' }}">
                        {{ $student->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>

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

                <form action="{{ route('students.update', $student) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <x-input-label for="name" :value="__('Nombre Completo')" class="text-white" />
                        <x-text-input id="name" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('name') border-red-500 @enderror" type="text" name="name" :value="old('name', $student->name)" required />
                        @error('name')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Correo Institucional')" class="text-white" />
                        <x-text-input id="email" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('email') border-red-500 @enderror" type="email" name="email" :value="old('email', $student->email)" required />
                        @error('email')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="control_number" :value="__('No. Control')" class="text-white" />
                            <x-text-input id="control_number" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('control_number') border-red-500 @enderror" type="text" name="control_number" :value="old('control_number', $student->studentProfile->control_number ?? '')" required placeholder="Ej: CTRL-005"/>
                            @error('control_number')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <x-input-label for="semester" :value="__('Semestre')" class="text-white" />
                            <select name="semester" id="semester" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-md shadow-sm focus:border-ito-orange focus:ring-ito-orange @error('semester') border-red-500 @enderror">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" @if(old('semester', $student->studentProfile->semester ?? '') == $i) selected @endif>{{ $i }}° Semestre</option>
                                @endfor
                            </select>
                            @error('semester')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <x-input-label for="career_id" :value="__('Carrera')" class="text-white" />
                        <select name="career_id" id="career_id" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-md shadow-sm focus:border-ito-orange focus:ring-ito-orange @error('career_id') border-red-500 @enderror">
                            <option value="">Seleccionar carrera</option>
                            @foreach ($careers as $career)
                                <option value="{{ $career->id }}" @if(old('career_id', $student->studentProfile->career_id ?? '') == $career->id) selected @endif>{{ $career->name }}</option>                                  
                            @endforeach
                        </select>
                        @error('career_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Teléfono (opcional)')" class="text-white" />
                        <x-text-input id="phone" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('phone') border-red-500 @enderror" type="text" name="phone" :value="old('phone', $student->phone)" placeholder="Ej: 951 123 4567"/>
                        @error('phone')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-gray-900 rounded-lg border border-gray-700">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $student->is_active) ? 'checked' : '' }} class="w-5 h-5 rounded bg-gray-800 border-gray-600 text-ito-orange focus:ring-ito-orange">
                        <x-input-label for="is_active" :value="__('Alumno activo')" class="text-white cursor-pointer" />
                    </div>

                    {{-- Sección para cambiar contraseña (opcional) --}}
                    <div class="border-t border-gray-700 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Cambiar Contraseña <span class="text-sm text-gray-500 font-normal">(dejar en blanco para mantener la actual)</span></h3>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="password" :value="__('Nueva Contraseña')" class="text-white" />
                                <x-text-input id="password" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('password') border-red-500 @enderror" type="password" name="password" autocomplete="new-password" />
                                @error('password')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-white" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white" type="password" name="password_confirmation" autocomplete="new-password" />
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <a href="{{ route('students.index') }}" class="px-4 py-2 text-gray-400 hover:text-white transition">Cancelar</a>
                        <button type="submit" class="bg-ito-orange hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            Actualizar Alumno
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
