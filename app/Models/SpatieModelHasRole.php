<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpatieModelHasRole extends Model
{
    use HasFactory;

    protected $table = 'spatie_model_has_roles';

    public $timestamps = false;

    protected $fillable = [
        'role_id',
        'model_type',
        'model_id',
    ];

    public function spatieRole()
    {
        return $this->belongsTo(SpatieRole::class, 'role_id');
    }

    public function model()
    {
        return $this->morphTo();
    }
}
