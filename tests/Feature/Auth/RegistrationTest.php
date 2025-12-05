<?php

use App\Models\User;
use App\Models\Career;
use App\Notifications\EmailVerificationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register with valid institutional email', function () {
    Notification::fake();
    
    // Crear el rol de estudiante necesario
    Role::create(['name' => 'student']);
    
    // Crear una carrera para el test
    $career = Career::factory()->create();
    
    $controlNumber = '19160001';
    $email = $controlNumber . '@itoaxaca.edu.mx';
    
    // Paso 1: Enviar datos de registro (genera código de verificación)
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => $email,
        'phone' => '9511234567',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'student',
        'control_number' => $controlNumber,
        'career_id' => $career->id,
        'semester' => 5,
    ]);
    
    // Debe redirigir a la página de verificación
    $response->assertRedirect(route('register.verify', ['email' => $email]));
    
    // Verificar que se envió la notificación con el código
    Notification::assertSentOnDemand(EmailVerificationCode::class);
    
    // Obtener el código de verificación de la base de datos
    $verificationRecord = DB::table('email_verification_codes')
        ->where('email', $email)
        ->first();
    
    expect($verificationRecord)->not->toBeNull();
    
    // Paso 2: Verificar el código
    $response = $this->post('/register/verify', [
        'email' => $email,
        'code' => $verificationRecord->code,
    ]);
    
    // Debug: ver si hay errores de sesión
    if ($response->status() !== 302 || $response->isRedirect(route('dashboard')) === false) {
        // Si hay error de validación, el test falla con info útil
        $response->assertSessionHasNoErrors();
    }
    
    // Verificar que el usuario fue creado
    $this->assertDatabaseHas('users', [
        'email' => $email,
        'name' => 'Test User',
    ]);
    
    // Verificar que el perfil de estudiante fue creado
    $user = User::where('email', $email)->first();
    expect($user)->not->toBeNull();
    expect($user->studentProfile)->not->toBeNull();
    expect($user->studentProfile->control_number)->toBe($controlNumber);
    
    // Verificar redirección al dashboard
    $response->assertRedirect(route('dashboard'));
});

test('registration fails with non-institutional email', function () {
    $career = Career::factory()->create();
    
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '9511234567',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'student',
        'control_number' => '19160001',
        'career_id' => $career->id,
        'semester' => 5,
    ]);
    
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('registration fails when email does not match control number', function () {
    $career = Career::factory()->create();
    
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => '19160002@itoaxaca.edu.mx',
        'phone' => '9511234567',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'role' => 'student',
        'control_number' => '19160001', // No coincide con el email
        'career_id' => $career->id,
        'semester' => 5,
    ]);
    
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});
