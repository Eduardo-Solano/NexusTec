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
                        <x-text-input id="name" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('name') border-red-500 @enderror" type="text" name="name" :value="old('name')" required />
                        @error('name')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Correo Institucional')" class="text-white" />
                        <x-text-input id="email" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('email') border-red-500 @enderror" type="email" name="email" :value="old('email')" required />
                        @error('email')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="employee_number" :value="__('No. Empleado')" class="text-white" />
                            <x-text-input id="employee_number" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('employee_number') border-red-500 @enderror" type="text" name="employee_number" :value="old('employee_number')" required placeholder="Ej: EMP-005"/>
                            @error('employee_number')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <x-input-label for="department" :value="__('Departamento')" class="text-white" />
                            <select name="department" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-md shadow-sm focus:border-ito-orange focus:ring-ito-orange @error('department') border-red-500 @enderror">
                                <option value="">Seleccionar departamento</option>
                                <option value="Sistemas y Computación" @if(old('department') == 'Sistemas y Computación') selected @endif>Sistemas y Computación</option>
                                <option value="Ciencias Básicas" @if(old('department') == 'Ciencias Básicas') selected @endif>Ciencias Básicas</option>
                                <option value="Industrial" @if(old('department') == 'Industrial') selected @endif>Industrial</option>
                                <option value="Económico-Administrativo" @if(old('department') == 'Económico-Administrativo') selected @endif>Económico-Administrativo</option>
                            </select>
                            @error('department')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="password" :value="__('Contraseña')" class="text-white" />
                            <x-text-input id="password" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('password') border-red-500 @enderror" type="password" name="password" required />
                            @error('password')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-white" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('password_confirmation') border-red-500 @enderror" type="password" name="password_confirmation" required />
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