<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $fillable = [
        'name',
        'e_name',
        'picture',
        'phone',
        'email',
        'date_of_birth',
        'gender',
        'is_active',
        'created_at',
        'updated_at',
        'user_id',
        'subject_id',
        'staff_type',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'owner');
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

     public function section_subject_teachers()
    {
        return $this->hasMany(SectionSubjectTeacher::class);
    }
}
