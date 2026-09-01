<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_parent extends Model
{
    use HasFactory;

    protected $table = 'students_parents';

    protected $fillable = [
        'student_id',
        'parent_id',
        'relationship_to_student',
        'created_at',
    ];

    public function guardian()
    {
        return $this->belongsTo(Guardian::class, 'parent_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
