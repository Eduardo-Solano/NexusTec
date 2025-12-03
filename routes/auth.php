<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    // Rutas para verificación de registro
    Route::get('register/verify', [RegisteredUserController::class, 'showVerifyForm'])
        ->name('register.verify');

    Route::post('register/verify', [RegisteredUserController::class, 'verifyAndRegister'])
        ->name('register.verify.submit');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::get('forgot-password/verify', function (Request $request) {
    return view('auth.verify-token', ['email' => $request->email]);
})->name('password.verify');


// ---------------------------------------------------------------------
// RUTA 2: Verifica el código de 8 caracteres y redirige a cambiar contraseña
// ---------------------------------------------------------------------
Route::post('forgot-password/check-token', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'code' => ['required', 'string', 'size:8'],
    ]);

    // Buscar el código en la base de datos
    $record = \Illuminate\Support\Facades\DB::table('password_reset_codes')
        ->where('email', $request->email)
        ->where('code', strtoupper($request->code))
        ->where('expires_at', '>', now())
        ->first();

    if (!$record) {
        return back()->withErrors(['code' => 'El código es inválido o ha expirado.']);
    }

    // Código válido - redirigir a la vista de nueva contraseña
    return redirect()->route('password.reset', [
        'token' => $request->code,
        'email' => $request->email
    ]);
})->name('password.reset.verify');