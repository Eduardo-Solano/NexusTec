<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    /** @use HasFactory<\Database\Factories\EvaluationFactory> */
    use HasFactory;

    protected $fillable = ['project_id', 'judge_id', 'criterion_id', 'score', 'feedback'];

    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function judge() {
        return $this->belongsTo(User::class, 'judge_id');
    }

    public function criterion() {
        return $this->belongsTo(Criterion::class); // Singular
    }
}
