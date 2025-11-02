<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use App\Models\AllStudent;
use App\Models\ClassModel;
use App\Models\Department;
use App\Models\ParentModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ImportStudentsFromExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:import {file=Book1.xlsx} {--class=} {--section=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import students from Excel file based on class and section';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        
        // Check if file exists
        if (!file_exists($filePath)) {
            $filePath = base_path($filePath);
            if (!file_exists($filePath)) {
                $this->error("File not found: {$filePath}");
                return 1;
            }
        }

        $this->info("Reading file: {$filePath}");

        // Check if file is CSV or XLSX
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        if ($extension === 'csv') {
            return $this->importFromCsv($filePath);
        } elseif (class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            return $this->importWithPhpSpreadsheet($filePath);
        } else {
            $this->error("PhpSpreadsheet library is not installed.");
            $this->info("To install, run: composer require phpoffice/phpspreadsheet");
            $this->info("Note: You may need to enable PHP extensions: ext-gd and ext-zip");
            $this->info("\nAlternatively, convert your XLSX to CSV and use the CSV file.");
            return 1;
        }
    }

    private function importFromCsv($filePath)
    {
        try {
            $handle = fopen($filePath, 'r');
            if (!$handle) {
                $this->error("Cannot open CSV file: {$filePath}");
                return 1;
            }

            // Read headers
            $headers = fgetcsv($handle);
            if (!$headers) {
                $this->error("CSV file is empty or invalid");
                fclose($handle);
                return 1;
            }

            $this->info("Headers found: " . implode(', ', $headers));

            // Clean headers - remove empty columns
            $cleanHeaders = [];
            $headerMapping = [];
            foreach ($headers as $index => $header) {
                $cleanHeader = trim($header);
                if (!empty($cleanHeader)) {
                    $cleanHeaders[] = $cleanHeader;
                    $headerMapping[] = $index;
                }
            }

            // Find column indices (try exact match first, then case-insensitive)
            $nameCol = $this->findColumn($cleanHeaders, ['Student Name', 'student name', 'name', 'full name', 'student_name']);
            $emailCol = $this->findColumn($cleanHeaders, ['Email', 'email', 'email address', 'student_email']);
            $studentIdCol = $this->findColumn($cleanHeaders, ['Student ID', 'student_id', 'id', 'student id', 'studentid']);
            $classCol = $this->findColumn($cleanHeaders, ['Class', 'class', 'class_name', 'class name']);
            $sectionCol = $this->findColumn($cleanHeaders, ['Section', 'section', 'sec']);
            $departmentCol = $this->findColumn($cleanHeaders, ['Department', 'department', 'department_id', 'dept', 'department id']);
            $genderCol = $this->findColumn($cleanHeaders, ['Gender', 'gender', 'sex']);
            $religionCol = $this->findColumn($cleanHeaders, ['Religion', 'Relligion', 'religion', 'religious', 'relligion']);

            // Map back to original column indices
            if ($nameCol !== null) {
                $nameCol = $headerMapping[$nameCol];
            }
            if ($emailCol !== null) {
                $emailCol = $headerMapping[$emailCol];
            }
            if ($studentIdCol !== null) {
                $studentIdCol = $headerMapping[$studentIdCol];
            }
            if ($classCol !== null) {
                $classCol = $headerMapping[$classCol];
            }
            if ($sectionCol !== null) {
                $sectionCol = $headerMapping[$sectionCol];
            }
            if ($departmentCol !== null) {
                $departmentCol = $headerMapping[$departmentCol];
            }
            if ($genderCol !== null) {
                $genderCol = $headerMapping[$genderCol];
            }
            if ($religionCol !== null) {
                $religionCol = $headerMapping[$religionCol];
            }

            if ($nameCol === null || $emailCol === null) {
                $this->error("Required columns (name, email) not found in CSV file");
                $this->info("Available columns: " . implode(', ', $cleanHeaders));
                $this->info("Name column index: " . ($nameCol ?? 'NOT FOUND'));
                $this->info("Email column index: " . ($emailCol ?? 'NOT FOUND'));
                fclose($handle);
                return 1;
            }

            // Filter options
            $filterClass = $this->option('class');
            $filterSection = $this->option('section');

            $this->info("Filtering by Class: " . ($filterClass ?: 'All'));
            $this->info("Filtering by Section: " . ($filterSection ?: 'All'));

            $studentsProcessed = 0;
            $studentsCreated = 0;
            $errors = [];

            DB::beginTransaction();

            try {
                // Get default parent
                $parent = ParentModel::firstOrCreate(
                    ['parent_email' => 'default.parent@institution.edu'],
                    [
                        'father_name' => 'Default Father',
                        'mother_name' => 'Default Mother',
                        'father_occupation' => 'N/A',
                        'mother_occupation' => 'N/A',
                        'phone_number' => '+880 1XXX-XXXXXX',
                        'present_address' => 'Default Address',
                        'permanent_address' => 'Default Address',
                    ]
                );

                // Process rows
                $rowNum = 1;
                while (($row = fgetcsv($handle)) !== false) {
                    $rowNum++;

                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $studentsProcessed++;

                    // Extract data
                    $name = trim($row[$nameCol] ?? '');
                    $email = trim($row[$emailCol] ?? '');
                    
                    if (empty($name) || empty($email)) {
                        $errors[] = "Row {$rowNum}: Missing name or email";
                        continue;
                    }

                    // Get class name and section
                    $className = $classCol !== null ? trim($row[$classCol] ?? '') : '';
                    $section = $sectionCol !== null ? trim($row[$sectionCol] ?? '') : null;

                    // Filter by class and section if specified
                    if ($filterClass && $className !== $filterClass) {
                        continue;
                    }
                    if ($filterSection && $section !== $filterSection) {
                        continue;
                    }

                    // Find or create class
                    $class = null;
                    if ($className) {
                        $class = ClassModel::where('class_name', $className)
                            ->where(function($query) use ($section) {
                                if ($section) {
                                    $query->where('section', $section);
                                } else {
                                    $query->whereNull('section');
                                }
                            })
                            ->first();

                        if (!$class) {
                            $class = ClassModel::create([
                                'class_name' => $className,
                                'section' => $section,
                                'capacity' => 50,
                            ]);
                            $this->info("Created new class: {$className}" . ($section ? " - {$section}" : ''));
                        }
                    }

                    // Get department
                    $department = null;
                    if ($departmentCol !== null) {
                        $deptName = trim($row[$departmentCol] ?? '');
                        if ($deptName) {
                            $department = Department::where('name', 'like', "%{$deptName}%")
                                ->orWhere('code', 'like', "%{$deptName}%")
                                ->first();
                        }
                    }

                    if (!$department) {
                        $department = Department::first();
                        if (!$department) {
                            $errors[] = "Row {$rowNum}: No department found and no default department exists";
                            continue;
                        }
                    }

                    // Get student ID
                    $studentId = null;
                    if ($studentIdCol !== null) {
                        $studentId = trim($row[$studentIdCol] ?? '');
                        if ($studentId && is_numeric($studentId)) {
                            $studentId = (int)$studentId;
                        } else {
                            $studentId = null;
                        }
                    }

                    // Generate student ID if not provided
                    if (!$studentId) {
                        $maxId = AllStudent::max('student_id') ?? 0;
                        $studentId = $maxId + 1;
                    }

                    // Check if student already exists
                    $existingStudent = AllStudent::where('student_id', $studentId)->first();
                    if ($existingStudent) {
                        $this->warn("Student ID {$studentId} already exists, skipping...");
                        continue;
                    }

                    $existingEmail = AllStudent::where('email', $email)->orWhereHas('studentAccount', function($q) use ($email) {
                        $q->where('email', $email);
                    })->first();
                    if ($existingEmail) {
                        $this->warn("Email {$email} already exists, skipping...");
                        continue;
                    }

                    // Generate random birth date (between 18-25 years ago)
                    $randomDays = rand(6570, 9125); // 18-25 years in days
                    $birthDate = Carbon::now()->subDays($randomDays)->format('Y-m-d');

                    // Split name into first and last
                    $nameParts = explode(' ', $name, 2);
                    $firstName = $nameParts[0];
                    $lastName = $nameParts[1] ?? '';

                    // Get gender
                    $gender = 'Male';
                    if ($genderCol !== null) {
                        $genderValue = strtolower(trim($row[$genderCol] ?? ''));
                        if (in_array($genderValue, ['female', 'f', 'girl', 'woman'])) {
                            $gender = 'Female';
                        }
                    }

                    // Get religion
                    $religion = 'Islam';
                    if ($religionCol !== null) {
                        $religionValue = trim($row[$religionCol] ?? '');
                        if ($religionValue) {
                            $religion = $religionValue;
                        }
                    }

                    // Create AllStudent
                    $allStudent = AllStudent::create([
                        'student_id' => $studentId,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'class_id' => $class?->id,
                        'class' => $class?->class_name ?? $className,
                        'section' => $class?->section ?? $section,
                        'gender' => $gender,
                        'date_of_birth' => $birthDate,
                        'department_id' => $department->id,
                        'religion' => $religion,
                        'email' => $email,
                        'parent_id' => $parent->id,
                    ]);

                    // Create Student account
                    Student::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make('12345678'),
                        'year' => $this->determineYear($className),
                        'credit_completed' => 0,
                        'email_verified_at' => now(),
                        'class_id' => $class?->id,
                        'section' => $class?->section ?? $section,
                        'department_id' => $department->id,
                    ]);

                    $studentsCreated++;
                    $this->info("Created student: {$name} (ID: {$studentId}, Email: {$email})");
                }

                fclose($handle);
                DB::commit();

                $this->info("\n=== Import Summary ===");
                $this->info("Total rows processed: {$studentsProcessed}");
                $this->info("Students created: {$studentsCreated}");
                
                if (!empty($errors)) {
                    $this->warn("\nErrors encountered: " . count($errors));
                    foreach (array_slice($errors, 0, 10) as $error) {
                        $this->warn("  - {$error}");
                    }
                    if (count($errors) > 10) {
                        $this->warn("  ... and " . (count($errors) - 10) . " more errors");
                    }
                }

                $this->info("\nDefault password for all students: 12345678");
                return 0;

            } catch (\Exception $e) {
                fclose($handle);
                DB::rollBack();
                $this->error("Error importing students: " . $e->getMessage());
                $this->error($e->getTraceAsString());
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("Error reading CSV file: " . $e->getMessage());
            return 1;
        }
    }

    private function importWithPhpSpreadsheet($filePath)
    {
        try {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows)) {
                $this->error("Excel file is empty");
                return 1;
            }

            // Get headers from first row
            $headers = array_map('trim', $rows[0]);
            $this->info("Headers found: " . implode(', ', $headers));

            // Find column indices
            $nameCol = $this->findColumn($headers, ['name', 'student name', 'full name', 'student_name']);
            $emailCol = $this->findColumn($headers, ['email', 'email address', 'student_email']);
            $studentIdCol = $this->findColumn($headers, ['student_id', 'id', 'student id', 'studentid']);
            $classCol = $this->findColumn($headers, ['class', 'class_name', 'class name']);
            $sectionCol = $this->findColumn($headers, ['section', 'sec']);
            $departmentCol = $this->findColumn($headers, ['department', 'department_id', 'dept', 'department id']);
            $genderCol = $this->findColumn($headers, ['gender', 'sex']);
            $religionCol = $this->findColumn($headers, ['religion', 'religious']);

            if ($nameCol === null || $emailCol === null) {
                $this->error("Required columns (name, email) not found in Excel file");
                $this->info("Available columns: " . implode(', ', $headers));
                return 1;
            }

            // Filter options
            $filterClass = $this->option('class');
            $filterSection = $this->option('section');

            $this->info("Filtering by Class: " . ($filterClass ?: 'All'));
            $this->info("Filtering by Section: " . ($filterSection ?: 'All'));

            $studentsProcessed = 0;
            $studentsCreated = 0;
            $errors = [];

            DB::beginTransaction();

            try {
                // Get default parent
                $parent = ParentModel::firstOrCreate(
                    ['parent_email' => 'default.parent@institution.edu'],
                    [
                        'father_name' => 'Default Father',
                        'mother_name' => 'Default Mother',
                        'father_occupation' => 'N/A',
                        'mother_occupation' => 'N/A',
                        'phone_number' => '+880 1XXX-XXXXXX',
                        'present_address' => 'Default Address',
                        'permanent_address' => 'Default Address',
                    ]
                );

                // Process rows (skip header)
                for ($i = 1; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $studentsProcessed++;

                    // Extract data
                    $name = trim($row[$nameCol] ?? '');
                    $email = trim($row[$emailCol] ?? '');
                    
                    if (empty($name) || empty($email)) {
                        $errors[] = "Row " . ($i + 1) . ": Missing name or email";
                        continue;
                    }

                    // Get class name and section
                    $className = $classCol !== null ? trim($row[$classCol] ?? '') : '';
                    $section = $sectionCol !== null ? trim($row[$sectionCol] ?? '') : null;

                    // Filter by class and section if specified
                    if ($filterClass && $className !== $filterClass) {
                        continue;
                    }
                    if ($filterSection && $section !== $filterSection) {
                        continue;
                    }

                    // Find or create class
                    $class = null;
                    if ($className) {
                        $class = ClassModel::where('class_name', $className)
                            ->where(function($query) use ($section) {
                                if ($section) {
                                    $query->where('section', $section);
                                } else {
                                    $query->whereNull('section');
                                }
                            })
                            ->first();

                        if (!$class) {
                            $class = ClassModel::create([
                                'class_name' => $className,
                                'section' => $section,
                                'capacity' => 50,
                            ]);
                            $this->info("Created new class: {$className}" . ($section ? " - {$section}" : ''));
                        }
                    }

                    // Get department
                    $department = null;
                    if ($departmentCol !== null) {
                        $deptName = trim($row[$departmentCol] ?? '');
                        if ($deptName) {
                            $department = Department::where('name', 'like', "%{$deptName}%")
                                ->orWhere('code', 'like', "%{$deptName}%")
                                ->first();
                        }
                    }

                    if (!$department) {
                        $department = Department::first();
                        if (!$department) {
                            $errors[] = "Row " . ($i + 1) . ": No department found and no default department exists";
                            continue;
                        }
                    }

                    // Get student ID
                    $studentId = null;
                    if ($studentIdCol !== null) {
                        $studentId = trim($row[$studentIdCol] ?? '');
                        if ($studentId && is_numeric($studentId)) {
                            $studentId = (int)$studentId;
                        } else {
                            $studentId = null;
                        }
                    }

                    // Generate student ID if not provided
                    if (!$studentId) {
                        $maxId = AllStudent::max('student_id') ?? 0;
                        $studentId = $maxId + 1;
                    }

                    // Check if student already exists
                    $existingStudent = AllStudent::where('student_id', $studentId)->first();
                    if ($existingStudent) {
                        $this->warn("Student ID {$studentId} already exists, skipping...");
                        continue;
                    }

                    $existingEmail = AllStudent::where('email', $email)->orWhereHas('studentAccount', function($q) use ($email) {
                        $q->where('email', $email);
                    })->first();
                    if ($existingEmail) {
                        $this->warn("Email {$email} already exists, skipping...");
                        continue;
                    }

                    // Generate random birth date (between 18-25 years ago)
                    $randomDays = rand(6570, 9125); // 18-25 years in days
                    $birthDate = Carbon::now()->subDays($randomDays)->format('Y-m-d');

                    // Split name into first and last
                    $nameParts = explode(' ', $name, 2);
                    $firstName = $nameParts[0];
                    $lastName = $nameParts[1] ?? '';

                    // Get gender
                    $gender = 'Male';
                    if ($genderCol !== null) {
                        $genderValue = strtolower(trim($row[$genderCol] ?? ''));
                        if (in_array($genderValue, ['female', 'f', 'girl', 'woman'])) {
                            $gender = 'Female';
                        }
                    }

                    // Get religion
                    $religion = 'Islam';
                    if ($religionCol !== null) {
                        $religionValue = trim($row[$religionCol] ?? '');
                        if ($religionValue) {
                            $religion = $religionValue;
                        }
                    }

                    // Create AllStudent
                    $allStudent = AllStudent::create([
                        'student_id' => $studentId,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'class_id' => $class?->id,
                        'class' => $class?->class_name ?? $className,
                        'section' => $class?->section ?? $section,
                        'gender' => $gender,
                        'date_of_birth' => $birthDate,
                        'department_id' => $department->id,
                        'religion' => $religion,
                        'email' => $email,
                        'parent_id' => $parent->id,
                    ]);

                    // Create Student account
                    Student::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => Hash::make('12345678'),
                        'year' => $this->determineYear($className),
                        'credit_completed' => 0,
                        'email_verified_at' => now(),
                        'class_id' => $class?->id,
                        'section' => $class?->section ?? $section,
                        'department_id' => $department->id,
                    ]);

                    $studentsCreated++;
                    $this->info("Created student: {$name} (ID: {$studentId}, Email: {$email})");
                }

                DB::commit();

                $this->info("\n=== Import Summary ===");
                $this->info("Total rows processed: {$studentsProcessed}");
                $this->info("Students created: {$studentsCreated}");
                
                if (!empty($errors)) {
                    $this->warn("\nErrors encountered: " . count($errors));
                    foreach ($errors as $error) {
                        $this->warn("  - {$error}");
                    }
                }

                $this->info("\nDefault password for all students: 12345678");
                return 0;

            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Error importing students: " . $e->getMessage());
                $this->error($e->getTraceAsString());
                return 1;
            }

        } catch (\Exception $e) {
            $this->error("Error reading Excel file: " . $e->getMessage());
            return 1;
        }
    }

    private function findColumn($headers, $possibleNames)
    {
        foreach ($possibleNames as $name) {
            foreach ($headers as $index => $header) {
                $headerTrimmed = trim($header);
                $headerLower = strtolower($headerTrimmed);
                $nameLower = strtolower(trim($name));
                
                // Exact match (case-insensitive)
                if ($headerLower === $nameLower) {
                    return $index;
                }
                
                // Match without spaces
                if (str_replace(' ', '', $headerLower) === str_replace(' ', '', $nameLower)) {
                    return $index;
                }
                
                // Partial match - contains the search term
                if (strpos($headerLower, $nameLower) !== false || strpos($nameLower, $headerLower) !== false) {
                    return $index;
                }
            }
        }
        return null;
    }

    private function determineYear($className)
    {
        if (!$className) {
            return '1st Year';
        }

        $className = strtolower($className);
        if (stripos($className, '1') !== false || stripos($className, 'first') !== false || stripos($className, 'one') !== false) {
            return '1st Year';
        }
        if (stripos($className, '2') !== false || stripos($className, 'second') !== false || stripos($className, 'two') !== false) {
            return '2nd Year';
        }
        if (stripos($className, '3') !== false || stripos($className, 'third') !== false || stripos($className, 'three') !== false) {
            return '3rd Year';
        }
        if (stripos($className, '4') !== false || stripos($className, 'fourth') !== false || stripos($className, 'four') !== false) {
            return '4th Year';
        }
        
        return '1st Year';
    }
}
