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
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ActivityLogController;
use App\Models\Event;

// Página de inicio
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Calendario Público
Route::get('/calendar', function () {
    $events = Event::where('end_date', '>=', now()->subMonths(1))
        ->orderBy('start_date', 'asc')
        ->get()
        ->groupBy(fn($e) => \Carbon\Carbon::parse($e->start_date)->format('F Y'));

    return view('public.calendar', compact('events'));
})->name('public.calendar');

// Ganadores públicos
Route::get('/winners', [PublicController::class, 'winners'])->name('public.winners');
Route::get('/winners/{event}', [PublicController::class, 'eventWinners'])->name('public.event-winners');


// -----------------------------------------------------
// RUTAS CON AUTENTICACIÓN
// -----------------------------------------------------
Route::middleware('auth')->group(function () {

    /* PERFIL */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/recuperarcontra', fn() => view('auth.reset-password'));

    /* DASHBOARD */
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* EVENTOS */
    Route::resource('events', EventController::class);
    Route::get('/events/{event}/rankings', [EventController::class, 'rankings'])->name('events.rankings');

    /* EXPORTACIONES */
    Route::get('/events/{event}/export/excel', [ExportController::class, 'rankingsExcel'])->name('export.rankings.excel');
    Route::get('/events/{event}/export/pdf', [ExportController::class, 'rankingsPdf'])->name('export.rankings.pdf');
    Route::get('/events/{event}/diplomas', [ExportController::class, 'diplomasIndex'])->name('export.diplomas');
    Route::get('/events/{event}/diploma/{user}', [ExportController::class, 'diplomaParticipation'])->name('export.diploma.participation');
    Route::get('/awards/{award}/diploma/{user}', [ExportController::class, 'diplomaWinner'])->name('export.diploma.winner');

    /* EQUIPOS */
    Route::resource('teams', TeamController::class);

    // ✨ Solicitar unirse a un equipo (ESTUDIANTE)
    Route::post('/teams/{team}/join', [TeamController::class, 'requestJoin'])->name('teams.join');

    // ✨ Líder acepta o rechaza solicitudes
    Route::post('/teams/{team}/accept/{user}', [TeamController::class, 'accept'])->name('teams.accept');
    Route::post('/teams/{team}/reject/{user}', [TeamController::class, 'reject'])->name('teams.reject');

    // ✨ Aceptar / rechazar invitaciones (INVITADO)
    Route::post('/teams/{team}/invitations/accept/{notification?}', [TeamController::class, 'acceptInvitation'])
        ->name('teams.invitations.accept');

    Route::post('/teams/{team}/invitations/reject/{notification?}', [TeamController::class, 'rejectInvitation'])
        ->name('teams.invitations.reject');

    /* PROYECTOS */
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/assign-judge', [ProjectController::class, 'assignJudge'])->name('projects.assign-judge');
    Route::delete('/projects/{project}/remove-judge/{judge}', [ProjectController::class, 'removeJudge'])->name('projects.remove-judge');

    /* EVALUACIONES */
    Route::resource('evaluations', EvaluationController::class)->only(['create', 'store']);

    /* ASESOR RESPONDE SOLICITUD */
    Route::patch('/teams/{team}/advisor/{status}', [TeamController::class, 'respondAdvisory'])
        ->name('teams.advisor.response');

    /* CRITERIOS */
    Route::resource('criteria', CriterionController::class)->middleware('permission:criteria.view');

    /* PREMIOS */
    Route::resource('awards', AwardController::class)->middleware('role:admin|staff');

    /* NOTIFICACIONES */
    Route::post('/notifications/{notification}/read', function ($notificationId) {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification)
            $notification->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.markAsRead');

    Route::post('/notifications/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    })->name('notifications.markAllAsRead');

    /* ADMINISTRACIÓN */
    Route::group(['middleware' => ['role:admin']], function () {

        Route::resource('staff', StaffProfileController::class);
        Route::resource('students', StudentProfileController::class);
        Route::resource('judges', JudgeController::class);

        /* HISTORIAL DE ACTIVIDADES */
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    });
});

require __DIR__ . '/auth.php';
