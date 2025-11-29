<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    /** @use HasFactory<\Database\Factories\CareerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    // --- Relaciones de 1:N ---
    public function studentProfiles() {
        return $this->hasMany(StudentProfile::class);
    }
}
