<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 shadow-2xl rounded-2xl p-8">
                <h1 class="text-2xl font-bold text-white mb-6">Registrar Nuevo Juez</h1>
                <form action="{{ route('judges.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Nombre Completo')" class="text-white" />
                        <x-text-input id="name" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white"
                            type="text" name="name" required maxlength="255" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Correo electronico')" class="text-white" />
                        <x-text-input id="email" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white"
                            type="email" name="email" required maxlength="255" autocomplete="email" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="password" :value="__('Contrasena')" class="text-white" />
                            <x-text-input id="password" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white"
                                type="password" name="password" required minlength="8" autocomplete="new-password" />
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirmar Contrasena')" class="text-white" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white"
                                type="password" name="password_confirmation" required minlength="8" autocomplete="new-password" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('Teléfono (opcional)')" class="text-white" />
                        <x-text-input id="phone" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white"
                            type="text" name="phone" maxlength="20" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="company" :value="__('Empresa / Institución')" class="text-white" />
                            <x-text-input id="company"
                                class="block mt-1 w-full bg-gray-900 border-gray-600 text-white" type="text"
                                name="company" maxlength="255" />
                        </div>
                        <div>
                            <x-input-label for="specialty_id" :value="__('Especialidad')" class="text-white" />
                            <select name="specialty_id"
                                class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-md shadow-sm focus:border-ito-orange focus:ring-ito-orange">
                                <option value="">-- Selecciona --</option>
                                @foreach ($specialties as $specialty)
                                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end gap-3">
                        <a href="{{ route('judges.index') }}"
                            class="px-4 py-2 text-gray-400 hover:text-white">Cancelar</a>
                        <button type="submit"
                            class="bg-ito-orange hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg transition">
                            Guardar Juez
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
