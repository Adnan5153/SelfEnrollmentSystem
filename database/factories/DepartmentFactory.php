<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        $departments = [
            'Computer Science & Engineering' => 'CSE',
            'Electrical & Electronic Engineering' => 'EEE',
        ];

        $departmentName = $this->faker->unique()->randomElement(array_keys($departments));
        $departmentCode = $departments[$departmentName];

        return [
            'name' => $departmentName,
            'code' => $departmentCode,
        ];
    }
}
