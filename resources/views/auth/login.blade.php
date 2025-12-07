<x-guest-layout :hideLogo="true">
        <div class="flex justify-center items-center gap-8 mb-10">
           <img src="{{ asset('img/logo-tecnm.png') }}"
               class="h-14 w-auto filter brightness-0 invert opacity-90"
               alt="TecNM">

           <div class="h-12 w-px bg-gray-600"></div>

           <img src="{{ asset('img/logo-ito.png') }}"
               class="h-16 w-auto"
               alt="ITO">
        </div>

    <h2 class="text-center text-3xl font-extrabold text-white mb-2 tracking-tight">
        Bienvenido a <span class="text-ito-orange">NexusTec</span>
    </h2>
    <p class="text-center text-gray-500 dark:text-gray-400 text-sm mb-6">
        Gestión de Eventos Académicos
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Correo Institucional')" class="text-tecnm-blue dark:text-gray-300 font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg" 
                          type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" class="text-tecnm-blue dark:text-gray-300 font-semibold" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full bg-gray-900 border-gray-700 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg pr-10"
                                type="password" name="password" required />
                <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400">
                    <svg id="eye-icon-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg id="eye-slash-icon-password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
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
        </script>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-ito-orange shadow-sm focus:ring-ito-orange" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Recordarme') }}</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 dark:text-gray-400 hover:text-ito-orange rounded-md focus:outline-none" href="{{ route('password.request') }}">
                    {{ __('¿Olvidaste tu contraseña?') }}
                </a>
            @endif
        </div>

        <div class="flex items-center justify-center mt-6">
            <button class="w-full justify-center bg-ito-orange hover:bg-orange-600 text-white font-bold py-3 px-4 rounded">
                {{ __('Iniciar Sesión') }}
            </button>
        </div>
        
        <div class="mt-4 text-center">
             <a href="{{ route('register') }}" class="text-sm text-tecnm-blue dark:text-gray-300 hover:underline">
                ¿No tienes cuenta? Regístrate
             </a>
        </div>
    </form>
</x-guest-layout>