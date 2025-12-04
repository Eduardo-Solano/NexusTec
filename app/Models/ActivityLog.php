<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'properties',
        'ip_address',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * Obtener el usuario que realizó la acción
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener el modelo relacionado (polimórfico)
     */
    public function subject(): MorphTo
    {
        return $this->morphTo('model');
    }

    /**
     * Registrar una actividad
     */
    public static function log(string $action, string $description, $model = null, array $properties = []): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Scope para filtrar por acción
     */
    public function scopeAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope para filtrar por modelo
     */
    public function scopeForModel($query, $model)
    {
        return $query->where('model_type', get_class($model))
                     ->where('model_id', $model->id);
    }

    /**
     * Obtener el icono según la acción
     */
    public function getIconAttribute(): string
    {
        return match($this->action) {
            'created' => '➕',
            'updated' => '✏️',
            'deleted' => '🗑️',
            'assigned' => '📌',
            'evaluated' => '⚖️',
            'awarded' => '🏆',
            'joined' => '👥',
            'left' => '🚪',
            'approved' => '✅',
            'rejected' => '❌',
            'submitted' => '📋',
            'login' => '🔐',
            'logout' => '👋',
            default => '📝',
        };
    }

    /**
     * Obtener el color según la acción
     */
    public function getColorAttribute(): string
    {
        return match($this->action) {
            'created' => 'green',
            'updated' => 'blue',
            'deleted' => 'red',
            'assigned' => 'purple',
            'evaluated' => 'amber',
            'awarded' => 'yellow',
            'joined' => 'cyan',
            'left' => 'gray',
            'approved' => 'green',
            'rejected' => 'red',
            'submitted' => 'indigo',
            'login' => 'emerald',
            'logout' => 'slate',
            default => 'gray',
        };
    }
}
