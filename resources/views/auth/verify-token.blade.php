<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Hemos enviado un código de 8 caracteres a tu correo. Ingrésalo a continuación para continuar.') }}
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.reset.verify') }}">
        @csrf
        
        <input type="hidden" name="email" value="{{ request()->email }}">

        <div class="mt-4">
            <x-input-label for="code" :value="__('Código de recuperación')" />
            <x-text-input id="code" 
                          class="block mt-1 w-full font-mono text-center tracking-widest uppercase text-lg"
                          style="letter-spacing: 0.5em;"
                          type="text" 
                          name="code" 
                          maxlength="8"
                          required 
                          autofocus
                          placeholder="XXXXXXXX" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verificar Código') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>