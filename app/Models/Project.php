<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 
        'description', 
        'team_id', 
        'repository_url', 
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function evaluations() {
        return $this->hasMany(Evaluation::class);
    }
}
