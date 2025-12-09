<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Project extends Model
{
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

    public function team() 
    {
        return $this->belongsTo(Team::class);
    }

    public function evaluations() 
    {
        return $this->hasMany(Evaluation::class);
    }

    public function judges() 
    {
        return $this->belongsToMany(User::class, 'judge_project', 'project_id', 'judge_id')
            ->withPivot('assigned_at', 'is_completed')
            ->withTimestamps();
    }

    public function isEvaluatedBy($judgeId) 
    {
        return $this->judges()
            ->where('judge_id', $judgeId)
            ->wherePivot('is_completed', true)
            ->exists();
    }

    public function getAverageScoreAttribute() 
    {
        return $this->evaluations()->avg('score');
    }

    public function getDocumentationUrlAttribute()
    {
        return $this->documentation_path 
            ? Storage::url($this->documentation_path) 
            : null;
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path 
            ? Storage::url($this->image_path) 
            : null;
    }

    public function hasDocumentation(): bool
    {
        return !empty($this->documentation_path);
    }

    public function hasImage(): bool
    {
        return !empty($this->image_path);
    }

    public function hasVideo(): bool
    {
        return !empty($this->video_url);
    }

    public function getEmbedVideoUrlAttribute()
    {
        if (!$this->video_url) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->video_url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }

        return null;
    }

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
