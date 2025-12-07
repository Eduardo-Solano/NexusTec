<section>
    <header class="flex items-center gap-4 mb-8">
        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-2xl shadow-inner ring-1 ring-purple-500/10">
             <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <div>
            <h2 class="text-2xl font-black text-gray-900 dark:text-gray-100 tracking-tight">
                {{ __('Actualizar Contraseña') }}
            </h2>
            <p class="text-base text-gray-500 dark:text-gray-400 font-medium">
                {{ __('Asegura tu cuenta con una clave segura.') }}
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="group">
            <x-input-label for="update_password_current_password" :value="__('Contraseña Actual')" class="text-gray-700 dark:text-gray-300 font-bold mb-1 ml-1" />
            <div class="relative transition-all duration-300 transform group-focus-within:scale-[1.01]">
                 <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-purple-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full pl-12 pr-12 py-3 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:ring-4 focus:ring-purple-100 dark:focus:ring-purple-900/30 rounded-xl transition-all" autocomplete="current-password" placeholder="••••••••" />
                <button type="button" onclick="togglePasswordUpdate('update_password_current_password')" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-purple-600 transition-colors focus:outline-none">
                    <svg id="eye-icon-update_password_current_password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg id="eye-slash-icon-update_password_current_password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="group">
            <x-input-label for="update_password_password" :value="__('Nueva Contraseña')" class="text-gray-700 dark:text-gray-300 font-bold mb-1 ml-1" />
            <div class="relative transition-all duration-300 transform group-focus-within:scale-[1.01]">
                 <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-purple-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="update_password_password" name="password" type="password" class="block w-full pl-12 pr-12 py-3 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:ring-4 focus:ring-purple-100 dark:focus:ring-purple-900/30 rounded-xl transition-all" autocomplete="new-password" placeholder="Nueva clave..." />
                <button type="button" onclick="togglePasswordUpdate('update_password_password')" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-purple-600 transition-colors focus:outline-none">
                    <svg id="eye-icon-update_password_password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg id="eye-slash-icon-update_password_password" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="group">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirmar Contraseña')" class="text-gray-700 dark:text-gray-300 font-bold mb-1 ml-1" />
            <div class="relative transition-all duration-300 transform group-focus-within:scale-[1.01]">
                 <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-purple-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full pl-12 pr-12 py-3 bg-gray-50 dark:bg-gray-900/50 border-gray-200 dark:border-gray-700 focus:ring-4 focus:ring-purple-100 dark:focus:ring-purple-900/30 rounded-xl transition-all" autocomplete="new-password" placeholder="Confirmar clave..." />
                <button type="button" onclick="togglePasswordUpdate('update_password_password_confirmation')" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-purple-600 transition-colors focus:outline-none">
                    <svg id="eye-icon-update_password_password_confirmation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg id="eye-slash-icon-update_password_password_confirmation" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <script>
            function togglePasswordUpdate(fieldId) {
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

        <div class="flex items-center gap-4 pt-6">
            <x-primary-button class="bg-gradient-to-r from-purple-600 to-fuchsia-600 hover:from-purple-700 hover:to-fuchsia-700 border-0 shadow-lg shadow-purple-500/30 px-8 py-3 rounded-xl text-sm font-bold tracking-wide transform hover:-translate-y-0.5 transition-all duration-200">
                {{ __('Actualizar Clave') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
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
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Guardado correctamente') }}
                </div>
            @endif
        </div>
    </form>
</section>