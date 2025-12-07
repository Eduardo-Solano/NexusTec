@php
    $assignedProjects = $data['assigned_projects'] ?? collect();
    $pendingProjects = $data['pending_projects'] ?? collect();
    $completedProjects = $data['completed_projects'] ?? collect();
@endphp

<div class="space-y-8">
    <!-- Judge Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <p class="text-xs font-bold uppercase text-gray-500 mb-2">Pendientes</p>
            <p class="text-4xl font-black text-amber-500">{{ $data['pending_count'] ?? 0 }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
             <p class="text-xs font-bold uppercase text-gray-500 mb-2">Evaluados</p>
            <p class="text-4xl font-black text-green-500">{{ $data['completed_count'] ?? 0 }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
             <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <p class="text-xs font-bold uppercase text-gray-500 mb-2">Asignados</p>
            <p class="text-4xl font-black text-blue-500">{{ $assignedProjects->count() }}</p>
        </div>
        <div class="glass-card p-6 rounded-2xl relative overflow-hidden group">
             <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <p class="text-xs font-bold uppercase text-gray-500 mb-2">Promedio Criterio</p>
            <p class="text-4xl font-black text-purple-500">{{ $data['avg_score'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Assigned Projects List -->
    <div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <span class="p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">📋</span>
            Proyectos Asignados
        </h3>
        
        @if($assignedProjects->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($assignedProjects as $project)
                    <div class="glass-card rounded-2xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative group border-t-4 {{ $project->pivot->is_completed ? 'border-green-500' : 'border-amber-500' }}">
                         <div class="absolute top-4 right-4">
                            @if($project->pivot->is_completed)
                                <span class="text-[10px] font-bold px-2 py-1 bg-green-100 text-green-700 rounded-full">COMPLETADO</span>
                            @else
                                <span class="text-[10px] font-bold px-2 py-1 bg-amber-100 text-amber-700 rounded-full animate-pulse">PENDIENTE</span>
                            @endif
                        </div>

                        <p class="text-xs text-blue-500 font-bold uppercase tracking-wider mb-2">{{ Str::limit($project->team->event->name ?? 'Evento', 20) }}</p>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-1" title="{{ $project->name }}">{{ $project->name }}</h4>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $project->description }}</p>

                        <div class="flex items-center -space-x-2 mb-6">
                            @foreach($project->team->members->take(3) as $member)
                                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-white dark:border-gray-800 flex items-center justify-center text-xs font-bold">
                                    {{ substr($member->name, 0, 1) }}
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ route('projects.show', $project) }}" class="block w-full py-3 text-center rounded-xl font-bold transition-colors {{ $project->pivot->is_completed ? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200' : 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50' }}">
                            {{ $project->pivot->is_completed ? 'Ver Evaluación' : 'Evaluar Ahora' }}
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 rounded-3xl bg-gray-50 dark:bg-gray-800/50 border-2 border-dashed border-gray-200 dark:border-gray-700">
                <p class="text-gray-500 font-medium">No tienes proyectos asignados en este momento.</p>
            </div>
        @endif
    </div>
</div>
