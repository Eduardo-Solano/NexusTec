<x-app-layout>
    {{-- Animated Background --}}
    <div class="circuit-background-app"></div>
    <div class="light-particles-app"></div>

    <div class="min-h-screen py-12 relative z-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            {{-- Navigation Back --}}
            <nav class="flex items-center text-sm font-medium text-gray-400 mb-6">
                <a href="{{ route('careers.index') }}" class="group flex items-center hover:text-white transition-colors duration-300">
                    <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mr-3 group-hover:border-blue-500/50 group-hover:bg-blue-500/20 transition-all duration-300">
                        <svg class="w-4 h-4 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </div>
                    <span>Volver a Catálogo</span>
                </a>
            </nav>

            <div class="glass-card rounded-2xl overflow-hidden shadow-2xl animate-fade-in-up">
                <div class="p-8 border-b border-white/10 flex justify-between items-center bg-white/5">
                    <div>
                        <h2 class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-1">Registro</h2>
                        <h1 class="text-3xl font-bold text-white bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">
                            Nueva Carrera
                        </h1>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center border border-blue-500/20">
                        <span class="text-2xl">🎓</span>
                    </div>
                </div>
                
                <form action="{{ route('careers.store') }}" method="POST" class="p-8 space-y-8">
                    @csrf
                    
                    <div>
                        <x-input-label for="code" :value="__('Código / Siglas')" class="text-white font-bold mb-2 text-sm uppercase tracking-wider" />
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                            </div>
                            <input id="code" class="block w-full pl-10 py-3 bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl shadow-inner transition-all duration-300 hover:bg-black/30 backdrop-blur-sm uppercase" 
                                type="text" name="code" value="{{ old('code') }}" required maxlength="20" placeholder="Ej: ISC" autofocus />
                        </div>
                        @error('code')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <x-input-label for="name" :value="__('Nombre Completo de la Carrera')" class="text-white font-bold mb-2 text-sm uppercase tracking-wider" />
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <input id="name" class="block w-full pl-10 py-3 bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 rounded-xl shadow-inner transition-all duration-300 hover:bg-black/30 backdrop-blur-sm" 
                                type="text" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="Ej: Ingeniería en Sistemas Computacionales" />
                        </div>
                        @error('name')
                            <p class="text-red-400 text-sm mt-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/10">
                        <a href="{{ route('careers.index') }}" 
                            class="text-sm font-bold text-gray-400 hover:text-white transition-colors flex items-center gap-2 group">
                            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Cancelar
                        </a>
                        <button type="submit" 
                            class="relative inline-flex items-center justify-center px-8 py-3 overflow-hidden font-bold text-white transition-all duration-300 bg-blue-600 rounded-xl group hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:ring-offset-gray-900 shadow-lg hover:shadow-blue-500/40">
                            <span class="absolute inset-0 w-full h-full -mt-10 transition-all duration-700 opacity-0 bg-gradient-to-b from-transparent via-transparent to-black group-hover:opacity-20"></span>
                            <span class="relative flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Guardar Carrera
                            </span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
