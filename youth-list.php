<?php
session_start(); // Start the session to access session variables

// Check if email is set in session
if (isset($_SESSION['session_id'])) {
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

  

  $email = $_SESSION['email'];

   // Fetch additional information based on the email
   $sql = "SELECT street, municipal, province, first_name, middle_name, last_name FROM barangay WHERE session_id = '$email'";
   $result = $conn->query($sql);
 
   if ($result->num_rows > 0) {
     $row = $result->fetch_assoc();
     $street = $row['street'];
     $municipal = $row['municipal'];
     $province = $row['province'];
     $first_name = $row['first_name'];
     $middle_name = $row['middle_name'];
     $last_name = $row['last_name'];
   }


  $sql = "SELECT * FROM youth_barangay WHERE session_id = '$email'";
  $result = $conn->query($sql);
} else {
  // If email is not set in session, redirect to login page
  header("Location: barangay-login.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Youth List</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Jan 29 2024 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

   <!-- ======= Header ======= -->
   <?php
include("./include/barangay-header.php");
  ?>
 <!-- End Header -->

<!-- ======= Sidebar ======= -->
<?php
include("./include/barangay-sidebar.php");
  ?>
 <!-- End Sidebar-->


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Resident List</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Youth Resident List</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    

  </main><!-- End #main -->
  <section class="section-yt">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">


              <table class="table datatable">
                <thead>
                  <tr>
                    <th style="background-color: rgb(0, 123, 255); color: white;">Full Name</th>
                    <th style="background-color: rgb(0, 123, 255); color: white;">Purok</th>

                    <th hidden  style="background-color: rgb(0, 123, 255); color: white;">First Name</th>
                    <th hidden  style="background-color: rgb(0, 123, 255); color: white;">Middle Name</th>
                    <th hidden  style="background-color: rgb(0, 123, 255); color: white;">Last Name</th>

                    <th style="background-color: rgb(0, 123, 255); color: white;">Gender</th>
                    <th style="background-color: rgb(0, 123, 255); color: white;">Contact No.</th>
                    <th data-type="date" data-format="YYYY/DD/MM"  style="background-color: rgb(0, 123, 255); color: white;">Birthdate</th>
                    <th style="background-color: rgb(0, 123, 255); color: white;">Age</th>
                    <th style="background-color: rgb(0, 123, 255); color: white;">Marital Status</th>
                    <th style="background-color: rgb(0, 123, 255); color: white;">Religion</th>
                    <th hidden  style="background-color: rgb(0, 123, 255); color: white;">osy</th>
                    <th hidden  style="background-color: rgb(0, 123, 255); color: white;">ws</th>
                    <th hidden  style="background-color: rgb(0, 123, 255); color: white;">yp</th>
                    <th hidden  style="background-color: rgb(0, 123, 255); color: white;">pwd</th>
                    <th hidden  style="background-color: rgb(0, 123, 255); color: white;">session_id</th>
                    <th style="background-color: rgb(0, 123, 255); color: white;">Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
function calculateAge($birthdate)
{
    // Calculate age based on the birthdate
    $birthDate = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y;

    return $age;
}

// Check if there are any records fetched
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
        echo "<td>" . $row["barangay_purok_no"] . "</td>";
        echo "<td hidden>" . $row["first_name"] . "</td>";
        echo "<td hidden>" . $row["middle_name"] . "</td>";
        echo "<td hidden>" . $row["last_name"] . "</td>";
        echo "<td>" . $row["gender"] . "</td>";
        echo "<td>" . $row["contact_no"] . "</td>";
        echo "<td>" . $row["birthdate"] . "</td>";
        $age = calculateAge($row["birthdate"]);
        echo "<td>" . $age . "</td>";

        // Check if age is greater than 30 and trigger archiveRecord function
        if ($age > 30) {
            echo "<script>archiveRecord(" . $row["id"] . ");</script>";
        }

        echo "<td>" . $row["marital_status"] . "</td>";
        echo "<td>" . $row["religion"] . "</td>";
        echo "<td hidden>" . $row["osy"] . "</td>";
        echo "<td hidden>" . $row["ws"] . "</td>";
        echo "<td hidden>" . $row["yp"] . "</td>";
        echo "<td hidden>" . $row["pwd"] . "</td>";
        echo "<td hidden>" . $row["session_id"] . "</td>";

        echo "<td><button style=\"padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: 0.3s;\" onclick=\"editRecord('" . $row["id"] . "')\">Edit</button> <button style=\"padding: 10px 20px; background-color: #ffc107; color: white; border: none; border-radius: 4px; cursor: pointer; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: 0.3s;\" onclick=\"archiveRecord('" . $row["id"] . "')\">Archive</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>No records found</td></tr>";
}
?>
</tbody>
</table>

<div id="editForm" style="display: none;">
    <!-- Edit Form will be displayed here -->
</div>

<script>
    function editRecord(id) {
        // AJAX request to fetch record details
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("editForm").innerHTML = this.responseText;
                document.getElementById("editForm").style.display = "block";
                var myModal = new bootstrap.Modal(document.getElementById('editModal'));
                myModal.show();
            }
        };
        xhttp.open("GET", "edit-youth.php?id=" + id, true);
        xhttp.send();
    }

    function archiveRecord(id) {
        // AJAX request to archive record
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // Refresh the page after archiving
                window.location.reload();
            }
        };
        xhttp.open("GET", "archive-youth.php?id=" + id, true);
        xhttp.send();
    }
</script>
<script>
    // Function to calculate age
    function calculateAge(birthdate) {
        var birthDate = new Date(birthdate);
        var currentDate = new Date();
        var age = currentDate.getFullYear() - birthDate.getFullYear();

        // Check if the birthday has occurred for this year
        if (currentDate.getMonth() < birthDate.getMonth() || (currentDate.getMonth() === birthDate.getMonth() && currentDate.getDate() < birthDate.getDate())) {
            age--;
        }

        return age;
    }

    // Function to automatically archive records with age > 30 on page load
    window.onload = function () {
        var tableRows = document.querySelectorAll(".datatable tbody tr");

        for (var i = 0; i < tableRows.length; i++) {
            var birthdateCell = tableRows[i].querySelector("td:nth-child(8)");
            var age = calculateAge(birthdateCell.textContent);

            if (age > 30) {
                var idCell = tableRows[i].querySelector("td:last-child button:last-child").getAttribute("onclick");
                var id = idCell.match(/\d+/)[0]; // Extracting the ID from the onclick attribute
                archiveRecord(id);
            }
        }
    };

    // ... (Your existing functions remain unchanged)
</script>





            </div>
          </div>

        </div>
      </div>
    </section>
      
    <?php
include("./include/barangay-footer.php");
  ?>
 

<?php
// Close database connection
$conn->close();
?>