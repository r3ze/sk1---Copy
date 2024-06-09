<?php

// Get the format and barangay name from the request
$format = isset($_POST['format']) ? $_POST['format'] : 'xlsx';
$barangayName = isset($_POST['barangayName']) ? $_POST['barangayName'] : '';
$jsonString = isset($_POST['jsonString']) ? $_POST['jsonString'] : '';

// Set appropriate headers based on the format
switch ($format) {
    case 'xlsx':
        // Generate XLSX file content
        require 'export_xlsx.php';
        $fileContent = generateXlsxContent($barangayName);
        break;
    case 'csv':
        // Generate CSV file content
        require 'export_csv.php';
        $fileContent = generateCsvContent($barangayName, $purokCount, $residentCount);
        break;
    case 'pdf':
        // Generate PDF file content
        require 'export_pdf.php';
        $fileContent = generatePdfContent($barangayName);
        break;
    case 'json':
        // Use the provided JSON string
        require 'export_json.php';
        $fileContent = $jsonString;
        break;
    default:
        // Invalid format
        http_response_code(400);
        echo 'Invalid export format';
        exit;
}

// Set headers for file download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $barangayName . '.' . $format . '"');

// Output the file content
echo $fileContent;

?>