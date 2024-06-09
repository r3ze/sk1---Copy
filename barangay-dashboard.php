<?php
session_start();

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


  // Query to fetch totals for each category
  $sql_piechart = "SELECT 
SUM(CASE WHEN osy = 'No' THEN 1 ELSE 0 END) AS osy_total,
SUM(CASE WHEN ws = 'Yes' THEN 1 ELSE 0 END) AS ws_total,
SUM(CASE WHEN yp = 'Yes' THEN 1 ELSE 0 END) AS yp_total,
SUM(CASE WHEN pwd = 'Yes' THEN 1 ELSE 0 END) AS pwd_total
FROM youth_barangay WHERE session_id='$email' ";
  $result_piechart = $conn->query($sql_piechart);

  $osy_total = 0;
  $ws_total = 0;
  $yp_total = 0;
  $pwd_total = 0;

  if ($result->num_rows > 0) {
    // Fetch totals
    $row_piechart = $result_piechart->fetch_assoc();
    $osy_total = $row_piechart['osy_total'];
    $ws_total = $row_piechart['ws_total'];
    $yp_total = $row_piechart['yp_total'];
    $pwd_total = $row_piechart['pwd_total'];
  }


  $email2 = $_SESSION['email'];

  $sql_status = "SELECT status FROM barangay WHERE session_id='$email2'";
  $result_status = $conn->query($sql_status);

  // Check if query executed successfully
  if ($result_status) {
    $row_status = $result_status->fetch_assoc();
    $status = $row_status["status"];

    // Determine if the link should be disabled based on the status
    $isDisabled = ($status === "Pending");

    // Define CSS class for disabled link
    $linkClass = ($isDisabled) ? "disabled-link" : "";

    // Show the popup div if status is Pending
    $popupDisplay = ($status === "Pending") ? "block" : "none";
  } else {
    echo "Error fetching status: " . $conn->error;
  }



  $sql2 = "SELECT * FROM barangay_official WHERE session_id = '$email2'";
  $result2 = $conn->query($sql2);


  // Close database connection
  $conn->close();
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

  <title>Barangay Dashboard</title>
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


</head>

