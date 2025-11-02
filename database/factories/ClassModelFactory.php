<?php

namespace Database\Factories;

use App\Models\ClassModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClassModel>
 */
class ClassModelFactory extends Factory
{
    protected $model = ClassModel::class;

    public function definition(): array
    {
        return [
            'class_name' => 'One',
            'section' => 'A',
            'capacity' => $this->faker->numberBetween(20, 60),
        ];
    }
}
