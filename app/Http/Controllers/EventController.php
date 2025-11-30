<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // Opcional: Constructor para proteger todo el controlador con Spatie
    /*
    public function __construct()
    {
        $this->middleware('permission:events.view')->only('index');
        $this->middleware('permission:events.create')->only(['create', 'store']);
        // etc...
    }
    */

    public function index()
    {
        // Obtener todos los eventos con sus equipos asociados ordenados por fecha de inicio descendente.
        // Usamos 'paginate(10)' para que si hay 100 eventos, no explote la pantalla
        $events = Event::withCount('teams') // Eager loading (optimización)
            ->orderBy('start_date', 'desc')
            ->paginate(9);

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // 1. Validaciones Robustas
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            // Regla 'after': La fecha fin debe ser POSTERIOR a la fecha inicio
            'end_date' => 'required|date|after:start_date', 
        ]);

        // 2. Crear el Evento
        Event::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => true, // Por defecto nace activo
        ]);

        // 3. Redireccionar con Mensaje de Éxito
        return redirect()->route('events.index')
            ->with('success', 'Evento creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
       // Cargamos los equipos
        $event->load(['teams.leader', 'teams.members']);
        
        $userHasTeam = false;
        
        // CORRECCIÓN AQUÍ: Usamos Auth::check() y Auth::id()
        if (Auth::check()) {
            $userHasTeam = $event->teams()->whereHas('members', function($query) {
                $query->where('user_id', Auth::id());
            })->exists();
        }
        
        return view('events.show', compact('event', 'userHasTeam'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
        // 1. Validar (Igual que en store, pero a veces es bueno permitir no cambiar nada)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            // El checkbox si no se marca no se envía, así que lo manejamos abajo
        ]);

        // 2. Actualizar
        $event->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            // Truco para checkbox: Si viene en el request es true, si no, false
            'is_active' => $request->has('is_active'), 
        ]);

        // 3. Redireccionar
        return redirect()->route('events.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // Simplemente lo borramos
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Evento eliminado del sistema.');
    }
}
