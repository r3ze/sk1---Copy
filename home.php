<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
  <link rel="stylesheet" href="assets/css/stylehome.css">
  <link rel="stylesheet" href="assets/css/fontawesome.css">
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="assets/css/glightbox.min.css" />
  <link href="assets/css/css2.css" rel="stylesheet">
  <link href="assets/css/success_error.css" rel="stylesheet">
</head>

<body>
  <!-- ////////////////////////////////////////////////////////////////////////////////////////
                               START SECTION 1 - THE NAVBAR SECTION  
/////////////////////////////////////////////////////////////////////////////////////////////-->
  <nav class="navbar navbar-expand-lg navbar-dark menu shadow fixed-top">
    <div class="container">
      <a class="navbar-brand" href="home.php">
        <img src="images/logo.png" alt="logo image">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link active" aria-current="page" href="home.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#services">Who are we</a></li>
          <li class="nav-item"><a class="nav-link" href="#mission">Mission & Vision</a></li>
          <li class="nav-item"><a class="nav-link" href="#testimonials">Get Involved</a></li>
          <li class="nav-item"><a class="nav-link" href="#gallery">Gallery</a>
          </li>
        </ul>
        <!-- Button to open the modal -->
        <button type="button" class="rounded-pill btn-rounded" data-toggle="modal" data-target="#barangayLoginModal">Login <span><i class="fas fa-arrow-right"></i></span></button>

        <!-- Modal -->



      </div>
    </div>
  </nav>
  <div class="modal" id="barangayLoginModal" tabindex="-1" role="dialog" aria-labelledby="barangayLoginModalLabel" aria-hidden="true" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: auto;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="barangayLoginModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Your PHP code here -->
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
                // echo "Invalid username.";
                header("Location: home.php?error=Invalid username");


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

                    header("Location: municipal-dashboard.php");
                  }
                  exit();
                } else {
                  // echo "Incorrect username or password.";
                  header("Location: home.php?error=Incorrect username or password.");
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
            <style>
              .close {
                border: none;
                border-radius: 3px;

              }

              .close:hover {
                background-color: #dc3545;
                color: white;
                border-radius: 3px;
              }

              .credits {
                display: flex;
                justify-content: center;
              }

              .credits a {
                text-decoration: none;
                color: #0046fa;
              }

              input[type="text"] {
                text-transform: none;
              }
            </style>
            <div class="credits">
              <a href="barangay-add.php">Request Account?</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Get the login button element
      var loginBtn = document.getElementById('loginBtn');

      // Add a click event listener to the login button
      loginBtn.addEventListener('click', function(event) {
        // Prevent the modal from closing
        event.stopPropagation();

        // Add your login logic here if needed
        // For example:
        // loginUser();
      });
    });
  </script>

  <!-- /////////////////////////////////////////////////////////////////////////////////////////////////
                            START SECTION 2 - THE INTRO SECTION  
