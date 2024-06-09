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

    $email = $_SESSION['email'];
    $username = $_SESSION['username'];

    $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("s", $username);

    if ($stmt1->execute()) {
        $result1 = $stmt1->get_result();
        if ($result1->num_rows > 0) {
            $row1 = $result1->fetch_assoc();
            $municipal = $row1['municipal']; // Fix: Access $row1 correctly

            // You can display the email wherever you want on the page
            echo "<span>" . $municipal . "</span>";
        } else {
            // Handle the case where no matching username is found
            echo "<span>Municipal not found</span>";
        }
    } else {
        // Handle database execution error
        echo "<span>Error retrieving municipal</span>";
    }

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
    $username = $_SESSION['username'];
    $sql1 = "SELECT municipal FROM admin WHERE username = '$username'";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $row = $result->fetch_assoc();
        $municipality = $row['municipal'];
        $m_username = $row['username'];
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


    $sql2 = "SELECT * FROM barangay_official WHERE session_id = '$email2'";
    $result2 = $conn->query($sql2);




    // Bar Chart PHP CODE


    //   OSY
    // Fetch barangay names and corresponding counts of "Yes" in osy column
    // $sql = "SELECT barangay_name, COUNT(CASE WHEN osy = 'Yes' THEN 1 END) AS osy_count FROM youth_barangay GROUP BY barangay_name";
    // $result = $conn->query($sql);

    $stmt = $conn->prepare("SELECT street, COUNT(CASE WHEN osy = 'Yes' THEN 1 END) AS osy_count FROM youth_barangay WHERE municipal = ? GROUP BY street");
    $stmt->bind_param("s", $municipal);
    $stmt->execute();
    $result = $stmt->get_result();

    // Arrays to store barangay names and corresponding osy counts
    $barangay_names_osy = [];
    $osy_counts = [];

    // Fetch data and populate arrays
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $barangay_names_osy[] = $row['street'];
            $osy_counts[] = $row['osy_count'];
        }
    }
    // END OF OSY






    $stmt = $conn->prepare("SELECT street, COUNT(CASE WHEN ws = 'Yes' THEN 1 END) AS ws_count FROM youth_barangay WHERE municipal = ? GROUP BY street");
    $stmt->bind_param("s", $municipal);
    $stmt->execute();
    $result5 = $stmt->get_result();

    // Arrays to store barangay names and corresponding osy counts
    $barangay_names_ws = [];
    $ws_counts = [];

    // Fetch data and populate arrays
    if ($result5->num_rows > 0) {
        while ($row = $result5->fetch_assoc()) {
            $barangay_names_ws[] = $row['street'];
            $ws_counts[] = $row['ws_count'];
        }
    }
    // END WS
    // Fetch barangay names and corresponding counts of "Yes" in osy column
    // $sql6 = "SELECT barangay_name, COUNT(CASE WHEN yp = 'Yes' THEN 1 END) AS yp_count FROM youth_barangay GROUP BY barangay_name";
    // $result6 = $conn->query($sql6);

    $stmt = $conn->prepare("SELECT street, COUNT(CASE WHEN yp = 'Yes' THEN 1 END) AS yp_count FROM youth_barangay WHERE municipal = ? GROUP BY street");
    $stmt->bind_param("s", $municipal);
    $stmt->execute();
    $result6 = $stmt->get_result();


    // Arrays to store barangay names and corresponding osy counts
    $barangay_names_yp = [];
    $yp_counts = [];

    // Fetch data and populate arrays
    if ($result6->num_rows > 0) {
        while ($row = $result6->fetch_assoc()) {
            $barangay_names_yp[] = $row['street'];
            $yp_counts[] = $row['yp_count'];
        }
    }

    // END YP

    // Fetch barangay names and corresponding counts of "Yes" in osy column
    // $sql7 = "SELECT barangay_name, COUNT(CASE WHEN pwd = 'Yes' THEN 1 END) AS pwd_count FROM youth_barangay GROUP BY barangay_name";
    // $result7 = $conn->query($sql7);

    $stmt = $conn->prepare("SELECT street, COUNT(CASE WHEN pwd = 'Yes' THEN 1 END) AS pwd_count FROM youth_barangay WHERE municipal = ? GROUP BY street");
    $stmt->bind_param("s", $municipal);
    $stmt->execute();
    $result7 = $stmt->get_result();

    // Arrays to store barangay names and corresponding osy counts
    $barangay_names_pwd = [];
    $pwd_counts = [];

    // Fetch data and populate arrays
    if ($result7->num_rows > 0) {
        while ($row = $result7->fetch_assoc()) {
            $barangay_names_pwd[] = $row['street'];
            $pwd_counts[] = $row['pwd_count'];
        }
    }

    // END pwd



    // Close database connection
    // $conn->close();

    // nag comment ako ng close

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

    <title>MUNICIPAL DASHBOARD</title>
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
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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

        <?php
        // Check if email is set in session
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            // Use a prepared statement for security
            $sql1 = "SELECT * FROM municipality WHERE username = ?";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("s", $username);

            if ($stmt1->execute()) {
                $result1 = $stmt1->get_result();
                if ($result1->num_rows > 0) {
                    $row1 = $result1->fetch_assoc();
                    $municipal = $row1['municipal']; // Fix: Access $row1 correctly

                    // You can display the email wherever you want on the page
                    echo "<span>" . $municipal . "</span>";
                } else {
                    // Handle the case where no matching username is found
                    echo "<span>Municipal not found</span>";
                }
            } else {
                // Handle database execution error
                echo "<span>Error retrieving municipal</span>";
            }

            $stmt1->close();  // Close the statement
        }
        ?>



        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-5">
                    <div class="row">

                        <!-- Resident Card -->
                        <div class="col-xxl-6 col-md-3">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Residents </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ri-account-circle-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
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
                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal']; // Fix: Access $row1 correctly

                                                        // You can display the email wherever you want on the page
                                                        // echo "<span>" . $municipal . "</span>";
                                                    } else {
                                                        // Handle the case where no matching username is found
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS total_yes_voters FROM youth_barangay WHERE municipal = ?");

                                                // Bind the parameter
                                                $stmt->bind_param("s", $municipal);  // "i" indicates an integer parameter

                                                // Execute the statement
                                                $stmt->execute();

                                                // Get the result
                                                $result = $stmt->get_result();

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
                        </div>
                        <!--End  Resident Card -->

                        <!-- Barangay Account Count Cart -->
                        <div class="col-xxl-6 col-md-3">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Barangay Account </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ri-account-circle-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
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
                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal']; // Fix: Access $row1 correctly

                                                        // You can display the email wherever you want on the page
                                                        // echo "<span>" . $municipal . "</span>";
                                                    } else {
                                                        // Handle the case where no matching username is found
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS total_barangay FROM barangay WHERE municipal = ?");

                                                // Bind the parameter
                                                $stmt->bind_param("s", $municipal);  // "i" indicates an integer parameter

                                                // Execute the statement
                                                $stmt->execute();

                                                // Get the result
                                                $result = $stmt->get_result();

                                                if ($result->num_rows > 0) {
                                                    $row = $result->fetch_assoc();
                                                    $total_barangay = $row['total_barangay'];

                                                    // Display the total "Yes" voters
                                                    // echo '<a href="barangay_list.php>"';
                                                    echo '<div class="ps-3">';
                                                    echo '<h6>' . $total_barangay . '</h6>';
                                                    echo '<a href="municipal-account.php?barangay=' . $municipal . '">View List</a>';

                                                    echo '</div>';
                                                    // echo '</a>';
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
                        </div>
                        <!-- End Barangay Account Card -->


                        <!-- Registered Voters Card -->
                        <div class="col-xxl-6 col-md-3">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Registered Voters </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ri-account-circle-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
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
                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal']; // Fix: Access $row1 correctly

                                                        // You can display the email wherever you want on the page
                                                        // echo "<span>" . $municipal . "</span>";
                                                    } else {
                                                        // Handle the case where no matching username is found
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS total_yes_voters FROM youth_barangay WHERE voters = 'Yes' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result->num_rows > 0) {
                                                    $row = $result->fetch_assoc();
                                                    $total_yes_voters = $row['total_yes_voters'];

                                                    // Display the total "Yes" voters
                                                    // echo '<a href="barangay_list.php>"';
                                                    echo '<div class="ps-3">';
                                                    echo '<h6>' . $total_yes_voters . '</h6>';
                                                    // echo '<a href="municipal-account.php?barangay=' . $municipal . '">View List</a>';

                                                    echo '</div>';
                                                    // echo '</a>';
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
                        </div>
                        <!-- End Registered Voters Card -->



                        <!-- not-registered Voters Card -->
                        <div class="col-xxl-6 col-md-3">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">Not Registered Yet </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="ri-account-circle-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
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
                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal']; // Fix: Access $row1 correctly

                                                        // You can display the email wherever you want on the page
                                                        // echo "<span>" . $municipal . "</span>";
                                                    } else {
                                                        // Handle the case where no matching username is found
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS total_yes_voters FROM youth_barangay WHERE voters = 'No' AND municipal = ?");

                                                // Bind the parameter
                                                $stmt->bind_param("s", $municipal);  // "i" indicates an integer parameter

                                                // Execute the statement
                                                $stmt->execute();

                                                // Get the result
                                                $result = $stmt->get_result();

                                                if ($result->num_rows > 0) {
                                                    $row = $result->fetch_assoc();
                                                    $total_yes_voters = $row['total_yes_voters'];

                                                    // Display the total "Yes" voters
                                                    // echo '<a href="barangay_list.php>"';
                                                    echo '<div class="ps-3">';
                                                    echo '<h6>' . $total_yes_voters . '</h6>';
                                                    // echo '<a href="municipal-account.php?barangay=' . $municipal . '">View List</a>';

                                                    echo '</div>';
                                                    // echo '</a>';
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
                        </div>
                        <!-- End not registered Voters Card -->


                        <h6 style="font-size: 23px;">Gender Population</h6>



                        <!-- Customers Card -->
                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">

                                <div class="card-body">
                                    <h5 class="card-title">Male </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bx bx-male"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
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
                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS total_yes_voters FROM youth_barangay WHERE Gender = 'Male' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result->num_rows > 0) {
                                                    $row = $result->fetch_assoc();
                                                    $total_yes_voters = $row['total_yes_voters'];
                                                    echo '<div class="ps-3">';
                                                    echo '<h6>' . $total_yes_voters . '</h6>';
                                                    echo '</div>';
                                                    // echo '</a>';
                                                }
                                                $conn->close();
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- End Customers Card -->

                        <!-- Female Card -->
                        <div class="col-xxl-6 col-xl-4">

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
                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS total_yes_voters FROM youth_barangay WHERE Gender = 'Female' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result->num_rows > 0) {
                                                    $row = $result->fetch_assoc();
                                                    $total_yes_voters = $row['total_yes_voters'];
                                                    echo '<div class="ps-3">';
                                                    echo '<h6>' . $total_yes_voters . '</h6>';
                                                    echo '</div>';
                                                    // echo '</a>';
                                                }
                                                $conn->close();
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- End Customers Card -->


                        <!-- Female Card -->
                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">

                                <div class="card-body">
                                    <h5 class="card-title">Others </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bx bx-group"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php
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
                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS total_yes_voters FROM youth_barangay WHERE Gender = 'Prefer not to say' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result->num_rows > 0) {
                                                    $row = $result->fetch_assoc();
                                                    $total_yes_voters = $row['total_yes_voters'];
                                                    echo '<div class="ps-3">';
                                                    echo '<h6>' . $total_yes_voters . '</h6>';
                                                    echo '</div>';
                                                    // echo '</a>';
                                                }
                                                $conn->close();
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div><!-- End Customers Card -->


                        <!-- Calasicas Condino-->





                        <div class="col-xxl-6 col-xl-4">

                        </div> <!-- for spacing-->


                        <h6 style="font-size: 23px;">Youth Population</h6>


                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Age 15-30 </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: green; background: #C9FFDA;">
                                            <i class="bx bxs-group"></i>
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

                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }


                                            }

                                            // $sql = "SELECT TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS in_age FROM youth_barangay";
                                            // $result = $conn->query($sql);

                                            $stmt = $conn->prepare("SELECT street, TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS in_age FROM youth_barangay WHERE municipal = ? GROUP BY street");
                                            $stmt->bind_param("s", $municipal);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            

                                            $barangay_names_in_age = [];
                                            $in_age = [];

                                            // Fetch data and populate arrays
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                            $barangay_names_in_age[] = $row['street'];

                                                    $in_age[] = $row['in_age'];
                                                }
                                            }

                                            ?>
                                            <?php
                                            $total_youth_inage = 0;

                                            foreach ($in_age as $inage) {
                                                if ($inage >= 15 && $inage <= 30) {
                                                    $total_youth_inage++;
                                                }
                                            }

                                            echo "<h6> $total_youth_inage</h6>";
                                            ?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Age out of 15-30 </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: red; background: #FFC9C9;">
                                            <i class="bx bxs-group"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php

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
                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }


                                            }

                                            // $sql = "SELECT TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS in_age FROM youth_barangay";
                                            // $result = $conn->query($sql);

                                            $stmt = $conn->prepare("SELECT street, TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS out_age FROM youth_barangay WHERE municipal = ?");
                                            $stmt->bind_param("s", $municipal);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            $out_age = [];

                                            // Fetch data and populate arrays
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $out_age[] = $row['out_age'];
                                                }
                                            }

                                            $total_youth_outage = 0;

                                            foreach ($out_age as $outage) {
                                                if ($outage < 15 || $outage > 30) {
                                                    $total_youth_outage++;
                                                }
                                            }

                                            echo "<h6> $total_youth_outage</h6>";
                                            ?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Voters Age 15-30 </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: green; background: #C9FFDA;">
                                            <i class="bx bxs-group"></i>
                                        </div>
                                        <div class="ps-3">
                                            <?php

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
                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }
                                            }

                                            // $sql = "SELECT TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS Vote_age FROM youth_barangay WHERE TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 15 AND 30 AND voters = 'Yes'";
                                            // $result = $conn->query($sql);

                                            $stmt = $conn->prepare("SELECT TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS Vote_age FROM youth_barangay WHERE TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 15 AND 30 AND voters = 'Yes' AND municipal = ?");
                                            $stmt->bind_param("s", $municipal);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            $Vote_age = [];

                                            // Fetch data and populate arrays
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $Vote_age[] = $row['Vote_age'];
                                                }
                                            }

                                            ?>
                                            <?php
                                            $total_youth_voter = 0;

                                            foreach ($Vote_age as $vage) {
                                                if ($vage >= 15 && $vage <= 30) {
                                                    $total_youth_voter++;
                                                }
                                            }

                                            echo "<h6> $total_youth_voter</h6>";

                                            ?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">Non-voters Age 15-30 </h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: red; background: #FFC9C9;">
                                            <i class="bx bxs-group"></i>
                                        </div>
                                        <div class="ps-3">

                                            <?php

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
                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }
                                            }

                                            // $sql = "SELECT TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS notVote_age FROM youth_barangay WHERE TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 15 AND 30 AND voters = 'No'";
                                            // $result = $conn->query($sql);

                                            $stmt = $conn->prepare("SELECT TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) AS notVote_age FROM youth_barangay WHERE TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 15 AND 30 AND voters = 'No' AND municipal = ?");
                                            $stmt->bind_param("s", $municipal);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            // Arrays to store barangay names and corresponding ages
                                            $notVote_age = [];

                                            // Fetch data and populate arrays
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $notVote_age[] = $row['notVote_age'];
                                                }
                                            }

                                            ?>

                                            <?php
                                            $total_youth_nonvoter = 0;

                                            foreach ($notVote_age as $nvage) {
                                                if ($nvage >= 15 && $nvage <= 30) {
                                                    $total_youth_nonvoter++;
                                                }
                                            }

                                            echo "<h6> $total_youth_nonvoter</h6>";

                                            ?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>



                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title"> Enrolled in School</h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: green; background: #C9FFDA;">
                                            <i class="bx bxs-group"></i>
                                        </div>
                                        <div class="ps-3">

                                            <?php

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

                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }


                                                // Query to get the count of out-of-school youth
                                                // $sql = "SELECT COUNT(*) AS on_school FROM youth_barangay WHERE osy = 'No'";
                                                // $result = $conn->query($sql);

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS on_school FROM youth_barangay WHERE osy = 'No' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result) {
                                                    $row = $result->fetch_assoc();
                                                    $total_youth_inschool = $row['on_school'];
                                                    echo "<h6> $total_youth_inschool</h6>";
                                                } else {
                                                    echo "Error in executing query: " . $conn->error;
                                                }

                                                // Close connection
                                                $conn->close();
                                            }

                                            ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title"> Out of School</h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: red; background: #FFC9C9;">
                                            <i class="bx bxs-group"></i>
                                        </div>
                                        <div class="ps-3">

                                            <?php

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

                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }


                                                // Query to get the count of out-of-school youth
                                                // $sql = "SELECT COUNT(*) AS out_of_school FROM youth_barangay WHERE osy = 'Yes'";
                                                // $result = $conn->query($sql);

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS out_of_school FROM youth_barangay WHERE osy = 'Yes' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result) {
                                                    $row = $result->fetch_assoc();
                                                    $total_youth_outOfschool = $row['out_of_school'];
                                                    echo "<h6> $total_youth_outOfschool</h6>";
                                                } else {
                                                    echo "Error in executing query: " . $conn->error;
                                                }

                                                // Close connection
                                                $conn->close();
                                            }

                                            ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title"> Working Student</h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: green; background: #C9FFDA;">
                                            <i class="bx bxs-group"></i>
                                        </div>
                                        <div class="ps-3">

                                            <?php

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

                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }

                                                // Query to get the count of out-of-school youth
                                                // $sql = "SELECT COUNT(*) AS working_student FROM youth_barangay WHERE ws = 'Yes'";
                                                // $result = $conn->query($sql);

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS working_student FROM youth_barangay WHERE ws = 'Yes' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result) {
                                                    $row = $result->fetch_assoc();
                                                    $total_youth_working_student = $row['working_student'];
                                                    echo "<h6> $total_youth_working_student</h6>";
                                                } else {
                                                    echo "Error in executing query: " . $conn->error;
                                                }

                                                // Close connection
                                                $conn->close();
                                            }

                                            ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title"> Non-Working Student</h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: red; background: #FFC9C9;">
                                            <i class="bx bxs-group"></i>
                                        </div>
                                        <div class="ps-3">

                                            <?php

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

                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }


                                                // Query to get the count of out-of-school youth
                                                // $sql = "SELECT COUNT(*) AS non_ws FROM youth_barangay WHERE ws = 'No'";
                                                // $result = $conn->query($sql);

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS non_ws FROM youth_barangay WHERE ws = 'No' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                

                                                if ($result) {
                                                    $row = $result->fetch_assoc();
                                                    $total_youth_non_ws = $row['non_ws'];
                                                    echo "<h6> $total_youth_non_ws</h6>";
                                                } else {
                                                    echo "Error in executing query: " . $conn->error;
                                                }

                                                // Close connection
                                                $conn->close();
                                            }

                                            ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title"> PWD</h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: green; background: #C9FFDA;">
                                            <i class="bx bxs-group"></i>
                                        </div>
                                        <div class="ps-3">

                                            <?php

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

                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }


                                                // Query to get the count of out-of-school youth
                                                // $sql = "SELECT COUNT(*) AS p_w_d FROM youth_barangay WHERE pwd = 'Yes'";
                                                // $result = $conn->query($sql);

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS p_w_d FROM youth_barangay WHERE pwd = 'Yes' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result) {
                                                    $row = $result->fetch_assoc();
                                                    $total_youth_p_w_d = $row['p_w_d'];
                                                    echo "<h6> $total_youth_p_w_d</h6>";
                                                } else {
                                                    echo "Error in executing query: " . $conn->error;
                                                }

                                                // Close connection
                                                $conn->close();
                                            }

                                            ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title"> Non-PWD</h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: red; background: #FFC9C9;">
                                            <i class="bx bxs-group"></i>
                                        </div>
                                        <div class="ps-3">

                                            <?php

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

                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }


                                                // Query to get the count of out-of-school youth
                                                // $sql = "SELECT COUNT(*) AS non_pwd FROM youth_barangay WHERE pwd = 'No'";
                                                // $result = $conn->query($sql);

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS non_pwd FROM youth_barangay WHERE pwd = 'No' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result) {
                                                    $row = $result->fetch_assoc();
                                                    $total_youth_non_pwd = $row['non_pwd'];
                                                    echo "<h6> $total_youth_non_pwd</h6>";
                                                } else {
                                                    echo "Error in executing query: " . $conn->error;
                                                }

                                                // Close connection
                                                $conn->close();
                                            }

                                            ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title"> Young Parent</h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: green; background: #C9FFDA;">
                                            <i class="bx bxs-group"></i>
                                        </div>
                                        <div class="ps-3">

                                            <?php

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

                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }


                                                // Query to get the count of out-of-school youth
                                                $sql = "SELECT COUNT(*) AS young_parent FROM youth_barangay WHERE yp = 'Yes'";
                                                $result = $conn->query($sql);

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS young_parent FROM youth_barangay WHERE yp = 'Yes' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result) {
                                                    $row = $result->fetch_assoc();
                                                    $total_youth_young_parent = $row['young_parent'];
                                                    echo "<h6> $total_youth_young_parent</h6>";
                                                } else {
                                                    echo "Error in executing query: " . $conn->error;
                                                }

                                                // Close connection
                                                $conn->close();
                                            }

                                            ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="col-xxl-6 col-xl-4">

                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title"> Not Parent</h5>

                                    <div class="d-flex align-items-center">
                                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="color: red; background: #FFC9C9;">
                                            <i class="bx bxs-group"></i>
                                        </div>
                                        <div class="ps-3">

                                            <?php

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

                                                $username = $_SESSION['username'];

                                                $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                                                $stmt1 = $conn->prepare($sql1);
                                                $stmt1->bind_param("s", $username);

                                                if ($stmt1->execute()) {
                                                    $result1 = $stmt1->get_result();
                                                    if ($result1->num_rows > 0) {
                                                        $row1 = $result1->fetch_assoc();
                                                        $municipal = $row1['municipal'];
                                                    } else {
                                                        echo "<span>Municipal not found</span>";
                                                    }
                                                } else {
                                                    // Handle database execution error
                                                    echo "<span>Error retrieving municipal</span>";
                                                }


                                                // Query to get the count of out-of-school youth
                                                $sql = "SELECT COUNT(*) AS non_parent FROM youth_barangay WHERE yp = 'No'";
                                                $result = $conn->query($sql);

                                                $stmt = $conn->prepare("SELECT COUNT(*) AS non_parent FROM youth_barangay WHERE yp = 'No' AND municipal = ?");
                                                $stmt->bind_param("s", $municipal);
                                                $stmt->execute();
                                                $result = $stmt->get_result();

                                                if ($result) {
                                                    $row = $result->fetch_assoc();
                                                    $total_youth_non_parent = $row['non_parent'];
                                                    echo "<h6> $total_youth_non_parent</h6>";
                                                } else {
                                                    echo "Error in executing query: " . $conn->error;
                                                }

                                                // Close connection
                                                $conn->close();
                                            }

                                            ?>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>



                        <!-- Calasicas Condino-->


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
                $sql = "SELECT * FROM recent_activity WHERE municipal = '$municipal' ORDER by id DESC LIMIT 10";
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
                        <!-- <div class="card">


                            <div class="card-body pb-0">
                                <h5 class="card-title">Barangay List</h5>


                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>Barangay No.</th>
                                            <th>Total Residents</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <php

                                       
                                        $servername = "localhost";
                                        $username = "root";
                                        $password = "";
                                        $dbname = "sk_database";

                                     
                                        $conn = new mysqli($servername, $username, $password, $dbname);

                                      
                                        if ($conn->connect_error) {
                                            die("Connection failed: " . $conn->connect_error);
                                        }

                                        $session_id = $_SESSION['email'];
                                        $username = $_SESSION['username'];

                        $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                        $stmt1 = $conn->prepare($sql1);
                        $stmt1->bind_param("s", $username);

                        if ($stmt1->execute()) {
                            $result1 = $stmt1->get_result();
                            if ($result1->num_rows > 0) {
                                $row1 = $result1->fetch_assoc();
                                $municipal = $row1['municipal'];
                            } else {
                                echo "<span>Municipal not found</span>";
                            }
                        } else {
                         
                            echo "<span>Error retrieving municipal</span>";
                        }

                                      
                                        $stmt = $conn->prepare("SELECT street FROM barangay WHERE municipal = ?");
                                        $stmt->bind_param("s", $municipal);
                                        $stmt->execute();
                                        $result_purok1 = $stmt->get_result();


                                        if ($result_purok1->num_rows > 0) {
                                          
                                            while ($row_purok1 = $result_purok1->fetch_assoc()) {
                                                $barangay_purok1 = $row_purok1["street"];

                                               
                                                $sql_residents = "SELECT COUNT(*) AS total_residents FROM youth_barangay WHERE street='$barangay_purok1'";
                                                $result_residents = $conn->query($sql_residents);
                                                $row_residents = $result_residents->fetch_assoc();
                                                $total_residents = $row_residents["total_residents"];

                                               
                                                echo "<tr>";
                                                echo "<td>" . $barangay_purok1 . "</td>";
                                                echo "<td>" . $total_residents . "</td>";
                                                echo "<td>" . $total_residents . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>No records found</td></tr>";
                                        }

                                     
                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>











                            </div>
                        </div> -->

                     
    <!-- Barangay Table -->
        <div class="card">
    <div class="card-body pb-0">
        <h5 class="card-title">Barangay List</h5>
        <table class="table datatable">
            <thead>
                <tr>
                    <th>Barangay</th>
                    <th>Action</th>
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

            // $sql = "SELECT * FROM `barangay` WHERE municipal = ?;";
            // $result = $conn->query($sql);

            $username = $_SESSION['username'];

            $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("s", $username);

            if ($stmt1->execute()) {
                $result1 = $stmt1->get_result();
                if ($result1->num_rows > 0) {
                    $row1 = $result1->fetch_assoc();
                    $municipal = $row1['municipal'];
                } else {
                    echo "<span>Municipal not found</span>";
                }
            } else {
             
                echo "<span>Error retrieving municipal</span>";
            }

            $stmt = $conn->prepare("SELECT id, street FROM barangay WHERE municipal = ?");
            $stmt->bind_param("s", $municipal);
            $stmt->execute();
            $result = $stmt->get_result();



            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["street"]. "</td><td><a href='#' class='btn btn-primary view-barangay' data-toggle='modal' data-target='#barangayModal".$row["id"]."'>View</a></td></tr>";
                }
            } else {
                echo "<tr><td colspan='2'>No barangays found</td></tr>";
            }

            $conn->close();
            ?>
            </tbody>
        </table>
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

