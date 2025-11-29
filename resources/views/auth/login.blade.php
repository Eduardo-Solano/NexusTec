<x-guest-layout>
    <div class="flex justify-center items-center gap-8 mb-10">
    
    <img src="{{ asset('img/logo-tecnm.png') }}" 
         class="h-14 w-auto filter brightness-0 invert opacity-90 transition duration-300" 
         alt="TecNM">
    
    <div class="h-12 w-px bg-gray-600"></div> 
    
    <img src="{{ asset('img/logo-ito.png') }}" 
         class="h-16 w-auto drop-shadow-lg hover:scale-105 transition duration-300" 
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
            <x-text-input id="email" class="block mt-1 w-full 
           bg-gray-900 border-gray-700 text-gray-200 
           focus:border-orange-500 focus:ring-orange-500 rounded-lg shadow-sm" 
                          type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" class="text-tecnm-blue dark:text-gray-300 font-semibold" />
            <x-text-input id="password" class="block mt-1 w-full 
           bg-gray-900 border-gray-700 text-gray-200 
           focus:border-orange-500 focus:ring-orange-500 rounded-lg shadow-sm"
                            type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

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
            <button class="w-full justify-center bg-ito-orange hover:bg-orange-600 text-white font-bold py-3 px-4 rounded shadow-lg transition duration-300 transform hover:scale-105">
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