<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';

    protected $fillable = [
        'Day',
        'start_time',
        'end_time',
        'status',
        'updated_at',
    ];

    public function section_subject_teachers()
    {
        return $this->hasMany(SectionSubjectTeacher::class);
    }
}