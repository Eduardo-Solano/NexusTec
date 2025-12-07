<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\ActivityLog;
use App\Http\Requests\Career\StoreCareerRequest;
use App\Http\Requests\Career\UpdateCareerRequest;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Career::withCount('studentProfiles');

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $careers = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('careers.index', compact('careers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('careers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCareerRequest $request)
    {
        $validated = $request->validated();

        $career = Career::create($validated);

        ActivityLog::log('created', "Carrera '{$career->name}' creada", $career);

        return redirect()->route('careers.index')
            ->with('success', 'Carrera creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Career $career)
    {
        $career->load(['studentProfiles.user']);
        return view('careers.show', compact('career'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Career $career)
    {
        return view('careers.edit', compact('career'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCareerRequest $request, Career $career)
    {
        $validated = $request->validated();

        $career->update($validated);

        ActivityLog::log('updated', "Carrera '{$career->name}' actualizada", $career);

        return redirect()->route('careers.index')
            ->with('success', 'Carrera actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Career $career)
    {
        // Verificar si tiene estudiantes asociados
        if ($career->studentProfiles()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la carrera porque tiene estudiantes asociados.');
        }

        $name = $career->name;
        $career->delete();

        ActivityLog::log('deleted', "Carrera '{$name}' eliminada");

        return redirect()->route('careers.index')
            ->with('success', 'Carrera eliminada exitosamente.');
    }
}
