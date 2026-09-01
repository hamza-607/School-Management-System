<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            [
                'name' => 'الأحياء',
                'e_name' => 'Science',
                'description' => null,
            ],
            [
                'name' => 'الرياضيات',
                'e_name' => 'Mathematics',
                'description' => null,
            ],
            [
                'name' => 'فيزياء',
                'e_name' => 'Physics',
                'description' => null,
            ],
            [
                'name' => 'كيمياء',
                'e_name' => 'Chemistry',
                'description' => null,
            ],
            [
                'name' => 'اللغة العربية',
                'e_name' => 'Arabic',
                'description' => null,
            ],
            [
                'name' => 'اللغة الإنكليزية',
                'e_name' => 'English',
                'description' => null,
            ],
            [
                'name' => 'إجتماعيات',
                'e_name' => 'Social Studies',
                'description' => null,
            ],
            [
                'name' => 'التربية الدينية',
                'e_name' => 'Religion',
                'description' => null,
            ],
            [
                'name' => 'الجغرافية',
                'e_name' => 'Geography',
                'description' => null,
            ],
            [
                'name' => 'التاريخ',
                'e_name' => 'History',
                'description' => null,
            ],
            [
                'name' => 'التربية الوطنية',
                'e_name' => 'National Education',
                'description' => null,
            ],
            [
                'name' => 'علوم عامة(فيزياء-كيمياء-علوم)',
                'e_name' => 'General Science',
                'description' => null,
            ],
            [
                'name' => 'التربية الرياضية',
                'e_name' => 'Sports',
                'description' => null,
            ],
            [
                'name' => 'الفنون',
                'e_name' => 'Arts',
                'description' => null,
            ],
            [
                'name' => 'الموسيقى',
                'e_name' => 'Music',
                'description' => null,
            ],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                [
                    'name' => $subject['name'],
                    'e_name' => $subject['e_name'],
                ],
                [
                    'name' => $subject['name'],
                    'e_name' => $subject['e_name'],
                    'description' => $subject['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
