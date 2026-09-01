<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryAdjustments extends Model
{
    use HasFactory;

    protected $table = 'employee_salary_adjustments';

    protected $fillable = [
        'contract_id',
        'amount',
        'amount_type',
        'date',
        'reason',
        'type',
        'is_applied',
        'created_at',
        'updated_at',
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
