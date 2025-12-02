<?php

namespace App\Http\Controllers;

use App\Models\StaffProfile;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;

class StaffProfileController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    // Filtramos usuarios que tengan el rol 'staff' o 'advisor'
    $staffMembers = User::role(['staff', 'advisor'])->with('staffProfile')->paginate(10);
    return view('staff.index', compact('staffMembers'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
    return view('staff.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
      'employee_number' => ['required', 'string', 'unique:staff_profiles'],
      'department' => ['required', 'string'],
      'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    DB::transaction(function () use ($request) {
      // 1. Crear Usuario
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'is_active' => true,
      ]);

      // 2. Asignar Roles (Staff y Advisor)
      $user->assignRole(['staff', 'advisor']);

      // 3. Crear Perfil
      StaffProfile::create([
        'user_id' => $user->id,
        'employee_number' => $request->employee_number,
        'department' => $request->department,
      ]);
    });

    return redirect()->route('staff.index')->with('success', 'Docente registrado correctamente.');

  }

  /**
   * Display the specified resource.
   */
  public function show(StaffProfile $staffProfile)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(User $staff)
  {
    // Cargamos el perfil para que no de error al acceder
    $staff->load('staffProfile');
    return view('staff.edit', compact('staff'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, User $staff)
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      // Validamos unique ignorando el ID actual para que no choque consigo mismo
      'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $staff->id],
      'employee_number' => ['required', 'string'], // Podrías validar unique en staff_profiles ignorando ID
      'department' => ['required', 'string'],
    ]);

    DB::transaction(function () use ($request, $staff) {
      // 1. Actualizar Usuario Base
      $staff->update([
        'name' => $request->name,
        'email' => $request->email,
      ]);

      // 2. Si marcó el checkbox, resetear contraseña
      if ($request->has('reset_password')) {
        $staff->update([
          'password' => Hash::make('password'),
        ]);
      }

      // 3. Actualizar Perfil (StaffProfile)
      // updateOrCreate es útil por si el perfil fue borrado manualmente o no existía
      $staff->staffProfile()->updateOrCreate(
        ['user_id' => $staff->id],
        [
          'employee_number' => $request->employee_number,
          'department' => $request->department,
        ]
      );
    });

    return redirect()->route('staff.index')->with('success', 'Información actualizada correctamente.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(User $staff)
  {
    // Cambio: De auth()->id() a Auth::id()
    if ($staff->id === Auth::id()) {
      return back()->with('error', 'No puedes eliminar tu propia cuenta.');
    }
    $staff->delete();
    return back()->with('success', 'Docente eliminado del sistema.');
  }
}
