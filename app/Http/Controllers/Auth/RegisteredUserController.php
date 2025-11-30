<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Career;
use App\Models\Specialty;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Enviamos los catálogos a la vista
        $careers = Career::all();
        $specialties = Specialty::all();
        
        return view('auth.register', compact('careers', 'specialties'));    
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
       $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'phone' => ['required', 'string', 'max:20'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'role' => ['required', 'in:student,judge'], // Validamos el rol
    ]);

    // Transacción para garantizar integridad
    $user = DB::transaction(function () use ($request) {
        
        // 1. Crear Usuario Base
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'control_number' => $request->role === 'student' ? $request->control_number : null,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        // 2. Asignar Rol Spatie
        $user->assignRole($request->role);

        // 3. Crear Perfil Específico
        if ($request->role === 'student') {
            $user->studentProfile()->create([
                'career_id' => $request->career_id,
                'semester' => $request->semester,
            ]);
        } elseif ($request->role === 'judge') {
            $user->judgeProfile()->create([
                'specialty_id' => $request->specialty_id,
                'company' => $request->company,
            ]);
        }

        return $user;
    });

    event(new Registered($user));

    Auth::login($user);

        return redirect()->route('dashboard');
    }
}
