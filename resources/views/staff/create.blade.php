<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 border border-gray-700 shadow-2xl rounded-2xl p-8">
                
                <h1 class="text-2xl font-bold text-white mb-6">Registrar Nuevo Docente</h1>

                <form action="{{ route('staff.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <x-input-label for="name" :value="__('Nombre Completo')" class="text-white" />
                        <x-text-input id="name" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white" type="text" name="name" required />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Correo Institucional')" class="text-white" />
                        <x-text-input id="email" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white" type="email" name="email" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="employee_number" :value="__('No. Empleado')" class="text-white" />
                            <x-text-input id="employee_number" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white" type="text" name="employee_number" required placeholder="Ej: EMP-005"/>
                        </div>
                        <div>
                            <x-input-label for="department" :value="__('Departamento')" class="text-white" />
                            <select name="department" class="block mt-1 w-full bg-gray-900 border-gray-600 text-white rounded-md shadow-sm focus:border-ito-orange focus:ring-ito-orange">
                                <option>Sistemas y Computación</option>
                                <option>Ciencias Básicas</option>
                                <option>Industrial</option>
                                <option>Económico-Administrativo</option>
                            </select>
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