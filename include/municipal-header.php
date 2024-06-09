  <?php_ini_loaded_file()

  ?>
  
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
    <a href="municipal-dashboard.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <?php

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
?>

        
        <span class="d-none d-lg-block">Municipality of <?php echo $municipal;?>
         

        </span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->


</div><!-- End Search Bar -->

<nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

        <li class="nav-item d-block d-lg-none">
            <a class="nav-link nav-icon search-bar-toggle " href="#">
                <i class="bi bi-search"></i>
            </a>
        </li><!-- End Search Icon-->


        <li class="nav-item dropdown pe-3">

            <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">

                <?php

                // Check if email is set in session
                if (isset($_SESSION['email'])) {
                    $email = $_SESSION['email'];
                    // You can display the email wherever you want on the page
                    echo "<span class='d-none d-md-block dropdown-toggle ps-2'>" . $email . "</span>";

                } else {
                    // If email is not set in session, redirect to login page
                    header("Location: barangay-login.php");
                    exit();
                }
                ?>

            </a><!-- End Profile Iamge Icon -->

            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                <hr class="dropdown-divider">
        </li>

        <li>
            <a class="dropdown-item d-flex align-items-center" href="municipal-profile.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Update Account</span>
            </a>
        </li>
        <li>
            <a class="dropdown-item d-flex align-items-center" href="municipal-logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
            </a>
        </li>

    </ul><!-- End Profile Dropdown Items -->
    </li><!-- End Profile Nav -->

    </ul>
</nav><!-- End Icons Navigation -->

</header><!-- End Header -->