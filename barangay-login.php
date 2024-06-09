<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Barangay Login</title>
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
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-6 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">Barangay Login</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">
                <?php

                // Enhanced error reporting (remove after debugging) 
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
                // Start the session (if you plan to use sessions)
                session_start();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                  $servername = "localhost";
                  $dbUsername = "root";
                  $dbPassword = "";
                  $dbName = "sk_database";

                  $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

                  if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                  }

                  // $username = $_POST['username'];
                  // $password = $_POST['password'];

                  $username = trim($_POST['username']);
                  $password = trim($_POST['password']);

                  // 1. Check if username exists and get the role
                  $usernameCheckSql = "SELECT role FROM barangay WHERE username = ? 
                  UNION 
                  SELECT role FROM admin WHERE username = ?
                  UNION
                  SELECT role FROM municipality WHERE username = ?";

                  $usernameCheckStmt = $conn->prepare($usernameCheckSql);
                  $usernameCheckStmt->bind_param("sss", $username, $username, $username);
                  $usernameCheckStmt->execute();
                  $usernameCheckStmt->store_result();

                  if ($usernameCheckStmt->num_rows == 0) {


                    echo "Invalid username.";
                    header("Location: barangay-login.php?error=Invalid username");
                    exit;
                  }
                  // Get and display the role
                  $usernameCheckStmt->bind_result($role);  // Bind the 'role' column to the $role variable
                  $usernameCheckStmt->fetch();             // Fetch the result 
                  echo "Role: " . $role;                   // Display the role
                  // $usernameCheckStmt->close();

                  // Get the role
                  $usernameCheckStmt->bind_result($role);
                  $usernameCheckStmt->fetch();
                  $usernameCheckStmt->close();

                  // 2. Determine the table and check credentials 
                  if ($role == 'Barangay SK') {
                    $loginSql = "SELECT username, password FROM barangay WHERE username = ?";
                  } elseif ($role == 'admin') {
                    $loginSql = "SELECT username, password FROM admin WHERE username = ?";
                  } elseif ($role == 'municipal') {
                    $loginSql = "SELECT username, password FROM municipality WHERE username = ?";
                  } else {
                    echo "Invalid role.";
                    exit;
                  }

                  // 3. Login check
                  $stmt = $conn->prepare($loginSql);
                  $stmt->bind_param("s", $username);
                  $stmt->execute();
                  $result = $stmt->get_result(); // Get results 

                  if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    // Use password_verify for secure comparison
                    if (password_verify($password, $row['password'])) {

                      // Redirect to appropriate dashboard
                      if ($role == 'Barangay SK') {
                        $_SESSION['username'] = $username;
                        $_SESSION['role'] = $role;
                        $_SESSION['session_id'] = $username;
                        $_SESSION['email'] = $username;
                        header("Location: barangay-dashboard.php");
                      } elseif ($role == 'admin') { // Assuming the other role is admin
                        $_SESSION['username'] = $username;
                        $_SESSION['email'] = $username;
                        $_SESSION['role'] = $role;

                        header("Location: municipal-dashboard.php");
                      } elseif ($role == 'municipal') { // Assuming the other role is municipal
                        $_SESSION['username'] = $username;
                        $_SESSION['email'] = $username;
                        $_SESSION['role'] = $role;

                        header("Location: municipal-dashboard1.php");
                      }
                      exit();
                    } else {
                      // echo "Incorrect username or password.";
                      header("Location: barangay-login.php?error=Incorrect username or password.");
                    }
                  } else {
                    echo "Incorrect username or password";
                  }

                  $stmt->close();
                  $conn->close();
                }
                ?>

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <?php
                    include("./include/success-error.php");
                    ?>
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Barangay Account</h5>
                    <p class="text-center small">Enter your username & password to login</p>
                  </div>

                  <form class="row g-3 needs-validation" method="post" action="" novalidate>

                    <div class="col-12">
                      <label for="yourUsername" class="form-label">Username</label>
                      <div class="input-group has-validation">
                        <input type="text" name="username" class="form-control" id="yourUsername" required>
                        <div class="invalid-feedback">Please enter your Barangay Username.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Please enter your Barangay password!</div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Login</button>

                    </div>
                  </form>
                </div>

                <?php
                // Close database connection
                // $conn->close();
                ?>

              </div>



              <div class="credits">
                <a href="barangay-add.php">Request Account?</a>
              </div>
             


            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

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