<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishedSession extends Model
{
    use HasFactory;

    protected $table;

    protected $fillable = [
        'actual_start_time',
        'actual_end_time',
        'section_subject_teacher_id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function sectionSubjectTeacher()
    {
        return $this->belongsTo(SectionSubjectTeacher::class);
    }
}
