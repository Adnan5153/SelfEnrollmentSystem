<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassRoutine;
use App\Models\Credit;
use App\Models\Department;
use App\Models\Admin;
use App\Models\Student;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            GradeSeeder::class,
        ]);

        // Ensure exactly two base credit types exist with correct hours (canonical rows)
        Credit::updateOrCreate(
            ['subject_type' => 'theory'],
            ['subject_type' => 'theory', 'credit_hour' => 3.0]
        );
        Credit::updateOrCreate(
            ['subject_type' => 'lab'],
            ['subject_type' => 'lab', 'credit_hour' => 1.5]
        );

        // Create departments first
        if (Department::count() == 0) {
            Department::create([
                'name' => 'Computer Science & Engineering',
                'code' => 'CSE'
            ]);
            Department::create([
                'name' => 'Electrical & Electronic Engineering',
                'code' => 'EEE'
            ]);
        }

        // Only create one class
        if (ClassModel::count() == 0) {
            ClassModel::factory()->create();
        }

        // Create teachers after classes exist
        if (Teacher::count() == 0) {
            Teacher::factory(10)->create();
        }

        // Call the course catalogue seeder for CSE and EEE subjects (only once)
        $this->call(CourseCatalogueSeeder::class);

        // Keep existing year fallback, no cross-department uniqueness enforcement here
        Subject::whereNull('year')->orWhere('year', '')->update(['year' => '1st Year']);

        if (Admin::count() == 0) {
            Admin::factory(3)->create();
        }

        if (Student::count() == 0) {
            Student::factory(30)->create();
        }

    // EnrollSummer2025Seeder was removed/cancelled to avoid importing from the provided CSV.
    // If you want to re-enable it later, restore this call:
    // $this->call(EnrollSummer2025Seeder::class);

        // \App\Models\ClassRoutine::factory(40)->create(); // This line is commented out as per the edit hint
        // \App\Models\ExamSchedule::factory(20)->create(); // Commented out to avoid potential issues

        // Seed prerequisites: for each subject, assign 0-2 random other subjects as prerequisites
        if (Subject::count() > 0) {
            $subjects = Subject::all();
            foreach ($subjects as $subject) {
                $possiblePrereqs = $subjects->where('id', '!=', $subject->id)->pluck('id')->shuffle();
                $prereqCount = rand(0, 2);
                $subject->prerequisites()->sync($possiblePrereqs->take($prereqCount));
            }
        }

        // Assign 3-6 random subjects to each class in the class_subject pivot table
        if (Subject::count() > 0) {
            $allSubjects = Subject::all();
            foreach (ClassModel::all() as $class) {
                $subjectIds = $allSubjects->random(rand(3, min(6, $allSubjects->count())))->pluck('id')->toArray();
                $class->offered_subjects()->sync($subjectIds);
            }
        }

        // Create Class Routines based on class-subject relationships
        $this->seedClassRoutines();
    }

    private function seedClassRoutines(): void
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $timeSlots = [
            ['start' => '08:00', 'end' => '09:00'],
            ['start' => '09:00', 'end' => '10:00'],
            ['start' => '10:00', 'end' => '11:00'],
            ['start' => '11:00', 'end' => '12:00'],
            ['start' => '13:00', 'end' => '14:00'],
            ['start' => '14:00', 'end' => '15:00'],
        ];

        $classes = ClassModel::all();
        foreach ($classes as $class) {
            $offeredSubjects = $class->offered_subjects()->get();
            $teachers = Teacher::all();

            foreach ($days as $day) {
                $usedTeachers = [];
                $usedSubjects = [];
                foreach ($timeSlots as $slot) {
                    $availableSubjects = $offeredSubjects->whereNotIn('id', $usedSubjects);
                    if ($availableSubjects->isEmpty()) {
                        $usedSubjects = [];
                        $availableSubjects = $offeredSubjects;
                    }
                    $subject = $availableSubjects->random();
                    $usedSubjects[] = $subject->id;

                    $teacher = $teachers->where('id', $subject->teacher_id)->first();
                    if (!$teacher || in_array($teacher->id, $usedTeachers)) {
                        $teacher = $teachers->whereNotIn('id', $usedTeachers)->random();
                    }
                    $usedTeachers[] = $teacher->id;

                    ClassRoutine::create([
                        'subject_id' => $subject->id,
                        'teacher_id' => $teacher->id,
                        'day_of_week' => $day,
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                        'room_number' => 'Room ' . rand(100, 499),
                    ]);
                }
            }
        }

        if (ClassRoutine::count() < 100) {
            ClassRoutine::factory(30)->create();
        }
    }
}
