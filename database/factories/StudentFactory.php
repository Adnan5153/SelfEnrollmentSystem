<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\ClassModel;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $class = ClassModel::inRandomOrder()->first();
        $department = Department::inRandomOrder()->first();

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'year' => $this->faker->randomElement(['1st Year', '2nd Year', '3rd Year', '4th Year']),
            'credit_completed' => $this->faker->numberBetween(0, 160),
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'class_id' => $class?->id,
            'section' => $class?->section ?? 'A',
            'department_id' => $department?->id,
            'remember_token' => Str::random(10),
        ];
    }
}
