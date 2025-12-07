<section>
    <header class="flex items-center gap-4 mb-8">
        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-2xl shadow-inner ring-1 ring-blue-500/10">
            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100 tracking-tight">
                {{ __('Información del Perfil') }}
            </h2>
            <p class="text-base text-gray-500 dark:text-gray-400 font-medium">
                {{ __('Actualiza tu información personal y correo.') }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div class="group">
            <x-input-label for="name" :value="__('Nombre Completo')" class="text-gray-700 dark:text-gray-300 font-bold mb-1 ml-1" />
            <div class="relative transition-all duration-300 transform group-focus-within:scale-[1.01]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <x-text-input id="name" name="name" type="text" class="block w-full pl-12 py-3 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 rounded-xl transition-all" :value="old('name', $user->name)" required autofocus autocomplete="name" placeholder="Tu nombre..." />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="group">
            <x-input-label for="email" :value="__('Correo Electrónico')" class="text-gray-700 dark:text-gray-300 font-bold mb-1 ml-1" />
            <div class="relative transition-all duration-300 transform group-focus-within:scale-[1.01]">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <x-text-input id="email" name="email" type="email" class="block w-full pl-12 py-3 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:ring-4 focus:ring-blue-100 dark:focus:ring-blue-900/30 rounded-xl transition-all" :value="old('email', $user->email)" required autocomplete="username" placeholder="ejemplo@correo.com" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-900/20 rounded-2xl border border-amber-200 dark:border-amber-800 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-amber-500/10 rounded-full blur-xl"></div>
                    <p class="text-sm text-amber-800 dark:text-amber-200 flex items-center gap-2 font-medium relative z-10">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        {{ __('Tu correo no está verificado.') }}
                    </p>

                    <button form="send-verification" class="mt-3 px-4 py-2 text-xs font-bold bg-amber-100 hover:bg-amber-200 text-amber-800 rounded-lg transition-colors focus:outline-none relative z-10">
                        {{ __('Reenviar correo de verificación') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-3 font-bold text-xs text-green-600 dark:text-green-400 flex items-center gap-1 relative z-10">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Enlace enviado correctamente.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-6">
            <x-primary-button class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 border-0 shadow-lg shadow-blue-500/30 px-8 py-3 rounded-xl text-sm font-bold tracking-wide transform hover:-translate-y-0.5 transition-all duration-200">
                {{ __('Guardar Cambios') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-init="setTimeout(() => show = false, 2000)"
                    class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400 font-bold bg-green-50 dark:bg-green-900/20 px-4 py-2 rounded-lg border border-green-200 dark:border-green-800"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Guardado correctamente') }}
                </div>
            @endif
        </div>
    </form>
</section>