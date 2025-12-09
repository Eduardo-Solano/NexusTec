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
use App\Http\Controllers\CareerController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\ReportController;

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

    /* EVENTOS - Todos pueden ver, solo admin/staff pueden gestionar */
    Route::get('/events', [EventController::class, 'index'])->name('events.index');

    Route::middleware(['role:admin|staff'])->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::patch('/events/{event}', [EventController::class, 'update']);
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
        // Gestión de jueces en eventos
        Route::post('/events/{event}/assign-judge', [EventController::class, 'assignJudge'])->name('events.assign-judge');
        Route::delete('/events/{event}/remove-judge/{judge}', [EventController::class, 'removeJudge'])->name('events.remove-judge');
    });

    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/rankings', [EventController::class, 'rankings'])->name('events.rankings');

    /* EXPORTACIONES - Solo admin/staff */
    Route::middleware(['role:admin|staff'])->group(function () {
        Route::get('/events/{event}/export/excel', [ExportController::class, 'rankingsExcel'])->name('export.rankings.excel');
        Route::get('/events/{event}/export/pdf', [ExportController::class, 'rankingsPdf'])->name('export.rankings.pdf');
        Route::get('/events/{event}/diplomas', [ExportController::class, 'diplomasIndex'])->name('export.diplomas');
        Route::get('/events/{event}/diploma/{user}', [ExportController::class, 'diplomaParticipation'])->name('export.diploma.participation');
        Route::get('/awards/{award}/diploma/{user}', [ExportController::class, 'diplomaWinner'])->name('export.diploma.winner');
        // Generación masiva de diplomas
        Route::get('/events/{event}/diplomas/team/{team}', [ExportController::class, 'diplomasByTeam'])->name('export.diplomas.team');
        Route::get('/events/{event}/diplomas/all', [ExportController::class, 'diplomasByEvent'])->name('export.diplomas.event');
        // Envío de diplomas por correo
        Route::post('/events/{event}/diploma/{user}/send', [ExportController::class, 'sendDiplomaToUser'])->name('export.diploma.send');
        Route::post('/events/{event}/diplomas/team/{team}/send', [ExportController::class, 'sendDiplomasToTeam'])->name('export.diplomas.team.send');
        Route::post('/events/{event}/diplomas/send-all', [ExportController::class, 'sendDiplomasToEvent'])->name('export.diplomas.send');
    });

    /* EQUIPOS - Todos pueden ver, estudiantes pueden crear/unirse */
    Route::middleware(['role:student'])->group(function () {
        Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
        Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
        Route::post('/teams/{team}/join', [TeamController::class, 'requestJoin'])->name('teams.join');
        Route::post('/teams/{team}/accept/{user}', [TeamController::class, 'accept'])->name('teams.accept');
        Route::post('/teams/{team}/reject/{user}', [TeamController::class, 'reject'])->name('teams.reject');
        Route::post('/teams/{team}/invitations/accept/{notification?}', [TeamController::class, 'acceptInvitation'])->name('teams.invitations.accept');
        Route::post('/teams/{team}/invitations/reject/{notification?}', [TeamController::class, 'rejectInvitation'])->name('teams.invitations.reject');
        // Nuevas rutas de gestión de miembros
        Route::post('/teams/{team}/leave', [TeamController::class, 'leaveTeam'])->name('teams.leave');
        Route::delete('/teams/{team}/kick/{user}', [TeamController::class, 'kickMember'])->name('teams.kick');
        Route::post('/teams/{team}/transfer/{user}', [TeamController::class, 'transferLeadership'])->name('teams.transfer');
        Route::delete('/teams/{team}/cancel-invitation/{user}', [TeamController::class, 'cancelInvitation'])->name('teams.cancel-invitation');
    });

    Route::middleware(['role:admin|staff'])->group(function () {
        Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
        Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
        Route::patch('/teams/{team}', [TeamController::class, 'update']);
    });

    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');

    /* PROYECTOS - Todos pueden ver, estudiantes entregan, admin/staff gestionan */
    Route::middleware(['role:student'])->group(function () {
        Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
        Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    });

    Route::middleware(['role:admin|staff|student'])->group(function () {
        Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
        Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
        Route::patch('/projects/{project}', [ProjectController::class, 'update']);
    });

    Route::middleware(['role:admin|staff'])->group(function () {
        Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
        Route::post('/projects/{project}/assign-judge', [ProjectController::class, 'assignJudge'])->name('projects.assign-judge');
        Route::delete('/projects/{project}/remove-judge/{judge}', [ProjectController::class, 'removeJudge'])->name('projects.remove-judge');
    });

    Route::middleware(['auth'])->group(function () {

        // ...tus otras rutas...

        Route::post('/teams/{team}/invitations/check', [TeamController::class, 'checkInvitationEmail'])
            ->name('teams.invitations.check');
    });
    Route::get('/teams/{team}/invitations/create', [TeamController::class, 'edit'])
        ->name('teams.invitations.create');

    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

    /* EVALUACIONES - Solo jueces */
    Route::middleware(['role:judge'])->group(function () {
        Route::get('/evaluations/create', [EvaluationController::class, 'create'])->name('evaluations.create');
        Route::post('/evaluations', [EvaluationController::class, 'store'])->name('evaluations.store');
    });

    /* ASESOR RESPONDE SOLICITUD */
    Route::patch('/teams/{team}/advisor/{status}', [TeamController::class, 'respondAdvisory'])
        ->middleware('role:advisor|staff')
        ->name('teams.advisor.response');

    /* CRITERIOS - Solo admin/staff */
    Route::middleware(['role:admin|staff'])->group(function () {
        Route::resource('criteria', CriterionController::class);
    });

    /* PREMIOS - Solo admin/staff */
    Route::middleware(['role:admin|staff'])->group(function () {
        Route::resource('awards', AwardController::class);
        Route::post('/events/{event}/generate-winners', [AwardController::class, 'generateWinners'])->name('events.generateWinners');
    });

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
        Route::post('/staff/import-csv', [StaffProfileController::class, 'importCsv'])->name('staff.importCsv');

        Route::resource('students', StudentProfileController::class);
        Route::post('/students/import-csv', [StudentProfileController::class, 'importCsv'])->name('students.importCsv');

        Route::resource('judges', JudgeController::class);
        Route::post('/judges/import-csv', [JudgeController::class, 'importCsv'])->name('judges.importCsv');

        /* GESTIÓN DE CARRERAS */
        Route::resource('careers', CareerController::class);

        /* GESTIÓN DE ESPECIALIDADES */
        Route::resource('specialties', SpecialtyController::class);

        /* REPORTES Y ESTADÍSTICAS */
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/by-event', [ReportController::class, 'byEvent'])->name('reports.by-event');
        Route::get('/reports/by-career', [ReportController::class, 'byCareer'])->name('reports.by-career');
        Route::get('/reports/by-period', [ReportController::class, 'byPeriod'])->name('reports.by-period');
        Route::get('/reports/export-participants', [ReportController::class, 'exportParticipants'])->name('reports.export-participants');

        /* HISTORIAL DE ACTIVIDADES */
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    });

});

require __DIR__ . '/auth.php';
