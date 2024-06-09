<?php
// index.php

// Specify the target PHP file
$targetFile = 'home.php';

// Use header() to perform the redirection
header("Location: $targetFile");
exit; // Make sure to exit after the header() to prevent further execution
?>
