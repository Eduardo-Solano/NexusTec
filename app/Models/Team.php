<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'leader_id',
        'event_id',
        'advisor_id',
        'creator_id',
        'status',
        'max_members'
    ];

    public function leader()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)->withPivot('is_accepted', 'requested_by_user');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function project()
    {
        return $this->hasOne(Project::class);
    }

    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function awards()
    {
        return $this->hasMany(Award::class);
    }
    public function maxMembers(): int
    {
        // Ajusta el campo según tu tabla de eventos
        return $this->event->max_team_members ?? 5;
    }

    public function currentMembersCount(): int
    {
        // Aquí usas la misma relación que en la vista: members
        return $this->members()->count();
    }

    public function hasReachedMemberLimit(): bool
    {
        return $this->currentMembersCount() >= $this->maxMembers();
    }

}
