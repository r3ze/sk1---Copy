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

  $email = $_SESSION['session_id'];


  $session_id = $_SESSION['session_id'];

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
} else {
  // If email is not set in session, redirect to login page
  header("Location: barangay-login.php");
  exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $barangay_name = $_POST['barangay_name'];
  $barangay_purok1 = $_POST['barangay_purok1'];
  $street = $_POST['street'];
  $municipal = $_POST['municipal'];

  // Check if the barangay_purok1 already exists
  $check_query = "SELECT * FROM barangay_purok WHERE barangay_purok1 = '$barangay_purok1' AND session_id ='$session_id' ";

  $check_result = $conn->query($check_query);
  if ($check_result->num_rows > 0) {
    echo "Error: Barangay Purok already exists.";
  } else {
    // Prepare SQL statement to insert data
    $sql1 = "INSERT INTO barangay_purok (barangay_name, street, municipal, barangay_purok1, session_id)
               VALUES ('$barangay_name','$street','$municipal', '$barangay_purok1', '$session_id')";

    // Execute SQL query
    if ($conn->query($sql1) === TRUE) {
      header("Location: " . $_SERVER['PHP_SELF']);
    } else {
      echo "Error: " . $sql1 . "<br>" . $conn->error;
      header("Location: " . $_SERVER['PHP_SELF']);
    }
  }
}

// Fetch records from the database where session_id matches
$sql = "SELECT * FROM barangay_purok WHERE session_id = '$email'";
$result1 = $conn->query($sql);

// Close database connection
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Barangay Purok</title>
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
  <link href="assets/css/success_error.css" rel="stylesheet">

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
include("./include/barangay-header.php");
  ?>
 <?php
include("./include/barangay-sidebar.php");
  ?>
 

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Manage Purok</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Purok</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">



      <div class="row">
        <div class="col-lg-7">
          <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate>
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">General Form </h5>
                <?php if (isset($_GET['error'])) : ?>
                  <div class="error-message">
                    <p><?php echo $_GET['error']; ?></p>
                  </div>
                  <?php elseif(isset($_GET['success'])):?>
                    <div class="success-message">
                    <p><?php echo $_GET['success']; ?></p>
                  </div>
                <?php endif; ?>
                <!-- General Form Elements -->
                <div class="row mb-3">
                  <!-- <label class="col-sm-2 col-form-label">Barangay</label> -->
                  <div class="col-sm-10">
                    <input type="hidden" name="barangay_name" class="form-control" value="<?php echo $street . ', ' . $municipal . ', ' . $province; ?>">

                    <input type="hidden" name="street" class="form-control" value="<?php echo $street;?>">
                    <input type="hidden" name="municipal" class="form-control" value="<?php echo $municipal;?>">
                    <br>
                    <label> Barangay <?php echo $street . ', ' . $municipal . ', ' . $province; ?>
                    </label>


                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Purok Name/No.</label>
                  <div class="col-sm-10">
                    <input type="text" name="barangay_purok1" class="form-control" required>
                    <div class="invalid-feedback">
                      Please enter Purok Name/No!
                    </div>
                  </div>
                </div>
                <div class="row mb-2 justify-content-center">
                  <div class="col-sm-8">
                    <button type="submit" class="btn btn-primary btn-block">Add Purok</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="col-lg-5">

          <div class="card">
            <div class="card-body">


              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Purok</th>
                    <th>Action</th>
                    <th hidden>session_id</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  // Check if there are any records fetched
                  if ($result1->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result1->fetch_assoc()) {
                      echo "<tr>";
                      echo "<td>" . $row["barangay_purok1"] . "</td>";
                      echo "<td hidden>" . $row["session_id"] . "</td>";
                      // echo "<td><button class=\"btn btn-danger btn-block\" onclick=\"deleteRecord('" . $row["session_id"] . "')\">Delete</button></td>";
                      echo "<td><button  class=\"btn btn-danger btn-block\" onclick=\"deleteRecord('" . $row["id"] . "')\">Delete</button> ";

                      echo "</tr>";
                    }
                  } else {
                    echo "<tr><td colspan='3'>No records found</td></tr>";
                  }
                  ?>
                </tbody>
              </table>

              <div id="editForm" style="display: none;">
                <!-- Edit Form will be displayed here -->
              </div>




              <script>
                function deleteRecord(id) {
                  // AJAX request to archive record
                  if (confirm("Are you sure you want to delete this record?")) {
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                      if (this.readyState == 4 && this.status == 200) {
                        // Refresh the page after archiving
                        window.location.reload();
                      }
                    };
                    xhttp.open("GET", "delete-purok.php?id=" + id, true);
                    xhttp.send();
                  }
                }
              </script>


              <script>
                function editRecord(id) {
                  // AJAX request to fetch record details
                  var xhttp = new XMLHttpRequest();
                  xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                      document.getElementById("editForm").innerHTML = this.responseText;
                      document.getElementById("editForm").style.display = "block";
                    }
                  };
                  xhttp.open("GET", "edit-youth.php?id=" + id, true);
                  xhttp.send();
                }

                function archiveRecord(id) {
                  // AJAX request to archive record
                  var xhttp = new XMLHttpRequest();
                  xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                      // Refresh the page after archiving
                      window.location.reload();
                    }
                  };
                  xhttp.open("GET", "archive-youth.php?id=" + id, true);
                  xhttp.send();
                }
              </script>


            </div>
          </div>

        </div>



      </div>
    </section>

  </main><!-- End #main -->
<?php
include("./include/barangay-footer.php");
  ?>
 