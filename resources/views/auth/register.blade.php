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
                <x-input-label for="phone" :value="__('Teléfono')" class="text-gray-300" />
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
                <div class="relative">
                    <x-text-input id="password" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg pr-10" type="password" name="password" required />
                    <button type="button" onclick="togglePasswordRegister('password', 'password')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-orange-500">
                        <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg id="eye-slash-icon-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-gray-300" />
                <div class="relative">
                    <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg pr-10" type="password" name="password_confirmation" required />
                    <button type="button" onclick="togglePasswordRegister('password_confirmation', 'password_confirmation')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-orange-500">
                        <svg id="eye-icon-password_confirmation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg id="eye-slash-icon-password_confirmation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <script>
            function togglePasswordRegister(fieldId, iconSuffix) {
                const field = document.getElementById(fieldId);
                const eyeIcon = document.getElementById('eye-icon-' + iconSuffix);
                const eyeSlashIcon = document.getElementById('eye-slash-icon-' + iconSuffix);
                
                if (field.type === 'password') {
                    field.type = 'text';
                    eyeIcon.classList.add('hidden');
                    eyeSlashIcon.classList.remove('hidden');
                } else {
                    field.type = 'password';
                    eyeIcon.classList.remove('hidden');
                    eyeSlashIcon.classList.add('hidden');
                }
            }
        </script>

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