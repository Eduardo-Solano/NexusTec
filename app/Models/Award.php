<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    // Posiciones disponibles (solo 3 lugares)
    public const POSITION_FIRST = '1er Lugar';
    public const POSITION_SECOND = '2do Lugar';
    public const POSITION_THIRD = '3er Lugar';

    public const POSITIONS = [
        1 => self::POSITION_FIRST,
        2 => self::POSITION_SECOND,
        3 => self::POSITION_THIRD,
    ];

    protected $fillable = ['event_id', 'team_id', 'position', 'awarded_at'];
    
    protected $casts = [
        'awarded_at' => 'datetime',
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function team() {
        return $this->belongsTo(Team::class);
    }

    /**
     * Obtener el emoji correspondiente a la posición
     */
    public function getPositionEmojiAttribute(): string
    {
        return match($this->position) {
            self::POSITION_FIRST => '🥇',
            self::POSITION_SECOND => '🥈',
            self::POSITION_THIRD => '🥉',
            default => '🏆',
        };
    }

    /**
     * Obtener el número de posición
     */
    public function getPositionNumberAttribute(): int
    {
        return array_search($this->position, self::POSITIONS) ?: 0;
    }
}
