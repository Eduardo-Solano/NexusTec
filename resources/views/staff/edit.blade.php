<x-app-layout>
    <div class="min-h-screen bg-[#0B1120] py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <nav class="flex items-center text-sm font-medium text-gray-400 mb-8">
                <a href="{{ route('staff.index') }}" class="hover:text-white transition flex items-center group">
                    <div class="w-8 h-8 rounded-full bg-gray-800 border border-gray-700 flex items-center justify-center mr-3 group-hover:border-ito-orange transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </div>
                    Volver a la Lista
                </a>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-8 shadow-xl text-center relative overflow-hidden h-full">
                        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-blue-900/50 to-transparent"></div>
                        
                        <div class="relative z-10">
                            {{-- <div class="w-32 h-32 mx-auto bg-gray-900 rounded-3xl flex items-center justify-center border-4 border-gray-700 mb-6 shadow-2xl">
                                <span class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-br from-blue-400 to-indigo-500">
                                    {{ strtoupper(substr($staff->name, 0, 1)) }}
                                </span>
                            </div> --}}
                            <div class="w-28 h-28 mx-auto bg-gradient-to-br from-gray-800 to-black rounded-full flex items-center justify-center border-4 border-gray-700 mb-4 shadow-2xl relative group">
                                <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                                
                                <svg class="w-14 h-14 text-gray-400 group-hover:text-white transition duration-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                                </svg>
                            </div>

                            <h2 class="text-xl font-bold text-white mb-1">{{ $staff->name }}</h2>
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-900/30 border border-blue-500/30 text-blue-300 text-xs font-bold mb-6">
                                {{ $staff->staffProfile->department ?? 'Docente' }}
                            </div>
                            
                            <div class="border-t border-gray-700 pt-6 grid grid-cols-1 gap-4 text-left">
                                <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-700">
                                    <p class="text-[10px] uppercase text-gray-500 font-bold tracking-wider mb-1">ID Sistema</p>
                                    <p class="text-sm text-white font-mono font-bold">#{{ str_pad($staff->id, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                                <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-700">
                                    <p class="text-[10px] uppercase text-gray-500 font-bold tracking-wider mb-1">Fecha de Alta</p>
                                    <p class="text-sm text-white font-mono">{{ $staff->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-xl overflow-hidden">
                        
                        <div class="border-b border-gray-700 px-8 py-6 bg-gray-800 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-white">Editar Información</h3>
                                <p class="text-sm text-gray-400">Actualiza los datos personales y laborales.</p>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]"></span>
                        </div>

                        <form action="{{ route('staff.update', $staff) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="p-8 space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <x-input-label for="name" :value="__('Nombre Completo')" class="text-white font-bold text-xs uppercase tracking-wide" />
                                        <x-text-input id="name" class="block w-full bg-gray-900 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500 rounded-lg h-11" 
                                                      type="text" name="name" :value="old('name', $staff->name)" required maxlength="255" />
                                    </div>

                                    <div class="space-y-2">
                                        <x-input-label for="email" :value="__('Correo Institucional')" class="text-white font-bold text-xs uppercase tracking-wide" />
                                        <x-text-input id="email" class="block w-full bg-gray-900 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500 rounded-lg h-11" 
                                                      type="email" name="email" :value="old('email', $staff->email)" required maxlength="255" autocomplete="email" />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <x-input-label for="employee_number" :value="__('No. Empleado')" class="text-white font-bold text-xs uppercase tracking-wide" />
                                        <x-text-input id="employee_number" class="block w-full bg-gray-900 border-gray-600 text-white focus:border-purple-500 focus:ring-purple-500 rounded-lg h-11 font-mono" 
                                                      type="text" name="employee_number" :value="old('employee_number', $staff->staffProfile->employee_number ?? '')" required maxlength="255" />
                                    </div>

                                    <div class="space-y-2">
                                        <x-input-label for="department" :value="__('Departamento')" class="text-white font-bold text-xs uppercase tracking-wide" />
                                        <select name="department" required class="block w-full bg-gray-900 border-gray-600 text-white rounded-lg shadow-sm focus:border-purple-500 focus:ring-purple-500 h-11 text-sm">
                                            @php
                                                $currentDept = $staff->staffProfile->department ?? '';
                                                $departments = ['Sistemas y Computación', 'Ciencias Básicas', 'Industrial', 'Económico-Administrativo'];
                                            @endphp
                                            @foreach($departments as $dept)
                                                <option value="{{ $dept }}" {{ $currentDept == $dept ? 'selected' : '' }}>
                                                    {{ $dept }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Tipo de Staff --}}
                                <div class="space-y-3">
                                    <x-input-label :value="__('Tipo de Personal')" class="text-white font-bold text-xs uppercase tracking-wide" />
                                    @php
                                        $hasStaff = $staff->hasRole('staff');
                                        $hasAdvisor = $staff->hasRole('advisor');
                                        $currentType = ($hasStaff && $hasAdvisor) ? 'both' : ($hasStaff ? 'staff' : 'advisor');
                                    @endphp
                                    <div class="grid grid-cols-3 gap-3">
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="staff_type" value="advisor" class="peer sr-only" {{ old('staff_type', $currentType) == 'advisor' ? 'checked' : '' }} required>
                                            <div class="p-4 bg-gray-900 border-2 border-gray-600 rounded-xl text-center transition peer-checked:border-blue-500 peer-checked:bg-blue-500/10 hover:border-gray-500">
                                                <div class="text-2xl mb-2">👨‍🏫</div>
                                                <p class="text-white font-bold text-sm">Docente</p>
                                                <p class="text-gray-500 text-xs mt-1">Solo asesoría</p>
                                            </div>
                                        </label>
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="staff_type" value="staff" class="peer sr-only" {{ old('staff_type', $currentType) == 'staff' ? 'checked' : '' }}>
                                            <div class="p-4 bg-gray-900 border-2 border-gray-600 rounded-xl text-center transition peer-checked:border-purple-500 peer-checked:bg-purple-500/10 hover:border-gray-500">
                                                <div class="text-2xl mb-2">🎯</div>
                                                <p class="text-white font-bold text-sm">Organizador</p>
                                                <p class="text-gray-500 text-xs mt-1">Gestión de eventos</p>
                                            </div>
                                        </label>
                                        <label class="relative cursor-pointer">
                                            <input type="radio" name="staff_type" value="both" class="peer sr-only" {{ old('staff_type', $currentType) == 'both' ? 'checked' : '' }}>
                                            <div class="p-4 bg-gray-900 border-2 border-gray-600 rounded-xl text-center transition peer-checked:border-ito-orange peer-checked:bg-ito-orange/10 hover:border-gray-500">
                                                <div class="text-2xl mb-2">⭐</div>
                                                <p class="text-white font-bold text-sm">Ambos</p>
                                                <p class="text-gray-500 text-xs mt-1">Todos los permisos</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="bg-red-900/10 border border-red-500/20 rounded-xl p-4 flex items-center justify-between hover:bg-red-900/20 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-red-500/10 rounded-lg text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                        </div>
                                        <div>
                                            <label for="reset_password" class="block font-bold text-red-400 text-sm cursor-pointer select-none">Restablecer Contraseña</label>
                                            <p class="text-gray-500 text-xs mt-0.5">Reiniciar a "password"</p>
                                        </div>
                                    </div>
                                    
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="reset_password" name="reset_password" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-red-500 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                                    </label>
                                </div>
                            </div>

                            <div class="px-8 py-5 bg-gray-900/50 border-t border-gray-700 flex items-center justify-end gap-4">
                                <a href="{{ route('staff.index') }}" class="px-4 py-2 text-sm font-bold text-gray-400 hover:text-white transition">
                                    Cancelar
                                </a>
                                <button type="submit" class="bg-ito-orange hover:bg-orange-600 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
