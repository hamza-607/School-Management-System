<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance_records';

    protected $fillable = [
        'student_id',
        'section_subject_teacher_id',
        'section_id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function sectionSubjectTeacher()
    {
        return $this->belongsTo(SectionSubjectTeacher::class);
    }
}