/////////////////////////////////////////////////////////////////////////////////////////////////////-->



  <section id="home" class="intro-section">
    <div class="container">
      <div class="row align-items-center text-white">
        <style>
          .error-message {
            background-color: #e7b8b3;
            color: #e72727;
            padding: 15px;
            margin-bottom: 0;
            border: 1px solid #dca6a6;
            border-radius: 4px;
            display: flex;
            justify-content: center;

          }

          .error-message p {
            margin-bottom: 0;
          }
        </style>
        <?php if (isset($_GET['success'])) : ?>
          <div class="success-message">
            <p><?php echo $_GET['success']; ?></p>
          </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])) : ?>
          <div class="error-message">
            <p><?php echo $_GET['error']; ?></p>
          </div>
        <?php endif; ?>

        <!-- START THE CONTENT FOR THE INTRO  -->
        <div class="col-md-6 intros text-start">
          <h1 class="display-2">
            <span class="display-2--intro" style="font-size: 50px; color: #ffffff;">Sangguniang </span>
            <span class="display-2--intro" style="font-size: 50px; color: #dc3545;"> Kabataan</span>
            <span class="display-2--description lh-base" style="color: #fbd218;">
              Often abbreviated as SK, is a youth council in the Philippines that represents the interests and concerns of young people at the local level. It serves as a platform for youth empowerment, participation in governance, and community development. Comprised of elected youth leaders aged 18 to 30, the SK plays a vital role in promoting youth welfare, initiating projects, and advocating for policies that address the needs of young Filipinos within their respective barangays or villages.
            </span>
          </h1>
          <!-- <button type="button" class="rounded-pill btn-rounded" onclick="window.location.href='barangay-add.php'">
            Register here
            <span><i class="fas fa-arrow-right"></i></span>
          </button> -->

        </div>
        <!-- START THE CONTENT FOR THE VIDEO -->
        <div class="col-md-6 intros text-end">
          <div class="video-box">
            <img src="images/arts/intro-section-illustration.png" alt="video illutration" class="img-fluid">
            <a href="#" class="glightbox position-absolute top-50 start-50 translate-middle">
              <span>
                <i style="color: #203d86;" class="fas fa-play-circle"></i>
              </span>
              <span class="border-animation border-animation--border-1"></span>
              <span class="border-animation border-animation--border-2"></span>
            </a>
          </div>
        </div>
      </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
      <path fill="#ffffff" fill-opacity="1" d="M0,160L48,176C96,192,192,224,288,208C384,192,480,128,576,133.3C672,139,768,213,864,202.7C960,192,1056,96,1152,74.7C1248,53,1344,107,1392,133.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
    </svg>
  </section>

  <!-- //////////////////////////////////////////////////////////////////////////////////////////////
                             START SECTION 3 - THE CAMPANIES SECTION  
////////////////////////////////////////////////////////////////////////////////////////////////////-->

  <section id="campanies" class="campanies">
    <div class="container">

    </div>
    <!-- START THE CAMPANIES CONTENT  -->

  </section>

  <!-- //////////////////////////////////////////////////////////////////////////////////////////////
                         START SECTION 4 - THE SERVICES  
///////////////////////////////////////////////////////////////////////////////////////////////////-->
  <section id="services" class="services">
    <div class="container services" id="services">
      <div class="row text-center services" id="services">
        <h1 class="display-3 fw-bold services" id="services">Who are we?</h1>
        <div class="heading-line mb-1"></div>
      </div>
      <!-- START THE DESCRIPTION CONTENT  -->
      <div class="row pt-2 pb-2 mt-0 mb-3">
        <div class="col-md-6 border-right">
          <div class="bg-white p-3">
            <h2 class="fw-bold text-capitalize text-center">
              The Sangguniang Kabataan
            </h2>
          </div>
        </div>
        <div class="col-md-6">
          <div class="bg-white p-4 text-start">
            <p class="fw-light">
              "We are the Sangguniang Kabataan (SK), the vibrant voice of the youth in the Philippines. Elected by our peers, we stand as the advocates, innovators, and leaders of our communities. With passion and dedication, we work tirelessly to address the needs and concerns of young people, striving to create a brighter future for all. Together, we are a force for positive change, shaping our barangays and empowering our fellow youth to thrive and succeed."
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- START THE CONTENT FOR THE SERVICES  -->
    <div class="container">
      <!-- START THE MARKETING CONTENT  -->
      <div class="row mission" id="mission">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 services mt-4">
          <div class="services__content">
            <h3 class="display-3--title mt-1">Mission</h3>
            <p class="lh-lg">
              "To serve as the beacon of youth empowerment, advocacy, and community engagement, fostering a culture of active citizenship and inclusive development among Filipino youth. We commit to championing the voices and aspirations of our fellow young Filipinos, working collaboratively to address their needs, cultivate their talents, and nurture their potential as catalysts for positive change."
            </p>

          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 services mt-4 text-end">
          <div class="services__pic">
            <img src="images/services/service-1.png" alt="marketing illustration" class="img-fluid">
          </div>
        </div>
      </div>
      <!-- START THE WEB DEVELOPMENT CONTENT  -->
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 services mt-4 text-start">
          <div class="services__pic">
            <img src="images/services/service-2.png" alt="web development illustration" class="img-fluid">
          </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 services mt-4">
          <div class="services__content">
            <h3 class="display-3--title mt-1">Vision</h3>
            <p class="lh-lg">
              "We envision a dynamic and resilient community where every young Filipino is empowered, educated, and actively involved in shaping their future. Through our unwavering dedication to youth welfare and community service, we strive to build a society that values diversity, fosters innovation, and embraces solidarity, paving the way for a brighter and more inclusive tomorrow.
            </p>

          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- ////////////////////////////////////////////////////////////////////////////////////////////////
                               START SECTION 5 - THE TESTIMONIALS  
