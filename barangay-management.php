 <?php
  session_start(); // Start the session to access session variables



  // Database connection (replace with your credentials)
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "sk_database";

  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Check if logged in
  if (!isset($_SESSION['session_id'])) {
    header("Location: barangay-login.php");
    exit();
  }

  // Check if email is set in session
  if (isset($_SESSION['session_id'])) {
    // Database connection


    $email = $_SESSION['session_id'];


    $sql12 = "SELECT * FROM barangay_purok WHERE session_id = '$email'";
    $result12 = $conn->query($sql12);

    if ($result12->num_rows == 0) {
      // No records in the 'purok' table
      header("Location: barangay-purok.php?error=Need to Add Purok first to add Resident.");
      exit();
    }
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
    header("Location: home.php");
    exit();
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

    $check_sql1 = "SELECT * FROM youth_barangay WHERE contact_no = '$contact_no'";
    $check_result1 = $conn->query($check_sql1);



    if ($check_result->num_rows > 0) {
      // Record with the same first name and last name already exists
      echo "Error: A record with the same first name and last name already exists.";
      header("Location: barangay-management.php?error=A record with the same first name and last name already exists.");
    } 
    elseif ($check_result1->num_rows > 0) {
      // Record with the same first name and last name already exists
      echo "Error: A record with the same first name and last name already exists.";
      header("Location: barangay-management.php?error=Contact Number Existing, Please use other Contact No.");
    } 
    
    else {
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
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }
  }


  // Close database connection
  $conn->close();
  ?>


 <!DOCTYPE html>
 <html lang="en">

 <head>
   <meta charset="utf-8">
   <meta content="width=device-width, initial-scale=1.0" name="viewport">

   <title>Barangay Management</title>
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
   <style>
     input[type="text"] {
       text-transform: capitalize;
     }
     .success-message{
      margin-bottom: 0px;
      display: flex;
      justify-content: center;
      margin: 0;

     }

     .success-message p{
      margin-bottom: 0px;
      margin: 0;
     }
     
   </style>

 </head>

 <body>

   <!-- ======= Header ======= -->
   <?php
