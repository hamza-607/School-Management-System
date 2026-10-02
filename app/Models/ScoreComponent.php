<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScoreComponent extends Model
{
    use HasFactory;

    protected $table = 'score_components';

    protected $fillable = [
        'finished_session_id',
        'semester_id',
        'type',
        'max_score',
        'created_at',
        'updated_at'
    ];

    public function finished_session()
    {
        return $this->belongsTo(FinishedSession::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function students_scores()
    {
        return $this->hasMany(StudentScore::class);
    }
}
