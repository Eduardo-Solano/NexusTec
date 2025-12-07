<x-app-layout>
    <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Background Decor -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-red-500/20 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-orange-500/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-2xl w-full text-center relative z-10">
            <!-- Glass Card -->
            <div class="backdrop-blur-xl bg-white/30 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-2xl rounded-3xl p-12 md:p-16 transform transition-all hover:scale-[1.01] hover:shadow-red-500/10">
                
                <!-- Icon -->
                <div class="mb-8 relative inline-block">
                    <div class="absolute inset-0 bg-red-500 blur-2xl opacity-20 animate-pulse"></div>
                    <svg class="w-32 h-32 text-red-500 relative z-10 drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-6xl md:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-orange-500 mb-4 tracking-tighter">
                    403
                </h1>

                <!-- Subtitle -->
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-6">
                    Acceso Prohibido
                </h2>

                <!-- Message -->
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-10 leading-relaxed max-w-lg mx-auto">
                    {{ $exception->getMessage() ?: 'Lo sentimos, no tienes permiso para acceder a esta área restringida. Si crees que esto es un error, contacta al administrador.' }}
                </p>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ url()->previous() }}" class="px-8 py-3 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition-all transform hover:-translate-y-1">
                        ← Regresar
                    </a>
                    <a href="{{ route('dashboard') }}" class="px-8 py-3 rounded-xl bg-gradient-to-r from-red-500 to-orange-500 text-white font-bold shadow-lg shadow-red-500/30 hover:shadow-red-500/50 transition-all transform hover:-translate-y-1">
                        Ir al Dashboard
                    </a>
                </div>

            </div>
            
            <!-- Footer Info -->
            <p class="mt-8 text-sm text-gray-500 dark:text-gray-400 font-mono">
                Error Code: 403 | NexusTec Security Protocol
            </p>
        </div>
    </div>
</x-app-layout>
