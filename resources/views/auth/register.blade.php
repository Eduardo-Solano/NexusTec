<x-guest-layout :hideLogo="true">
    <div class="flex justify-center items-center gap-6 mb-6">
        <img src="{{ asset('img/logo-tecnm.png') }}" class="h-10 w-auto filter brightness-0 invert opacity-90"
            alt="TecNM">
        <div class="h-8 w-px bg-gray-600"></div>
        <img src="{{ asset('img/logo-ito.png') }}" class="h-12 w-auto drop-shadow-lg" alt="ITO">
    </div>

    <h2 class="text-center text-2xl font-bold text-white mb-2">
        Registro en <span class="text-ito-orange">NexusTec</span>
    </h2>
    <p class="text-center text-gray-400 text-sm mb-6">Registro exclusivo para estudiantes del TecNM</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf
        
        {{-- Campo oculto para el rol --}}
        <input type="hidden" name="role" value="student">

        <div class="space-y-4">
            {{-- Nombre --}}
            <div>
                <x-input-label for="name" :value="__('Nombre Completo')" class="text-gray-300" />
                <x-text-input id="name"
                    class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg"
                    type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            {{-- Teléfono --}}
            <div>
                <x-input-label for="phone" :value="__('Teléfono')" class="text-gray-300" />
                <x-text-input id="phone"
                    class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg"
                    type="text" name="phone" :value="old('phone')" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>
        </div>

        {{-- Datos Académicos --}}
        <div class="mt-6 space-y-4 border-t border-gray-700 pt-4">
            <h3 class="text-orange-400 text-sm font-bold uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Datos Académicos
            </h3>

            {{-- Número de Control --}}
            <div>
                <x-input-label for="control_number" :value="__('Número de Control')" class="text-gray-300" />
                <x-text-input id="control_number"
                    class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg"
                    type="text" name="control_number" :value="old('control_number')" required 
                    maxlength="10"
                    placeholder="Ej: 19161234, L1916123"
                    oninput="updateEmail(this.value)" />
                <p class="mt-1 text-xs text-gray-500">Puede incluir 0-2 letras al inicio + dígitos</p>
                <x-input-error :messages="$errors->get('control_number')" class="mt-2" />
            </div>

            {{-- Email (generado automáticamente) --}}
            <div>
                <x-input-label for="email" :value="__('Correo Institucional')" class="text-gray-300" />
                <x-text-input id="email"
                    class="block mt-1 w-full bg-gray-800 border-gray-600 text-gray-400 rounded-lg cursor-not-allowed"
                    type="email" name="email" :value="old('email')" required readonly />
                <p class="mt-1 text-xs text-gray-500">Se genera automáticamente: número_control@itoaxaca.edu.mx</p>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Carrera --}}
            <div>
                <x-input-label for="career_id" :value="__('Carrera')" class="text-gray-300" />
                <select name="career_id" id="career_id" required
                    class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg">
                    <option value="">-- Selecciona tu carrera --</option>
                    @foreach ($careers as $career)
                        <option value="{{ $career->id }}" {{ old('career_id') == $career->id ? 'selected' : '' }}>
                            {{ $career->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('career_id')" class="mt-2" />
            </div>

            {{-- Semestre --}}
            <div>
                <x-input-label for="semester" :value="__('Semestre')" class="text-gray-300" />
                <select name="semester" id="semester" required
                    class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg">
                    <option value="">-- Selecciona tu semestre --</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>
                            {{ $i }}° Semestre
                        </option>
                    @endfor
                </select>
                <x-input-error :messages="$errors->get('semester')" class="mt-2" />
            </div>
        </div>

        {{-- Contraseña --}}
        <div class="mt-6 space-y-4 border-t border-gray-700 pt-4">
            <h3 class="text-orange-400 text-sm font-bold uppercase tracking-wider flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Seguridad
            </h3>

            <div>
                <x-input-label for="password" :value="__('Contraseña')" class="text-gray-300" />
                <div class="relative">
                    <x-text-input id="password"
                        class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg pr-10"
                        type="password" name="password" required />
                    <button type="button" onclick="togglePassword('password')"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-orange-500">
                        <svg id="eye-icon-password" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg id="eye-slash-icon-password" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-gray-300" />
                <div class="relative">
                    <x-text-input id="password_confirmation"
                        class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg pr-10"
                        type="password" name="password_confirmation" required />
                    <button type="button" onclick="togglePassword('password_confirmation')"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-orange-500">
                        <svg id="eye-icon-password_confirmation" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg id="eye-slash-icon-password_confirmation" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <script>
            function togglePassword(fieldId) {
                const field = document.getElementById(fieldId);
                const eyeIcon = document.getElementById('eye-icon-' + fieldId);
                const eyeSlashIcon = document.getElementById('eye-slash-icon-' + fieldId);

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

            // Función para actualizar el email basado en el número de control
            function updateEmail(controlNumber) {
                const emailField = document.getElementById('email');
                // Permitir letras al inicio (máx 2) + números (máx 8), total máximo 10
                controlNumber = controlNumber.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 10);
                
                // Validar formato: 0-2 letras seguidas de números
                const match = controlNumber.match(/^([A-Z]{0,2})(\d*)$/);
                if (match) {
                    const letters = match[1] || '';
                    const numbers = match[2] || '';
                    controlNumber = letters + numbers;
                }
                
                document.getElementById('control_number').value = controlNumber;
                
                // Generar email si tiene al menos 8 caracteres válidos
                if (controlNumber.length >= 8) {
                    emailField.value = controlNumber.toLowerCase() + '@itoaxaca.edu.mx';
                } else {
                    emailField.value = '';
                }
            }

            // Inicializar email si hay valor previo en control_number
            document.addEventListener('DOMContentLoaded', function() {
                const controlNumber = document.getElementById('control_number').value;
                if (controlNumber) {
                    updateEmail(controlNumber);
                }
            });
        </script>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-400 hover:text-white" href="{{ route('login') }}">
                {{ __('¿Ya tienes cuenta?') }}
            </a>
            <button type="submit"
                class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition transform hover:scale-105">
                {{ __('Verificar Correo') }}
            </button>
        </div>
    </form>

    {{-- Nota informativa --}}
    <div class="mt-6 p-4 bg-gray-800/50 border border-gray-700 rounded-lg">
        <p class="text-gray-400 text-xs text-center">
            <span class="text-orange-400 font-bold">Nota:</span> El registro de jueces y personal administrativo 
            es gestionado exclusivamente por los organizadores del evento.
        </p>
        <p class="text-gray-500 text-xs text-center mt-2">
            Se enviará un código de verificación a tu correo institucional.
        </p>
    </div>
</x-guest-layout>
