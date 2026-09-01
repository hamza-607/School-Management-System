<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'e_name',
        'picture',
        'section_id',
        'phoneNumber',
        'grade_id',
        'date_of_birth',
        'gender',
        'address',
        'is_active',
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

    public function student_parents()
    {
        return $this->hasMany(Student_parent::class, 'student_id', 'id');
    }

    public function files(){
        return $this->morphMany(File::class, 'owner');
    }
}


