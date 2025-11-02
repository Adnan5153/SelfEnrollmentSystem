<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\AllStudent;
use App\Models\Subject;
use App\Models\Credit;
use App\Models\Mark;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnrollSummer2025Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = base_path('summer 2025.csv');
        if (!file_exists($csvPath)) {
            $this->command->warn("CSV not found at {$csvPath}, skipping EnrollSummer2025Seeder.");
            return;
        }

        // Ensure credit types exist
        $theoryCredit = Credit::updateOrCreate(['subject_type' => 'theory'], ['credit_hour' => 3.0]);
        $labCredit = Credit::updateOrCreate(['subject_type' => 'lab'], ['credit_hour' => 1.5]);

        // Preferred department (CSE) if exists
        $department = Department::where('code', 'CSE')->first() ?? Department::first();

        // Subjects to enroll every summer-2025 student into
        $subjectNames = [
            'Engineering Chemistry',
            'Functional English',
            'Calculus',
            'Engineering Physics I',
            'Engineering Physics I Lab',
        ];

        // Ensure subjects exist (create if missing)
        $subjects = [];
        foreach ($subjectNames as $name) {
            $subject = Subject::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first();
            if (!$subject) {
                // detect lab by presence of 'Lab' in name
                $creditId = (stripos($name, 'lab') !== false) ? $labCredit->id : $theoryCredit->id;
                $subject = Subject::create([
                    'name' => $name,
                    'subject_code' => preg_replace('/[^A-Z0-9]/i', '', strtoupper(substr($name, 0, 6))) . rand(10,99),
                    'year' => '1st Year',
                    'teacher_id' => null,
                    'credit_id' => $creditId,
                    'department_id' => $department ? $department->id : null,
                ]);
                $this->command->info("Created missing subject: {$name} (id: {$subject->id})");
            }
            $subjects[$name] = $subject;
        }

        // Read CSV and enroll students
        if (($fh = fopen($csvPath, 'r')) === false) {
            $this->command->error('Failed to open CSV file.');
            return;
        }

        $rowNumber = 0;
        while (($row = fgetcsv($fh)) !== false) {
            $rowNumber++;
            if (count($row) === 1 && trim($row[0]) === '') continue;

            $studentIdentifier = $row[1] ?? null;
            $studentName = $row[2] ?? null;

            if (!$studentIdentifier) {
                $this->command->warn("Row {$rowNumber}: no student id, skipping");
                continue;
            }

            // Find in allstudents table first (CSV ids are stored there)
            $all = AllStudent::where('student_id', $studentIdentifier)->first();
            if (!$all) {
                $this->command->warn("Row {$rowNumber}: student id {$studentIdentifier} not found in allstudents — skipping.");
                continue;
            }

            // Map to the Student account using email stored in allstudents
            $student = null;
            if (!empty($all->email)) {
                $student = Student::where('email', $all->email)->first();
            }

            if (!$student) {
                $this->command->warn("Row {$rowNumber}: No Student account found for allstudents.email={$all->email} — skipping.");
                continue;
            }

            DB::beginTransaction();
            try {
                $this->command->info("Enrolling student {$student->id} - {$student->name} (row {$rowNumber})");

                foreach ($subjects as $name => $subject) {
                    // attach if not already
                    if (!$student->subjects()->where('subject_id', $subject->id)->exists()) {
                        $student->subjects()->attach($subject->id);
                        $this->command->info("  Attached subject: {$name}");
                    } else {
                        $this->command->info("  Already attached: {$name}");
                    }

                    // Create a random mark for the subject between 40 and 90
                    $randomMarks = rand(40, 90);
                    // Prefer subject's teacher if set, otherwise pick a random teacher (create one if none exists)
                    $teacherId = $subject->teacher_id;
                    if (empty($teacherId)) {
                        $teacherId = Teacher::inRandomOrder()->value('id');
                        if (empty($teacherId)) {
                            // Create a teacher placeholder if none exist
                            $teacher = Teacher::factory()->create();
                            $teacherId = $teacher->id;
                        }
                    }

                    Mark::create([
                        'student_id' => $student->id,
                        'teacher_id' => $teacherId,
                        'subject_id' => $subject->id,
                        'marks' => $randomMarks,
                        'remarks' => 'Seeded mark',
                    ]);
                    $this->command->info("    Created mark {$randomMarks} for {$name}");
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error("  Error enrolling student {$student->id} on row {$rowNumber}: {$e->getMessage()}");
            }
        }

        fclose($fh);
        $this->command->info('EnrollSummer2025Seeder completed.');
    }
}
