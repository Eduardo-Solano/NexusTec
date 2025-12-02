<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Career;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class StudentProfileController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    // Obtener solo usuarios con rol 'student'
    $students = User::role('student')->with('studentProfile.career')->paginate(15);
    return view('students.index', compact('students'));
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    $careers = Career::all();
    return view('students.create', compact('careers'));
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users',
      'control_number' => 'required|unique:student_profiles',
      'career_id' => 'required|exists:careers,id',
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

      // 2. Asignar Roles
      $user->assignRole(['student']);

      // 3. Crear Perfil
      StudentProfile::create([
        'user_id' => $user->id,
        'control_number' => $request->control_number,
        'career_id' => $request->career_id,
      ]);
    });

    return redirect()->route('students.index')->with('success', 'Alumno registrado correctamente.');
  }

  /**
   * Display the specified resource.
   */
  public function show(StudentProfile $studentProfile)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(StudentProfile $studentProfile)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, StudentProfile $studentProfile)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(User $student)
  {
    $student->delete();
    return back()->with('success', 'Alumno eliminado.');
  }
}
