<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    /** @use HasFactory<\Database\Factories\StaffProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_number',
        'department',
    ];

    // --- Relaciones de 1:1 ---
    public function user() {
        return $this->belongsTo(User::class);
    }
}
