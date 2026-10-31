<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'next_grade_id',
        'created_at',
        'updated_at',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function student_enrollments()
    {
        return $this->hasMany(StudentEnrollment::class);
    }

    public function nextGrade()
    {
        return $this->hasOne(Grade::class, 'next_grade_id');
    }
}
