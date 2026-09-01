<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $Staff_members = [
            [
                'id' => 1,
                'name' => 'حمزة محمد حيش',
                'e_name' => 'Hamza Heesh',
                'picture' => null,
                'phone' => '0952731427',
                'email' => 'superAdmin123@test.com',
                'date_of_birth' =>  \Carbon\Carbon::createFromFormat('d/m/Y', '18/8/2005')->format('Y-m-d'),
                'gender' => 'male',
                'is_active'  => 1,
                'user_id' => 1,
                'subject_id' => null,
                'staff_type'  => 'Super Admin'
            ],
            [
                'id' => 2,
                'name' => 'حمزة محمد حيش',
                'e_name' => 'Hamza Heesh',
                'picture' => null,
                'phone' => '0952731427',
                'email' => 'admin123@test.com',
                'date_of_birth' =>  \Carbon\Carbon::createFromFormat('d/m/Y', '18/8/2005')->format('Y-m-d'),
                'gender' => 'male',
                'is_active'  => 1,
                'user_id' => 2,
                'subject_id' => null,
                'staff_type'  => 'admin'
            ],
            [
                'id' => 3,
                'name' => 'حمزة محمد حيش',
                'e_name' => 'Hamza Heesh',
                'picture' => null,
                'phone' => '0952731427',
                'email' => 'teacher123@test.com',
                'date_of_birth' =>  \Carbon\Carbon::createFromFormat('d/m/Y', '18/8/2005')->format('Y-m-d'),
                'gender' => 'male',
                'is_active'  => 1,
                'user_id' => 3,
                'subject_id' => 3,
                'staff_type'  => 'teacher'
            ],
        ];

        foreach ($Staff_members as $staff) {
            Staff::firstOrCreate([
                'id' => $staff['id']
            ], [
                'name' => $staff['name'],
                'e_name' => $staff['e_name'],
                'picture' => $staff['picture'],
                'phone' => $staff['phone'],
                'email' => $staff['email'],
                'date_of_birth' =>  $staff['date_of_birth'],
                'gender' => $staff['gender'],
                'is_active'  => $staff['is_active'],
                'user_id' => $staff['user_id'],
                'subject_id' => $staff['subject_id'],
                'staff_type'  => $staff['staff_type']
            ]);
        }
    }
}
