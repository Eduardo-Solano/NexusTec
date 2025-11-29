<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

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
        $events = Event::with('teams') // Eager loading (optimización)
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
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
