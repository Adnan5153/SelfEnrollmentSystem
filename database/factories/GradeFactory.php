<?php

namespace Database\Factories;

use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradeFactory extends Factory
{
    protected $model = Grade::class;

    public function definition()
    {
        $min = $this->faker->numberBetween(0, 90);
        $max = $min + $this->faker->numberBetween(5, 10);
        $grade = collect(['F', 'D', 'C', 'B', 'A', 'A+'])->random();
        $remarks = $grade === 'F' ? 'Fail' : 'Pass';
        return [
            'min_marks' => $min,
            'max_marks' => min($max, 100),
            'grade' => $grade,
            'remarks' => $remarks,
        ];
    }
}
