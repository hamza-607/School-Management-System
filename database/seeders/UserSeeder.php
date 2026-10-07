<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => 'حمزة محمد حيش',
                'email' => 'superAdmin123@test.com',
                'password' => '$2y$10$CwDpkuyPigcWUMtJuoan3eUAvQSVqOoMpa5qKQVCH5Xvl1XZXCJPu',
            ],
            [
                'id' => 2,
                'name' => 'حمزة محمد حيش',
                'email' => 'admin123@test.com',
                'password' => '$2y$10$CwDpkuyPigcWUMtJuoan3eUAvQSVqOoMpa5qKQVCH5Xvl1XZXCJPu',
            ],
            [
                'id' => 3,
                'name' => 'حمزة محمد حيش',
                'email' => 'teacher123@test.com',
                'password' => '$2y$10$CwDpkuyPigcWUMtJuoan3eUAvQSVqOoMpa5qKQVCH5Xvl1XZXCJPu',
            ]
        ];

        foreach ($users as $user) {
            User::firstOrCreate([
                'id' => $user['id']
            ], [
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'remember_token' => null,
                'is_active' => true
            ]);
        }
    }
}
