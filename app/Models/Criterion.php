<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    /** @use HasFactory<\Database\Factories\CriterionFactory> */
    use HasFactory;

    protected $table = 'criteria';
    protected $fillable = [
        'name',
        'max_points',
    ];

    // --- Relaciones de 1:N ---
    public function evaluations() {
        return $this->hasMany(Evaluation::class);
    }
}
