<?php

namespace App\Http\Controllers;

use App\Models\JudgeProfile;
use App\Models\User;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class JudgeController extends Controller
{
    /**
     * Display a listing of the judges.
     */
    public function index()
    {
        $judges = User::role('judge')->with('judgeProfile')->paginate(10);
        return view('judges.index', compact('judges'));
    }

    /**
     * Show the form for creating a new judge.
     */
    public function create()
    {
        $specialties = Specialty::all();
        return view('judges.create', compact('specialties'));
    }

    /**
     * Store a newly created judge in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make('password'), // contraseña temporal
                'is_active' => true,
            ]);

            // asegurar rol
            Role::findOrCreate('judge');
            $user->assignRole('judge');

            JudgeProfile::create([
                'user_id' => $user->id,
                'specialty_id' => $request->specialty_id,
                'company' => $request->company,
            ]);
        });

        return redirect()->route('judges.index')->with('success', 'Juez registrado correctamente. Contraseña temporal: "password"');
    }

    /**
     * Remove the specified judge from storage.
     */
    public function destroy(User $judge)
    {
        $judge->delete();
        return back()->with('success', 'Juez eliminado.');
    }
}
