
<?php
session_start(); // Start the session to access session variables

// Check if email is set in session
if (isset($_SESSION['username'])) {
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

  

  $email = $_SESSION['username'];

   // Fetch additional information based on the email
   $sql = "SELECT street, municipal, province, first_name, middle_name, last_name FROM barangay WHERE email = '$email'";
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


  $sql = "SELECT * FROM barangay";
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

  <title>Request Account</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

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

<?php
    include("./include/municipal-header.php")
    ?>


    <?php
    include("./include/municipal-sidebar.php")
    ?>




  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Barangay Request Account List</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="municipal-dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Manage Account</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Barangay Account List</h5>
             

              <table class="table datatable">
    <thead>
        <tr>
            <th>Barangay</th>
            <th>Full Name</th>
            <th>Gender</th>
            <th>Contact No.</th>
            <th>Valid ID</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Check if there are any records fetched
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["street"] . " " . $row["municipal"] . "," . $row["province"] . "</td>";
                echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
                echo "<td>" . $row["gender"] . "</td>";
                echo "<td>" . $row["contact_no"] . "</td>";
                // echo "<td><img src='uploads/" . $row["valid_id"] . "' alt='Valid ID'></td>";
                echo '<td><img src="uploads/' . $row["valid_id"] . '" alt="Valid ID" class="valid-id-img" style="max-width: 100px; max-height: 100px;"></td>';

                echo "<td>" . $row["status"] . "</td>";
                echo "<td>";
                if ($row["status"] == "Pending") {
                    echo "<select id='select_" . $row["id"] . "' onchange=\"updateStatus(this.value, " . $row["id"] . ")\">
                            <option value=''>Select Action</option>
                            <option value='Approve'>Approve</option>
                            <option value='Decline'>Decline</option>
                          </select>";
                } else {
                    // Just display the status if it's not Pending
                    // echo $row["status"]; 
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>

<script>
    // JavaScript to scale up the image on hover
    const validIdImages = document.querySelectorAll('.valid-id-img');
    validIdImages.forEach(image => {
        image.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(5)';
            this.style.transition = 'transform 0.5s';
            this.style.zIndex = '99999';
        });
        image.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
            this.style.transition = 'transform 0.5s';
        });
    });
</script>

<script>
    function updateStatus(status, id) {
        // Send AJAX request to update status in the database
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Reload the page or update specific elements as needed
                location.reload(); // Example: Reload the page after updating status
            }
        };
        xhttp.open("POST", "update_status.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("status=" + status + "&id=" + id);
    }
</script>

            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->

  
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>LSPU BSIT- 3A_WAM</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="#">3A WAM</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>