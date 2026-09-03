<?php

namespace Database\Seeders;

use App\Models\SpatieModelHasRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpatieModelHasRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'role_id' => 1,
                'model_type' => 'App\Models\User',
                'model_id' => 1,
            ],
            [
                'role_id' => 2,
                'model_type' => 'App\Models\User',
                'model_id' => 2,
            ],
            [
                'role_id' => 3,
                'model_type' => 'App\Models\User',
                'model_id' => 3,
            ],
        ];

        foreach ($data as $i) {
            SpatieModelHasRole::firstOrCreate([
                'role_id' => $i['role_id'],
                'model_type' => $i['model_type'],
            ], [
                'role_id' => $i['role_id'],
                'model_type' => $i['model_type'],
                'model_id' => $i['model_id'],
            ]);
        }
    }
}
