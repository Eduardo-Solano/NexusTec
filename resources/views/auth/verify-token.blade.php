<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Hemos enviado un código de seguridad a tu correo. Ingrésalo a continuación para continuar.') }}
    </div>

    <form method="GET" action="{{ route('password.reset.verify') }}">
        
        <input type="hidden" name="email" value="{{ request()->email }}">

        <div class="mt-4">
            <x-input-label for="token" :value="__('Código / Token')" />
            <x-text-input id="token" 
                          class="block mt-1 w-full font-mono text-center tracking-widest uppercase" 
                          type="text" 
                          name="token" 
                          required 
                          autofocus
                          placeholder="PEGA EL CÓDIGO AQUÍ" />
            <x-input-error :messages="$errors->get('token')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verificar Código') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>