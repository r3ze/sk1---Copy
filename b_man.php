<?php
session_start(); // Make sure you have this at the beginning of your script
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sk_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

$email = $_SESSION['email'];
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Insert data into a table
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $barangay_name = $_POST['barangay_name'];

    $street = $_POST['street'];
    $municipal = $_POST['municipal'];
    $province = $_POST['province'];

    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $gender = $_POST['gender'];
    $marital_status = $_POST['marital_status'];
    $contact_no = $_POST['contact_no'];
    $religion = $_POST['religion'];
    $birthdate = $_POST['birthdate'];
    $osy = $_POST['osy'];
    $ws = $_POST['ws'];
    $yp = $_POST['yp'];
    $pwd = $_POST['pwd'];
    $barangay_purok_no = $_POST['barangay_purok_no'];
    $voters = $_POST['voters'];

    $yag = $_POST['yag'];
    $educ_background = $_POST['educ_background'];
    $yc = $_POST['yc'];
    $didvote = $_POST['didvote'];
    $skv = $_POST['skv'];
    $last_sk_vote = $_POST['last_sk_vote'];
    $kk_assembly_attendance = $_POST['kk_assembly_attendance'];
    $kk_assembly_no_reason = isset($_POST['kk_assembly_no_reason']) ? $_POST['kk_assembly_no_reason'] : '';
    $kk_assembly_yes = isset($_POST['kk_assembly_yes']) ? $_POST['kk_assembly_yes'] : '';
    $sk_suggestions = $_POST['sk_suggestions'];

    $recent_act = $last_name . ',' . $first_name . ' add record in Youth Resident';

    // Check if a record with the same first name and last name already exists
    $check_sql = "SELECT * FROM youth_barangay WHERE first_name = '$first_name' AND last_name = '$last_name'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        // Record with the same first name and last name already exists
        echo "Error: A record with the same first name and last name already exists.";
    } else {
        // Insert data into the youth_barangay table
        $sql = "INSERT INTO youth_barangay (session_id, barangay_name, street, municipal, province, last_name, first_name, middle_name, gender, marital_status, contact_no, religion, birthdate, osy, ws, yp, pwd, barangay_purok_no, voters, yag, educ_background, yc, didvote, skv, last_sk_vote, kk_assembly_attendance, kk_assembly_no_reason, kk_assembly_yes, sk_suggestions)
    VALUES ('$email', '$barangay_name','$street', '$municipal', '$province', '$last_name', '$first_name', '$middle_name', '$gender', '$marital_status', '$contact_no', '$religion', '$birthdate', '$osy', '$ws', '$yp', '$pwd', '$barangay_purok_no', '$voters', '$yag', '$educ_background', '$yc', '$didvote', '$skv', '$last_sk_vote', '$kk_assembly_attendance', '$kk_assembly_no_reason', '$kk_assembly_yes', '$sk_suggestions')";

        if ($conn->query($sql) === TRUE) {
            // Insert data into the recent_activity table
            $sql = "INSERT INTO recent_activity (session_id, barangay_name, last_name, first_name, middle_name, gender, marital_status, contact_no, religion, birthdate, osy, ws, yp, pwd, barangay_purok_no, voters, yag, educ_background, yc, didvote, skv, last_sk_vote, kk_assembly_attendance, kk_assembly_no_reason, kk_assembly_yes, sk_suggestions)
        VALUES ('$email', '$barangay_name', '$last_name', '$first_name', '$middle_name', '$gender', '$marital_status', '$contact_no', '$religion', '$birthdate', '$osy', '$ws', '$yp', '$pwd', '$barangay_purok_no', '$voters', '$yag', '$educ_background', '$yc', '$didvote', '$skv', '$last_sk_vote', '$kk_assembly_attendance', '$kk_assembly_no_reason', '$kk_assembly_yes', '$sk_suggestions')";


            if ($conn->query($sql) === TRUE) {
                echo "Record inserted successfully";
                header("Location: barangay-management.php?success=Record Inserted.");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
              }

        
            // echo "Success:";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
