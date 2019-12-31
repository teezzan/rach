<?php
    require_once("common.php");
    $preflang = getlang();
    require_once("lang/lang.$preflang.php");
?>
<!DOCTYPE html>
<html lang="<?php echo $preflang ?>">
<head>
<meta charset="utf-8">
<title>TedBond - <?php echo $lang['home'] ?></title>
<link rel="stylesheet" href="css/normalize-1.1.3.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/mdb.min.css">
<link rel="stylesheet" href="fontawesome/css/all.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/styles.css">
<link rel="stylesheet" href="css/ui-lightness/jquery-ui-1.10.4.custom.min.css">
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/jquery-ui-1.10.4.custom.min.js"></script>
<script>
    // this sets the autocomplete handler for each module's text input field
    $(document).ready( function () {
        $(":text").each( function () {
            var myid = $(this).attr("id");
            if (myid) {
                var moddir = myid.replace(/_search$/, "");
                $("#"+myid).autocomplete({
                        source: "modules/"+moddir+"/search/suggest.php",
                });
            }
        });
    });
</script>
</head>

<body>
<!-- <div id="rachel" style="position: relative;"> -->
    <!-- Rachel -->
    <!-- <div id="ip"> -->
    <?php
        # some notes to prevent future regression:
        # the PHP suggested gethostbyname(gethostname())
        # brings back the unhelpful 127.0.0.1 on RPi systems,
        # as well as slowing down some Windows installations
        # with a DNS lookup. $_SERVER["SERVER_ADDR"] will just
        # display what's in the user's address bar, so also
        # not useful - using ifconfig/ipconfig is probably
        # the way to go, but may require some tweaking

        // echo "<b>" . $lang['server-address'] . "</b><br>\n";
        if (preg_match("/^win/i", PHP_OS)) {
            # under windows it's ipconfig
            $output = shell_exec("ipconfig");
            preg_match("/IPv4 Address.+?: (.+)/", $output, $match);
            if (isset($match[1])) { 
                // echo "$match[1]<br>\n"; 
            }
        } else if (preg_match("/^darwin/i", PHP_OS)) {
            # OSX is unix, but it's a little different
            exec("/sbin/ifconfig", $output);
            preg_match("/en0.+?inet (.+?) /", join("", $output), $match);
            if (isset($match[1])) { 
                // echo "$match[1]<br>\n"; 
            }
        } else {
            # most likely linux based - so ifconfig should work
            exec("/sbin/ifconfig", $output);
            preg_match("/eth0.+?inet addr:(.+?) /", join("", $output), $match);
            if (isset($match[1])) { echo "LAN: $match[1]<br>\n"; }
            preg_match("/wlan0.+?inet addr:(.+?) /", join("", $output), $match);
            if (isset($match[1])) { echo "WIFI: $match[1]<br>\n"; }
        }

    ?>
    <!-- <div style="position: absolute; font-size: small; bottom: 6px; right: 8px;">
    <a href="admin.php" style="color: #999;"><?php echo $lang['admin'] ?></a> |
    <a href="stats.php" style="color: #999;"><?php echo $lang['stats'] ?></a> |
    <a href="version.php" style="color: #999;"><?php echo $lang['version'] ?></a>
    </div>
    </div>
</div> -->
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
          <a class="nav-link" href="index.php">Home
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
                <a class="nav-link" href="admin.php" style="color: #999;"><?php echo $lang['admin'] ?></a>
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


<!-- <div class="menubar cf">
    <ul>
    <li><a href="index.php"><?php echo strtoupper($lang['home']) ?></a></li>
    <li><a href="about.html"><?php echo strtoupper($lang['about']) ?></a></li>
    </ul>
    
</div> -->

<!-- <div id="content"> -->
    <!-- <div class="container">
        <div class="row mt-4 mb-4">
            <div class="col-md-12 mr-auto ml-auto">
                <div class="row action">
                    
                </div>    
            </div>
        </div>
    </div>
</div> -->
<div class="container">
<div class="row mt-5">
<?php

    $fsmods = getmods_fs();

    # if there were any modules found in the filesystem
    if ($fsmods) {

        # get a list from the databases (where the sorting
        # and visibility is stored)
        $dbmods = getmods_db();

        # populate the module list from the filesystem 
        # with the visibility/sorting info from the database
        foreach (array_keys($dbmods) as $moddir) {
            if (isset($fsmods[$moddir])) {
                $fsmods[$moddir]['position'] = $dbmods[$moddir]['position'];
                $fsmods[$moddir]['hidden'] = $dbmods[$moddir]['hidden'];
            }
        }

        # custom sorting function in common.php
        uasort($fsmods, 'bypos');

        # whether or not we were able to get anything
        # from the DB, we show what we found in the filesystem
        $modcount = 0;
        foreach (array_values($fsmods) as $mod) {
            if ($mod['hidden'] || $mod['nohtmlf']) { continue; }
            $dir  = $mod['dir'];
            $moddir  = $mod['moddir'];
            if (file_exists("$mod[dir]/index.htmlf")) {
                # old name - deprecated
                // include "$mod[dir]/index.htmlf";
                include "$mod[dir]/rachel-index.php";
                
            } else {
                # new name - less confusing, and
                # will get syntax highlighting in editors
                include "$mod[dir]/rachel-index.php";
            }
            ++$modcount;
        }

    }

    if ($modcount == 0) {
        echo $lang['no-mods-error'];
    }

?>
</div>
</div>

    <a href="file_upload.html">
        <div class="add-module">
            <i class="fa fa-plus"></i>
        </div>
    </a>

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
  <a href="index.php">Tedprimehub</a>
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
