<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student_penalties extends Model
{
    use HasFactory;

    protected $table = 'student_penalties';

    protected $fillable = [
        'penalty_type',
        'reason',
        'user_id',
        'updated_by',
        'status',
        'notes',
        'student_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
