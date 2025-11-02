<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Classes Created ===\n";
$classes = App\Models\ClassModel::all(['id', 'class_name', 'section']);
foreach ($classes as $class) {
    echo "ID: {$class->id}, Name: {$class->class_name}, Section: {$class->section}\n";
}

echo "\n=== Students by Class ===\n";
$students = App\Models\Student::with('class:id,class_name,section')->get();
$grouped = $students->groupBy('class_id');

foreach ($grouped as $classId => $studentGroup) {
    if ($studentGroup->first()->class) {
        $class = $studentGroup->first()->class;
        echo "\n{$class->class_name} - Section {$class->section}: {$studentGroup->count()} students\n";
    } else {
        echo "\nNo Class (class_id: {$classId}): {$studentGroup->count()} students\n";
    }
}

echo "\n=== Summary ===\n";
echo "Total classes: " . $classes->count() . "\n";
echo "Total students: " . $students->count() . "\n";

