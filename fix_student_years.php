<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Fixing Student Years Based on Semester/Class ===\n\n";

// Map semester/class names to academic years
// This mapping determines which academic year students belong to
$yearMapping = [
    'Spring-2024' => '1st Year',      // Students starting in Spring 2024
    'Summer-2024' => '1st Year',      // Same cohort continuing (or could be 2nd year)
    'Spring-2025' => '2nd Year',      // Students advancing to 2nd year
    'Summer-2025' => '2nd Year',      // 2nd year continuing (or could be 3rd year)
];

// Alternative: More aggressive progression (if Summer 2024 means they're now in 2nd year)
// $yearMapping = [
//     'Spring-2024' => '1st Year',
//     'Summer-2024' => '2nd Year',  // If they progressed
//     'Spring-2025' => '2nd Year',
//     'Summer-2025' => '3rd Year',
// ];

$students = App\Models\Student::with('class')->get();
$updated = 0;

foreach ($students as $student) {
    if ($student->class && isset($yearMapping[$student->class->class_name])) {
        $newYear = $yearMapping[$student->class->class_name];
        
        if ($student->year !== $newYear) {
            echo "Updating {$student->name} ({$student->class->class_name}): {$student->year} -> {$newYear}\n";
            $student->year = $newYear;
            $student->save();
            $updated++;
        }
    }
}

echo "\n=== Summary ===\n";
echo "Total students checked: " . $students->count() . "\n";
echo "Students updated: {$updated}\n";

// Show final distribution
echo "\n=== Final Distribution by Year ===\n";
$distribution = App\Models\Student::select('year', DB::raw('count(*) as count'))
    ->groupBy('year')
    ->get();
    
foreach ($distribution as $dist) {
    echo "{$dist->year}: {$dist->count} students\n";
}

