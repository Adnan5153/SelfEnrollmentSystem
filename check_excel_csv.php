<?php

// Alternative: Check if CSV exists, or provide instructions
$csvFile = 'Book1.csv';
$xlsxFile = 'Book1.xlsx';

echo "=== Excel File Checker ===\n\n";

if (file_exists($csvFile)) {
    echo "Found CSV file: $csvFile\n";
    echo "Reading CSV file...\n\n";
    
    $handle = fopen($csvFile, 'r');
    if ($handle) {
        $rowNum = 0;
        echo "=== HEADERS ===\n";
        $headers = fgetcsv($handle);
        if ($headers) {
            foreach ($headers as $index => $header) {
                echo "Column " . ($index + 1) . ": " . trim($header) . "\n";
            }
        }
        echo "\n";
        
        echo "=== SAMPLE DATA (First 5 rows) ===\n";
        $sampleCount = 0;
        while (($row = fgetcsv($handle)) !== false && $sampleCount < 5) {
            $rowNum++;
            echo "\n--- Row " . ($rowNum + 1) . " ---\n";
            foreach ($headers as $index => $header) {
                $value = isset($row[$index]) ? trim($row[$index]) : '';
                echo trim($header) . ": " . $value . "\n";
            }
            $sampleCount++;
        }
        fclose($handle);
        
        // Count total rows
        $totalRows = count(file($csvFile));
        echo "\n=== SUMMARY ===\n";
        echo "Total rows in CSV: $totalRows\n";
    }
} elseif (file_exists($xlsxFile)) {
    echo "Found XLSX file: $xlsxFile\n";
    echo "But cannot read it because PHP zip extension is not enabled.\n\n";
    echo "=== SOLUTION ===\n";
    echo "Option 1: Enable PHP zip extension\n";
    echo "  1. Open: C:\\xampp\\php\\php.ini\n";
    echo "  2. Find: ;extension=zip\n";
    echo "  3. Remove semicolon: extension=zip\n";
    echo "  4. Restart Apache\n\n";
    echo "Option 2: Convert XLSX to CSV manually\n";
    echo "  1. Open Book1.xlsx in Excel\n";
    echo "  2. Save As > CSV (Comma delimited)\n";
    echo "  3. Save as Book1.csv in project root\n";
    echo "  4. Run: php check_excel_csv.php\n";
} else {
    echo "Neither $csvFile nor $xlsxFile found!\n";
    echo "Please ensure the Excel file is in the project root directory.\n";
}

