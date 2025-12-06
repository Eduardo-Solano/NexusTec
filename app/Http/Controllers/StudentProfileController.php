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
    public function index(Request $request)
    {
        // Obtener carreras para el filtro
        $careers = Career::orderBy('name')->get();

        $query = User::role('student')->with('studentProfile.career');

        // Filtro por búsqueda (nombre, email o número de control)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('studentProfile', function ($q) use ($search) {
                        $q->where('control_number', 'like', "%{$search}%");
                    });
            });
        }

        // Filtro por carrera
        if ($request->filled('career_id')) {
            $query->whereHas('studentProfile', function ($q) use ($request) {
                $q->where('career_id', $request->career_id);
            });
        }

        $students = $query->paginate(15)->withQueryString();

        return view('students.index', compact('students', 'careers'));
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
    public function importCsv(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|mimes:csv,txt'
            ]);

            $file = $request->file('csv_file');
            $handle = fopen($file, 'r');

            $rowNumber = 0;
            $imported = [];
            $failed = [];

            while (($data = fgetcsv($handle, 2000, ',')) !== false) {
                $rowNumber++;

                // Ignorar encabezado
                if ($rowNumber == 1) {
                    continue;
                }

                // Columnas esperadas:
                // 0 = Nombre
                // 1 = Correo
                // 2 = Matricula
                // 3 = Carrera (texto)
                $name = trim($data[0] ?? '');
                $email = trim($data[1] ?? '');
                $controlNumber = trim($data[2] ?? '');
                $careerText = trim($data[3] ?? '');

                // Resolver carrera por fuzzy matching
                $careerId = \App\Models\Career::detectFromName($careerText);

                // Acumular errores
                $errors = [];

                if (!$name)
                    $errors[] = "Nombre vacío";
                if (!$email)
                    $errors[] = "Correo vacío";
                if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL))
                    $errors[] = "Correo inválido";

                // Verificar correo duplicado
                if ($email && \App\Models\User::where('email', $email)->exists())
                    $errors[] = "Correo duplicado";

                // Verificar nombre duplicado
                if ($name && \App\Models\User::where('name', $name)->exists())
                    $errors[] = "Ya existe un usuario con este nombre";

                if (!$controlNumber)
                    $errors[] = "Número de control vacío";
                    
                // Verificar número de control duplicado
                if ($controlNumber && \App\Models\StudentProfile::where('control_number', $controlNumber)->exists())
                    $errors[] = "Número de control duplicado";

                if (!$careerId)
                    $errors[] = "Carrera no identificada";

                // Si tuvo errores → enviarlo a la lista de fallos
                if (count($errors) > 0) {
                    $failed[] = [
                        'row' => $rowNumber,
                        'name' => $name,
                        'email' => $email,
                        'control_number' => $controlNumber,
                        'career' => $careerText,
                        'errors' => $errors
                    ];
                    continue;
                }

                // INSERTAR ALUMNO CORRECTO
                try {
                    DB::transaction(function () use ($name, $email, $controlNumber, $careerId, &$imported) {
                        // 1. Crear usuario
                        $user = \App\Models\User::create([
                            'name' => $name,
                            'email' => $email,
                            'password' => \Illuminate\Support\Facades\Hash::make('password'),
                            'is_active' => true,
                        ]);

                        // 2. Asignar rol student
                        if (method_exists($user, 'assignRole')) {
                            $user->assignRole('student');
                        }

                        // 3. Crear perfil
                        \App\Models\StudentProfile::create([
                            'user_id' => $user->id,
                            'control_number' => $controlNumber,
                            'career_id' => $careerId,
                        ]);

                        // 4. Registrar como importado
                        $imported[] = [
                            'name' => $name,
                            'email' => $email,
                            'control_number' => $controlNumber,
                            'career_id' => $careerId,
                        ];
                    });
                } catch (\Exception $e) {
                    // Limpiar mensaje de error para el usuario
                    $errorMessage = 'Error al guardar el registro';
                    
                    if (str_contains($e->getMessage(), 'Duplicate entry')) {
                        $errorMessage = 'Registro duplicado en el sistema';
                    } elseif (str_contains($e->getMessage(), 'career_id')) {
                        $errorMessage = 'Carrera no válida';
                    } elseif (str_contains($e->getMessage(), 'control_number')) {
                        $errorMessage = 'Número de control duplicado';
                    } elseif (str_contains($e->getMessage(), 'foreign key')) {
                        $errorMessage = 'Error de validación de datos';
                    }
                    
                    $failed[] = [
                        'row' => $rowNumber,
                        'name' => $name,
                        'email' => $email,
                        'control_number' => $controlNumber,
                        'career' => $careerText,
                        'errors' => [$errorMessage]
                    ];
                }
            }

            fclose($handle);

            // Respuesta para el frontend (modal 3 pasos)
            return response()->json([
                'success' => true,
                'imported' => $imported,
                'failed' => $failed,
                'total_imported' => count($imported),
                'total_failed' => count($failed),
                'default_password' => "password",
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el archivo CSV',
                'imported' => [],
                'failed' => [],
                'total_imported' => 0,
                'total_failed' => 0,
            ], 500);
        }
    }


    public function edit(User $student)
    {
        //

        $student->load('studentProfile.career'); // Cargar perfil y carrera relacionada
        $careers = Career::all();
        return view('students.edit', compact('student', 'careers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'control_number' => 'required|unique:student_profiles,control_number,' . $student->studentProfile->id,
            'career_id' => 'required|exists:careers,id',
            'semester' => 'required|integer|min:1|max:12',
            'phone' => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request, $student) {
            // 1. Actualizar Usuario
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'is_active' => $request->has('is_active'),
            ];

            // Restablecer contraseña si se solicitó
            if ($request->has('reset_password')) {
                $userData['password'] = Hash::make('password');
            }

            $student->update($userData);

            // 2. Actualizar Perfil de Estudiante
            $student->studentProfile->update([
                'control_number' => $request->control_number,
                'career_id' => $request->career_id,
                'semester' => $request->semester,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Alumno actualizado correctamente.');
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
