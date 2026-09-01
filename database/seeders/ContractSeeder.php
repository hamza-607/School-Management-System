<?php

namespace Database\Seeders;

use App\Models\Contract;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contracts = [
            [
                'id' => 1,
                'staff_id' => 1,
                'salary' => 70000,
                'contract_file' => 'contracts/FNOUfz4c0JBxNZ7nYKSpCvixPekWn9ODUx4a72xb',
            ],
            [
                'id' => 2,
                'staff_id' => 2,
                'salary' => 70000,
                'contract_file' => 'contracts/FNOUfz4c0JBxNZ7nYKSpCvixPekWn9ODUx4a72xb',
            ],
            [
                'id' => 3,
                'staff_id' => 3,
                'salary' => 70000,
                'contract_file' => 'contracts/FNOUfz4c0JBxNZ7nYKSpCvixPekWn9ODUx4a72xb',
            ],
        ];

        // Contract::create($contract);
        foreach ($contracts as $contract) {
            Contract::firstOrCreate([
                'id' => $contract['id']
            ], [
                'staff_id' => $contract['staff_id'],
                'salary' => $contract['salary'],
                'contract_file' => $contract['contract_file'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
