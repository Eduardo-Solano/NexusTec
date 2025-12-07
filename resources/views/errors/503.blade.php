<x-app-layout>
    <div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
             <div class="absolute top-[30%] left-[30%] w-[40%] h-[40%] bg-amber-500/20 rounded-full blur-[120px] animate-pulse"></div>
        </div>

        <div class="max-w-xl w-full text-center relative z-10">
             <div class="backdrop-blur-xl bg-white/30 dark:bg-gray-900/50 border border-white/20 dark:border-gray-700/50 shadow-2xl rounded-3xl p-12 transition-all hover:scale-[1.01]">
                
                <div class="mb-6 relative inline-block">
                    <div class="absolute inset-0 bg-amber-500 blur-2xl opacity-20 animate-pulse"></div>
                    <svg class="w-24 h-24 text-amber-500 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>

                <h1 class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-yellow-500 mb-2">
                    503
                </h1>

                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    En Mantenimiento
                </h2>

                <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
                    Estamos realizando mejoras en la plataforma para ofrecerte una experiencia aún más increíble. Volveremos en breve.
                </p>

                <div class="animate-pulse flex justify-center gap-2">
                    <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                    <div class="w-3 h-3 bg-amber-500 rounded-full" style="animation-delay: 0.2s"></div>
                    <div class="w-3 h-3 bg-amber-500 rounded-full" style="animation-delay: 0.4s"></div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
