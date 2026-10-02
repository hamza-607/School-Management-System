<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $table = 'academic_years';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_current',
        'created_at',
        'updated_at',
    ];

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function students_enrollment()
    {
        return $this->hasMany(StudentEnrollment::class);
    }
}
