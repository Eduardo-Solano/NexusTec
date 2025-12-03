<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Notifications\ResetPasswordNotification;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active'=> 'boolean',
        ];
    }

    // --- RELACIONES DE PERFILES (1 a 1) ---

    public function studentProfile() {
        return $this->hasOne(StudentProfile::class);
    }

    public function judgeProfile() {
        return $this->hasOne(JudgeProfile::class);
    }

    public function staffProfile() {
        return $this->hasOne(StaffProfile::class);
    }

    // Relación con equipos (muchos a muchos)
    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('is_accepted');
    }

    //notificacion correo
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // Proyectos asignados para evaluar (como juez)
    public function assignedProjects()
    {
        return $this->belongsToMany(Project::class, 'judge_project', 'judge_id', 'project_id')
            ->withPivot('assigned_at', 'is_completed')
            ->withTimestamps();
    }

    // Evaluaciones realizadas (como juez)
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'judge_id');
    }
}