$username = $_SESSION['username'];

                        $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                        $stmt1 = $conn->prepare($sql1);
                        $stmt1->bind_param("s", $username);

                        if ($stmt1->execute()) {
                            $result1 = $stmt1->get_result();
                            if ($result1->num_rows > 0) {
                                $row1 = $result1->fetch_assoc();
                                $municipal = $row1['municipal'];
                            } else {
                                echo "<span>Municipal not found</span>";
                            }
                        } else {
                         
                            echo "<span>Error retrieving municipal</span>";
                        }

// $sql = "SELECT * FROM `barangay`;";
// $result = $conn->query($sql);

$stmt = $conn->prepare("SELECT id, street FROM barangay WHERE municipal = ?");
$stmt->bind_param("s", $municipal);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // Modal for each barangay
        echo "<div class='modal fade' id='barangayModal".$row["id"]."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
        echo "<div class='modal-dialog modal-dialog-centered modal-xl ' role='document'>";
        echo "<div class='modal-content'>";
        echo "<div class='modal-header'>";
        echo "<h5 class='modal-title' id='exampleModalLabel'>View Barangay Officials</h5>";
        echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
        echo "<span aria-hidden='true'>&times;</span>";
        echo "</button>";
        echo "</div>";
        echo "<div class='modal-body'>";
        echo "<p>Barangay: " . $row["street"] . "</p>";
        // Add more details here if needed
        echo "</div>";
        echo "<div class='modal-footer'>";
        echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>"; 
        echo "</div>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
}

