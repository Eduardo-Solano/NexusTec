<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->orderBy('created_at', 'desc');

        // Filtro por acción
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filtro por usuario
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtro por tipo de modelo
        if ($request->filled('model_type')) {
            $modelClass = 'App\\Models\\' . $request->model_type;
            $query->where('model_type', $modelClass);
        }

        // Filtro por fecha
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Búsqueda por descripción
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $activities = $query->paginate(25)->withQueryString();

        // Obtener acciones únicas para el filtro
        $actions = ActivityLog::distinct()->pluck('action');

        // Obtener tipos de modelo únicos para el filtro
        $modelTypes = ActivityLog::distinct()
            ->whereNotNull('model_type')
            ->pluck('model_type')
            ->map(fn($type) => class_basename($type));

        return view('activity-logs.index', compact('activities', 'actions', 'modelTypes'));
    }

    /**
     * Display the specified activity log.
     */
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');
        return view('activity-logs.show', compact('activityLog'));
    }

    /**
     * Get activities for a specific model (API endpoint)
     */
    public function forModel(Request $request)
    {
        $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
        ]);

        $modelClass = 'App\\Models\\' . $request->model_type;
        
        $activities = ActivityLog::with('user')
            ->where('model_type', $modelClass)
            ->where('model_id', $request->model_id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json($activities);
    }
}
