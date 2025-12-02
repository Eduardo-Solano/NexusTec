<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 shadow-2xl rounded-2xl p-8">

                <h1 class="text-2xl font-bold text-white mb-6">Registrar Nuevo Alumno</h1>

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

                <form action="{{ route('students.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Nombre Completo')" class="text-white" />
                        <x-text-input id="name"
                            class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('name') border-red-500 @enderror"
                            type="text" name="name" :value="old('name')" required />
                        @error('name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Correo Institucional')" class="text-white" />
                        <x-text-input id="email"
                            class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('email') border-red-500 @enderror"
                            type="email" name="email" :value="old('email')" required />
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="control_number" :value="__('No. Control')" class="text-white" />
                            <x-text-input id="control_number"
                                class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('control_number') border-red-500 @enderror"
                                type="text" name="control_number" :value="old('control_number')" required
                                placeholder="Ej: CTRL-005" />
                            @error('control_number')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <x-input-label for="major" :value="__('Carrera')" class="text-white" />
                            <select name="career_id"
                                class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-md shadow-sm focus:border-ito-orange focus:ring-ito-orange @error('career_id') border-red-500 @enderror">
                                <option value="">Seleccionar carrera</option>
                                @foreach ($careers as $career)
                                    <option value="{{ $career->id }}"
                                        @if (old('career_id') == $career->id) selected @endif>{{ $career->name }}</option>
                                @endforeach
                            </select>
                            @error('career_id')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="password" :value="__('Contraseña')" class="text-white" />
                            <x-text-input id="password"
                                class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('password') border-red-500 @enderror"
                                type="password" name="password" required />
                            @error('password')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-white" />
                            <x-text-input id="password_confirmation"
                                class="block mt-1 w-full bg-gray-900 border-gray-600 text-white @error('password_confirmation') border-red-500 @enderror"
                                type="password" name="password_confirmation" required />
                            @error('password_confirmation')
                                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <a href="{{ route('students.index') }}"
                            class="px-4 py-2 text-gray-400 hover:text-white">Cancelar</a>
                        <button type="submit"
                            class="bg-ito-orange hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            Guardar Alumno
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
