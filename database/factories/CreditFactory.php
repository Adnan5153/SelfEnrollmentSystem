<?php

namespace Database\Factories;

use App\Models\Credit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Credit>
 */
class CreditFactory extends Factory
{
    protected $model = Credit::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['theory', 'lab']);
        return [
            'subject_type' => $type,
            'credit_hour' => $type === 'theory' ? 3.0 : 1.5,
        ];
    }
}
