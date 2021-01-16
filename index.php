<?php 
session_start();
if(isset($_POST['oui'])) { 
  $_SESSION['index'] = true;
  header("Location:log.php"); 
} 

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Younes Benreguieg">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Integral Sales App</title>
    
    <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/product/">

    <!-- Bootstrap core CSS -->
<link href="/docs/4.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

    <!-- Favicons -->
<link rel="apple-touch-icon" href="/docs/4.5/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
<link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
<link rel="manifest" href="/docs/4.5/assets/img/favicons/manifest.json">
<link rel="mask-icon" href="/docs/4.5/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
<link rel="icon" href="/docs/4.5/assets/img/favicons/favicon.ico">
<link rel="icon" href="logos/logo.JPG">
<meta name="msapplication-config" content="/docs/4.5/assets/img/favicons/browserconfig.xml">
<meta name="theme-color" content="#563d7c">


    <style>
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
      div#zone_bouton {
        width : 50%;
        margin-left:auto;
        margin-right:auto;
        margin-top : 20%;
        border : 2px solid red;
        text-align:center;
      }

      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/product.css" rel="stylesheet">
  </head>
  <body>

<form method="post">
<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
  <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
    <div class="bg-dark shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
<img class="bd-placeholder-img" style="width: 100%; height: 300px; border-radius: 21px 21px 0 0;" src="logos/Images_Page_Accueil/img_png/img00.png"/>
    </div>
  </div>

  <div class="bg-dark mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center text-white overflow-hidden">
    <div class="bg-light shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
      <img class="bd-placeholder-img" style="width: 100%; height: 100%; border-radius: 21px 21px 0 0;" src="logos/Images_Page_Accueil/img_png/img01.png"/>
    </div>
  </div>
</div>

<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
  <div class="bg-dark mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
    <div class="bg-dark shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
<img class="bd-placeholder-img" style="width: 100%; height: 300px; border-radius: 21px 21px 0 0;" src="logos/Images_Page_Accueil/img_png/img02.png"/>
    </div>
  </div>

  <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center text-white overflow-hidden">
    <div class="bg-light shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
      <img class="bd-placeholder-img" style="width: 100%; height: 100%; border-radius: 21px 21px 0 0;" src="logos/Images_Page_Accueil/img_png/img10.png"/>
    </div>
  </div>
</div>

<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
  <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
    <div class="bg-dark shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
<img class="bd-placeholder-img" style="width: 100%; height: 300px; border-radius: 21px 21px 0 0;" src="logos/Images_Page_Accueil/img_png/img11.png"/>
    </div>
  </div>

  <div class="bg-dark mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center text-white overflow-hidden">
    <div class="bg-light shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
      <img class="bd-placeholder-img" style="width: 100%; height: 100%; border-radius: 21px 21px 0 0;" src="logos/Images_Page_Accueil/img_png/img12.png"/>
    </div>
  </div>
</div>

<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
  <div class="bg-dark mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
    <div class="bg-dark shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
<img class="bd-placeholder-img" style="width: 100%; height: 300px; border-radius: 21px 21px 0 0;" src="logos/Images_Page_Accueil/img_png/img20.png"/>
    </div>
  </div>

  <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center text-white overflow-hidden">
    <div class="bg-light shadow-sm mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;">
      <img class="bd-placeholder-img" style="width: 100%; height: 100%; border-radius: 21px 21px 0 0;" src="logos/Images_Page_Accueil/img_png/img21.png"/>
    </div>
    <button type="submit" name="oui" class="btn btn-light btn-sm" style="width: 3px; height: 3px;"/>
  </div>

</div>
</form>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="/docs/4.5/assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script></body>
</html>
