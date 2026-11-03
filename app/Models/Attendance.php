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
        'finished_session_id',
        'section_id',
        'status',
        'created_at',
        'updated_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function finishedSession()
    {
        return $this->belongsTo(FinishedSession::class);
    }

    public function section(){
        return $this->belongsTo(Section::class);
    }
}
