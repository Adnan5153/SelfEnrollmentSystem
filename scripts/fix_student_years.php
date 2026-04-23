<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Backup current student year mapping
$backup = App\Models\Student::pluck('year', 'id')->toArray();
file_put_contents(__DIR__ . '/../storage/app/student_years_backup_20251103.json', json_encode($backup));
echo "Backup saved to storage/app/student_years_backup_20251103.json\n";

// Apply requested updates
App\Models\Student::whereBetween('id', [1, 41])->update(['year' => '2nd Year']);
App\Models\Student::where('id', '>', 41)->update(['year' => '1st Year']);

echo "Update complete\n";

// Verification counts
echo '2nd: ' . App\Models\Student::where('year', '2nd Year')->count() . PHP_EOL;
echo '1st: ' . App\Models\Student::where('year', '1st Year')->count() . PHP_EOL;
