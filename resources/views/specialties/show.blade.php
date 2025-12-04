<x-app-layout>
    <div class="min-h-screen bg-gray-900 py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 border border-gray-700 rounded-2xl p-8 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-amber-500/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('specialties.index') }}" 
                            class="p-2 bg-gray-700 hover:bg-gray-600 rounded-lg transition">
                            <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <div>
                            <div class="p-2 bg-amber-500/10 rounded-lg inline-block mb-2">
                                <svg class="w-6 h-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <h1 class="text-2xl font-black text-white">{{ $specialty->name }}</h1>
                        </div>
                    </div>
                    
                    <a href="{{ route('specialties.edit', $specialty) }}" 
                        class="px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-xl transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Editar
                    </a>
                </div>
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm font-bold uppercase">Jueces</p>
                            <p class="text-3xl font-black text-white mt-1">{{ $specialty->judgeProfiles->count() }}</p>
                        </div>
                        <div class="p-3 bg-amber-500/10 rounded-xl">
                            <svg class="w-7 h-7 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm font-bold uppercase">Fecha de Creación</p>
                            <p class="text-lg font-bold text-white mt-1">{{ $specialty->created_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="p-3 bg-green-500/10 rounded-xl">
                            <svg class="w-7 h-7 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lista de jueces --}}
            <div class="bg-gray-800 border border-gray-700 rounded-2xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700">
                    <h3 class="text-lg font-bold text-white">Jueces con esta Especialidad</h3>
                </div>

                @if($specialty->judgeProfiles->count() > 0)
                    <div class="divide-y divide-gray-700">
                        @foreach($specialty->judgeProfiles as $profile)
                            <div class="px-6 py-4 hover:bg-gray-700/30 transition flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-amber-500/10 flex items-center justify-center text-amber-400 font-bold">
                                        {{ substr($profile->user->name ?? 'J', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-white font-medium">{{ $profile->user->name ?? 'Sin nombre' }}</p>
                                        <p class="text-gray-500 text-sm">{{ $profile->institution ?? 'Sin institución' }}</p>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">No hay jueces con esta especialidad</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
