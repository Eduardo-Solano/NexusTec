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
    public function index(Request $request)
    {
        $query = User::role(['staff', 'advisor'])->with('staffProfile');

        // Filtro por búsqueda (nombre, email o número de empleado)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('staffProfile', function ($q) use ($search) {
                      $q->where('employee_number', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por tipo de rol
        if ($request->filled('role_type')) {
            if ($request->role_type === 'staff') {
                $query->role('staff');
            } elseif ($request->role_type === 'advisor') {
                $query->role('advisor')->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'staff');
                });
            } elseif ($request->role_type === 'both') {
                $query->role('staff')->role('advisor');
            }
        }

        $staffMembers = $query->paginate(10)->withQueryString();

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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'employee_number' => ['required', 'string', 'unique:staff_profiles'],
            'department' => ['required', 'string'],
            'staff_type' => ['required', 'in:advisor,staff,both'],
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

            // 2. Asignar Roles según el tipo seleccionado
            $roles = match($request->staff_type) {
                'advisor' => ['advisor'],
                'staff' => ['staff'],
                'both' => ['staff', 'advisor'],
            };
            $user->assignRole($roles);

            // 3. Crear Perfil
            StaffProfile::create([
                'user_id' => $user->id,
                'employee_number' => $request->employee_number,
                'department' => $request->department,
            ]);
        });

        $typeLabel = match($request->staff_type) {
            'advisor' => 'Docente',
            'staff' => 'Organizador',
            'both' => 'Docente/Organizador',
        };

        return redirect()->route('staff.index')->with('success', "$typeLabel registrado correctamente.");
    
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
            'staff_type' => ['required', 'in:advisor,staff,both'],
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

            // 3. Actualizar Roles según el tipo seleccionado
            // Primero quitamos los roles anteriores de staff/advisor
            $staff->removeRole('staff');
            $staff->removeRole('advisor');
            
            // Asignar nuevos roles
            $roles = match($request->staff_type) {
                'advisor' => ['advisor'],
                'staff' => ['staff'],
                'both' => ['staff', 'advisor'],
            };
            $staff->assignRole($roles);

            // 4. Actualizar Perfil (StaffProfile)
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

    /**
     * Importar docentes desde un archivo CSV
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
                // 2 = No. Empleado
                // 3 = Departamento
                // 4 = Tipo (advisor/staff/both) - opcional, default: advisor
                $name = trim($data[0] ?? '');
                $email = trim($data[1] ?? '');
                $employeeNumber = trim($data[2] ?? '');
                $department = trim($data[3] ?? '');
                $staffType = strtolower(trim($data[4] ?? 'advisor'));

                // Normalizar tipo
                if (!in_array($staffType, ['advisor', 'staff', 'both'])) {
                    $staffType = 'advisor';
                }

                // Acumular errores
                $errors = [];

                if (!$name) $errors[] = "Nombre vacío";
                if (!$email) $errors[] = "Correo vacío";
                if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Correo inválido";
                
                // Verificar correo duplicado
                if ($email && User::where('email', $email)->exists()) {
                    $errors[] = "Correo duplicado";
                }
                
                // Verificar nombre duplicado
                if ($name && User::where('name', $name)->exists()) {
                    $errors[] = "Ya existe un usuario con este nombre";
                }
                
                if (!$employeeNumber) $errors[] = "No. empleado vacío";
                
                // Verificar número de empleado duplicado
                if ($employeeNumber && StaffProfile::where('employee_number', $employeeNumber)->exists()) {
                    $errors[] = "No. empleado duplicado";
                }
                
                if (!$department) $errors[] = "Departamento vacío";

                // Si tuvo errores → enviarlo a la lista de fallos
                if (count($errors) > 0) {
                    $failed[] = [
                        'row' => $rowNumber,
                        'name' => $name,
                        'email' => $email,
                        'employee_number' => $employeeNumber,
                        'department' => $department,
                        'errors' => $errors
                    ];
                    continue;
                }

                // INSERTAR DOCENTE CORRECTO
                try {
                    DB::transaction(function () use ($name, $email, $employeeNumber, $department, $staffType, &$imported) {
                        $user = User::create([
                            'name' => $name,
                            'email' => $email,
                            'password' => Hash::make('password'),
                            'is_active' => true,
                        ]);

                        // Asignar roles según tipo
                        $roles = match($staffType) {
                            'advisor' => ['advisor'],
                            'staff' => ['staff'],
                            'both' => ['staff', 'advisor'],
                        };
                        $user->assignRole($roles);

                        StaffProfile::create([
                            'user_id' => $user->id,
                            'employee_number' => $employeeNumber,
                            'department' => $department,
                        ]);

                        $imported[] = [
                            'name' => $name,
                            'email' => $email,
                            'employee_number' => $employeeNumber,
                            'department' => $department,
                            'type' => $staffType,
                        ];
                    });
                } catch (\Exception $e) {
                    // Limpiar mensaje de error para el usuario
                    $errorMessage = 'Error al guardar el registro';
                    
                    if (str_contains($e->getMessage(), 'Duplicate entry')) {
                        $errorMessage = 'Registro duplicado en el sistema';
                    } elseif (str_contains($e->getMessage(), 'employee_number')) {
                        $errorMessage = 'Número de empleado duplicado';
                    } elseif (str_contains($e->getMessage(), 'foreign key')) {
                        $errorMessage = 'Error de validación de datos';
                    }
                    
                    $failed[] = [
                        'row' => $rowNumber,
                        'name' => $name,
                        'email' => $email,
                        'employee_number' => $employeeNumber,
                        'department' => $department,
                        'errors' => [$errorMessage]
                    ];
                }
            }

            fclose($handle);

            return response()->json([
                'success' => true,
                'imported' => $imported,
                'failed' => $failed,
                'total_imported' => count($imported),
                'total_failed' => count($failed),
                'default_password' => 'password',
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
}
