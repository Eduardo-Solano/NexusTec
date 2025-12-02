<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\JudgeController;
use App\Models\Event;

Route::get('/', function () {
  return view('welcome');
});

Route::get('/dashboard', function () {
  return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Ruta para el Calendario Público
Route::get('/calendar', function () {
  // Obtenemos eventos ordenados por fecha de inicio, solo los futuros o recientes
  $events = Event::where('end_date', '>=', now()->subMonths(1)) // Incluimos recientes de hace 1 mes
    ->orderBy('start_date', 'asc')
    ->get()
    ->groupBy(function ($date) {
      // Agrupamos por Mes y Año (Ej: "Diciembre 2025")
      return \Carbon\Carbon::parse($date->start_date)->format('F Y');
    });

  return view('public.calendar', compact('events'));
})->name('public.calendar');

Route::middleware('auth')->group(function () {

  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  Route::get('/recuperarcontra', function () {
    return view('auth.reset-password');
  });

  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
  Route::patch('/projects/{project}/advisor/{status}', [ProjectController::class, 'respondAdvisory'])
    ->name('projects.advisor.response');
  Route::group(['middleware' => ['role:admin']], function () {
    Route::resource('staff', StaffProfileController::class);
    Route::resource('students', StudentProfileController::class);
    // Rutas para gestión de jueces (admin)
    Route::resource('judges', JudgeController::class);
  });
});

require __DIR__ . '/auth.php';
