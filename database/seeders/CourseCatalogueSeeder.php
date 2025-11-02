<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Department;
use App\Models\Credit;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class CourseCatalogueSeeder extends Seeder
{
    public function run(): void
    {
        $cseDepartment = Department::firstOrCreate(
            ['code' => 'CSE'],
            [
                'name' => 'Computer Science & Engineering',
                'code' => 'CSE',
                'description' => 'Computer Science & Engineering Department'
            ]
        );

        $eeeDepartment = Department::firstOrCreate(
            ['code' => 'EEE'],
            [
                'name' => 'Electrical & Electronic Engineering',
                'code' => 'EEE',
                'description' => 'Electrical & Electronic Engineering Department'
            ]
        );

        $defaultTeacher = Teacher::first();

        // Ensure canonical credits exist and fetch them
        $theory = Credit::updateOrCreate(['subject_type' => 'theory'], ['subject_type' => 'theory', 'credit_hour' => 3.0]);
        $lab = Credit::updateOrCreate(['subject_type' => 'lab'], ['subject_type' => 'lab', 'credit_hour' => 1.5]);

        $this->seedFromJSON('course catalogue CSE.json', $cseDepartment, $defaultTeacher, $theory, $lab);
        $this->seedFromJSON('course catalogue EEE.json', $eeeDepartment, $defaultTeacher, $theory, $lab);

        $this->command->info('Course catalogue seeding completed successfully!');
    }

    private function seedFromJSON($filename, $department, $teacher, $theory, $lab)
    {
        $jsonPath = base_path($filename);
        if (!file_exists($jsonPath)) {
            $this->command->error("JSON file not found: {$filename}");
            return;
        }
        $jsonContent = file_get_contents($jsonPath);
        $this->command->info("Reading JSON file: {$filename}");
        $this->command->info("File size: " . strlen($jsonContent) . " bytes");
        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->command->error("JSON parsing error: " . json_last_error_msg());
            $this->command->error("First 200 characters: " . substr($jsonContent, 0, 200));
            return;
        }
        if (!$data || !is_array($data)) {
            $this->command->error("Invalid JSON format in: {$filename}");
            return;
        }
        $this->command->info("Successfully parsed JSON with " . count($data) . " subjects");

        foreach ($data as $subjectData) {
            $this->createSubjectFromRow($subjectData, $department, $teacher, $theory, $lab);
        }
    }

    private function createSubjectFromRow($subjectData, $department, $teacher, $theory, $lab): void
    {
        $subjectName = $subjectData['Subject Name'] ?? null;
        $subjectCode = $subjectData['Subject Code'] ?? null;
        $year = $subjectData['Year'] ?? null;
        $subjectType = strtolower(trim((string) ($subjectData['Subject Type'] ?? '')));
        $creditPerHour = (float) ($subjectData['Credit per Hour'] ?? 0);
        if (!$subjectName || !$subjectCode || !$year) {
            $this->command->warn("Missing required data for subject: " . json_encode($subjectData));
            return;
        }
        $yearString = $this->convertYearToString($year);
        $uniqueSubjectName = $this->makeSubjectNameUnique($subjectName, $yearString, $subjectCode);

        // Choose canonical credit row
        $isLab = str_contains($subjectType, 'lab') || $creditPerHour < 3;
        $credit = $isLab ? $lab : $theory;

        $existsInDepartment = Subject::where('subject_code', $subjectCode)
            ->where('department_id', $department->id)
            ->exists();

        if (!$existsInDepartment) {
            Subject::create([
                'name' => $uniqueSubjectName,
                'subject_code' => $subjectCode,
                'year' => $yearString,
                'department_id' => $department->id,
                'credit_id' => $credit->id,
                'teacher_id' => $teacher ? $teacher->id : null,
            ]);
            $this->command->info("Created course: {$uniqueSubjectName} ({$subjectCode}) - {$yearString}");
        } else {
            $this->command->info("Skipped existing course in {$department->code}: {$uniqueSubjectName} ({$subjectCode})");
        }
    }

    private function makeSubjectNameUnique($subjectName, $yearString, $subjectCode)
    {
        $existingSubject = Subject::where('name', $subjectName)->first();
        if ($existingSubject) {
            if ($existingSubject->year === $yearString) {
                return $subjectName . ' (' . $subjectCode . ')';
            } else {
                return $subjectName . ' (' . $yearString . ')';
            }
        }
        return $subjectName;
    }

    private function convertYearToString($year)
    {
        if (is_string($year)) {
            return $year;
        }
        switch ($year) {
            case 1:
                return '1st Year';
            case 2:
                return '2nd Year';
            case 3:
                return '3rd Year';
            case 4:
                return '4th Year';
            default:
                return $year;
        }
    }
}
