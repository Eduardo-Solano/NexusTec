<section class="space-y-6">
    <header class="flex items-center gap-4 mb-6">
        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-2xl shadow-inner ring-1 ring-red-500/10">
            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100 tracking-tight">
                {{ __('Eliminar Cuenta') }}
            </h2>
            <p class="text-base text-gray-500 dark:text-gray-400 font-medium">
                {{ __('Zona de peligro.') }}
            </p>
        </div>
    </header>

    <div class="p-6 bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl">
        <p class="text-sm font-medium text-red-800 dark:text-red-200 leading-relaxed">
            {{ __('Una vez que elimines tu cuenta, todos sus recursos y datos serán eliminados permanentemente. Antes de proceder, por favor descarga cualquier dato o información que desees conservar.') }}
        </p>
    </div>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="w-full justify-center py-4 text-lg font-bold bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 shadow-xl shadow-red-500/30 rounded-2xl transform hover:scale-[1.02] transition-all duration-3000"
    >{{ __('ELIMINAR MI CUENTA') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 relative overflow-hidden">
             <!-- Background decoration for modal -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-red-500/10 rounded-full blur-3xl"></div>

            @csrf
            @method('delete')

            <div class="flex items-center gap-4 mb-6 relative z-10">
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100">
                    {{ __('¿Estás absolutamente seguro?') }}
                </h2>
            </div>

            <p class="mt-1 text-base text-gray-600 dark:text-gray-400 relative z-10 leading-relaxed mb-6">
                {{ __('Esta acción no se puede deshacer. Se eliminarán permanentemente todos tus datos. Por favor, ingresa tu contraseña para confirmar.') }}
            </p>

            <div class="mt-6 relative z-10">
                <x-input-label for="password" value="{{ __('Contraseña') }}" class="sr-only" />

                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="block w-full pl-12 py-3 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-red-500"
                        placeholder="{{ __('Ingresa tu contraseña para confirmar...') }}"
                    />
                </div>

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end gap-3 relative z-10">
                <x-secondary-button x-on:click="$dispatch('close')" class="py-3 px-6 rounded-xl">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-danger-button class="bg-red-600 hover:bg-red-700 py-3 px-6 rounded-xl shadow-lg shadow-red-500/30">
                    {{ __('Sí, deseo eliminar mi cuenta') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>