/////////////////////////////////////////////////////////////////////////////////////////////////////-->
  <section id="testimonials" class="testimonials">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
      <path fill="#fff" fill-opacity="1" d="M0,96L48,128C96,160,192,224,288,213.3C384,203,480,117,576,117.3C672,117,768,203,864,202.7C960,203,1056,117,1152,117.3C1248,117,1344,203,1392,245.3L1440,288L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
    </svg>
    <div class="container">
      <div class="row text-center text-white">
        <h1 class="display-3 fw-bold">Get Involved</h1>
        <hr style="width: 100px; height: 3px; " class="mx-auto">
      </div>

      <!-- START THE CAROUSEL CONTENT  -->
      <div class="row align-items-center">
        <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <!-- CAROUSEL ITEM 1 -->
            <div class="carousel-item active">
              <!-- testimonials card  -->
              <div class="testimonials__card">
                <p class="lh-lg">

                  Join Committees: Be part of our dynamic team by joining one of our committees focused on various areas such as education, youth welfare, sports, culture, and environment. Contribute your skills, ideas, and energy to help implement projects and initiatives that benefit our fellow youth and barangay.

                  Volunteer for Events: Make an impact by volunteering for our events and activities. Whether it's organizing a community cleanup, conducting a youth seminar, or hosting a sports tournament, your participation and support are invaluable in creating successful and meaningful experiences for our community.

                  Run for Office: Are you a natural leader with a vision for positive change? Consider running for office in future SK elections. As an elected SK officer, you'll have the opportunity to represent the voices and interests of your peers, advocate for youth issues, and spearhead initiatives that shape the future of our barangay.

                  Share Your Ideas: Your input matters! We welcome your ideas, suggestions, and feedback on how we can better serve the needs of our community and fellow youth. Together, let's brainstorm innovative solutions and initiatives that make a real difference in the lives of others.

                  Join us in building a stronger, more vibrant community where every young Filipino can thrive and succeed. Together, we can make a lasting impact and create a brighter future for all!


                </p>
              </div>
              <!-- client picture  -->

              <!-- client name & role  -->

            </div>
            <!-- CAROUSEL ITEM 2 -->

            <!-- CAROUSEL ITEM 3 -->

            <!-- testimonials card  -->

            <!-- client picture  -->

            <!-- client name & role  -->

          </div>
          <!-- CAROUSEL ITEM 4 -->

        </div>

      </div>
    </div>
    </div>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
      <path fill="#fff" fill-opacity="1" d="M0,96L48,128C96,160,192,224,288,213.3C384,203,480,117,576,117.3C672,117,768,203,864,202.7C960,203,1056,117,1152,117.3C1248,117,1344,203,1392,245.3L1440,288L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
    </svg>
  </section>

  <section id="gallery" class="gallery">
    <div class="container">
      <div class="row text-center mt-5">
        <h1 class="display-3 fw-bold text-capitalize">District IV</h1>
        <div class="heading-line"></div>
        <p class="lead">
          Laguna's 4th congressional district is one of the seven congressional districts of the Philippines in the province of Laguna.
        </p>
      </div>

      <!-- START THE PORTFOLIO ITEMS  -->
      <div class="row">
        <div class="col-lg-4 col-md-6">
          <div class="portfolio-box shadow">
            <img src="images/stacruz.jpg" alt="portfolio 1 image" title="portfolio 1 picture" class="img-fluid">
            <div class="portfolio-info">
              <div class="caption">
                <h4>Sta Cruz</h4>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="portfolio-box shadow">
            <img src="images/pila.jpg" alt="portfolio 2 image" title="portfolio 2 picture" class="img-fluid">
            <div class="portfolio-info">
              <div class="caption">
                <h4>Pila</h4>
                <p></p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="portfolio-box shadow">
            <img src="images/pagsan.jpg" alt="portfolio 3 image" title="portfolio 3 picture" class="img-fluid">
            <div class="portfolio-info">
              <div class="caption">
                <h4>Pagsanjan</h4>
                <p></p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="portfolio-box shadow">
            <img src="images/magda.jpg" alt="portfolio 4 image" title="portfolio 4 picture" class="img-fluid">
            <div class="portfolio-info">
              <div class="caption">
                <h4>Magdalena</h4>
                <p></p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="portfolio-box shadow">
            <img src="images/majay.jpg" alt="portfolio 5 image" title="portfolio 5 picture" class="img-fluid">
            <div class="portfolio-info">
              <div class="caption">
                <h4>Majayjay</h4>
                <p></p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="portfolio-box shadow">
            <img src="images/lumban.JPG" alt="portfolio 6 image" title="portfolio 6 picture" class="img-fluid">
            <div class="portfolio-info">
              <div class="caption">
                <h4>Lumban</h4>
                <p></p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="portfolio-box shadow">
            <img src="images/paete.jpg" alt="portfolio 7 image" title="portfolio 7 picture" class="img-fluid">
            <div class="portfolio-info">
              <div class="caption">
                <h4>Paete</h4>
                <p></p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="portfolio-box shadow">
            <img src="images/pakil.jpg" alt="portfolio 8 image" title="portfolio 8 picture" class="img-fluid">
            <div class="portfolio-info">
              <div class="caption">
                <h4>Pakil</h4>
                <p></p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <div class="portfolio-box shadow">
            <img src="images/pangil.jpg" alt="portfolio 9 image" title="portfolio 9 picture" class="img-fluid">
            <div class="portfolio-info">
              <div class="caption">
                <h4>Pangil</h4>
                <p></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>



  <!-- ///////////////////////////////////////////////////////////////////////////////////////////
                           START SECTION 9 - THE FOOTER  
///////////////////////////////////////////////////////////////////////////////////////////////-->
  <footer class="footer">




    <!-- START THE COPYRIGHT INFO  -->
    <div class="container">
      <div class="row text-center text-white">
        <div class="col-12">
          <div class="footer-bottom__copyright">
            &COPY; Copyright 2024 <a href="#">BSIT 3A-WAM</a><br><br>
          </div </div>
        </div>
      </div>

  </footer>

  <!-- BACK TO TOP BUTTON  -->
  <a href="#" class="shadow btn-primary rounded-circle back-to-top">
    <i class="fas fa-chevron-up"></i>
  </a>




  <script src="assets/js/glightbox.min.js"></script>

  <script type="text/javascript">
    const lightbox = GLightbox({
      'touchNavigation': true,
      'href': 'https://www.youtube.com/watch?v=-KMEf6-zMUA',
      'type': 'video',
      'source': 'youtube', //vimeo, youtube or local
      'width': 900,
      'autoPlayVideos': 'true',
    });
  </script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
</body>

</html>