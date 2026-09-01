<?php

namespace Database\Seeders;

use App\Models\SpatieRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpatieRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'Super Admin',
                'web',
            ],
            [
                'Admin',
                'web',
            ],
            [
                'Teacher',
                'web',
            ]
        ];

        foreach ($roles as $role) {
            SpatieRole::firstOrCreate(
                [
                    'name' => $role[0],
                    'guard_name' => $role[1]
                ],
                [
                    'name' => $role[0],
                    'guard_name' => $role[1],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
