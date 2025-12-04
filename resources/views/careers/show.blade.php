<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-blue-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('careers.index') }}" 
                            class="p-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition">
                            <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <div>
                            <span class="px-3 py-1 bg-blue-500/10 text-blue-400 border border-blue-500/20 rounded-lg text-sm font-mono font-bold">
                                {{ $career->code }}
                            </span>
                            <h1 class="text-2xl font-black text-white mt-2">{{ $career->name }}</h1>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('careers.edit', $career) }}" 
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Editar
                        </a>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm font-bold uppercase">Estudiantes</p>
                            <p class="text-3xl font-black text-white mt-1">{{ $career->studentProfiles->count() }}</p>
                        </div>
                        <div class="p-3 bg-blue-500/10 rounded-xl">
                            <svg class="w-7 h-7 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm font-bold uppercase">Creado</p>
                            <p class="text-lg font-bold text-white mt-1">{{ $career->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="p-3 bg-green-500/10 rounded-xl">
                            <svg class="w-7 h-7 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm font-bold uppercase">Código</p>
                            <p class="text-2xl font-mono font-bold text-white mt-1">{{ $career->code }}</p>
                        </div>
                        <div class="p-3 bg-purple-500/10 rounded-xl">
                            <svg class="w-7 h-7 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lista de estudiantes --}}
            <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-bold text-white">Estudiantes de esta Carrera</h3>
                </div>

                @if($career->studentProfiles->count() > 0)
                    <div class="divide-y divide-gray-700">
                        @foreach($career->studentProfiles as $profile)
                            <div class="px-6 py-4 hover:bg-gray-700/30 transition flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-bold">
                                        {{ substr($profile->user->name ?? 'N', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-white font-medium">{{ $profile->user->name ?? 'Sin nombre' }}</p>
                                        <p class="text-gray-500 text-sm">{{ $profile->control_number ?? 'Sin No. Control' }} • Semestre {{ $profile->semester ?? '?' }}</p>
                                    </div>
                                </div>
                                <span class="text-gray-500 text-sm">{{ $profile->user->email ?? '' }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="inline-flex p-4 rounded-full bg-gray-700/50 mb-4">
                            <svg class="w-8 h-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">No hay estudiantes en esta carrera</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
