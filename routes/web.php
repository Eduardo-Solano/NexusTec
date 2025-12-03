<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\PublicController;
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

// Rutas públicas de ganadores
Route::get('/winners', [PublicController::class, 'winners'])->name('public.winners');
Route::get('/winners/{event}', [PublicController::class, 'eventWinners'])->name('public.event-winners');

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
  // Ruta para ver rankings/resultados de un evento
  Route::get('/events/{event}/rankings', [EventController::class, 'rankings'])->name('events.rankings');
  // Rutas para la gestión de equipos
  Route::resource('teams', TeamController::class);
  // Rutas para la gestión de evaluaciones
  Route::resource('evaluations', EvaluationController::class)->only(['create', 'store']);
  // NUEVA RUTA: Unirse a equipo existente
  Route::post('/teams/{team}/join', [TeamController::class, 'join'])->name('teams.join');
  // Rutas para la gestión de proyectos
  Route::resource('projects', ProjectController::class);
  // Rutas para asignación de jueces a proyectos
  Route::post('/projects/{project}/assign-judge', [ProjectController::class, 'assignJudge'])->name('projects.assign-judge');
  Route::delete('/projects/{project}/remove-judge/{judge}', [ProjectController::class, 'removeJudge'])->name('projects.remove-judge');
  Route::post('/teams/{team}/accept/{user}', [TeamController::class, 'acceptMember'])->name('teams.accept');
  Route::post('/teams/{team}/reject/{user}', [TeamController::class, 'rejectMember'])->name('teams.reject');
  Route::patch('/teams/{team}/advisor/{status}', [TeamController::class, 'respondAdvisory'])
    ->name('teams.advisor.response');

  // Rutas para gestión de criterios (admin y staff/organizadores)
  Route::resource('criteria', CriterionController::class)->middleware('permission:criteria.view');

  // Rutas para gestión de premios (admin y staff)
  Route::resource('awards', AwardController::class)->middleware('role:admin|staff');

  // Ruta para marcar notificaciones como leídas
  Route::post('/notifications/{notification}/read', function ($notificationId) {
    $notification = Auth::user()->notifications()->find($notificationId);
    if ($notification) {
      $notification->markAsRead();
    }
    return response()->json(['success' => true]);
  })->name('notifications.markAsRead');

  // Ruta para marcar todas las notificaciones como leídas
  Route::post('/notifications/read-all', function () {
    Auth::user()->unreadNotifications->markAsRead();
    return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
  })->name('notifications.markAllAsRead');

  // Rutas exclusivas de administrador
  Route::group(['middleware' => ['role:admin']], function () {
    // Rutas para gestión de personal (admin)
    Route::resource('staff', StaffProfileController::class);
    // Rutas para gestión de estudiantes (admin)
    Route::resource('students', StudentProfileController::class);
    // Rutas para gestión de jueces (admin)
    Route::resource('judges', JudgeController::class);
  });
});

require __DIR__ . '/auth.php';
