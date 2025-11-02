<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;

$id = $argv[1] ?? '25302001';
$cols = ['id','student_id','student_no','roll','roll_no','registration_no','reg_no','id_no','admission_no','admission_no','registration_no'];
$found = false;
foreach ($cols as $c) {
    try {
        $s = Student::where($c, $id)->first();
        if ($s) {
            echo "found by {$c}: id={$s->id}, name={$s->name}\n";
            $found = true;
        }
    } catch (\Exception $e) {
        // ignore invalid columns
    }
}
if (!$found) {
    echo "No student found for identifier {$id} in common columns.\n";
}
