<?php

require 'PhpSpreadsheet\src\PhpSpreadsheet\Spreadsheet.php';
require 'PhpSpreadsheet\src\PhpSpreadsheet\Writer\Xlsx.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function generateXlsxContent($barangayName, $purokCount, $residentCount) {
    // Create a new PhpSpreadsheet instance
    $spreadsheet = new Spreadsheet();

    // Get the active sheet
    $sheet = $spreadsheet->getActiveSheet();

    // Populate the sheet with data
    $sheet->setCellValue('A1', 'Purok Count');
    $sheet->setCellValue('B1', 'Resident Count');
    $sheet->setCellValue('C1', 'Barangay Name');

    $sheet->setCellValue('A2', $purokCount);
    $sheet->setCellValue('B2', $residentCount);
    $sheet->setCellValue('C2', $barangayName);

    // Set auto width for all columns
    foreach (range('A', 'C') as $column) {
        $sheet->getColumnDimension($column)->setAutoSize(true);
    }

    // Create a writer object
    $writer = new Xlsx($spreadsheet);

    // Save the XLSX file content to a variable
    ob_start();
    $writer->save('php://output');
    $content = ob_get_clean();

    return $content;
}

?>