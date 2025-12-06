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
    public function index(Request $request)
    {
        // Obtener especialidades para el filtro
        $specialties = Specialty::orderBy('name')->get();

        $query = User::role('judge')->with(['judgeProfile.specialty', 'assignedProjects']);

        // Filtro por búsqueda (nombre, email o empresa)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('judgeProfile', function ($q) use ($search) {
                      $q->where('company', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por especialidad
        if ($request->filled('specialty_id')) {
            $query->whereHas('judgeProfile', function ($q) use ($request) {
                $q->where('specialty_id', $request->specialty_id);
            });
        }

        $judges = $query->paginate(10)->withQueryString();

        return view('judges.index', compact('judges', 'specialties'));
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

    /**
     * Show the form for editing the specified judge.
     */
    public function edit(User $judge)
    {
        $judge->load('judgeProfile.specialty');
        $specialties = Specialty::all();
        return view('judges.edit', compact('judge', 'specialties'));
    }

    /**
     * Update the specified judge in storage.
     */
    public function update(Request $request, User $judge)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $judge->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'company' => ['nullable', 'string', 'max:255'],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
        ]);

        DB::transaction(function () use ($request, $judge) {
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

            $judge->update($userData);

            // 2. Actualizar Perfil de Juez
            $judge->judgeProfile->update([
                'specialty_id' => $request->specialty_id,
                'company' => $request->company,
            ]);
        });

        return redirect()->route('judges.index')->with('success', 'Juez actualizado correctamente.');
    }

    /**
     * Importar jueces desde un archivo CSV
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
                // 2 = Teléfono (opcional)
                // 3 = Empresa (opcional)
                // 4 = Especialidad (nombre, se detecta por similitud)
                $name = trim($data[0] ?? '');
                $email = trim($data[1] ?? '');
                $phone = trim($data[2] ?? '');
                $company = trim($data[3] ?? '');
                $specialtyText = trim($data[4] ?? '');

                // Resolver especialidad por fuzzy matching
                $specialtyId = null;
                if ($specialtyText) {
                    $specialtyId = Specialty::detectFromName($specialtyText);
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
                
                // Verificar nombre duplicado (advertencia, no error crítico)
                if ($name && User::where('name', $name)->exists()) {
                    $errors[] = "Ya existe un usuario con este nombre";
                }
                
                // Verificar teléfono duplicado
                if ($phone && User::where('phone', $phone)->exists()) {
                    $errors[] = "Teléfono duplicado";
                }

                // Si tuvo errores → enviarlo a la lista de fallos
                if (count($errors) > 0) {
                    $failed[] = [
                        'row' => $rowNumber,
                        'name' => $name,
                        'email' => $email,
                        'company' => $company,
                        'specialty' => $specialtyText,
                        'errors' => $errors
                    ];
                    continue;
                }

                // INSERTAR JUEZ CORRECTO
                try {
                    DB::transaction(function () use ($name, $email, $phone, $company, $specialtyId, &$imported) {
                        $user = User::create([
                            'name' => $name,
                            'email' => $email,
                            'phone' => $phone ?: null,
                            'password' => Hash::make('password'),
                            'is_active' => true,
                        ]);

                        Role::findOrCreate('judge');
                        $user->assignRole('judge');

                        JudgeProfile::create([
                            'user_id' => $user->id,
                            'specialty_id' => $specialtyId,
                            'company' => $company ?: null,
                        ]);

                        $imported[] = [
                            'name' => $name,
                            'email' => $email,
                            'phone' => $phone,
                            'company' => $company,
                        ];
                    });
                } catch (\Exception $e) {
                    // Limpiar mensaje de error para el usuario
                    $errorMessage = 'Error al guardar el registro';
                    
                    if (str_contains($e->getMessage(), 'Duplicate entry')) {
                        $errorMessage = 'Correo electrónico duplicado';
                    } elseif (str_contains($e->getMessage(), 'specialty_id')) {
                        $errorMessage = 'Especialidad no válida';
                    } elseif (str_contains($e->getMessage(), 'foreign key')) {
                        $errorMessage = 'Error de validación de datos';
                    }
                    
                    $failed[] = [
                        'row' => $rowNumber,
                        'name' => $name,
                        'email' => $email,
                        'company' => $company,
                        'specialty' => $specialtyText,
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
