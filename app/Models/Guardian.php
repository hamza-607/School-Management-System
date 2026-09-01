<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'name',
        'e_name',
        'phone',
        'email',
        'address',
        'date_of_birth',
        'gender',
        'created_at',
        'updated_at',
    ];

    public function student_parents()
    {
        return $this->hasMany(Student_parent::class, 'parent_id', 'id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'owner');
    }
}
