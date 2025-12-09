<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AwardController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\JudgeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\StaffProfileController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\TeamController;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// --- RUTAS PÚBLICAS ---
Route::get('/', function () {
    return view('welcome');
});

Route::get('/calendar', function () {
    $events = Event::where('end_date', '>=', now()->subMonths(1))
        ->orderBy('start_date', 'asc')
        ->get()
        ->groupBy(fn($e) => \Carbon\Carbon::parse($e->start_date)->format('F Y'));

    return view('public.calendar', compact('events'));
})->name('public.calendar');

Route::get('/winners', [PublicController::class, 'winners'])->name('public.winners');
Route::get('/winners/{event}', [PublicController::class, 'eventWinners'])->name('public.event-winners');

Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

// --- RUTAS PROTEGIDAS (AUTH) ---
Route::middleware(['auth'])->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/recuperarcontra', fn() => view('auth.reset-password'));

    // Dashboard
    Route::middleware('verified')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notificaciones
    Route::post('/notifications/{notification}/read', function ($notificationId) {
        $notification = Auth::user()->notifications()->find($notificationId);
        if ($notification) $notification->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.markAsRead');

    Route::post('/notifications/read-all', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    })->name('notifications.markAllAsRead');

    // Validación de invitaciones
    Route::post('/teams/{team}/invitations/check', [TeamController::class, 'checkInvitationEmail'])
        ->name('teams.invitations.check');

    // Teams - Acciones comunes
    Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
    Route::patch('/teams/{team}', [TeamController::class, 'update']);
    Route::get('/teams/{team}/invitations/create', [TeamController::class, 'edit'])->name('teams.invitations.create');
});

// --- RUTAS DE ADMINISTRACIÓN Y STAFF (ROLE: ADMIN|STAFF) ---
Route::middleware(['auth', 'role:admin|staff'])->group(function () {
    
    // Eventos
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::patch('/events/{event}', [EventController::class, 'update']);
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    // Jueces en Eventos
    Route::post('/events/{event}/assign-judge', [EventController::class, 'assignJudge'])->name('events.assign-judge');
    Route::delete('/events/{event}/remove-judge/{judge}', [EventController::class, 'removeJudge'])->name('events.remove-judge');

    // Exportaciones
    Route::get('/events/{event}/export/excel', [ExportController::class, 'rankingsExcel'])->name('export.rankings.excel');
    Route::get('/events/{event}/export/pdf', [ExportController::class, 'rankingsPdf'])->name('export.rankings.pdf');
    Route::get('/events/{event}/diplomas', [ExportController::class, 'diplomasIndex'])->name('export.diplomas');
    Route::get('/events/{event}/diploma/{user}', [ExportController::class, 'diplomaParticipation'])->name('export.diploma.participation');
    Route::get('/awards/{award}/diploma/{user}', [ExportController::class, 'diplomaWinner'])->name('export.diploma.winner');
    Route::get('/events/{event}/diplomas/team/{team}', [ExportController::class, 'diplomasByTeam'])->name('export.diplomas.team');
    Route::get('/events/{event}/diplomas/all', [ExportController::class, 'diplomasByEvent'])->name('export.diplomas.event');
    Route::post('/events/{event}/diploma/{user}/send', [ExportController::class, 'sendDiplomaToUser'])->name('export.diploma.send');
    Route::post('/events/{event}/diplomas/team/{team}/send', [ExportController::class, 'sendDiplomasToTeam'])->name('export.diplomas.team.send');
    Route::post('/events/{event}/diplomas/send-all', [ExportController::class, 'sendDiplomasToEvent'])->name('export.diplomas.send');

    // Gestión de Equipos y Proyectos
    Route::delete('/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{project}/assign-judge', [ProjectController::class, 'assignJudge'])->name('projects.assign-judge');
    Route::delete('/projects/{project}/remove-judge/{judge}', [ProjectController::class, 'removeJudge'])->name('projects.remove-judge');

    // Criterios y Premios
    Route::resource('criteria', CriterionController::class);
    Route::resource('awards', AwardController::class);
    Route::post('/events/{event}/generate-winners', [AwardController::class, 'generateWinners'])->name('events.generateWinners');
});

// --- RUTAS DE ESTUDIANTES (ROLE: STUDENT) ---
Route::middleware(['auth', 'role:student'])->group(function () {
    // Equipos
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::post('/teams/{team}/join', [TeamController::class, 'requestJoin'])->name('teams.join');
    Route::post('/teams/{team}/accept/{user}', [TeamController::class, 'accept'])->name('teams.accept');
    Route::post('/teams/{team}/reject/{user}', [TeamController::class, 'reject'])->name('teams.reject');
    Route::post('/teams/{team}/invitations/accept/{notification?}', [TeamController::class, 'acceptInvitation'])->name('teams.invitations.accept');
    Route::post('/teams/{team}/invitations/reject/{notification?}', [TeamController::class, 'rejectInvitation'])->name('teams.invitations.reject');
    Route::post('/teams/{team}/leave', [TeamController::class, 'leaveTeam'])->name('teams.leave');
    Route::delete('/teams/{team}/kick/{user}', [TeamController::class, 'kickMember'])->name('teams.kick');
    Route::post('/teams/{team}/transfer/{user}', [TeamController::class, 'transferLeadership'])->name('teams.transfer');
    Route::delete('/teams/{team}/cancel-invitation/{user}', [TeamController::class, 'cancelInvitation'])->name('teams.cancel-invitation');

    // Proyectos
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
});

// --- RUTAS COMPARTIDAS ADMIN/STAFF/STUDENT ---
Route::middleware(['auth', 'role:admin|staff|student'])->group(function () {
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::patch('/projects/{project}', [ProjectController::class, 'update']);
});

// --- RUTAS DE JUECES (ROLE: JUDGE) ---
Route::middleware(['auth', 'role:judge'])->group(function () {
    Route::get('/evaluations/create', [EvaluationController::class, 'create'])->name('evaluations.create');
    Route::post('/evaluations', [EvaluationController::class, 'store'])->name('evaluations.store');
});

// --- RUTAS DE ASESORES (ROLE: ADVISOR|STAFF) ---
Route::middleware(['auth', 'role:advisor|staff'])->group(function () {
    Route::patch('/teams/{team}/advisor/{status}', [TeamController::class, 'respondAdvisory'])->name('teams.advisor.response');
});

// --- RUTAS DE ADMINISTRACIÓN GLOBAL (ROLE: ADMIN) ---
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('staff', StaffProfileController::class);
    Route::post('/staff/import-csv', [StaffProfileController::class, 'importCsv'])->name('staff.importCsv');

    Route::resource('students', StudentProfileController::class);
    Route::post('/students/import-csv', [StudentProfileController::class, 'importCsv'])->name('students.importCsv');

    Route::resource('judges', JudgeController::class);
    Route::post('/judges/import-csv', [JudgeController::class, 'importCsv'])->name('judges.importCsv');

    Route::resource('careers', CareerController::class);
    Route::resource('specialties', SpecialtyController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/by-event', [ReportController::class, 'byEvent'])->name('reports.by-event');
    Route::get('/reports/by-career', [ReportController::class, 'byCareer'])->name('reports.by-career');
    Route::get('/reports/by-period', [ReportController::class, 'byPeriod'])->name('reports.by-period');
    Route::get('/reports/export-participants', [ReportController::class, 'exportParticipants'])->name('reports.export-participants');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
});

// --- RUTAS ABIERTAS (AUTH REQUERIDO) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/rankings', [EventController::class, 'rankings'])->name('events.rankings');
});

require __DIR__ . '/auth.php';
