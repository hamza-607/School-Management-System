<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $table = 'contracts';

    protected $fillable = [
        'staff_id',
        'salary',
        'contract_file',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function salaryAdjustments()
    {
        return $this->hasMany(EmployeeSalaryAdjustments::class);
    }
}