<body>
  <style>
    .popup {
      display: block;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 99999999;
      font-size: 30px;





    }

    .popup-content {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background-color: white;
      padding: 20px;
      border-radius: 5px;
      text-align: center;
    }
  </style>

  <div id="popup" class="popup" style="display: <?php echo $popupDisplay; ?>">
    <div class="popup-content">
      <div class="container">

        <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
          <h1>hmmm...</h1>
          <h2> Wait for the Admin to Accept your request. Thank You!</h2>
          <!-- <img src="assets/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found"> -->
          <div class="credits">
            <a href="logout.php">back to login</a>

          </div>
        </section>

      </div>
    </div>
  </div>

  <script>

    var status = "<?php echo $status; ?>";
    if (status === "Pending") {
      document.getElementById("popup").style.display = "block";
    }
  </script>

  <!-- ======= Header ======= -->
 <?php
 include("./include/barangay-header.php");
 ?>
  <!-- End Header -->

  <?php
 include("./include/barangay-sidebar.php");
 ?>




  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Barangay Management</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Brgy.
            <?php echo $street . ', ' . $municipal . ', ' . $province; ?>
          </li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-5">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-xxl-6 col-md-12">
              <div class="card info-card sales-card">

                <!-- <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>

                  </ul>
                </div> -->

                <div class="card-body">
                  <h5 class="card-title">Residents </h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="ri-account-circle-fill"></i>
                    </div>
                    <div class="ps-3">
                      <?php
                      // Check if email is set in session
                      if (isset($_SESSION['email'])) {
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

                        // Query to count the total "Yes" values in the voters column
                        $sql = "SELECT COUNT(*) AS total_yes_voters FROM youth_barangay WHERE session_id = '$session_id'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $total_yes_voters = $row['total_yes_voters'];

                          // Display the total "Yes" voters
                          echo '<div class="ps-3">';
                          echo '<h6>' . $total_yes_voters . '</h6>';
                          echo '</div>';
                        } else {
                          echo '<div class="ps-3">';
                          echo '<h6>No "Yes" voters found</h6>';
                          echo '</div>';
                        }

                        // Close database connection
                        $conn->close();
                      } else {
                        // If email is not set in session, display an error message
                        echo '<div class="ps-3">';
                        echo '<h6>Error: Session ID not found</h6>';
                        echo '</div>';
                      }
                      ?>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Registered Voters Card -->
            <div class="col-xxl-6 col-md-12">
              <div class="card info-card revenue-card">



                <div class="card-body">
                  <h5 class="card-title">Registered Voters</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <?php
                    // Check if email is set in session
                    if (isset($_SESSION['email'])) {
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

                      // Query to count the total "Yes" values in the voters column
                      $sql = "SELECT COUNT(*) AS total_yes_voters FROM youth_barangay WHERE voters = 'Yes' AND session_id = '$session_id'";
                      $result = $conn->query($sql);

                      if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $total_yes_voters = $row['total_yes_voters'];

                        // Display the total "Yes" voters
                        echo '<div class="ps-3">';
                        echo '<h6>' . $total_yes_voters . '</h6>';
                        echo '</div>';
                      } else {
                        echo '<div class="ps-3">';
                        echo '<h6>No "Yes" voters found</h6>';
                        echo '</div>';
                      }

                      // Close database connection
                      $conn->close();
                    } else {
                      // If email is not set in session, display an error message
                      echo '<div class="ps-3">';
                      echo '<h6>Error: Session ID not found</h6>';
                      echo '</div>';
                    }
                    ?>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-xxl-4 col-xl-4">

              <div class="card info-card customers-card">

                <div class="card-body">
                  <h5 class="card-title">Male</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bx bx-male"></i>
                    </div>
                    <div class="ps-3">
                      <?php
                      // Check if email is set in session
                      if (isset($_SESSION['email'])) {
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

                        // Query to count the total "Yes" values in the voters column
                        $sql = "SELECT COUNT(*) AS total_yes_voters FROM youth_barangay WHERE Gender = 'Male' AND session_id = '$session_id'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $total_yes_voters = $row['total_yes_voters'];

                          // Display the total "Yes" voters
                          echo '<div class="ps-3">';
                          echo '<h6>' . $total_yes_voters . '</h6>';
                          echo '</div>';
                        } else {
                          echo '<div class="ps-3">';
                          echo '<h6>No "Yes" voters found</h6>';
                          echo '</div>';
                        }

                        // Close database connection
                        $conn->close();
                      } else {
                        // If email is not set in session, display an error message
                        echo '<div class="ps-3">';
                        echo '<h6>Error: Session ID not found</h6>';
                        echo '</div>';
                      }
                      ?>

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->

            <!-- Female Card -->
            <div class="col-xxl-4 col-xl-4">

              <div class="card info-card customers-card">
                <div class="card-body">
                  <h5 class="card-title">Female </h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bx bx-female"></i>
                    </div>
                    <div class="ps-3">
                      <?php
                      // Check if email is set in session
                      if (isset($_SESSION['email'])) {
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

                        // Query to count the total "Yes" values in the voters column
                        $sql = "SELECT COUNT(*) AS total_yes_voters FROM youth_barangay WHERE Gender = 'Female' AND session_id = '$session_id'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $total_yes_voters = $row['total_yes_voters'];

                          // Display the total "Yes" voters
                          echo '<div class="ps-3">';
                          echo '<h6>' . $total_yes_voters . '</h6>';
                          echo '</div>';
                        } else {
                          echo '<div class="ps-3">';
                          echo '<h6>No "Yes" voters found</h6>';
                          echo '</div>';
                        }

                        // Close database connection
                        $conn->close();
                      } else {
                        // If email is not set in session, display an error message
                        echo '<div class="ps-3">';
                        echo '<h6>Error: Session ID not found</h6>';
                        echo '</div>';
                      }
                      ?>
                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->

            <!-- Female Card -->
            <div class="col-xxl-4 col-xl-4">

              <div class="card info-card customers-card">
                <div class="card-body">
                  <h5 class="card-title">Others </h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bx bx-female"></i>
                    </div>
                    <div class="ps-3">
                      <?php
                      // Check if email is set in session
                      if (isset($_SESSION['email'])) {
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

                        // Query to count the total "Yes" values in the voters column
                        $sql = "SELECT COUNT(*) AS total_yes_voters FROM youth_barangay WHERE Gender = 'Female' AND session_id = '$session_id'";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          $total_yes_voters = $row['total_yes_voters'];

                          // Display the total "Yes" voters
                          echo '<div class="ps-3">';
                          echo '<h6>' . $total_yes_voters . '</h6>';
                          echo '</div>';
                        } else {
                          echo '<div class="ps-3">';
                          echo '<h6>No "Yes" voters found</h6>';
                          echo '</div>';
                        }

                        // Close database connection
                        $conn->close();
                      } else {
                        // If email is not set in session, display an error message
                        echo '<div class="ps-3">';
                        echo '<h6>Error: Session ID not found</h6>';
                        echo '</div>';
                      }
                      ?>
                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->



          </div>




          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Youth Chart</h5>

                <!-- Pie Chart -->
                <canvas id="pieChart" style="max-height: 400px;"></canvas>
                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    new Chart(document.querySelector('#pieChart'), {
                      type: 'pie',
                      data: {
                        labels: [
                          'Out of School',
                          'Working Student',
                          'Young parent',
                          'PWD'
                        ],
                        datasets: [{
                          label: 'Total Tally',
                          data: [<?php echo $osy_total; ?>, <?php echo $ws_total; ?>, <?php echo $yp_total; ?>, <?php echo $pwd_total; ?>],
                          backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)',
                            'rgb(205, 20, 76)'
                          ],
                          hoverOffset: 4
                        }]
                      }
                    });
                  });
                </script>
                <!-- End Pie Chart -->

              </div>
            </div>
          </div>

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

          // Fetch recent activity records based on session_id
          $session_id = $_SESSION['session_id']; // Assuming session_id is stored in $_SESSION['email']
          
          $sql3 = "SELECT 
            SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) AS male_count,
            SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) AS female_count,
            SUM(CASE WHEN gender NOT IN ('Male', 'Female') THEN 1 ELSE 0 END) AS others_count
        FROM youth_barangay WHERE session_id = '$session_id'";

          $result3 = $conn->query($sql3);
          if ($result3->num_rows > 0) {
            $row = $result3->fetch_assoc();
            $male_count = $row['male_count'];
            $female_count = $row['female_count'];
            $others_count = $row['others_count'];
          } else {
            $male_count = 0;
            $female_count = 0;
            $others_count = 0;
          }
          ?>

          <div class="card">


            <div class="card-body pb-0">
              <h5 class="card-title">Registered Voters <span>| Youth Edition</span></h5>

              <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  var male_count = <?php echo $male_count; ?>;
                  var female_count = <?php echo $female_count; ?>;
                  var others_count = <?php echo $others_count; ?>;

                  echarts.init(document.querySelector("#trafficChart")).setOption({
                    tooltip: {
                      trigger: 'item'
                    },
                    legend: {
                      top: '5%',
                      left: 'center'
                    },
                    series: [{
                      name: 'Access From',
                      type: 'pie',
                      radius: ['40%', '70%'],
                      avoidLabelOverlap: false,
                      label: {
                        show: false,
                        position: 'center'
                      },
                      emphasis: {
                        label: {
                          show: true,
                          fontSize: '18',
                          fontWeight: 'bold'
                        }
                      },
                      labelLine: {
                        show: false
                      },
                      data: [{
                        value: <?php echo $male_count; ?>,
                        name: 'Male'
                      },
                      {
                        value: <?php echo $female_count; ?>,
                        name: 'Female'
                      },
                      {
                        value: <?php echo $others_count; ?>,
                        name: 'Others'
                      }
                      ]
                    }]
                  });
                });
              </script>

            </div>
          </div><!-- End Website Traffic -->

        </div><!-- End Left side columns -->



        <!-- Right side columns -->
        <div class="col-lg-7">
          <!-- Recent Brgy. Officials -->
          <!-- End Recent Sales -->

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Barangay Officials <span>| Current </span></h5>

              <table class="table datatable">
              <thead>
                  <tr>
                      <th>Profile Picture</th>
                      <th>Full Name</th>
                      <th hidden>First Name</th>
                      <th hidden>Middle Name</th>
                      <th hidden>Last Name</th>
                      <th>Committee</th>
                      <th>Brgy. Position</th>
                      <th hidden>session_id</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  // Check if there are any records fetched
                  if ($result2->num_rows > 0) {
                      // Output data of each row
                      while ($row = $result2->fetch_assoc()) {
                          echo "<tr>";
                          echo '<td class="profile-picture-cell"><div class="profile-picture-container"><img src="uploads/' . $row["profile_picture"] . '" alt="Profile Picture" class="profile-picture rounded-circle" style="width: 70px; height: 70px;"></div></td>';
                          echo "<td>" . $row["first_name"] . " " . $row["middle_name"] . " " . $row["last_name"] . "</td>";
                          echo "<td hidden>" . $row["first_name"] . "</td>";
                          echo "<td hidden>" . $row["middle_name"] . "</td>";
                          echo "<td hidden>" . $row["last_name"] . "</td>";
                          echo "<td>" . $row["committee"] . "</td>";
                          echo "<td>" . $row["barangay_position"] . "</td>";
                          echo "<td hidden>" . $row["session_id"] . "</td>";
                          echo "</tr>";
                      }
                  } else {
                      echo "<tr><td colspan='6'>No records found</td></tr>";
                  }
                  ?>
              </tbody>
          </table>


            </div>
          </div>



          <!-- Recent Activity -->
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Recent Activity </h5>

              <div class="activity">

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

                // Fetch recent activity records based on session_id
                $session_id = $_SESSION['email']; // Assuming session_id is stored in $_SESSION['email']
                $sql = "SELECT * FROM recent_activity WHERE session_id = '$session_id' ORDER by id DESC LIMIT 10";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                  // Output data of each row
                  while ($row = $result->fetch_assoc()) {
                    echo "<div class='activity-item d-flex'>";
                    echo "<div class='activite-label'>" . $row["reg_date"] . "</div>"; // Assuming 'created_at' contains the time created
                    echo "<i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>";
                    
                    echo "<div class='activity-content'>" . $row["first_name"]. ' Added in Brgy.' . $row["barangay_name"] . "</div>"; // Assuming 'activity_content' contains the activity content
                    echo "</div><!-- End activity item-->";
                  }
                } else {
                  echo "<p>No recent activity.</p>";
                }

                // Close database connection
                $conn->close();
                ?>

              </div>

            </div>

          </div><!-- End Recent Activity -->

          <!-- Budget Report -->
          <div class="card">


            <div class="card-body pb-0">
              <h5 class="card-title">Purok List</h5>


              <table class="table datatable">
                <thead>
                  <tr>
                    <th>Purok</th>
                    <th>Total Residents</th>
                  </tr>
                </thead>
                <tbody>
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

                  $session_id = $_SESSION['email'];

                  // Fetch all unique barangay_purok1 values from barangay_purok table where session_id matches
                  $sql_purok1 = "SELECT DISTINCT barangay_purok1 FROM barangay_purok WHERE session_id='$session_id'";
                  $result_purok1 = $conn->query($sql_purok1);


                  // Check if there are any purok1 values found
                  
                  
                  if ($result_purok1->num_rows > 0) {
                    // Output data of each purok1
                    while ($row_purok1 = $result_purok1->fetch_assoc()) {
                      $barangay_purok1 = $row_purok1["barangay_purok1"];

                      // Query to count total residents for each barangay_purok1
                      $sql_residents = "SELECT COUNT(*) AS total_residents FROM youth_barangay WHERE barangay_purok_no='$barangay_purok1' AND session_id='$session_id'";
                      $result_residents = $conn->query($sql_residents);
                      $row_residents = $result_residents->fetch_assoc();
                      $total_residents = $row_residents["total_residents"];
                      ?>

                     
                     <tr>
                      <td>
                      <a href='purok_list.php?purok=<?php echo $barangay_purok1;?>'><?php echo $barangay_purok1;?> </a>
                       </td>
                     <td><?php echo $total_residents;?></td>
                      </tr>
                      <?php

                    }
                  } 
                  
                  else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                  }

                  // Close database connection
                  $conn->close();
                  ?>
                </tbody>
              </table>











            </div>
          </div><!-- End Budget Report -->






        </div><!-- End Right side columns -->

      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php
include("./include/barangay-footer.php");
  ?>
 