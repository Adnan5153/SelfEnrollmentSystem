<?php

namespace Database\Factories;

use App\Models\ClassRoutine;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClassRoutine>
 */
class ClassRoutineFactory extends Factory
{
    protected $model = ClassRoutine::class;

    public function definition(): array
    {
        // Get a random class and one of its offered subjects
        $class = ClassModel::inRandomOrder()->first();
        $subject = null;
        $teacher = null;

        if ($class) {
            // Get a random subject offered by this class
            $offeredSubjects = $class->offered_subjects()->get();
            if ($offeredSubjects->isNotEmpty()) {
                $subject = $offeredSubjects->random();
                // Get the teacher assigned to this subject
                $teacher = Teacher::find($subject->teacher_id);
            }
        }

        // Fallback if no class or subject found
        if (!$subject) {
            $subject = Subject::inRandomOrder()->first();
        }
        if (!$teacher) {
            $teacher = Teacher::inRandomOrder()->first();
        }

        $startHour = $this->faker->numberBetween(8, 15); // 8am to 3pm
        $startMinute = $this->faker->randomElement([0, 30]);
        $startTime = sprintf('%02d:%02d', $startHour, $startMinute);
        $endTime = sprintf('%02d:%02d', $startHour + 1, $startMinute);

        return [
            'subject_id' => $subject ? $subject->id : null,
            'teacher_id' => $teacher ? $teacher->id : null,
            'day_of_week' => $this->faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'room_number' => 'Room ' . $this->faker->numberBetween(100, 499),
        ];
    }
}
