<x-guest-layout>
    <div class="flex justify-center items-center gap-6 mb-6">
        <img src="{{ asset('img/logo-tecnm.png') }}" class="h-10 w-auto filter brightness-0 invert opacity-90" alt="TecNM">
        <div class="h-8 w-px bg-gray-600"></div> 
        <img src="{{ asset('img/logo-ito.png') }}" class="h-12 w-auto drop-shadow-lg" alt="ITO">
    </div>

    <h2 class="text-center text-2xl font-bold text-white mb-6">
        Registro en <span class="text-ito-orange">NexusTec</span>
    </h2>

    <form method="POST" action="{{ route('register') }}" x-data="{ role: 'student' }">
        @csrf

        <div class="mb-6">
            <label class="block text-gray-300 font-bold mb-2">¿Cómo deseas participar?</label>
            <div class="grid grid-cols-2 gap-4">
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="student" x-model="role" class="peer sr-only">
                    <div class="rounded-lg border border-gray-600 bg-gray-800 p-4 text-center text-gray-400 hover:bg-gray-700 peer-checked:border-orange-500 peer-checked:bg-orange-900 peer-checked:text-white transition">
                        🎓 Estudiante
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="role" value="judge" x-model="role" class="peer sr-only">
                    <div class="rounded-lg border border-gray-600 bg-gray-800 p-4 text-center text-gray-400 hover:bg-gray-700 peer-checked:border-orange-500 peer-checked:bg-orange-900 peer-checked:text-white transition">
                        ⚖️ Juez Externo
                    </div>
                </label>
            </div>
        </div>

        <div class="space-y-4">
            <div>
                <x-input-label for="name" :value="__('Nombre Completo')" class="text-gray-300" />
                <x-text-input id="name" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg" type="text" name="name" :value="old('name')" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" class="text-gray-300" />
                <x-text-input id="email" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            
            <div>
                <x-input-label for="phone" :value="__('Teléfono / WhatsApp')" class="text-gray-300" />
                <x-text-input id="phone" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg" type="text" name="phone" :value="old('phone')" required />
            </div>
        </div>

        <div x-show="role === 'student'" class="mt-4 space-y-4 border-t border-gray-700 pt-4" x-transition>
            <h3 class="text-orange-400 text-sm font-bold uppercase tracking-wider">Datos Académicos</h3>
            
            <div>
                <x-input-label for="control_number" :value="__('Número de Control')" class="text-gray-300" />
                <x-text-input id="control_number" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg" type="text" name="control_number" :value="old('control_number')" />
            </div>

            <div>
                <x-input-label for="career_id" :value="__('Carrera')" class="text-gray-300" />
                <select name="career_id" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg">
                    <option value="">-- Selecciona tu carrera --</option>
                    @foreach($careers as $career)
                        <option value="{{ $career->id }}">{{ $career->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <x-input-label for="semester" :value="__('Semestre')" class="text-gray-300" />
                <x-text-input id="semester" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg" type="number" min="1" max="14" name="semester" />
            </div>
        </div>

        <div x-show="role === 'judge'" class="mt-4 space-y-4 border-t border-gray-700 pt-4" x-transition style="display: none;">
            <h3 class="text-orange-400 text-sm font-bold uppercase tracking-wider">Perfil Profesional</h3>
            
            <div>
                <x-input-label for="company" :value="__('Empresa / Institución')" class="text-gray-300" />
                <x-text-input id="company" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg" type="text" name="company" />
            </div>

            <div>
                <x-input-label for="specialty_id" :value="__('Especialidad Principal')" class="text-gray-300" />
                <select name="specialty_id" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg">
                    <option value="">-- Selecciona área de dominio --</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-6 space-y-4 border-t border-gray-700 pt-4">
            <div>
                <x-input-label for="password" :value="__('Contraseña')" class="text-gray-300" />
                <x-text-input id="password" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg" type="password" name="password" required />
            </div>
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-gray-300" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg" type="password" name="password_confirmation" required />
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-400 hover:text-white" href="{{ route('login') }}">
                {{ __('¿Ya tienes cuenta?') }}
            </a>
            <button class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
                {{ __('Completar Registro') }}
            </button>
        </div>
    </form>
</x-guest-layout>