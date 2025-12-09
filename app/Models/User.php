<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function staffProfile()
    {
        return $this->hasOne(StaffProfile::class);
    }

    public function judgeProfile()
    {
        return $this->hasOne(JudgeProfile::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withPivot('is_accepted', 'requested_by_user');
    }

    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'leader_id');
    }

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
