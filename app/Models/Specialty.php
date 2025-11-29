<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    /** @use HasFactory<\Database\Factories\SpecialtyFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // --- Relaciones de 1:N ---
    public function judgeProfiles() {
        return $this->hasMany(JudgeProfile::class);
    }
}
