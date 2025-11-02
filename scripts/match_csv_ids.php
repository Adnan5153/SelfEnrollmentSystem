<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\AllStudent;
use App\Models\Student;

$csv = $argv[1] ?? base_path('summer 2025.csv');
if (!file_exists($csv)) {
    echo "CSV not found: {$csv}\n";
    exit(1);
}

$fh = fopen($csv, 'r');
if (!$fh) { echo "Failed to open CSV\n"; exit(1); }

echo "CSV -> AllStudent -> Student mapping report\n";
echo str_repeat('=', 60) . "\n";
$row = 0;
while (($data = fgetcsv($fh)) !== false) {
    $row++;
    if (count($data) === 1 && trim($data[0]) === '') continue;
    $csvId = trim($data[1] ?? '');
    $name = trim($data[2] ?? '');
    if ($csvId === '') {
        echo "Row {$row}: no id\n";
        continue;
    }

    $all = AllStudent::where('student_id', $csvId)->first();
    if (!$all) {
        echo "Row {$row}: CSV id {$csvId} - NOT FOUND in allstudents (name: {$name})\n";
        continue;
    }

    $email = $all->email ?? '';
    $student = null;
    if ($email) {
        $student = Student::where('email', $email)->first();
    }

    echo "Row {$row}: CSV id {$csvId} -> allstudents.id={$all->student_id}, all.email={$email} -> ";
    if ($student) {
        echo "students.id={$student->id}, students.name={$student->name}\n";
    } else {
        echo "NO linked Student account (students.email not found)\n";
    }
}

fclose($fh);
echo "Done.\n";
