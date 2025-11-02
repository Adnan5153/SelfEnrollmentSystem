<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Student;
use Illuminate\Support\Facades\DB;

echo "=== Updating All Student Years Based on Semester ===\n\n";

// Map semester/class names to academic years
$yearMapping = [
    'Spring-2024' => '1st Year',      // Students starting in Spring 2024
    'Summer-2024' => '1st Year',      // Same cohort continuing (1st year students)
    'Spring-2025' => '2nd Year',      // Students advancing to 2nd year
    'Summer-2025' => '2nd Year',      // 2nd year continuing
];

$students = Student::with('class')->get();
$updated = 0;
$byClass = [];

foreach ($students as $student) {
    if ($student->class && isset($yearMapping[$student->class->class_name])) {
        $newYear = $yearMapping[$student->class->class_name];
        $className = $student->class->class_name;
        
        if (!isset($byClass[$className])) {
            $byClass[$className] = ['total' => 0, 'updated' => 0];
        }
        $byClass[$className]['total']++;
        
        if ($student->year !== $newYear) {
            $student->year = $newYear;
            $student->save();
            $updated++;
            $byClass[$className]['updated']++;
        }
    }
}

echo "=== Summary ===\n";
echo "Total students checked: " . $students->count() . "\n";
echo "Students updated: {$updated}\n\n";

echo "=== Updates by Class ===\n";
foreach ($byClass as $className => $stats) {
    echo "{$className}: {$stats['updated']}/{$stats['total']} updated\n";
}

echo "\n=== Final Distribution by Year ===\n";
$distribution = Student::select('year', DB::raw('count(*) as count'))
    ->groupBy('year')
    ->orderBy('year')
    ->get();
    
foreach ($distribution as $dist) {
    echo "{$dist->year}: {$dist->count} students\n";
}

echo "\n=== Students by Class and Year ===\n";
$studentsByClass = Student::with('class')
    ->whereNotNull('class_id')
    ->get()
    ->groupBy(function($student) {
        return $student->class ? $student->class->class_name : 'No Class';
    });

foreach ($studentsByClass as $className => $studentGroup) {
    $yearBreakdown = $studentGroup->groupBy('year');
    echo "\n{$className}:\n";
    foreach ($yearBreakdown as $year => $students) {
        echo "  {$year}: {$students->count()} students\n";
    }
}

