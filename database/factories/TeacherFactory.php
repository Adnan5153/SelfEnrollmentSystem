<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        $class = \App\Models\ClassModel::inRandomOrder()->first();
        if (!$class) {
            throw new \Exception('No classes found. Please seed classes before teachers.');
        }
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('12345678'),
            'class_id' => $class->id,
            'section' => $class->section,
        ];
    }
}
