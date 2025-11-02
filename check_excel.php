<?php

require 'vendor/autoload.php';

try {
    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    $spreadsheet = $reader->load('Book1.xlsx');
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    echo "=== Excel File Analysis ===\n\n";
    echo "Total rows: " . count($rows) . "\n";
    echo "Total columns: " . ($rows[0] ? count($rows[0]) : 0) . "\n\n";

    // Headers
    echo "=== HEADERS (Row 1) ===\n";
    if (!empty($rows[0])) {
        foreach ($rows[0] as $index => $header) {
            echo "Column " . ($index + 1) . " ($index): " . trim($header) . "\n";
        }
    }
    echo "\n";

    // Sample data rows
    echo "=== SAMPLE DATA ROWS ===\n";
    $sampleRows = min(5, count($rows) - 1);
    for ($i = 1; $i <= $sampleRows && $i < count($rows); $i++) {
        echo "\n--- Row " . ($i + 1) . " ---\n";
        if (!empty($rows[$i])) {
            foreach ($rows[$i] as $index => $cell) {
                $header = isset($rows[0][$index]) ? trim($rows[0][$index]) : "Column " . ($index + 1);
                echo $header . ": " . trim($cell) . "\n";
            }
        }
    }

    echo "\n=== SUMMARY ===\n";
    echo "Total data rows: " . (count($rows) - 1) . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}

