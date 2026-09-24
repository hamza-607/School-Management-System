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
        'teacher_id',
        'subject_id',
        'section_id',
        'grade_id',
        'session_type',
        'appointment_id',
        'status',
        'created_at',
        'updated_at'
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
