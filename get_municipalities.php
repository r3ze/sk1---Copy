<?php
// Database connection details (replace with yours)
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'sk_database';

$conn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

// Fetch municipalities (you'll need a table with District 4 municipality names)
$query = "SELECT DISTINCT name FROM municipalities WHERE district = '4'"; 
$result = mysqli_query($conn, $query);

$municipalities = [];
while ($row = mysqli_fetch_assoc($result)) {
    $municipalities[] = $row['name'];
}

echo json_encode($municipalities); 
