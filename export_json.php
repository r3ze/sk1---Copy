<?php

function generateJsonContent($barangayName, $purokCount, $residentCount) {
    // Define data array
    $data = array(
        'purokCount' => $purokCount,
        'residentCount' => $residentCount,
        'barangayName' => $barangayName
    );

    // Convert data array to JSON format
    $jsonContent = json_encode($data, JSON_PRETTY_PRINT);

    return $jsonContent;
}

?>