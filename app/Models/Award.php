<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    //
    protected $fillable = ['event_id', 'team_id', 'name', 'category', 'awarded_at'];
    
    protected $casts = [
        'awarded_at' => 'date',
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function team() {
        return $this->belongsTo(Team::class);
    }
}
