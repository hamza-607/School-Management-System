<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'name',
        'file_path',
        'uploaded_by',
        'created_at',
        'updated_at',
        'owner_type',
        'owner_id',
    ];

    public function owner()
    {
        return $this->morphTo();
    }

     public function uploudedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
