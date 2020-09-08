<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 
  $sql_engins='SELECT id, Categorie, Marque , Type, Ref, Prix, prix_transport, Origine, ConfBase
          FROM engins
          ORDER BY Marque;';
  $engins=array();
  $db = include 'db_mysql.php';
  try{
    $stmt = $db->prepare($sql_engins);
    $stmt->execute(array());
    $engins = $stmt->fetchAll();
  }
  catch (Exception $e) {
      print "Erreur ! " . $e->getMessage() . "<br/>";
  }
  ?>



<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Younes Benreguieg">
    <title>Integral Sales App</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="logos/logo.JPG" sizes="180x180">
    <link rel="icon" href="logos/logo.JPG" sizes="32x32" type="image/png">
    <link rel="icon" href="logos/logo.JPG" sizes="16x16" type="image/png">
    <link rel="manifest" href="favicons/manifest.json">
    <link rel="mask-icon" href="favicons/safari-pinned-tab.svg" color="#563d7c">
    <link rel="icon" href="logos/logo.JPG">
    <meta name="msapplication-config" content="favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        padding-top:100px;
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
      .sidebar{
        min-height: : 330px;
        max-height: 330px;
      }

      body{
        margin:0;
        padding:0;
        background: url(logos/fond_site.jpg) no-repeat center fixed;
        -webkit-background-size: cover; /* pour anciens Chrome et Safari */
        background-size: cover; /* version standardisée */
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
  </head>
  <body>
  <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <!--<span data-feather="home"></span>-->
      <!--<img class="mb-0" src="logos/logo integral_origin.jpg" width="212" height="100">-->
      <a class="navbar-brand col-sm-0 col-md-0 mr-0" href="">
        </a>
    
      <a class="nav-link" href="marketing.php?categ=Postes%20Premium">
        <span data-feather="shopping-cart" align="center"></span>
        <br/>Marketing 
      </a>
    
    <a class="nav-link" href="newClient.php">
      <span data-feather="users"></span>
      <br/>Nouveau Client
    </a>
    <a class="nav-link" href="proforma.php?categ=Postes%20Premium">
      <span data-feather="file"></span>
      <br/>Proforma 
    </a>
    <a class="nav-link" href="bon_de_commande.php">
      <span data-feather="file-text"></span>
      <br/>Bon de Commande
    </a>    
    <a class="nav-link" href="calendrier.php">
      <span data-feather="calendar"></span>
      <br/>Agenda<span align="center"class="sr-only">(current)</span>
    </a>
    <ul class="navbar-nav px-3">
    <a class="nav-link" href="">
      <span data-feather="bar-chart-2"></span>
      <br/>Objectifs
    </a>
    </ul>
    <a class="nav-link" href="rapports.php">
      <span data-feather="file-text"></span>
      <br/>Rapports
    </a>
    <ul class="navbar-nav px-3">
      <li class="nav-item text-nowrap">
        <br/><p style="color:#49FF00">Session Ouverte<br/>
        <?php echo $_SESSION['prenom'] ." ".$_SESSION['nom']?><br/>
        <a style="color:#FF0000" href="deconnection.php">Déconnection</a></p>
      </li>
    </ul>
  </nav>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 mb-4">
    <div class="container">
      <div class="row">
            <div class="col-lg-10">
      <div class="card mb-4 shadow-sm">
                  <div class="table-responsive">
                    <table class="table table-striped table-sm">
                       <thead>
                        <tr>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while($i<count($engins)){?>
                        <tr>
                          <th scope="row">2</th>
                          <?php for ($j=0; $j < $engins[$j]['Marque']; $j++) { 
                            
                          } ?>

                        <tr>
                          <th scope="row">2</th>
                          <td></td>
                          <td>Thornton</td>
                          <td>@fat</td>
                          <td>Jacob</td>
                          <td>Thornton</td>
                          <td>@fat</td>
                          <td>Jacob</td>
                          <td>Thornton</td>
                          <td>@fat</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
      </div>
    </div>
  </div>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
        <script src="dashboard.js"></script></body>
</html>