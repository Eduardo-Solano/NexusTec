<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    protected $fillable = [
        'name', 'event_id', 'leader_id', 
        'advisor_id', 'advisor_status'
    ];

    // El evento al que pertenece
    public function event() {
        return $this->belongsTo(Event::class);
    }

    // El Líder (Creador) es un Usuario
    public function leader() {
        return $this->belongsTo(User::class, 'leader_id');
    }

    // Los Miembros (Muchos a Muchos)
    public function members() {
        return $this->belongsToMany(User::class, 'team_user')
                    ->withPivot('is_accepted', 'role') // <--- ¡ESTO ES LO QUE FALTABA!
                    ->withTimestamps();
    }

    // Un equipo tiene UN proyecto
    public function project() {
        return $this->hasOne(Project::class);
    }

    // Relación con el Asesor
    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }
}
