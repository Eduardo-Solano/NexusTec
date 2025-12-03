<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'team_id', 
        'repository_url', 
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function evaluations() {
        return $this->hasMany(Evaluation::class);
    }

    // Jueces asignados a este proyecto
    public function judges() {
        return $this->belongsToMany(User::class, 'judge_project', 'project_id', 'judge_id')
            ->withPivot('assigned_at', 'is_completed')
            ->withTimestamps();
    }

    // Verificar si un juez específico ya completó la evaluación
    public function isEvaluatedBy($judgeId) {
        return $this->judges()
            ->where('judge_id', $judgeId)
            ->wherePivot('is_completed', true)
            ->exists();
    }

    // Obtener el promedio de calificaciones
    public function getAverageScoreAttribute() {
        return $this->evaluations()->avg('score');
    }
}
