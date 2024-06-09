<?php
// Start the session
// session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

  // Check if the street already exists
  $street = $_POST["street"];
  $municipal = $_POST["municipal"];
  $check_sql = "SELECT id FROM barangay WHERE street = ? AND municipal = ?";
  $check_stmt = $conn->prepare($check_sql);
  $check_stmt->bind_param("ss", $street, $municipal);
  $check_stmt->execute();
  $check_stmt->store_result();

  if ($check_stmt->num_rows > 0) {
    // echo "Street already exists. Please enter a unique street name.";
    header("Location: barangay-add.php?error=Barangay already Registered. Please select other Barangay Name.");

    exit; // Stop execution if street already exists
  }


    // Username Uniqueness Check 
    $username = $_POST["username"];
    $check_username_sql = "SELECT id FROM barangay WHERE username = ?";
    $check_username_stmt = $conn->prepare($check_username_sql);
    $check_username_stmt->bind_param("s", $username);
    $check_username_stmt->execute();
    $check_username_stmt->store_result();

    if ($check_username_stmt->num_rows > 0) {
        echo "Username already exists. Please enter a unique username.";
        header("Location: barangay-add.php?error=Username already exists. Please enter a unique username.");
        exit; // Stop execution if username already exists
    }

    // Username Uniqueness Check 
    $email = $_POST["email"];
    $check_username_sql1 = "SELECT id FROM barangay WHERE email = ?";
    $check_email_stmt = $conn->prepare($check_username_sql1);
    $check_email_stmt->bind_param("s", $email);
    $check_email_stmt->execute();
    $check_email_stmt->store_result();

    if ($check_email_stmt->num_rows > 0) {
        echo "Email Address already exists. Please enter a unique Email Address.";
        header("Location: barangay-add.php?error=Email already exists. Please enter a other Email Address.");
        exit; // Stop execution if username already exists
    }

  $contact_no = $_POST["contact_no"];
  $check_contact_sql1 = "SELECT id FROM barangay WHERE contact_no = ?";
  $check_contact_stmt = $conn->prepare($check_contact_sql1);
  $check_contact_stmt->bind_param("s", $contact_no);
  $check_contact_stmt->execute();
  $check_contact_stmt->store_result();

  if ($check_contact_stmt->num_rows > 0) {
      header("Location: barangay-add.php?error=Contact No already exists. Please enter a other Contact No.");
      exit; // Stop execution if username already exists
  }



  // Prepare SQL query
  $sql = "INSERT INTO barangay (street, municipal, province, first_name, middle_name, last_name, contact_no, gender, valid_id, email, password, session_id, status, registered_date, role, username) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', CURRENT_DATE, 'Barangay SK', ?)"; // Added registered_date

  // Prepare and bind parameters
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssssssssss", $street, $municipal, $province, $first_name, $middle_name, $last_name, $contact_no, $gender, $valid_id, $email, $password, $session_id, $username);

  // Set parameters
  $municipal = $_POST["municipal"];
  $province = $_POST["province"];
  $first_name = $_POST["first_name"];
  $middle_name = $_POST["middle_name"];
  $last_name = $_POST["last_name"];
  $gender = $_POST["gender"];

  $role = $_POST["role"];
  // $username = $_POST["username"];

  // Handling file upload for valid_id (Assuming it's stored as a file path)
  $valid_id = $_FILES['valid_id']['name'];
  $image_tmp_name = $_FILES['valid_id']['tmp_name'];
  $image_folder = 'uploads/' . $valid_id;

  // Move the uploaded file to the destination folder
  if (move_uploaded_file($image_tmp_name, $image_folder)) {
    // echo "File uploaded successfully";
  } else {
    echo "Error uploading file";
  }

  $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password before storing
  $session_id = $_POST["username"]; // Set session_id to username

  // Execute SQL
  if ($stmt->execute()) {
    // echo "New record inserted successfully";
    header("Location: barangay-add.php?success=Succesfully Account Created!");
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  // Close statements
  $stmt->close();
  $check_stmt->close();
  $check_username_stmt->close();
  $check_email_stmt->close();
  $check_contact_stmt->close();
  
  // Close connection
  $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />

  <title>Barangay Registration</title>
  <meta content="" name="description" />
  <meta content="" name="keywords" />

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon" />
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon" />

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet" />

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet" />
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet" />
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet" />
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet" />

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet" />
  <link href="assets/css/pop_up.css" rel="stylesheet" />
</head>

<style>

/* input[type="text"] {
  text-transform: capitalize;
} */

#yourUsername{

  text-transform: capitalize;
}
  </style>

