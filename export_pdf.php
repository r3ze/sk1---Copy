<?php

require 'fpdf/fpdf.php'; // Include FPDF library

function generatePdfContent($barangayName, $purokCount, $residentCount) {
    // Create a new PDF document
    $pdf = new FPDF();
    $pdf->AddPage();

    // Add content to the PDF
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Barangay Information', 0, 1, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Barangay Name: ' . $barangayName, 0, 1);
    $pdf->Cell(0, 10, 'Number of Puroks: ' . $purokCount, 0, 1);
    $pdf->Cell(0, 10, 'Number of Residents: ' . $residentCount, 0, 1);

    // Output the PDF content as a string
    ob_start();
    $pdf->Output('S');
    $pdfContent = ob_get_clean();

    return $pdfContent;
}
?>