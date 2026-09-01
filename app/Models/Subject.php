<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects';

    protected $fillable = [
        'name',
        'e_name',
        'description',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function teachers()
    {
        return $this->hasMany(Staff::class);
    }

    public function files(){
        return $this->morphMany(File::class, 'owner');
    }

    public function section_subject_teachers()
    {
        return $this->hasMany(SectionSubjectTeacher::class);
    }
}