$conn->close();
?>

<script>
    $(document).ready(function(){
        // Function to open modal when view button is clicked
        $('.view-barangay').click(function(){
            var target = $(this).attr('data-target');
            $(target).modal('show');
        });
    });
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

                    </div>








                </div><!-- End Left side columns -->



                <!-- Right side columns -->
                <div class="col-lg-7">
                    <!-- Recent Brgy. Officials -->
                    <!-- End Recent Sales -->

                    <!-- OSY CHART -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">TOTAL OF ALL BARANGAY OUT OF SCHOOL YEAR</h5>

                                <!-- Bar Chart -->
                                <div id="barChart1"></div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new ApexCharts(document.querySelector("#barChart1"), {
                                            series: [{
                                                data: <?php echo json_encode($osy_counts); ?> // Use the osy counts data
                                            }],
                                            chart: {
                                                type: 'bar',
                                                height: 300
                                            },
                                            plotOptions: {
                                                bar: {
                                                    borderRadius: 4,
                                                    horizontal: false,
                                                }
                                            },
                                            dataLabels: {
                                                enabled: true,
                                                data: <?php echo json_encode($barangay_names_osy); ?>,
                                                formatter: function(val) { // Add a formatter function
                                                    return val.toFixed(0); // Convert to integer and display
                                                }
                                                
                                            },
                                            xaxis: {
                                                categories: <?php echo json_encode($barangay_names_osy); ?>
                                            }
                                        }).render();
                                    });
                                </script>
                                <!-- End Bar Chart -->

                            </div>
                        </div>
                    </div>
                    <!-- END OSY CHART -->


                    <!-- WS CHART -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">TOTAL OF ALL BARANGAY WORKING STUDENT</h5>

                                <!-- Bar Chart -->
                                <div id="barChart"></div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new ApexCharts(document.querySelector("#barChart"), {
                                            series: [{
                                                data: <?php echo json_encode($ws_counts); ?> // Use the osy counts data
                                            }],
                                            chart: {
                                                type: 'bar',
                                                height: 350
                                            },
                                            plotOptions: {
                                                bar: {
                                                    borderRadius: 4,
                                                    horizontal: false,
                                                }
                                            },
                                            dataLabels: {
                                                enabled: false
                                            },
                                            xaxis: {
                                                categories: <?php echo json_encode($barangay_names_ws); ?> // Use the barangay names as categories
                                            }
                                        }).render();
                                    });
                                </script>
                                <!-- End Bar Chart -->

                            </div>
                        </div>
                    </div>
                    <!--END  WS CHART -->

                    <!-- YP CHART -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">TOTAL OF ALL BARANGAY YOUNG PARENTS</h5>

                                <!-- Bar Chart -->
                                <div id="barChart2"></div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new ApexCharts(document.querySelector("#barChart2"), {
                                            series: [{
                                                data: <?php echo json_encode($yp_counts); ?> // Use the osy counts data
                                            }],
                                            chart: {
                                                type: 'bar',
                                                height: 350
                                            },
                                            plotOptions: {
                                                bar: {
                                                    borderRadius: 4,
                                                    horizontal: false,
                                                }
                                            },
                                            dataLabels: {
                                                enabled: false
                                            },
                                            xaxis: {
                                                categories: <?php echo json_encode($barangay_names_yp); ?> // Use the barangay names as categories
                                            }
                                        }).render();
                                    });
                                </script>
                                <!-- End Bar Chart -->

                            </div>
                        </div>
                    </div>

                    <!-- END YP CHART -->

                    <!-- PWD CHART -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">TOTAL OF ALL BARANGAY PERSON WITH DISABILITY</h5>

                                <!-- Bar Chart -->
                                <div id="barChart3"></div>

                                <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new ApexCharts(document.querySelector("#barChart3"), {
                                            series: [{
                                                data: <?php echo json_encode($pwd_counts); ?> // Use the osy counts data
                                            }],
                                            chart: {
                                                type: 'bar',
                                                height: 350
                                            },
                                            plotOptions: {
                                                bar: {
                                                    borderRadius: 4,
                                                    horizontal: false,
                                                }
                                            },
                                            dataLabels: {
                                                enabled: false
                                            },
                                            xaxis: {
                                                categories: <?php echo json_encode($barangay_names_pwd); ?> // Use the barangay names as categories
                                            }
                                        }).render();
                                    });
                                </script>
                                <!-- End Bar Chart -->

                            </div>
                        </div>
                    </div>

                    <!--END  PWD CHART -->

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
                        $username = $_SESSION['username'];

                        $sql1 = "SELECT municipal FROM municipality WHERE username = ?";
                        $stmt1 = $conn->prepare($sql1);
                        $stmt1->bind_param("s", $username);

                        if ($stmt1->execute()) {
                            $result1 = $stmt1->get_result();
                            if ($result1->num_rows > 0) {
                                $row1 = $result1->fetch_assoc();
                                $municipal = $row1['municipal'];
                            } else {
                                echo "<span>Municipal not found</span>";
                            }
                        } else {
                            // Handle database execution error
                            echo "<span>Error retrieving municipal</span>";
                        }
                        $stmt = $conn->prepare("SELECT 
                        SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) AS male_count,
                        SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) AS female_count,
                        SUM(CASE WHEN gender NOT IN ('Male', 'Female') THEN 1 ELSE 0 END) AS others_count
                    FROM youth_barangay WHERE municipal = ?");
                        $stmt->bind_param("s", $municipal);
                        $stmt->execute();
                        $result3 = $stmt->get_result();


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
                                <h5 class="card-title">Resident <span>| Youth Edition</span></h5>

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
                                                avoidLabelOverlap: true,
                                                label: {
                                                    show: true,
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
                                                    show: true
                                                },
                                                data: [{
                                                        value: <?php echo $male_count; ?>,
                                                        name: 'Male',
                                                        value: <?php echo $male_count; ?>,
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


                    





                </div><!-- End Right side columns -->

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
    <script src="assets/vendor/simple-datatables/simple-datatables1.js"></script>
    <script src="assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- Template Main JS File -->
    <script src="assets/js/main.js"></script>

</body>

</html>