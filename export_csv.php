<?php

function generateCsvContent($barangayName, $purokCount, $residentCount) {
    // Define CSV content
    $csvContent = "Purok Count,Resident Count,Barangay Name\n";
    $csvContent .= "$purokCount,$residentCount,$barangayName\n";

    return $csvContent;
}

?>