include("./include/barangay-header.php");
  ?>
 
   <!-- ======= Sidebar ======= -->

   <?php
    include("./include/barangay-sidebar.php");
    ?>




   <main id="main" class="main">

     <div class="pagetitle">
       <h1>Youth Resident</h1>
       <form method="post" enctype="multipart/form-data" action="import_excel.php">
         <input type="file" name="excel_file">
         <button type="submit" name="import_excel">Import Excel</button>
       </form>

       <?php
        if (isset($_SESSION['import_success']) && $_SESSION['import_success'] === true) {
          echo '<p>Data imported successfully.</p>';
          unset($_SESSION['import_success']); // Clear the session variable
        }
        ?>
       <nav>
         <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="index.html">Home</a></li>
           <!-- <li class="breadcrumb-item">Forms</li> -->
           <li class="breadcrumb-item active">Add Youth Details</li>
         </ol>
       </nav>
     </div><!-- End Page Title -->

     <section class="section">
       <form class="row g-3 needs-validation" method="POST" action="" enctype="multipart/form-data">
         <div class="row">
           <div class="col-lg-7">

             <div class="card">
               <div class="card-body">
                 <div id="generalSection">
                   <h5 class="card-title">PROFILE</h5>

                   <?php if (isset($_GET['error'])) : ?>
                     <div class="error-message">
                       <p><?php echo $_GET['error']; ?></p>
                     </div>
                   <?php elseif (isset($_GET['success'])) : ?>
                     <div class="success-message">
                       <p><?php echo $_GET['success']; ?></p>
                     </div>
                   <?php endif; ?>

                   <!-- General Form Elements -->
                   <div class="row mb-3">
                     <label name="barangay_name">Barangay <?php echo $street . ', ' . $municipal . ', ' . $province; ?> </label>
                     <div class="col-sm-12">
                       <input type="hidden" name="barangay_name" class="form-control" value="<?php echo $street . ', ' . $municipal . ', ' . $province; ?>">

                       <input type="hidden" name="street" class="form-control" value="<?php echo $street; ?>">
                       <input type="hidden" name="municipal" class="form-control" value="<?php echo $municipal; ?>">
                       <input type="hidden" name="province" class="form-control" value="<?php echo $province; ?>">

                     </div>
                   </div>



                   <div class="row mb-3">
                     <label for="text" class="col-sm-5 col-form-label">Last Name</label>
                     <div class="col-sm-12">
                       <input type="text" name="last_name" class="form-control" required>
                       <div class="invalid-feedback">
                         Please enter Last Name!
                       </div>
                     </div>
                   </div>
                   <div class="row mb-3">
                     <label for="inputText" class="col-sm-5 col-form-label">First Name</label>
                     <div class="col-sm-12">
                       <input type="text" name="first_name" class="form-control" required>
                       <div class="invalid-feedback">
                         Please enter First Name!
                       </div>
                     </div>
                   </div>

                   <div class="row mb-3">
                     <label for="inputText" class="col-sm-5 col-form-label">Middle Name</label>
                     <div class="col-sm-12">
                       <input type="text" name="middle_name" class="form-control">
                       <div class="invalid-feedback">
                         Please enter Middle Name!
                       </div>
                     </div>
                   </div>
                   <!-- End Gender Selection -->


                   <!-- Barangay Purok Selection -->
                   <div class="row mb-3">
                     <label class="col-sm-5 col-form-label">Purok</label>
                     <div class="col-sm-12">
                       <select class="form-select" aria-label="Default select example" name="barangay_purok_no" required>
                         <!-- <option disabled selected value="">Choose...</option> -->

                         <!-- Show the other Barangay Purok Option from database -->
                         <?php
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
                          // Get the session ID
                          $session_id = $_SESSION['email'];

                          // Fetch Barangay Purok options from database
                          $sql = "SELECT barangay_purok1 FROM barangay_purok WHERE session_id = '$session_id'";
                          $result = $conn->query($sql);

                          // Check if there are any options
                          if ($result->num_rows > 0) {
                            echo '<option disabled selected value="">Choose...</option>';

                            // Output data of each row
                            while ($row = $result->fetch_assoc()) {
                              echo '<option value="' . $row["barangay_purok1"] . '">' . $row["barangay_purok1"] . '</option>';
                            }
                          } else {
                            echo '<option disabled selected value="">No Purok available</option>';
                          }

                          // Close database connection
                          $conn->close();
                          ?>


                       </select>
                       <div class="invalid-feedback">
                         Please Select Purok! No Purok Available? Add Purok
                       </div>
                     </div>
                   </div>
                   <!-- End Barangay Purok Selection -->

                   <!-- Gender Selection -->
                   <div class="row mb-3">
                     <label class="col-sm-5 col-form-label">Gender</label>
                     <div class="col-sm-12">
                       <select class="form-select" aria-label="Default select example" name="gender">
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
                     <label for="inputText" class="col-sm-5 col-form-label">Contact No.</label>
                     <div class="col-sm-12">
                       <!-- <input type="number" name="contact_no" class="form-control" required> -->

                       <input type="text" id="contact_number" class="form-control" name="contact_no" onkeyup="limitContactNumber(this)" maxlength="11" placeholder="No." required />
                       <script>
                         function limitContactNumber(inputField) {
                           // Remove any existing non-digit characters
                           inputField.value = inputField.value.replace(/\D/g, "");

                           // Limit to 11 digits
                           if (inputField.value.length > 11) {
                             inputField.value = inputField.value.slice(0, 11);
                           }
                         }
                       </script>


                       <div class="invalid-feedback">
                         Please enter Contact No.!
                       </div>
                     </div>
                   </div>

                   <div class="row mb-3">
                     <label for="inputText" class="col-sm-5 col-form-label">Religion</label>
                     <div class="col-sm-12">
                       <input type="text" name="religion" class="form-control" required>
                       <div class="invalid-feedback">
                         Please enter Religion!
                       </div>
                     </div>
                   </div>

                   <div class="row mb-3">
                     <label for="inputText" class="col-sm-5 col-form-label">Birth Date</label>
                     <div class="col-sm-12">
                       <input type="date" name="birthdate" id="birthdate" class="form-control" onchange="calculateAge()" required max="<?php echo date('Y-m-d'); ?>">
                       <div class="invalid-feedback">
                         Please enter a valid Birthdate!
                       </div>
                     </div>
                   </div>

                   <div class="row mb-3">
                     <label for="inputText" class="col-sm-5 col-form-label">Age</label>
                     <div class="col-sm-12">
                       <input type="text" name="age" id="age" class="form-control" readonly>
                     </div>
                   </div>

                   <div class="row mb-2 justify-content-center">
                     <div class="col-sm-5">
                       <button type="button" class="btn btn-primary btn-block" id="nextButton" onclick="nextSection('additionalSection')">Next</button>
                     </div>
                   </div>

                   <script>
                     function calculateAge() {
                       // Get the birthdate value
                       var birthdateInput = document.getElementById('birthdate');
                       var birthdate = new Date(birthdateInput.value);

                       // Get the current date
                       var currentDate = new Date();

                       // Check if the birthdate is empty
                       if (birthdateInput.value === '') {
                         birthdateInput.setCustomValidity('Please enter a valid Birthdate.');
                         document.getElementById('nextButton').disabled = true; // Disable Next button
                         return;
                       } else {
                         birthdateInput.setCustomValidity('');
                       }

                       // Check if the selected date is in the future
                       if (birthdate > currentDate) {
                         birthdateInput.setCustomValidity('Please select a valid Birthdate.');
                         document.getElementById('nextButton').disabled = true; // Disable Next button
                         return;
                       } else {
                         birthdateInput.setCustomValidity('');
                       }

                       // Calculate the age
                       var age = currentDate.getFullYear() - birthdate.getFullYear();

                       // Check if the birthday hasn't occurred yet this year
                       if (currentDate.getMonth() < birthdate.getMonth() || (currentDate.getMonth() === birthdate.getMonth() && currentDate.getDate() < birthdate.getDate())) {
                         age--;
                       }

                       // Update the age textfield
                       document.getElementById('age').value = age;

                       // Disable Next button if age is below 14
                       document.getElementById('nextButton').disabled = age < 15;
                     }

                     function nextSection(nextSectionId) {
                       document.getElementById('generalSection').style.display = 'none';
                       document.getElementById(nextSectionId).style.display = 'block';
                     }

                     function previousSection(prevSectionId) {
                       document.getElementById('additionalSection').style.display = 'none';
                       document.getElementById(prevSectionId).style.display = 'block';
                     }

                     // Call calculateAge() initially and on date input change
                     document.addEventListener('DOMContentLoaded', function() {
                       calculateAge();
                       document.getElementById('birthdate').addEventListener('input', calculateAge);
                     });
                   </script>









                 </div>
               </div>
             </div>

           </div>
           <div id="additionalSection" style="display: none;">

             <div class="col-lg-5">

               <div class="card">
                 <div class="card-body">
                   <h5 class="card-title">DEMOGRAPHIC CHARACTERISTICS</h5>


                   <div class="row mb-3">
                     <label class="col-sm-5 col-form-label">Civil Status</label>
                     <div class="col-sm-12">
                       <select class="form-select" aria-label="Civil status" name="marital_status" required>
                         <option disabled selected value="">Choose...</option>
                         <option value="Single">Single</option>
                         <option value="Married">Married</option>
                         <option value="Divorced">Divorced</option>
                         <option value="Widowed">Widowed</option>
                         <option value="Separated">Separated</option>
                         <option value="Domestic Partnership/Civil Union">Domestic Partnership/Civil Union</option>
                         <option value="Prefer not to say">Prefer not to say</option>
                       </select>
                       <div class="invalid-feedback">
                         Please Select Civil Status!
                       </div>
                     </div>
                   </div>


                   <div class="row mb-3">
                     <label class="col-sm-5 col-form-label">Youth Age Group</label>
                     <div class="col-sm-12">
                       <select class="form-select" aria-label="Youth Age Group" name="yag" required>
                         <option disabled selected value="">Choose...</option>
                         <option value="child_youth">Child Youth (15/17 yrs old)</option>
                         <option value="core_youth">Core Youth (18-24 yrs old)</option>
                         <option value="young_adult">Young Adult (25-30 yrs old)</option>
                       </select>
                       <div class="invalid-feedback">
                         Please Select Civil Status!
                       </div>
                     </div>
                   </div>

                   <div class="row mb-3">
                     <label class="col-sm-5 col-form-label">Educational Background</label>
                     <div class="col-sm-12">
                       <select class="form-select" aria-label="Educational Background" name="educ_background" required>
                         <option disabled selected value="">Choose...</option>
                         <option value="elementary_level">Elementary Level</option>
                         <option value="elementary_graduate">Elementary Graduate</option>
                         <option value="high_school_level">High School Level</option>
                         <option value="high_school_graduate">High School Graduate</option>
                         <option value="vocational_graduate">Vocational Graduate</option>
                         <option value="college_level">College Level</option>
                         <option value="college_graduate">College Graduate</option>
                         <option value="masters_level">Masters Level</option>
                         <option value="masters_graduate">Masters Graduate</option>
                         <option value="doctorate_level">Doctorate Level</option>
                         <option value="doctorate_graduate">Doctorate Graduate</option>
                       </select>
                       <div class="invalid-feedback">
                         Please Select Educational Background!
                       </div>
                     </div>
                   </div>

                   <!-- Advanced Form Elements -->
                   <div class="col-12">

                     <label for="yourUsername" class="form-label">Are you enrolled in any school?</label>
                     <div class="row mb-3">
                       <!-- <label class="col-sm-5 col-form-label">Are you enrolled in any school?</label> -->
                       <div class="col-sm-12">
                         <select class="form-select" name="osy" aria-label="Default select example" required>
                           <option disabled selected value="">Choose...</option>
                           <option value="Yes">Yes</option>
                           <option value="No">No</option>
                         </select>
                         <div class="invalid-feedback">
                           Please Answer the Question!
                         </div>
                       </div>
                     </div>
                   </div>

                   <div class="col-12">
                     <label for="yourUsername" class="form-label">Youth Classification</label>
                     <div class="row mb-3">
                       <div class="col-sm-12">
                         <select class="form-select" aria-label="Default select example" name="yc" required>
                           <option disabled selected value="">Choose...</option>
                           <option value="in_school_youth">In School Youth</option>
                           <option value="out_of_school_youth">Out of School Youth</option>
                           <option value="working_youth">Working Youth</option>
                           <option value="youth_with_specific_need">Youth w/ Specific need</option>
                           <option value="person_with_disability">Person w/ Disability</option>
                           <option value="children_in_conflict_with_law">Children in Conflict w/ Law</option>
                           <option value="indigenous_people">Indigenous People</option>
                         </select>
                         <div class="invalid-feedback">
                           Please Select Youth Classification!
                         </div>
                       </div>
                     </div>
                   </div>






                   <div class="col-12">
                     <label for="yourUsername" class="form-label">Work Status</label>
                     <div class="row mb-3">
                       <div class="col-sm-12">
                         <select class="form-select" aria-label="Default select example" name="ws" required>
                           <option disabled selected value="">Choose...</option>
                           <option value="Employed">Employed</option>
                           <option value="Unemployed">Unemployed</option>
                           <option value="Self-Employed">Self-Employed</option>
                           <option value="Currently Looking for a Job">Currently Looking for a Job</option>
                           <option value="Not Interested Looking for a Job">Not Interested Looking for a Job</option>
                         </select>
                         <div class="invalid-feedback">
                           Please Select Work Status!
                         </div>
                       </div>
                     </div>
                   </div>


                   <div class="col-12">

                     <label for="yourUsername" class="form-label">Are you a Young Parents?</label>

                     <div class="row mb-3">
                       <div class="col-sm-12">
                         <select class="form-select" aria-label="Default select example" name="yp" required>
                           <option disabled selected value="">Choose...</option>
                           <option value="Yes">Yes</option>
                           <option value="No">No</option>
                         </select>
                         <div class="invalid-feedback">
                           Please Answer the Question!
                         </div>
                       </div>
                     </div>

                   </div>

                   <div class="col-12">


                     <label for="yourUsername" class="form-label">Are you a Person with Disability (PWD)?</label>
                     <div class="row mb-3">
                       <div class="col-sm-12">
                         <select class="form-select" aria-label="Default select example" name="pwd" required>
                           <option disabled selected value="">Choose...</option>
                           <option value="Yes">Yes</option>
                           <option value="No">No</option>
                         </select>
                         <div class="invalid-feedback">
                           Please Answer the Question!
                         </div>
                       </div>
                     </div>
                   </div>

                 </div>
               </div>

               <div class="card">
                 <div class="card-body" style="margin-top: 20px;">


                   <!-- Registered Voters(?) Selection -->
                   <div class="row mb-3">
                     <label class="col-sm-5 col-form-label">Are you a registered Voter?</label>
                     <div class="col-sm-12">
                       <select class="form-select" aria-label="Default select example" name="voters" required>
                         <option disabled selected value="">Choose Yes or No...</option>

                         <option value="Yes">Yes</option>
                         <option value="No">No</option>
                         =
                       </select>
                       <div class="invalid-feedback">
                         Please Select Yes or no choices!
                       </div>
                     </div>
                   </div>

                   <div class="row mb-3">
                     <label class="col-sm-5 col-form-label">Did you vote last Election?</label>
                     <div class="col-sm-12">
                       <select class="form-select" aria-label="Default select example" name="didvote" required>
                         <option disabled selected value="">Choose Yes or No...</option>

                         <option value="Yes">Yes</option>
                         <option value="No">No</option>
                         =
                       </select>
                       <div class="invalid-feedback">
                         Please Select Yes or no choices!
                       </div>
                     </div>
                   </div>



                   <div class="col-12">
                     <label for="yourUsername" class="form-label">Are you a registered SK Voter?</label>
                     <div class="row mb-3">
                       <div class="col-sm-12">
                         <select class="form-select" aria-label="Default select example" name="skv" required>
                           <option disabled selected value="">Choose...</option>
                           <option value="Yes">Yes</option>
                           <option value="No">No</option>
                         </select>
                         <div class="invalid-feedback">
                           Please Answer the Question!
                         </div>
                       </div>
                     </div>
                   </div>

                   <div class="col-12">
                     <label for="yourUsername" class="form-label">Did you vote last SK Election?</label>
                     <div class="row mb-3">
                       <div class="col-sm-12">
                         <select class="form-select" aria-label="Default select example" name="last_sk_vote" required>
                           <option disabled selected value="">Choose...</option>
                           <option value="Yes">Yes</option>
                           <option value="No">No</option>
                         </select>
                         <div class="invalid-feedback">
                           Please Answer the Question!
                         </div>
                       </div>
                     </div>
                   </div>


                   <div class="col-12">
                     <label for="yourUsername" class="form-label">Have you already attended a Katipunan ng Kabataan (KK) Assembly?</label>
                     <div class="row mb-3">
                       <div class="col-sm-12">
                         <select class="form-select" aria-label="Default select example" name="kk_assembly_attendance" required>
                           <option disabled selected value="">Choose...</option>
                           <option value="Yes">Yes</option>
                           <option value="No">No</option>
                         </select>
                         <div class="invalid-feedback">
                           Please Answer the Question!
                         </div>
                       </div>
                     </div>
                   </div>
                   <div class="col-12" id="noReason" style="display: none;">
                     <label for="yourUsername" class="form-label">If no, why?</label>
                     <div class="row mb-3">
                       <div class="col-sm-12">
                         <div class="form-check">
                           <input class="form-check-input" type="radio" name="kk_assembly_no_reason" id="noAssembly" value="No KK Assembly">
                           <label class="form-check-label" for="noAssembly">
                             There was no KK Assembly
                           </label>
                         </div>
                         <div class="form-check">
                           <input class="form-check-input" type="radio" name="kk_assembly_no_reason" id="notInterested" value="Not Interested">
                           <label class="form-check-label" for="notInterested">
                             Not Interested
                           </label>
                         </div>
                       </div>
                     </div>
                   </div>
                   <div class="col-12" id="attendanceFrequency" style="display: none;">
                     <label for="attendanceFrequency" class="form-label">If Yes, how many times?</label>
                     <div class="row mb-3">
                       <div class="col-sm-12">
                         <select class="form-select" aria-label="Default select example" name="kk_assembly_yes">
                           <option disabled selected value="">Choose...</option>
                           <option value="1-2 times">1-2 times</option>
                           <option value="3-4 times">3-4 times</option>
                           <option value="5 and above">5 and above</option>
                         </select>
                         <div class="invalid-feedback">
                           Please Answer the Question!
                         </div>
                       </div>
                     </div>
                   </div>
                   <script>
                     // JavaScript to show the reason input field when "No" is selected and the frequency input field when "Yes" is selected
                     const attendanceSelect = document.querySelector('[name="kk_assembly_attendance"]');
                     const noReason = document.getElementById('noReason');
                     const attendanceFrequency = document.getElementById('attendanceFrequency');

                     attendanceSelect.addEventListener('change', function() {
                       if (this.value === 'No') {
                         noReason.style.display = 'block';
                         attendanceFrequency.style.display = 'none';
                       } else if (this.value === 'Yes') {
                         noReason.style.display = 'none';
                         attendanceFrequency.style.display = 'block';
                       } else {
                         noReason.style.display = 'none';
                         attendanceFrequency.style.display = 'none';
                       }
                     });
                   </script>

                   <div class="col-12">
                     <label for="suggestions" class="form-label">Suggestions for future Sangguniang Kabataan Activities/Programs</label>
                     <div class="row mb-3">
                       <div class="col-sm-12">
                         <textarea class="form-control" rows="5" name="sk_suggestions"></textarea>
                       </div>
                     </div>
                   </div>


                 </div>
                 <br>
                 <br>
                 <br>

                 <div class="row mb-2 justify-content-center">
                   <div class="col-sm-5">
                     <button type="button" class="btn btn-primary btn-block" onclick="previousSection('generalSection')">Previous</button>
                     <button type="submit" class="btn btn-primary btn-block">Save Record</button>
                   </div>
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
 
