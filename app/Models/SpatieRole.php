<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpatieRole extends Model
{
    use HasFactory;

    protected $table = 'spatie_roles';

    protected $fillable = [
        'name',
        'guard_name',
        'created_at',
        'updated_at'
    ];

    public function spatieModelHasRole() {
        return $this->hasMany(SpatieModelHasRole::class, 'role_id');
    }
}
