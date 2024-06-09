<!-- <php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sk_database";

// Verify student login
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
        exit();
}

$admin_id = $_SESSION['admin_id'];

?> -->







<!DOCTYPE html>
<html>
<head>
    <title>Admin Registration</title>
    <link rel="stylesheet" type="text/css" href="styles.css"> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <h1>Admin Registration Form</h1>

    <?php
    // require_once 'config.php';

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
  

    if (isset($_POST['register'])) {
        $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $municipal = mysqli_real_escape_string($conn, $_POST['municipal']);
        $barangay = mysqli_real_escape_string($conn, $_POST['barangay']);
        $password_hash = password_hash($password, PASSWORD_DEFAULT); 

        // Check if admin_id exists
        $check_sql = "SELECT * FROM admin WHERE username = '$admin_id'";
        $result = mysqli_query($conn, $check_sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<p class='error'>Admin Username already exists!</p>";
        } else {
            // Insert the new admin
            $sql = "INSERT INTO admin (username, password, municipal, barangay, role) VALUES ('$admin_id', '$password_hash', '$municipal', '$barangay', 'admin')";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='success'>Registration successful!</p>";
            } else {
                echo "<p class='error'>Error during registration: " . $conn->error . "</p>";
            }
        }
    }
    ?>

    <form method="post">

    <div class="input-group has-validation">
                          <span class="input-group-text" id="inputGroupPrepend">Brgy.</span>
                          <!-- <input type="text" placeholder="street" name="street" class="form-control" id="yourUsername" required /> -->
                          <select id="municipalities" name="municipal" class="form-control" required>
                            <!-- <option value="">Select Municipality</option> -->
                          </select>
                          <select id="barangays" name="barangay" class="form-control" required>
                            <!-- <option value="">Select Barangay</option> -->
                          </select>
                          <!-- <input type="text" placeholder="municipal" name="municipal" class="form-control" id="yourUsername" required /> -->
                          <input type="hidden" placeholder="district" name="province" class="form-control" id="yourUsername" value="Laguna" required />
                          <div class="invalid-feedback">
                            Please, enter your Barangay.
                          </div>
                        </div>


        <label for="admin_id">Admin Username:</label><br>
        <input type="text" id="admin_id" name="admin_id" required><br><br>

        <label for="password">Password:</label><br> 
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" name="register" value="Register">
        <a href='admin_login.php'>Login</a>


    </form>

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