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
        'advisor_id', 
        'repository_url', 
        'advisor_status'
    ];

    public function team() {
        return $this->belongsTo(Team::class);
    }

    public function advisor() {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function evaluations() {
        return $this->hasMany(Evaluation::class);
    }
}
