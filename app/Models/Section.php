<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'created_at',
        'updated_at',
        'grade_id'
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'owner');
    }

    public function section_subject_teachers()
    {
        return $this->hasMany(SectionSubjectTeacher::class);
    }
}
