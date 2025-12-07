<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use App\Http\Requests\Criterion\StoreCriterionRequest;
use App\Http\Requests\Criterion\UpdateCriterionRequest;
use Illuminate\Http\Request;

class CriterionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criteria = Criterion::withCount('events')
            ->orderBy('name')
            ->paginate(15);

        return view('criteria.index', compact('criteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('criteria.create');
    }
     //Se agrega un comentario ffdfjfjfj
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCriterionRequest $request)
    {
        $validated = $request->validated();

        Criterion::create($validated);

        return redirect()->route('criteria.index')
            ->with('success', 'Criterio creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Criterion $criterion)
    {
        return view('criteria.show', compact('criterion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Criterion $criterion)
    {
        return view('criteria.edit', compact('criterion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCriterionRequest $request, Criterion $criterion)
    {
        $validated = $request->validated();

        $criterion->update($validated);

        return redirect()->route('criteria.index')
            ->with('success', 'Criterio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criterion $criterion)
    {
        // Verificar si tiene evaluaciones asociadas
        if ($criterion->evaluations()->exists()) {
            return redirect()->route('criteria.index')
                ->with('error', 'No se puede eliminar el criterio porque tiene evaluaciones asociadas.');
        }

        $criterion->events()->detach();
        $criterion->delete();

        return redirect()->route('criteria.index')
            ->with('success', 'Criterio eliminado exitosamente.');
    }
}
