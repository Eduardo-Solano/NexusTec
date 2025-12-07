<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'team_id', 
        'repository_url',
        'documentation_path',
        'image_path',
        'video_url',
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

    // ========== MÉTODOS PARA ARCHIVOS ==========

    /**
     * Obtener URL de la documentación
     */
    public function getDocumentationUrlAttribute()
    {
        return $this->documentation_path 
            ? Storage::url($this->documentation_path) 
            : null;
    }

    /**
     * Obtener URL de la imagen
     */
    public function getImageUrlAttribute()
    {
        return $this->image_path 
            ? Storage::url($this->image_path) 
            : null;
    }

    /**
     * Verificar si tiene documentación
     */
    public function hasDocumentation(): bool
    {
        return !empty($this->documentation_path);
    }

    /**
     * Verificar si tiene imagen
     */
    public function hasImage(): bool
    {
        return !empty($this->image_path);
    }

    /**
     * Verificar si tiene video
     */
    public function hasVideo(): bool
    {
        return !empty($this->video_url);
    }

    /**
     * Obtener URL embebible del video (YouTube/Vimeo)
     */
    public function getEmbedVideoUrlAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        // YouTube
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->video_url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }

        return null;
    }

    /**
     * Eliminar archivos asociados al proyecto
     */
    public function deleteFiles(): void
    {
        if ($this->documentation_path && Storage::exists($this->documentation_path)) {
            Storage::delete($this->documentation_path);
        }
        
        if ($this->image_path && Storage::exists($this->image_path)) {
            Storage::delete($this->image_path);
        }
    }
}
