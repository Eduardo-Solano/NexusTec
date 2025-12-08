<x-app-layout>
    <div class="min-h-screen bg-[#0B1120] py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <nav class="flex items-center text-sm font-medium text-gray-400 mb-8">
                <a href="{{ route('students.index') }}" class="hover:text-white transition flex items-center group">
                    <div class="w-8 h-8 rounded-full bg-gray-800 border border-gray-700 flex items-center justify-center mr-3 group-hover:border-ito-orange transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </div>
                    Volver a la Lista
                </a>
            </nav>

            @if ($errors->any())
                <div class="mb-6 bg-red-900/30 border border-red-500/50 rounded-lg p-4">
                    <p class="text-red-400 font-bold mb-2">Errores de validación:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-red-300 text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- Panel Izquierdo: Información del Estudiante --}}
                <div class="lg:col-span-1">
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-8 shadow-xl text-center relative overflow-hidden h-full">
                        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-blue-900/50 to-transparent"></div>
                        
                        <div class="relative z-10">
                            <div class="w-28 h-28 mx-auto bg-gradient-to-br from-gray-800 to-black rounded-full flex items-center justify-center border-4 border-gray-700 mb-4 shadow-2xl relative group">
                                <div class="absolute inset-0 bg-blue-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                                
                                <svg class="w-14 h-14 text-gray-400 group-hover:text-white transition duration-300" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" />
                                </svg>
                            </div>

                            <h2 class="text-xl font-bold text-white mb-1">{{ $student->name }}</h2>
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-900/30 border border-blue-500/30 text-blue-300 text-xs font-bold mb-2">
                                {{ $student->studentProfile->career->name ?? 'Sin carrera' }}
                            </div>
                            <p class="text-gray-400 text-sm mb-6">{{ $student->studentProfile->semester ?? '?' }}° Semestre</p>
                            
                            <div class="border-t border-gray-700 pt-6 grid grid-cols-1 gap-4 text-left">
                                <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-700">
                                    <p class="text-[10px] uppercase text-gray-500 font-bold tracking-wider mb-1">No. Control</p>
                                    <p class="text-sm text-white font-mono font-bold">{{ $student->studentProfile->control_number ?? 'N/A' }}</p>
                                </div>
                                <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-700">
                                    <p class="text-[10px] uppercase text-gray-500 font-bold tracking-wider mb-1">Estado</p>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $student->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $student->is_active ? 'bg-green-400' : 'bg-red-400' }}"></span>
                                        {{ $student->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                                <div class="bg-gray-900/50 p-3 rounded-lg border border-gray-700">
                                    <p class="text-[10px] uppercase text-gray-500 font-bold tracking-wider mb-1">Fecha de Alta</p>
                                    <p class="text-sm text-white font-mono">{{ $student->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Panel Derecho: Formulario de Edición --}}
                <div class="lg:col-span-2">
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-xl overflow-hidden">
                        
                        <div class="border-b border-gray-700 px-8 py-6 bg-gray-800 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-bold text-white">Editar Información</h3>
                                <p class="text-sm text-gray-400">Actualiza los datos personales y académicos.</p>
                            </div>
                            <span class="w-2 h-2 rounded-full {{ $student->is_active ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]' : 'bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.6)]' }}"></span>
                        </div>

                        <form action="{{ route('students.update', $student) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="p-8 space-y-8">
                                {{-- Datos Personales --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <x-input-label for="name" :value="__('Nombre Completo')" class="text-white font-bold text-xs uppercase tracking-wide" />
                                        <x-text-input id="name" class="block w-full bg-gray-900 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500 rounded-lg h-11 @error('name') border-red-500 @enderror" 
                                                      type="text" name="name" :value="old('name', $student->name)" required maxlength="255" />
                                        @error('name')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                                    </div>

                                    <div class="space-y-2">
                                        <x-input-label for="email" :value="__('Correo Institucional')" class="text-white font-bold text-xs uppercase tracking-wide" />
                                        <x-text-input id="email" class="block w-full bg-gray-900 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500 rounded-lg h-11 @error('email') border-red-500 @enderror" 
                                                      type="email" name="email" :value="old('email', $student->email)" required maxlength="255" autocomplete="email" />
                                        @error('email')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="phone" :value="__('Teléfono')" class="text-white font-bold text-xs uppercase tracking-wide" />
                                    <x-text-input id="phone" class="block w-full bg-gray-900 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500 rounded-lg h-11 @error('phone') border-red-500 @enderror" 
                                                  type="text" name="phone" :value="old('phone', $student->phone)" placeholder="Ej: 951 123 4567" maxlength="20" />
                                    @error('phone')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                {{-- Datos Académicos --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <x-input-label for="control_number" :value="__('No. Control')" class="text-white font-bold text-xs uppercase tracking-wide" />
                                        <x-text-input id="control_number" class="block w-full bg-gray-900 border-gray-600 text-white focus:border-blue-500 focus:ring-blue-500 rounded-lg h-11 font-mono @error('control_number') border-red-500 @enderror" 
                                                      type="text" name="control_number" :value="old('control_number', $student->studentProfile->control_number ?? '')" required maxlength="255" placeholder="Ej: 21160000" />
                                        @error('control_number')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                                    </div>

                                    <div class="space-y-2">
                                        <x-input-label for="semester" :value="__('Semestre')" class="text-white font-bold text-xs uppercase tracking-wide" />
                                        <select name="semester" id="semester" class="block w-full bg-gray-900 border-gray-600 text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 h-11 text-sm @error('semester') border-red-500 @enderror" required>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" @if(old('semester', $student->studentProfile->semester ?? '') == $i) selected @endif>{{ $i }}° Semestre</option>
                                            @endfor
                                        </select>
                                        @error('semester')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <x-input-label for="career_id" :value="__('Carrera')" class="text-white font-bold text-xs uppercase tracking-wide" />
                                    <select name="career_id" id="career_id" class="block w-full bg-gray-900 border-gray-600 text-white rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 h-11 text-sm @error('career_id') border-red-500 @enderror" required>
                                        <option value="">Seleccionar carrera</option>
                                        @foreach ($careers as $career)
                                            <option value="{{ $career->id }}" @if(old('career_id', $student->studentProfile->career_id ?? '') == $career->id) selected @endif>{{ $career->name }}</option>                                  
                                        @endforeach
                                    </select>
                                    @error('career_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                                </div>

                                {{-- Estado Activo --}}
                                <div class="bg-gray-900/50 border border-gray-700 rounded-xl p-4 flex items-center justify-between hover:bg-gray-900/70 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-green-500/10 rounded-lg text-green-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div>
                                            <label for="is_active" class="block font-bold text-white text-sm cursor-pointer select-none">Alumno Activo</label>
                                            <p class="text-gray-500 text-xs mt-0.5">Habilitar o deshabilitar acceso al sistema</p>
                                        </div>
                                    </div>
                                    
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $student->is_active) ? 'checked' : '' }} class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-green-500 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                                    </label>
                                </div>

                                {{-- Restablecer Contraseña --}}
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
                                <a href="{{ route('students.index') }}" class="px-4 py-2 text-sm font-bold text-gray-400 hover:text-white transition">
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
