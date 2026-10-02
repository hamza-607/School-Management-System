<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentScore extends Model
{
    use HasFactory;

    protected $table = 'student_scores';

    protected $fillable = [
        'student_id',
        'score_component_id',
        'score',
        'notes',
        'created_at',
        'updated_at'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function score_component()
    {
        return $this->belongsTo(ScoreComponent::class);
    }
}
