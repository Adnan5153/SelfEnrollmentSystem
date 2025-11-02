<?php

namespace Database\Factories;

use App\Models\ExamSchedule;
use App\Models\ClassModel;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExamSchedule>
 */
class ExamScheduleFactory extends Factory
{
    protected $model = ExamSchedule::class;

    public function definition(): array
    {
        $class = ClassModel::inRandomOrder()->first();
        $subject = Subject::inRandomOrder()->first();
        $startHour = $this->faker->numberBetween(8, 15); // 8am to 3pm
        $startMinute = $this->faker->randomElement([0, 30]);
        $startTime = sprintf('%02d:%02d', $startHour, $startMinute);
        $endTime = sprintf('%02d:%02d', $startHour + 2, $startMinute); // 2-hour exam
        return [
            'class_id' => $class ? $class->id : null,
            'subject_id' => $subject ? $subject->id : null,
            'exam_date' => $this->faker->dateTimeBetween('+1 week', '+2 months')->format('Y-m-d'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'room_number' => $this->faker->numberBetween(100, 499),
        ];
    }
}
