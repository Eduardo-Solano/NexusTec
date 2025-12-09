<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    const STATUS_REGISTRATION = 'registration';
    const STATUS_ACTIVE = 'active';
    const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'registration_deadline',
        'end_date',
        'status',
        'show_feedback_to_students'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'registration_deadline' => 'datetime',
        'end_date' => 'datetime',
        'show_feedback_to_students' => 'boolean',
    ];

    public function calculateStatus(): string
    {
        $now = now();

        if ($this->end_date && $now->greaterThan($this->end_date)) {
            return self::STATUS_CLOSED;
        }

        if ($this->registration_deadline && $now->greaterThan($this->registration_deadline)) {
            return self::STATUS_ACTIVE;
        }

        if (!$this->registration_deadline && $this->start_date && $now->greaterThan($this->start_date)) {
            return self::STATUS_ACTIVE;
        }

        return self::STATUS_REGISTRATION;
    }

    public function syncStatus(): bool
    {
        $calculatedStatus = $this->calculateStatus();
        
        if ($this->status !== $calculatedStatus) {
            $this->status = $calculatedStatus;
            $this->save();
            return true;
        }
        
        return false;
    }

    public function isRegistrationOpen(): bool
    {
        return $this->status === self::STATUS_REGISTRATION;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function allowsTeamRegistration(): bool
    {
        return $this->isRegistrationOpen();
    }

    public function allowsProjectActions(): bool
    {
        return $this->isActive();
    }

    public function allowsEvaluations(): bool
    {
        return $this->isActive();
    }

    public function allowsAwardsAndDiplomas(): bool
    {
        return $this->isClosed();
    }

    public function isOpen(): bool
    {
        return !$this->isClosed();
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_REGISTRATION => 'Inscripciones Abiertas',
            self::STATUS_ACTIVE => 'En Curso',
            self::STATUS_CLOSED => 'Finalizado',
            default => 'Desconocido'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_REGISTRATION => 'blue',
            self::STATUS_ACTIVE => 'green',
            self::STATUS_CLOSED => 'red',
            default => 'gray'
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            self::STATUS_REGISTRATION => '📝',
            self::STATUS_ACTIVE => '🚀',
            self::STATUS_CLOSED => '🏆',
            default => '❓'
        };
    }

    public static function getStatuses(): array
    {
        return [
            self::STATUS_REGISTRATION => 'Inscripciones Abiertas',
            self::STATUS_ACTIVE => 'En Curso',
            self::STATUS_CLOSED => 'Finalizado',
        ];
    }

    public function getAvailableRolesAttribute()
    {
        $name = strtolower($this->name);

        if (str_contains($name, 'hack')) {
            return [
                'Full Stack Developer',
                'Backend Developer',
                'Frontend Developer',
                'UI/UX Designer',
                'Data Scientist',
                'QA / Tester'
            ];
        }

        if (str_contains($name, 'innova') || str_contains($name, 'emprende')) {
            return [
                'Líder de Proyecto',
                'Desarrollador Técnico',
                'Analista de Negocios',
                'Especialista Financiero',
                'Diseñador de Producto',
                'Investigador'
            ];
        }

        return [
            'Capitán',
            'Investigador',
            'Orador',
            'Apoyo Logístico',
            'Miembro General'
        ];
    }

    public function teams() 
    {
        return $this->hasMany(Team::class);
    }

    public function awards() 
    {
        return $this->hasMany(Award::class);
    }

    public function criteria()
    {
        return $this->belongsToMany(Criterion::class, 'event_criterion');
    }

    public function judges()
    {
        return $this->belongsToMany(JudgeProfile::class, 'event_judge', 'event_id', 'judge_id');
    }
}
