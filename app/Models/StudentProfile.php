<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    /** @use HasFactory<\Database\Factories\StudentProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'career_id',
        'control_number',
        'semester',
    ];

    // --- Relaciones de 1:1 ---
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function career() {
        return $this->belongsTo(Career::class);
    }
}
