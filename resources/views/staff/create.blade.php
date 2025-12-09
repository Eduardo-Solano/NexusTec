<x-app-layout>
    {{-- Main Container with Animated Background --}}
    <div class="circuit-background-app"></div>
    <div class="light-particles-app"></div>

    <div class="min-h-screen py-12 relative z-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            {{-- Navigation Back --}}
            <nav class="flex items-center text-sm font-medium text-gray-400 mb-6">
                <a href="{{ route('staff.index') }}" class="group flex items-center hover:text-white transition-colors duration-300">
                    <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mr-3 group-hover:border-purple-500/50 group-hover:bg-purple-500/20 transition-all duration-300">
                        <svg class="w-4 h-4 group-hover:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </div>
                    <span>Volver a Lista de Docentes</span>
                </a>
            </nav>

            <div class="glass-card rounded-2xl overflow-hidden shadow-2xl animate-fade-in-up">
                <div class="p-8 border-b border-white/10 flex justify-between items-center bg-white/5">
                    <div>
                        <h2 class="text-xs font-bold text-purple-400 uppercase tracking-widest mb-1">Registro</h2>
                        <h1 class="text-3xl font-bold text-white bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">
                            Nuevo Docente / Staff
                        </h1>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center border border-purple-500/20">
                        <span class="text-2xl">👨‍🏫</span>
                    </div>
                </div>
                
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-500/10 border border-red-500/30 rounded-xl p-4 backdrop-blur-sm animate-pulse-slow">
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="text-red-400 font-bold text-sm uppercase tracking-wide">Errores de Validación</p>
                            </div>
                            <ul class="list-disc list-inside space-y-1 ml-2">
                                @foreach ($errors->all() as $error)
                                    <li class="text-red-300 text-sm opacity-90 hover:opacity-100 transition">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('staff.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <x-input-label for="name" :value="__('Nombre Completo')" class="text-white font-bold mb-2 text-sm uppercase tracking-wider" />
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <input id="name" class="block w-full pl-10 py-3 bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:border-purple-500/50 focus:ring-1 focus:ring-purple-500/50 rounded-xl shadow-inner transition-all duration-300 hover:bg-black/30 backdrop-blur-sm" 
                                    type="text" name="name" :value="old('name')" required maxlength="255" placeholder="Ej: Dra. María González" autofocus />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Correo Institucional')" class="text-white font-bold mb-2 text-sm uppercase tracking-wider" />
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <input id="email" class="block w-full pl-10 py-3 bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:border-purple-500/50 focus:ring-1 focus:ring-purple-500/50 rounded-xl shadow-inner transition-all duration-300 hover:bg-black/30 backdrop-blur-sm" 
                                    type="email" name="email" :value="old('email')" required maxlength="255" autocomplete="email" placeholder="docente@tecnm.mx" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="employee_number" :value="__('No. Empleado')" class="text-white font-bold mb-2 text-sm uppercase tracking-wider" />
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                    </div>
                                    <input id="employee_number" class="block w-full pl-10 py-3 bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:border-purple-500/50 focus:ring-1 focus:ring-purple-500/50 rounded-xl shadow-inner transition-all duration-300 hover:bg-black/30 backdrop-blur-sm" 
                                        type="text" name="employee_number" :value="old('employee_number')" required maxlength="255" placeholder="Ej: EMP-005"/>
                                </div>
                            </div>
                            <div>
                                <x-input-label for="department" :value="__('Departamento')" class="text-white font-bold mb-2 text-sm uppercase tracking-wider" />
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <select name="department" required class="block w-full pl-10 py-3 bg-black/20 border border-white/10 text-white rounded-xl shadow-inner focus:border-purple-500/50 focus:ring-1 focus:ring-purple-500/50 transition-all duration-300 hover:bg-black/30 backdrop-blur-sm appearance-none cursor-pointer">
                                        <option value="" class="bg-gray-900 text-gray-400">Seleccionar departamento</option>
                                        <option value="Sistemas y Computación" @if(old('department') == 'Sistemas y Computación') selected @endif class="bg-gray-900 text-white">Sistemas y Computación</option>
                                        <option value="Ciencias Básicas" @if(old('department') == 'Ciencias Básicas') selected @endif class="bg-gray-900 text-white">Ciencias Básicas</option>
                                        <option value="Industrial" @if(old('department') == 'Industrial') selected @endif class="bg-gray-900 text-white">Industrial</option>
                                        <option value="Económico-Administrativo" @if(old('department') == 'Económico-Administrativo') selected @endif class="bg-gray-900 text-white">Económico-Administrativo</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tipo de Staff --}}
                        <div class="p-4 rounded-xl bg-purple-500/5 border border-purple-500/10">
                            <x-input-label :value="__('Rol y Permisos')" class="text-purple-400 text-xs font-bold uppercase tracking-wider mb-3 flex items-center gap-2" />
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="staff_type" value="advisor" class="peer sr-only" {{ old('staff_type', 'advisor') == 'advisor' ? 'checked' : '' }} required>
                                    <div class="p-4 bg-black/40 border-2 border-white/5 rounded-xl text-center transition-all duration-300 peer-checked:border-blue-500 peer-checked:bg-blue-500/10 hover:border-blue-500/50 hover:-translate-y-1">
                                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">👨‍🏫</div>
                                        <p class="text-white font-bold text-sm">Docente</p>
                                        <p class="text-gray-500 text-xs mt-1">Solo asesoría</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="staff_type" value="staff" class="peer sr-only" {{ old('staff_type') == 'staff' ? 'checked' : '' }}>
                                    <div class="p-4 bg-black/40 border-2 border-white/5 rounded-xl text-center transition-all duration-300 peer-checked:border-purple-500 peer-checked:bg-purple-500/10 hover:border-purple-500/50 hover:-translate-y-1">
                                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">🎯</div>
                                        <p class="text-white font-bold text-sm">Organizador</p>
                                        <p class="text-gray-500 text-xs mt-1">Gestión de eventos</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="staff_type" value="both" class="peer sr-only" {{ old('staff_type') == 'both' ? 'checked' : '' }}>
                                    <div class="p-4 bg-black/40 border-2 border-white/5 rounded-xl text-center transition-all duration-300 peer-checked:border-amber-500 peer-checked:bg-amber-500/10 hover:border-amber-500/50 hover:-translate-y-1">
                                        <div class="text-3xl mb-2 group-hover:scale-110 transition-transform">⭐</div>
                                        <p class="text-white font-bold text-sm">Ambos</p>
                                        <p class="text-gray-500 text-xs mt-1">Todos los permisos</p>
                                    </div>
                                </label>
                            </div>
                            
                            <p class="text-gray-500 text-[10px] mt-3 bg-black/20 p-2 rounded-lg border border-white/5">
                                <strong class="text-gray-300">Nota:</strong> Seleccione el rol adecuado según las responsabilidades del usuario dentro de la plataforma.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="password" :value="__('Contraseña')" class="text-white font-bold mb-2 text-sm uppercase tracking-wider" />
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password" class="block w-full pl-10 py-3 bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:border-purple-500/50 focus:ring-1 focus:ring-purple-500/50 rounded-xl shadow-inner transition-all duration-300 hover:bg-black/30 backdrop-blur-sm" 
                                        type="password" name="password" minlength="8" autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-white font-bold mb-2 text-sm uppercase tracking-wider" />
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-500 group-focus-within:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <input id="password_confirmation" class="block w-full pl-10 py-3 bg-black/20 border border-white/10 text-white placeholder-gray-500 focus:border-purple-500/50 focus:ring-1 focus:ring-purple-500/50 rounded-xl shadow-inner transition-all duration-300 hover:bg-black/30 backdrop-blur-sm" 
                                        type="password" name="password_confirmation" minlength="8" autocomplete="new-password" placeholder="Confirmar contraseña" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-8 pt-6 border-t border-white/10">
                            <a href="{{ route('staff.index') }}" class="text-sm font-bold text-gray-400 hover:text-white transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                Cancelar
                            </a>
                            <button type="submit" class="relative inline-flex items-center justify-center px-8 py-3 overflow-hidden font-bold text-white transition-all duration-300 bg-purple-600 rounded-xl group hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 focus:ring-offset-gray-900">
                                <span class="absolute inset-0 w-full h-full -mt-10 transition-all duration-700 opacity-0 bg-gradient-to-b from-transparent via-transparent to-black group-hover:opacity-20"></span>
                                <span class="relative flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                                    Registrar Docente
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
