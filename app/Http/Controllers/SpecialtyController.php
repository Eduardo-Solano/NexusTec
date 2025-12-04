<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Specialty::withCount('judgeProfiles');

        // Búsqueda
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $specialties = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('specialties.index', compact('specialties'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('specialties.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:specialties,name',
        ]);

        $specialty = Specialty::create($validated);

        ActivityLog::log('created', "Especialidad '{$specialty->name}' creada", $specialty);

        return redirect()->route('specialties.index')
            ->with('success', 'Especialidad creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Specialty $specialty)
    {
        $specialty->load(['judgeProfiles.user']);
        return view('specialties.show', compact('specialty'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Specialty $specialty)
    {
        return view('specialties.edit', compact('specialty'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialty $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:specialties,name,' . $specialty->id,
        ]);

        $specialty->update($validated);

        ActivityLog::log('updated', "Especialidad '{$specialty->name}' actualizada", $specialty);

        return redirect()->route('specialties.index')
            ->with('success', 'Especialidad actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialty $specialty)
    {
        // Verificar si tiene jueces asociados
        if ($specialty->judgeProfiles()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la especialidad porque tiene jueces asociados.');
        }

        $name = $specialty->name;
        $specialty->delete();

        ActivityLog::log('deleted', "Especialidad '{$name}' eliminada");

        return redirect()->route('specialties.index')
            ->with('success', 'Especialidad eliminada exitosamente.');
    }
}