<body>
  <main>
    <div class="container">
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="row justify-content-center">
              <div class="col-lg-7 col-md-6 d-flex flex-column align-items-center justify-content-center">
                <div class="d-flex justify-content-center py-4">
                  <a href="index.html" class="logo d-flex align-items-center w-auto">
                    <img src="assets/img/logo.png" alt="" />
                    <span class="d-none d-lg-block">Barangay Management</span>
                  </a>
                </div>
                <!-- End Logo -->
                <div class="card mb-3">
                  <div class="card-body">
                    <div class="pt-4 pb-2">
                      

                     <?php
                    include("./include/success-error.php");

                     ?>


                      <h5 class="card-title text-center pb-0 fs-4">
                        Create an Sk Account
                      </h5>
                      <p class="text-center small">
                        Enter your personal details
                      </p>
                    </div>

                    <form class="row g-3 needs-validation" method="post" action="" enctype="multipart/form-data" novalidate onsubmit="return validatePassword()">


                      <div class="col-12">
                        <label for="yourUsername" class="form-label">Barangay</label>
                        <div class="input-group has-validation">
                          <span class="input-group-text" id="inputGroupPrepend">Brgy.</span>
                          <!-- <input type="text" placeholder="street" name="street" class="form-control" id="yourUsername" required /> -->
                          <select id="municipalities" name="municipal" class="form-control" required>
                            <!-- <option value="">Select Municipality</option> -->
                          </select>
                          <select id="barangays" name="street" class="form-control" required>
                            <!-- <option value="">Select Barangay</option> -->
                          </select>
                          <!-- <input type="text" placeholder="municipal" name="municipal" class="form-control" id="yourUsername" required /> -->
                          <input type="hidden" placeholder="district" name="province" class="form-control" id="yourUsername" value="Laguna" required />
                          <div class="invalid-feedback">
                            Please, enter your Barangay.
                          </div>
                        </div>
                      </div>

                      <div class="col-12">
                        <label for="yourUsername" class="form-label">Full Name</label>
                        <div class="input-group has-validation">
                          <!-- <span class="input-group-text" id="inputGroupPrepend">Brgy.</span> -->
                          <input type="text" placeholder="First Name" name="first_name" class="form-control" id="yourUsername" required />
                          <input type="text" placeholder="M.I" name="middle_name" class="form-control" id="yourUsername" required />
                          <input type="text" placeholder="Last Name" name="last_name" class="form-control" id="yourUsername" required />
                          <div class="invalid-feedback">
                            Please, enter your Full Name.
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="yourEmail" class="form-label">Contact No.</label>

                        <!-- <input type="number" min="11" class="form-control" name="contact_no" placeholder="No."> -->
                        <input type="text" id="contact_number" class="form-control"  name="contact_no" onkeyup="limitContactNumber(this)" maxlength="11" placeholder="No." required />
                      </div>
                      <div class="col-md-6">
                        <label for="yourEmail" class="form-label">Gender</label>
                        <select id="inputState" name="gender" class="form-select">
                          <option>Choose Gender</option>
                          <option name="gender" value="Male">Male</option>
                          <option name="gender" value="Female">Female</option>
                          <option name="gender" value="Prefer not to say">Prefer not to say</option>
                        </select>
                      </div>

                      <div class="col-12">
                        <label for="inputNumber" class="col-sm-2 col-form-label">Valid ID</label>
                        <div class="col-sm-10">
                          <input class="form-control" type="file" name="valid_id" id="formFile" required />
                        </div>
                      </div>

                      <div class="col-12">
                        <label for="yourEmail" class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" id="yourEmail" required />
                        <div class="invalid-feedback">
                          Please enter a valid Email adddress!
                        </div>
                      </div>
                      <div class="col-12">
                        <label for="yourUsername" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required />
                        <div class="invalid-feedback">
                          Please enter a valid Username!
                        </div>
                      </div>

                      <div class="col-12">
                        <label for="yourPassword" class="form-label">Password</label>
                        <div class="input-group">
                          <input type="password" name="password" class="form-control" id="yourPassword" required />
                          <div class="invalid-feedback">
                            Please enter your password!
                          </div>
                        </div>
                      </div>
                      <div class="col-12">
                        <label for="yourconfirmPassword" class="form-label">Confirm Password</label>
                        <div class="input-group">
                          <input type="password" name="confirm_password" class="form-control" id="yourconfirmPassword" required />
                          <div class="invalid-feedback">
                            Please enter your password!
                          </div>
                        </div>
                      </div>
                      <div class="col-12">
                        <input type="checkbox" id="showPasswordCheckbox" />
                        <label for="showPasswordCheckbox">Show Password</label>
                      </div>

                      <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">
                          Register
                        </button>
                      </div>

                    </form>

                  </div>


                </div>


                <div class="credits">
                  <!-- <a href="barangay-login.php">back to login</a> -->
                  <a href="home.php">Back to Home</a>
                </div>
      </section>
    </div>
  </main>
  <!-- End #main -->

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

  <!-- Show password JS FILE -->

  <script>
    document
      .getElementById("showPasswordCheckbox")
      .addEventListener("change", function() {
        var passwordInput = document.getElementById("yourPassword");
        var passwordInput1 = document.getElementById("yourconfirmPassword");
        passwordInput.type = this.checked ? "text" : "password";
        passwordInput1.type = this.checked ? "text" : "password";
      });

    function validatePassword() {
      const password = document.getElementById('yourPassword').value;
      const confirmPassword = document.getElementById('yourconfirmPassword').value;
      const passwordFeedback = document.querySelector('#yourPassword + .invalid-feedback'); // Target error message for password
      const confirmFeedback = document.querySelector('#yourconfirmPassword + .invalid-feedback'); // Target error message for confirmation

      if (password !== confirmPassword) {
        confirmFeedback.textContent = 'Passwords do not match!'; // Set error message 
        confirmFeedback.style.display = 'block'; // Show error message
        return false; // Indicate validation failure
      } else {
        confirmFeedback.style.display = 'none'; // Hide error message if passwords match
        return true; // Indicate validation success
      }
    }

    function limitContactNumber(inputField) {
        // Remove any existing non-digit characters
        inputField.value = inputField.value.replace(/\D/g, "");

        // Limit to 11 digits
        if (inputField.value.length > 11) {
            inputField.value = inputField.value.slice(0, 11);
        }
    }

  </script>


  <script>
    $(document).ready(function() {
      // Load municipalities on page load
      loadMunicipalities();

      // When the municipality selection changes:
      $('#municipalities').on('change', function() {
        var municipality = $(this).val();
        if (municipality) {
          loadBarangays(municipality);
        } else {
          $('#barangays').empty().append('<option value="">Select Barangay</option>');
        }
      });
    });

    function loadMunicipalities() {
      $.ajax({
        url: 'get_municipalities.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          $('#municipalities').append($('<option>', {
            value: '',
            text: 'Select Municipality'
          }));
          $.each(data, function(index, name) {
            $('#municipalities').append($('<option>', {
              value: name,
              text: name
            }));
          });
        }
      });
    }

    function loadBarangays(municipality) {
      $.ajax({
        url: 'get_barangays.php',
        type: 'GET',
        dataType: 'json',
        data: {
          municipality: municipality
        },
        success: function(data) {
          $('#barangays').empty().append('<option value="">Select Barangay</option>');
          $.each(data, function(index, name) {
            $('#barangays').append($('<option>', {
              value: name,
              text: name
            }));
          });
        }
      });
    }
  </script>



</body>

</html>