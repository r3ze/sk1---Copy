<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/stylehome.css">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
          </li>
        </ul>
        <button type="button" class="rounded-pill btn-rounded" onclick="window.location.href='barangay-login.php'">
    Login
    <span><i class="fas fa-arrow-right"></i></span>
</button>

      </div>
    </div>
  </nav>

<!-- /////////////////////////////////////////////////////////////////////////////////////////////////
                            START SECTION 2 - THE INTRO SECTION  
/////////////////////////////////////////////////////////////////////////////////////////////////////-->

<section id="home" class="intro-section">
  <div class="container">
    <div class="row align-items-center text-white">
      <!-- START THE CONTENT FOR THE INTRO  -->
      <div class="col-md-6 intros text-start">
        <h1 class="display-2">
          <span class="display-2--intro" style="font-size: 50px; color: #ffffff;">Sangguniang </span>
          <span class="display-2--intro" style="font-size: 50px; color: #dc3545;"> Kabataan</span>
          <span class="display-2--description lh-base" style="color: #fbd218;">
            Often abbreviated as SK, is a youth council in the Philippines that represents the interests and concerns of young people at the local level. It serves as a platform for youth empowerment, participation in governance, and community development. Comprised of elected youth leaders aged 18 to 30, the SK plays a vital role in promoting youth welfare, initiating projects, and advocating for policies that address the needs of young Filipinos within their respective barangays or villages.
          </span>
        </h1>
        <button type="button" class="rounded-pill btn-rounded" onclick="window.location.href='barangay-add.php'">
    Register here
    <span><i class="fas fa-arrow-right"></i></span>
</button>

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
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#ffffff" fill-opacity="1" d="M0,160L48,176C96,192,192,224,288,208C384,192,480,128,576,133.3C672,139,768,213,864,202.7C960,192,1056,96,1152,74.7C1248,53,1344,107,1392,133.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
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
    <div class="row mission" id="mission" >
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
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#fff" fill-opacity="1" d="M0,96L48,128C96,160,192,224,288,213.3C384,203,480,117,576,117.3C672,117,768,203,864,202.7C960,203,1056,117,1152,117.3C1248,117,1344,203,1392,245.3L1440,288L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path></svg>
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
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="#fff" fill-opacity="1" d="M0,96L48,128C96,160,192,224,288,213.3C384,203,480,117,576,117.3C672,117,768,203,864,202.7C960,203,1056,117,1152,117.3C1248,117,1344,203,1392,245.3L1440,288L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>
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
          </div
        </div>
      </div>
    </div>

</footer>

<!-- BACK TO TOP BUTTON  -->
<a href="#" class="shadow btn-primary rounded-circle back-to-top">
  <i class="fas fa-chevron-up"></i>
</a>



   
    <script src="assets/vendors/js/glightbox.min.js"></script>

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
     <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>