<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TedHub</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/mdb.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
      <!--Main Navigation-->
  <header>

<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-dark indigo">
  <div class="container">
    <a class="navbar-brand" href="#"><img src="images/logo.jpg" style="heigt: 70px; width: 100px;"></a>

    <!-- Collapse button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
      aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible content -->
    <div class="collapse navbar-collapse" id="basicExampleNav">

      <!-- Links -->
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="home.php">Home
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Contact Us</a>
        </li>
      </ul>
      <!-- Links -->

      <ul class="navbar-nav ml-auto">
            <!-- <li class="nav-item">
                <a href="#" class="nav-link"><?php echo "<b>" . $lang['server-address'] . "</b><br>\n";  echo "$match[2]<br>\n"; ?> </a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link" href="#">Stats</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin.php" style="color: #999;">Admin</a>
            </li>
        </ul>
    </div>
    <!-- Collapsible content -->

  </div>
  <!-- Additional container -->

</nav>
<!--/.Navbar-->

</header>
<!--Main Navigation-->

<!--Main layout-->
<main class="mt-5">

<!--Main container-->
<div class="container">

  <!--Grid row-->
  <div class="row">

    <!--Grid column-->
    <div class="col-md-7 mb-4">

      <div class="view overlay z-depth-1-half">
        <img src="images/students.jpg" class="card-img-top" alt="" style="height: 400px;">
        <div class="mask rgba-white-light"></div>
      </div>

    </div>
    <!--Grid column-->

    <!--Grid column-->
    <div class="col-md-5 mb-4">

      <h1>For every student every classroom. Real results.</h1>
      <hr>
      <p>We're an organization with the mission to provide quality, world-class education for anyone, anywhere.
        <br>
        Learners, and teachers alike.
      </p>
      <a href="home.php" class="btn btn-indigo">Start Here</a>

    </div>
    <!--Grid column-->
  </div>
  <!--Grid row-->
  
  <div class="container mt-5 mb-5">
    <h1 class="text-center">How Tedprimehub works</h1>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="text-center">
                <img src="images/personalized.svg">
            </div>
            <h3 class="text-center">Personalized Learning</h3>
            <p class="text-center">Students practice at their own pace, first filling in gaps in their understanding and
            then accelerating their learning</p>
        </div>
        <div class="col-md-4">
            <div class="text-center">
                <img src="images/trusted.svg">
            </div>
            <h3 class="text-center">Trusted Content</h3>
            <p class="text-center">Created by experts, Tedprimehub’s library of trusted practice and lessons covers math, 
            science, and more. Always free for learners and teachers.</p>
        </div>
        <div class="col-md-4">
        <div class="text-center">
                <img src="images/tools.svg">
            </div>
            <h3 class="text-center">Tools to empower teachers</h3>
            <p class="text-center">With TedPrimeHub, teachers can identify gaps in their students’ 
            understanding, tailor instruction, and meet the needs of every student.</p>
        </div>
    </div>
  </div>

  <!--Grid row-->
  <div class="row">

    <!--Grid column-->
    <div class="col-lg-4 col-md-12 mb-4">

      <!--Card-->
      <div class="card">
        <!--Card content-->
        <div class="card-body">
          <!--Title-->
          <h4 class="card-title">W3Schools</h4>
          <!--Text-->
          <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
            card's content.</p> -->
          <a href="home.php" class="btn btn-indigo">Get started</a>
        </div>
      </div>
      <!--/.Card-->
    </div>
    <!--Grid column-->
    <!--Grid column-->
    <div class="col-lg-4 col-md-12 mb-4">

      <!--Card-->
      <div class="card">
        <!--Card content-->
        <div class="card-body">
          <!--Title-->
          <h4 class="card-title">Music Theory</h4>
          <!--Text-->
          <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
            card's content.</p> -->
          <a href="home.php" class="btn btn-indigo">Get Started</a>
        </div>
      </div>
      <!--/.Card-->
    </div>
    <!--Grid column-->
    <!--Grid column-->
    <div class="col-lg-4 col-md-12 mb-4">

      <!--Card-->
      <div class="card">
        <!--Card content-->
        <div class="card-body">
          <!--Title-->
          <h4 class="card-title">Wikipedia</h4>
          <!--Text-->
          <!-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
            card's content.</p> -->
          <a href="home.php" class="btn btn-indigo">Get started</a>
        </div>
      </div>
      <!--/.Card-->
    </div>
    <!--Grid column-->
  </div>
  <!--Grid row-->

</div>
<!--Main container-->

</main>
<!--Main layout-->

<!-- Footer -->
<footer class="page-footer font-small indigo pt-4 mt-4">

<!-- Footer Links -->
<div class="container text-center text-md-left">

  <!-- Grid row -->
  <div class="row">

    <!-- Grid column -->
    <div class="col-md-6 mt-md-0 mt-3">

      <!-- Content -->
      <img src="images/logo.jpg" class="img-fluid" alt="TedPrimeHub" style="width: 50%; height: 100%;">
      <p>Our mission is to provide quality, world class education to everyone, anywhere.</p>

    </div>
    <!-- Grid column -->

    <hr class="clearfix w-100 d-md-none pb-3">

    <!-- Grid column -->
    <div class="col-md-3 mb-md-0 mb-3">

      <!-- Links -->
      <ul class="list-unstyled">
        <li>
          <a href="home.php">Home</a>
        </li>
        <li>
          <a href="about.php">About</a>
        </li>
        <li>
          <a href="contact.php">Contact Us</a>
        </li>
      </ul>
    </div>
    <!-- Grid column -->

    <!-- Grid column -->
    <div class="col-md-3 mb-md-0 mb-3">

      <!-- Links -->
      <ul class="list-unstyled">
        <li>
          <a href="admin.php">Admin</a>
        </li>
        <li>
          <a href="#!">stats.php</a>
        </li>
      </ul>

    </div>
    <!-- Grid column -->

  </div>
  <!-- Grid row -->

</div>
<!-- Footer Links -->

<!-- Copyright -->
<div class="footer-copyright text-center py-3">© 2018 Copyright:
  <a href="https://mdbootstrap.com/bootstrap-tutorial/"> MDBootstrap.com</a>
</div>
<!-- Copyright -->

</footer>
<!-- Footer -->
    <script src="js/jquery.min.js">
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js">
    <script src="js/mdb.min.js">
</body>
</html>