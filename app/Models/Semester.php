<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\CssSelector\Node\FunctionNode;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';

    protected $fillable = [
        'academic_year_id',
        'name',
        'start_date',
        'end_date',
        'is_current',
        'created_at',
        'updated_at'
    ];

    public function score_components()
    {
        return $this->hasMany(ScoreComponent::class);
    }

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
