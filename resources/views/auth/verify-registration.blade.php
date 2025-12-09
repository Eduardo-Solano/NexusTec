<x-guest-layout>
    <div class="flex justify-center items-center gap-6 mb-6">
        <img src="{{ asset('img/logo-tecnm.png') }}" class="h-10 w-auto filter brightness-0 invert opacity-90"
            alt="TecNM">
        <div class="h-8 w-px bg-gray-600"></div>
        <img src="{{ asset('img/logo-ito.png') }}" class="h-12 w-auto drop-shadow-lg" alt="ITO">
    </div>

    <h2 class="text-center text-2xl font-bold text-white mb-2">
        Verifica tu <span class="text-ito-orange">Correo</span>
    </h2>

    <div class="mb-4 text-sm text-gray-400 text-center">
        Hemos enviado un código de 8 caracteres a:<br>
        <span class="font-bold text-orange-400">{{ request()->email }}</span>
    </div>

    @if (session('status'))
        <div class="mb-4 p-3 bg-green-900/30 border border-green-700 rounded-lg text-sm font-medium text-green-400 text-center">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-6 p-4 bg-gray-800/50 border border-gray-700 rounded-lg">
        <p class="text-gray-400 text-xs text-center">
            <span class="text-orange-400 font-bold">Nota:</span> El registro de jueces y personal administrativo 
            es gestionado exclusivamente por los organizadores del evento.
        </p>
        <p class="text-gray-500 text-xs text-center mt-2">
            Se enviará un código de verificación a tu correo institucional.
        </p>
    </div>

    <form method="POST" action="{{ route('register.verify.submit') }}">
        @csrf
        
        <input type="hidden" name="email" value="{{ request()->email }}">

        <div class="mt-4">
            <x-input-label for="code" :value="__('Código de verificación')" class="text-gray-300" />
            <x-text-input id="code" 
                class="block mt-1 w-full bg-gray-900 border-gray-600 text-gray-200 focus:border-orange-500 focus:ring-orange-500 rounded-lg font-mono text-center text-xl tracking-[0.3em] uppercase"
                type="text" 
                name="code" 
                maxlength="8"
                required 
                autofocus
                placeholder="XXXXXXXX" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a href="{{ route('register') }}" class="text-sm text-gray-400 hover:text-orange-400 underline">
                ← Volver al registro
            </a>
            <x-primary-button class="bg-orange-600 hover:bg-orange-500">
                {{ __('Verificar y Registrar') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 pt-4 border-t border-gray-700 text-center">
        <p class="text-xs text-gray-500">
            El código expira en 30 minutos.<br>
            ¿No recibiste el correo? Revisa tu carpeta de spam.
        </p>
    </div>
</x-guest-layout>
