<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudgeProfile extends Model
{
    /** @use HasFactory<\Database\Factories\JudgeProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialty_id',
        'company',
    ];

    // --- Relaciones de 1:1 ---
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function specialty() {
        return $this->belongsTo(Specialty::class);
    }

    // --- Relaciones de N:M ---
    public function events() {
        return $this->belongsToMany(Event::class, 'event_judge');
    }
}
