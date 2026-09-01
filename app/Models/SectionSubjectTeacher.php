<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionSubjectTeacher extends Model
{
    use HasFactory;

    protected $table = 'section_subject_teacher';

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'section_id',
        'appointment_id',
        'created_at',
        'updated_at',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'teacher_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
