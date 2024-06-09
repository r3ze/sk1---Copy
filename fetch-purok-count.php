<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sk_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'barangay' POST parameter is set
if(isset($_POST['barangay'])) {
    // Sanitize the input to prevent SQL injection
    $barangayName = $_POST['barangay'];

    // Prepare your database connection
    // Example: $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch the total number of puroks for the selected barangay
    $query = "SELECT COUNT(barangay_name) AS purokCount FROM barangay_purok WHERE barangay_name = '$barangayName'";

    // Perform the query
    $result = $conn->query($query);

    // Check if the query was successful
    if($result) {
        // Fetch the result as an associative array
        $row = $result->fetch_assoc();

        // Prepare the response as JSON
        $response = array(
            'barangayName' => $barangayName,
            'purokCount' => $row['purokCount']
        );

        // Send the response as JSON
        echo json_encode($response);
    } else {
        // If the query fails, return an error message
        echo json_encode(array('error' => 'Failed to fetch purok count'));
    }

    // Close the database connection
    $conn->close();
} else {
    // If the 'barangay' POST parameter is not set, return an error message
    echo json_encode(array('error' => 'Barangay name not provided'));
}
?>