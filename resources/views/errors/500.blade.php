<x-app-layout>
    <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Background Decor -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-[20%] left-[20%] w-[40%] h-[40%] bg-rose-600/20 rounded-full blur-[120px] animate-pulse"></div>
        </div>

        <div class="max-w-2xl w-full text-center relative z-10">
            <!-- Glass Card -->
            <div class="backdrop-blur-xl bg-white/30 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-2xl rounded-3xl p-12 transform transition-all hover:scale-[1.01] hover:shadow-rose-500/10">
                
                <!-- Icon -->
                <div class="mb-8 relative inline-block">
                    <div class="absolute inset-0 bg-rose-600 blur-2xl opacity-20 animate-pulse"></div>
                    <svg class="w-24 h-24 text-rose-500 relative z-10 drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-7xl font-black text-transparent bg-clip-text bg-gradient-to-r from-rose-500 to-pink-600 mb-4 tracking-tighter">
                    500
                </h1>

                <!-- Subtitle -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    Error Interno del Servidor
                </h2>

                <!-- Message -->
                <p class="text-lg text-gray-600 dark:text-gray-300 mb-10 leading-relaxed max-w-lg mx-auto">
                    Algo salió mal en nuestros sistemas. Nuestros ingenieros ya han sido notificados (probablemente). Por favor, intenta de nuevo más tarde.
                </p>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button onclick="location.reload()" class="px-8 py-3 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition-all transform hover:-translate-y-1">
                        ↻ Recargar Página
                    </button>
                    <a href="{{ route('dashboard') }}" class="px-8 py-3 rounded-xl bg-gradient-to-r from-rose-500 to-pink-600 text-white font-bold shadow-lg shadow-rose-500/30 hover:shadow-rose-500/50 transition-all transform hover:-translate-y-1">
                        Ir al Inicio
                    </a>
                </div>

            </div>
             <p class="mt-8 text-sm text-gray-500 dark:text-gray-400 font-mono">
                Error Code: 500 | NexusTec Server Core
            </p>
        </div>
    </div>
</x-app-layout>
