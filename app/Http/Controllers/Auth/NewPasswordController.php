<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Verificar el código en nuestra tabla personalizada
        $record = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('code', strtoupper($request->token))
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'El código es inválido o ha expirado.']);
        }

        // Buscar el usuario
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'No encontramos un usuario con ese correo.']);
        }

        // Actualizar la contraseña
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
        ])->save();

        // Eliminar el código usado
        DB::table('password_reset_codes')->where('email', $request->email)->delete();

        event(new PasswordReset($user));

        return redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida exitosamente!');
    }
}
