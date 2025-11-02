<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            [
                'min_marks' => 0,
                'max_marks' => 39,
                'grade' => 'F',
                'remarks' => 'Fail',
            ],
            [
                'min_marks' => 40,
                'max_marks' => 49,
                'grade' => 'D',
                'remarks' => 'Pass',
            ],
            [
                'min_marks' => 50,
                'max_marks' => 59,
                'grade' => 'C',
                'remarks' => 'Pass',
            ],
            [
                'min_marks' => 60,
                'max_marks' => 69,
                'grade' => 'B',
                'remarks' => 'Pass',
            ],
            [
                'min_marks' => 70,
                'max_marks' => 79,
                'grade' => 'A',
                'remarks' => 'Pass',
            ],
            [
                'min_marks' => 80,
                'max_marks' => 100,
                'grade' => 'A+',
                'remarks' => 'Pass',
            ],
        ];

        foreach ($grades as $grade) {
            Grade::updateOrCreate(
                ['grade' => $grade['grade']],
                $grade
            );
        }
    }
}
