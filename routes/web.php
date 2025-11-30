<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para la gestión de eventos
    Route::resource('events', EventController::class);
    // Rutas para la gestión de equipos
    Route::resource('teams', TeamController::class);
    // NUEVA RUTA: Unirse a equipo existente
    Route::post('/teams/{team}/join', [TeamController::class, 'join'])->name('teams.join');
    // Rutas para la gestión de proyectos
    Route::resource('projects', ProjectController::class);
    Route::post('/teams/{team}/accept/{user}', [TeamController::class, 'acceptMember'])->name('teams.accept');
    Route::post('/teams/{team}/reject/{user}', [TeamController::class, 'rejectMember'])->name('teams.reject');
});

require __DIR__.'/auth.php';
