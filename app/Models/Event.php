<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    // Constantes para los estados del evento
    const STATUS_REGISTRATION = 'registration'; // Período de inscripciones
    const STATUS_ACTIVE = 'active';             // Evento en curso (proyectos, evaluaciones)
    const STATUS_CLOSED = 'closed';             // Evento cerrado (ganadores, diplomas)

    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'status', 'show_feedback_to_students'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'show_feedback_to_students' => 'boolean',
    ];

    /**
     * Verificar si el evento está en período de inscripciones
     */
    public function isRegistrationOpen(): bool
    {
        return $this->status === self::STATUS_REGISTRATION;
    }

    /**
     * Verificar si el evento está activo (en curso)
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Verificar si el evento está cerrado
     */
    public function isClosed(): bool
    {
        return $this->status === self::STATUS_CLOSED;
    }

    /**
     * Verificar si el evento permite inscripciones de equipos
     * Solo en estado "registration"
     */
    public function allowsTeamRegistration(): bool
    {
        return $this->isRegistrationOpen();
    }

    /**
     * Verificar si el evento permite acciones de proyecto (crear, editar, enviar)
     * Solo en estado "active"
     */
    public function allowsProjectActions(): bool
    {
        return $this->isActive();
    }

    /**
     * Verificar si el evento permite evaluaciones de jueces
     * Solo en estado "active"
     */
    public function allowsEvaluations(): bool
    {
        return $this->isActive();
    }

    /**
     * Verificar si el evento permite gestión de premios y diplomas
     * Solo en estado "closed"
     */
    public function allowsAwardsAndDiplomas(): bool
    {
        return $this->isClosed();
    }

    /**
     * Verificar si el evento está abierto para alguna acción (legacy support)
     * Retorna true si NO está cerrado
     */
    public function isOpen(): bool
    {
        return !$this->isClosed();
    }

    /**
     * Obtener el estado del evento como texto legible
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_REGISTRATION => 'Inscripciones Abiertas',
            self::STATUS_ACTIVE => 'En Curso',
            self::STATUS_CLOSED => 'Finalizado',
            default => 'Desconocido'
        };
    }

    /**
     * Obtener el color del badge según el estado
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_REGISTRATION => 'blue',
            self::STATUS_ACTIVE => 'green',
            self::STATUS_CLOSED => 'red',
            default => 'gray'
        };
    }

    /**
     * Obtener el icono del estado
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            self::STATUS_REGISTRATION => '📝',
            self::STATUS_ACTIVE => '🚀',
            self::STATUS_CLOSED => '🏆',
            default => '❓'
        };
    }

    /**
     * Obtener todos los estados disponibles
     */
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

        // CASO A: Hackathons (Enfoque 100% Software)
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

        // CASO B: InnovaTec / Emprendimiento (Enfoque Multidisciplinario)
        // Cubre las áreas de: Técnica, Negocios, Diseño y Finanzas
        if (str_contains($name, 'innova') || str_contains($name, 'emprende')) {
            return [
                'Líder de Proyecto',       // El CEO del equipo
                'Desarrollador Técnico',   // El que construye el prototipo (Ingenierías)
                'Analista de Negocios',    // El que hace el Canvas (Gestión/Admin)
                'Especialista Financiero', // Costos y Rentabilidad
                'Diseñador de Producto',   // Imagen y Marketing
                'Investigador'             // Fundamentación teórica
            ];
        }

        // CASO C: Otros (Robótica, Ciencias Básicas, etc.)
        return [
            'Capitán',
            'Investigador',
            'Orador',
            'Apoyo Logístico',
            'Miembro General'
        ];
    }
    // --- Relaciones de 1:N ---
    public function teams() {
        return $this->hasMany(Team::class);
    }
    // Relación con premios
    public function awards() {
        return $this->hasMany(Award::class);
    }
    // --- Relaciones de N:M ---
    public function criteria()
    {
        return $this->belongsToMany(Criterion::class, 'event_criterion');
    }

    public function judges()
    {
        return $this->belongsToMany(JudgeProfile::class, 'event_judge', 'event_id', 'judge_id');
    }
}
