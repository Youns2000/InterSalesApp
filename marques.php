<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 
  $_SESSION['currentPage'] = "marques";
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Younes Benreguieg">
    <title>Integral Sales App</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="apple-touch-icon" href="logos/logo.JPG" sizes="180x180">
    <link rel="icon" href="logos/logo.JPG" sizes="32x32" type="image/png">
    <link rel="icon" href="logos/logo.JPG" sizes="16x16" type="image/png">
    <link rel="manifest" href="favicons/manifest.json">
    <link rel="mask-icon" href="favicons/safari-pinned-tab.svg" color="#563d7c">
    <link rel="icon" href="logos/logo.JPG">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <meta name="msapplication-config" content="favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c">
    <link href="css/style_menu.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">

    <link href="my_css.css" rel="stylesheet">

    <style type="text/css">
      .colonne {
        margin: 15px 25px 0 25px;
        padding: 0;
      }
      .colonne:last-child {
        padding-bottom: 60px;
      }
      .colonne::after {
        content: '';
        clear: both;
        display: block;
      }
      .colonne div {
        position: relative;
        float: left;
        width: 200px;
        height: 150px;
        margin: 0 0 0 35px;
        padding: 0;
      }
      .colonne div:first-child {
        margin-left: 0;
      }
      /*div {
        width: 200px;
        height: 150px;
        margin: 0;
        padding: 0;
        background: #F4F4F4;
        overflow: hidden;
      }*/
      .zoom div img {
        -webkit-transform: scale(1);
        transform: scale(1);
        -webkit-transition: .3s ease-in-out;
        transition: .3s ease-in-out;
      }
      .zoom div:hover img {
        -webkit-transform: scale(1.3);
        transform: scale(1.3);
      }
      main{
        margin-top: 4%;
      }
    </style>
  </head>
  <body>

<!----------------------------------------------------------- NAVBAR-HAUT--------------------------------------------------------------------->
<header>
  <?php include("header.php"); ?> 
</header>
<!----------------------------------------------------------- NAVBAR-GAUCHE--------------------------------------------------------------------->


<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">


    <div class="row align-items-center zoom colonne">

      <div class="col-lg-3">
          <div class="card mb-4 text-white bg-card-blanc shadow-sm h-100 justify-content-center">
              <a href="https://www.ammann.com/" onclick="window.open(this.href); return false;"><img width = "90%" src="logos/ammann.png" /></a>
          </div>
      </div>

      <div class="col-md-3">
        <div class="card mb-4 text-white bg-card-blanc shadow-sm h-100 justify-content-center">
          <a href="https://www.terex.com/" onclick="window.open(this.href); return false;"><img width = "80%" src="logos/terex.jpg" /></a>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card mb-4 text-white bg-card-blanc shadow-sm h-100 justify-content-center">
          <a href="https://www.powerscreen.com/" onclick="window.open(this.href); return false;"><img width = "60%" src="logos/powerscreen.jpg" /></a>
        </div>
      </div>
    </div>

      <div style="margin-top: 7%;" class="row align-items-center zoom colonne">
      <div class="col-md-3">
        <div class="card mb-4 text-white bg-card-blanc shadow-sm h-100 justify-content-center">
          <a href="https://www.shaoruiheavy.com/?lang=en" onclick="window.open(this.href); return false;"><img width = "60%" src="logos/srh.png" /></a>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card mb-4 text-white bg-card-blanc shadow-sm h-100 justify-content-center">
          <a href="http://www.hydrog.com/" onclick="window.open(this.href); return false;"><img width = "80%" src="logos/hydrog.png" /></a>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card mb-4 text-white bg-card-blanc shadow-sm h-100 justify-content-center">
          <a href="https://www.maprein.com/en/" onclick="window.open(this.href); return false;"><img width = "90%" src="logos/maprein.png" /></a>
        </div>
      </div>

    </div>


</main>






<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
        <script src="dashboard.js"></script></body>
        <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</html>
