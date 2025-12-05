<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'is_active'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Verificar si el evento está abierto para inscripciones/acciones
     */
    public function isOpen(): bool
    {
        // El evento debe estar activo
        if (!$this->is_active) {
            return false;
        }

        // La fecha de fin no debe haber pasado
        if ($this->end_date && $this->end_date->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Verificar si el evento está cerrado
     */
    public function isClosed(): bool
    {
        return !$this->isOpen();
    }

    /**
     * Obtener el estado del evento como texto
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'Inactivo';
        }

        if ($this->end_date && $this->end_date->isPast()) {
            return 'Finalizado';
        }

        if ($this->start_date && $this->start_date->isFuture()) {
            return 'Próximamente';
        }

        return 'En curso';
    }

    /**
     * Obtener el color del badge según el estado
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'Inactivo' => 'gray',
            'Finalizado' => 'red',
            'Próximamente' => 'blue',
            'En curso' => 'green',
            default => 'gray'
        };
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
}
