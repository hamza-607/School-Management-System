<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEnrollment extends Model
{
    use HasFactory;

    protected $table = 'student_enrollments';

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'section_id',
        'grade_id',
        'result',
        'decided_by',
        'decided_at',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function students()
    {
        return $this->belongsTo(Student::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
     
    public function decided_by_user()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
