<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Verificar que el usuario existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'No encontramos un usuario con ese correo.']);
        }

        // Generar código de 8 caracteres (letras mayúsculas y números)
        $code = strtoupper(Str::random(8));

        // Eliminar códigos anteriores para este email
        DB::table('password_reset_codes')->where('email', $request->email)->delete();

        // Guardar el nuevo código (expira en 60 minutos)
        DB::table('password_reset_codes')->insert([
            'email' => $request->email,
            'code' => $code,
            'created_at' => now(),
            'expires_at' => now()->addMinutes(60),
        ]);

        // Enviar el código por correo
        $user->notify(new ResetPasswordNotification($code));

        return redirect()->route('password.verify', ['email' => $request->email])
            ->with('status', 'Hemos enviado un código de recuperación a tu correo.');
    }
}
