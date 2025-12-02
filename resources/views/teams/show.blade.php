<x-app-layout>
    <div class="min-h-screen bg-[#0B1120] py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8">
                <a href="{{ route('dashboard') }}" class="group inline-flex items-center text-sm font-medium text-gray-400 hover:text-white transition-colors">
                    <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Volver al Dashboard
                </a>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1 space-y-6">
                    
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-xl text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-purple-600"></div>
                        
                        <div class="w-24 h-24 mx-auto bg-gray-900 rounded-full flex items-center justify-center border-4 border-gray-700 mb-4 shadow-lg">
                            <span class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-br from-blue-400 to-purple-500">
                                {{ substr($team->name, 0, 1) }}
                            </span>
                        </div>

                        <h1 class="text-2xl font-black text-white mb-1">{{ $team->name }}</h1>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-widest mb-6">ID: TM-{{ str_pad($team->id, 4, '0', STR_PAD_LEFT) }}</p>

                        <div class="bg-gray-900/50 rounded-xl p-4 border border-gray-700 text-left">
                            <p class="text-xs text-gray-500 font-bold uppercase mb-1">Evento</p>
                            <p class="text-white font-bold text-sm flex items-center">
                                <span class="w-2 h-2 rounded-full {{ $team->event->is_active ? 'bg-green-500' : 'bg-red-500' }} mr-2"></span>
                                {{ $team->event->name }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 shadow-lg">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Estado del Proyecto</h3>
                        
                        @if($team->project)
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-green-500/10 text-green-400 rounded-lg border border-green-500/20">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">Entregado</p>
                                    <p class="text-xs text-gray-500">{{ $team->project->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('projects.show', $team->project) }}" class="block w-full py-2 bg-gray-700 hover:bg-gray-600 text-white text-xs font-bold uppercase rounded-lg text-center transition">
                                Ver Proyecto
                            </a>
                        @else
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-yellow-500/10 text-yellow-400 rounded-lg border border-yellow-500/20">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">Pendiente</p>
                                    <p class="text-xs text-gray-500">Aún no han subido su repositorio</p>
                                </div>
                            </div>
                        @endif
                    </div>

                </div>

                <div class="lg:col-span-2">
                    <div class="bg-gray-800 border border-gray-700 rounded-2xl overflow-hidden shadow-xl">
                        <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                            <h3 class="font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                Detalles del Equipo
                            </h3>
                            <span class="bg-gray-900 text-gray-300 text-xs px-3 py-1 rounded-full border border-gray-600">{{ $team->members->count() }} / 5</span>
                        </div>

                        <div class="divide-y divide-gray-700">
                            @foreach($team->members as $member)
                                <div class="p-4 flex items-center justify-between hover:bg-gray-700/30 transition">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm {{ $member->id === $team->leader_id ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/30' : 'bg-gray-700 text-gray-300' }}">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        
                                        <div>
                                            <p class="text-white font-bold text-sm flex items-center gap-2">
                                                {{ $member->name }}
                                                @if($member->id === $team->leader_id)
                                                    <span class="text-[10px] bg-blue-500/20 text-blue-300 px-2 py-0.5 rounded border border-blue-500/30">LÍDER</span>
                                                @endif
                                            </p>
                                            <p class="text-gray-500 text-xs">{{ $member->email }}</p>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-gray-300 text-xs font-bold uppercase tracking-wider mb-1">
                                            {{ $member->pivot->role ?? 'Miembro' }}
                                        </p>
                                        <span class="text-[10px] {{ $member->pivot->is_accepted ? 'text-green-400' : 'text-yellow-400' }}">
                                            {{ $member->pivot->is_accepted ? '● Activo' : '● Pendiente' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>