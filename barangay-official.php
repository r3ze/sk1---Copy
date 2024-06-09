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

    
    $session_id = $_SESSION['email'];

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
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $contact_no = $_POST['contact_no'];
    $gender = $_POST['gender'];
    $committee = $_POST['committee'];
    $brgy_position = $_POST['barangay_position'];
    $recent_act = $last_name . ', ' . $first_name . ' added in Brgy.Official as ' . $brgy_position;

    // File upload handling
$target_dir = "uploads/"; // Directory where uploaded files will be saved
$target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
if ($check !== false) {
    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["profile_picture"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now extract the file name
            $profile_picture = basename($_FILES["profile_picture"]["name"]); // Store only the file name in a variable

            // Prepare SQL statement to insert data
            $sql1 = "INSERT INTO barangay_official (barangay_name, last_name, first_name, middle_name, contact_no, gender, committee, barangay_position, session_id, profile_picture)
                    VALUES ('$barangay_name', '$last_name', '$first_name', '$middle_name', '$contact_no', '$gender', '$committee', '$brgy_position', '$session_id', '$profile_picture')";

            $sql = "INSERT INTO recent_activity (barangay_name, last_name, first_name, middle_name, contact_no, gender, comittee, barangay_position, session_id, recent_act)
                    VALUES ('$barangay_name', '$last_name', '$first_name', '$middle_name', '$contact_no', '$gender', '$committee', '$brgy_position', '$session_id', '$recent_act')";

            // Execute SQL query
            if ($conn->query($sql1) === TRUE) {
                echo "Record inserted successfully";
                header("Location: barangay-official.php");
            } else {
                echo "Error: " . $sql1 . "<br>" . $conn->error;
            }

            // Execute SQL query
            if ($conn->query($sql) === TRUE) {
                echo "Record inserted successfully";
                header("Location: barangay-official.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
} else {
    echo "File is not an image.";
    $uploadOk = 0;
}

}

// Fetch records from the database where session_id matches
$sql = "SELECT * FROM barangay_official WHERE session_id = '$email'";
$result1 = $conn->query($sql);

// Close database connection
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Barangay Official</title>
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

<?php
include("./include/barangay-header.php");
  ?>
 <?php
include("./include/barangay-sidebar.php");
  ?>
 


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Barangay Officials</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <!-- <li class="breadcrumb-item">Forms</li> -->
          <li class="breadcrumb-item active">Add Barangay Officials</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data"
        novalidate>
        <div class="row">
          <div class="col-lg-15">

            <div class="card">
              <div class="card-body">
                <h5 class="card-title">General Form </h5>

                <!-- General Form Elements -->
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Barangay</label>
                  <div class="col-sm-10">
                    <input type="text" name="barangay_name" class="form-control"
                      value="<?php echo $street . ', ' . $municipal . ', ' . $province; ?>" >
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Last Name</label>
                  <div class="col-sm-10">
                    <input type="text" name="last_name" class="form-control" required>
                    <div class="invalid-feedback">
                      Please enter Last Name!
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">First Name</label>
                  <div class="col-sm-10">
                    <input type="text" name="first_name" class="form-control" required>
                    <div class="invalid-feedback">
                      Please enter First Name!
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Middle Name</label>
                  <div class="col-sm-10">
                    <input type="text" name="middle_name" class="form-control" required>
                    <div class="invalid-feedback">
                      Please enter Middle Name!
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Contact No.</label>
                  <div class="col-sm-10">
                    <input type="number" name="contact_no" class="form-control" required>
                    <div class="invalid-feedback">
                      Please enter Contact No.!
                    </div>
                  </div>
                </div>
                 <!-- Gender Selection -->
                 <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Gender</label>
                  <div class="col-sm-10">
                    <select class="form-select" aria-label="Default select example" name="gender" required>
                      <option disabled selected value="">Choose...</option>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                      <option value="Prefer not to say">Prefer not to say</option>
                    </select>
                    <div class="invalid-feedback">
                      Please Select Gender!
                    </div>
                  </div>
                </div>
                <!-- End Gender Selection -->



                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Committee</label>
                  <div class="col-sm-10">
                    <input type="text" placeholder="Peace & Order, Health, Finance, etc..." name="committee" class="form-control" required>
                    <div class="invalid-feedback">
                      Please enter Committee!
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Brgy. Position</label>
                  <div class="col-sm-10">
                    <input type="text" placeholder="Captain, Kagawad, Secretary, etc..." name="barangay_position" class="form-control" required>
                    <div class="invalid-feedback">
                      Please enter Brgy. Position!
                    </div>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Profile Picture</label>
                  <div class="col-sm-10">
                    <input type="file" name="profile_picture" class="form-control" required>
                  </div>
                </div>

                <div class="row mb-2 justify-content-center">
                  <div class="col-sm-5">
                    <button type="submit" class="btn btn-primary btn-block">Save Record</button>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div> 
      </form>
         
        
     
    </section>

  </main><!-- End #main -->
  <?php
include("./include/barangay-footer.php");
  ?>
 