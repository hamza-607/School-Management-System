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
                'password' => '$2y$10$GoF65KHYEcCf2EAfA1x2pulgzOb5NyLo.BzeFga5SqXdhyd39C/.e',
            ],
            [
                'id' => 2,
                'name' => 'حمزة محمد حيش',
                'email' => 'admin123@test.com',
                'password' => '$2y$10$GoF65KHYEcCf2EAfA1x2pulgzOb5NyLo.BzeFga5SqXdhyd39C/.e',
            ],
            [
                'id' => 3,
                'name' => 'حمزة محمد حيش',
                'email' => 'teacher123@test.com',
                'password' => '$2y$10$GoF65KHYEcCf2EAfA1x2pulgzOb5NyLo.BzeFga5SqXdhyd39C/.e',
            ]
        ];

        foreach ($users as $user) {
            User::firstOrCreate([
                'id' => $user['id']
            ], [
                'name' => $user['id'],
                'email' => $user['id'],
                'password' => $user['id'],
                'remember_token' => null,
                'is_active' => true
            ]);
        }
    }
}
