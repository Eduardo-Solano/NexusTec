<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerificationCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
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
        $careers = Career::all();
        $specialties = Specialty::all();
        
        return view('auth.register', compact('careers', 'specialties'));    
    }

    /**
     * PASO 1: Validar datos y enviar código de verificación
     */
    public function store(Request $request): RedirectResponse
    {
        // Validar estructura del correo: número de control (8-10 caracteres alfanuméricos)@itoaxaca.edu.mx
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                'unique:'.User::class,
                'regex:/^[a-z]{0,2}\d{6,8}@itoaxaca\.edu\.mx$/i'
            ],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student'],
            'control_number' => [
                'required', 
                'string', 
                'min:8', 
                'max:10', 
                'regex:/^[A-Za-z]{0,2}\d{6,8}$/',
                'unique:student_profiles,control_number'
            ],
            'career_id' => ['required', 'exists:careers,id'],
            'semester' => ['required', 'integer', 'min:1', 'max:12'],
        ], [
            'email.regex' => 'El correo debe tener el formato: tu_número_control@itoaxaca.edu.mx',
            'control_number.regex' => 'El número de control debe tener 0-2 letras seguidas de 6-8 dígitos (ej: 19161234, L1916123, LE191612)',
            'control_number.min' => 'El número de control debe tener al menos 8 caracteres',
            'control_number.max' => 'El número de control no puede tener más de 10 caracteres',
            'control_number.unique' => 'Este número de control ya está registrado en el sistema.',
        ]);

        // Validar que el email coincida con el número de control
        $expectedEmail = $request->control_number . '@itoaxaca.edu.mx';
        if ($request->email !== $expectedEmail) {
            return back()->withInput()->withErrors([
                'email' => 'El correo debe coincidir con tu número de control: ' . $expectedEmail
            ]);
        }

        // Generar código de 8 caracteres
        $code = strtoupper(Str::random(8));

        // Eliminar códigos anteriores para este email
        DB::table('email_verification_codes')->where('email', $request->email)->delete();

        // Guardar código y datos del formulario
        DB::table('email_verification_codes')->insert([
            'email' => $request->email,
            'code' => $code,
            'registration_data' => json_encode($request->only([
                'name', 'email', 'phone', 'password', 'role',
                'control_number', 'career_id', 'semester'
            ])),
            'created_at' => now(),
            'expires_at' => now()->addMinutes(30),
        ]);

        // Enviar código por correo (usando notificación anónima)
        Notification::route('mail', $request->email)
            ->notify(new EmailVerificationCode($code));

        return redirect()->route('register.verify', ['email' => $request->email])
            ->with('status', 'Hemos enviado un código de verificación a tu correo institucional.');
    }

    /**
     * PASO 2: Mostrar vista para ingresar código
     */
    public function showVerifyForm(Request $request): View
    {
        return view('auth.verify-registration', ['email' => $request->email]);
    }

    /**
     * PASO 3: Verificar código y completar registro
     */
    public function verifyAndRegister(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'string', 'size:8'],
        ]);

        // Buscar el código
        $record = DB::table('email_verification_codes')
            ->where('email', $request->email)
            ->where('code', strtoupper($request->code))
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return back()->withErrors(['code' => 'El código es inválido o ha expirado.']);
        }

        // Recuperar datos del registro
        $data = json_decode($record->registration_data, true);

        // Crear usuario en transacción
        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'is_active' => true,
                'email_verified_at' => now(), // Ya verificado
            ]);

            $user->assignRole('student');

            $user->studentProfile()->create([
                'control_number' => $data['control_number'],
                'career_id' => $data['career_id'],
                'semester' => $data['semester'],
            ]);

            return $user;
        });

        // Eliminar código usado
        DB::table('email_verification_codes')->where('email', $request->email)->delete();

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('status', '¡Registro completado! Bienvenido a NexusTec.');
    }
}
