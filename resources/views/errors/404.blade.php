<x-app-layout>
    <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Background Decor -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
            <div class="absolute top-[10%] right-[10%] w-[30%] h-[30%] bg-purple-500/20 rounded-full blur-[100px] animate-pulse"></div>
            <div class="absolute bottom-[10%] left-[10%] w-[30%] h-[30%] bg-blue-500/20 rounded-full blur-[100px] animate-pulse" style="animation-delay: 1.5s;"></div>
        </div>

        <div class="max-w-xl w-full text-center relative z-10">
            <!-- Glass Card -->
            <div class="backdrop-blur-xl bg-white/30 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-2xl rounded-3xl p-12 transform transition-all hover:scale-[1.01] hover:shadow-purple-500/10">
                
                <!-- Icon (Ghost/Space) -->
                <div class="mb-6 relative inline-block">
                    <div class="absolute inset-0 bg-purple-500 blur-2xl opacity-20 animate-pulse"></div>
                    <span class="text-9xl relative z-10 drop-shadow-lg">🪐</span>
                </div>

                <!-- Title -->
                <h1 class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-blue-500 mb-2 tracking-tighter">
                    404
                </h1>

                <!-- Subtitle -->
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    Página No Encontrada
                </h2>

                <!-- Message -->
                <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                    Parece que te has perdido en el espacio. La página que buscas no existe o ha sido movida a otra galaxia.
                </p>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ url()->previous() }}" class="px-6 py-3 rounded-xl bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition-all transform hover:-translate-y-1">
                        ← Regresar
                    </a>
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl bg-gradient-to-r from-purple-500 to-blue-500 text-white font-bold shadow-lg shadow-purple-500/30 hover:shadow-purple-500/50 transition-all transform hover:-translate-y-1">
                        Ir al Dashboard
                    </a>
                </div>

            </div>
             <p class="mt-8 text-sm text-gray-500 dark:text-gray-400 font-mono">
                Error Code: 404 | NexusTec Navigation System
            </p>
        </div>
    </div>
</x-app-layout>
