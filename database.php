<?php
  session_start();
  if (!isset($_SESSION['email']) or $_SESSION['statut']!="admin"){
    header('Location: index.php');
  } 
  else{
  $sql_comptes='SELECT Statut,nom,prenom,mobile,email,Secteur,mdp
          FROM comptes
          ORDER BY id;';
  $sql_proformas='SELECT DateCreation , EmisPar, Client , PortDest , Engins
          FROM proformas
          ORDER BY id;';
  $sql_clients='SELECT NomSociete,CodeClient,NIF,Activite1,Activite2,Adresse,CodePostal,Ville,Wilaya,Pays,TelFixe,NomResp1,EmailResp1,PortableResp1,NomResp2,EmailResp2,PortableResp2
          FROM clients
          ORDER BY id;';
  $sql_categ='SELECT CatégoriesProduits
          FROM others
          ORDER BY id;';
  $sql_engins='SELECT Categorie, Marque , Type
          FROM engins
          ORDER BY id;';

          $categ=array();
          $engins=array();
          $clients=array();
          $proformas=array();
          $comptes=array();
          $db = include 'db_mysql.php';

          try {

             $stmt = $db->prepare($sql_categ);
             $stmt->execute(array());
             $categ= $stmt->fetchAll();

             $stmt2 = $db->prepare($sql_engins);
             $stmt2->execute(array());
             $engins= $stmt2->fetchAll();

             $stmt3 = $db->prepare($sql_clients);
             $stmt3->execute(array());
             $clients= $stmt3->fetchAll();

             $stmt4 = $db->prepare($sql_proformas);
             $stmt4->execute(array());
             $proformas= $stmt4->fetchAll();

             $stmt5 = $db->prepare($sql_comptes);
             $stmt5->execute(array());
             $comptes= $stmt5->fetchAll();
             unset($db);

          } catch (Exception $e) {
             print "Erreur ! " . $e->getMessage() . "<br/>";
          }
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
        padding-top: 800px;
        min-height: 250px;
        max-height: 250px;
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
      <?php if($_SESSION['statut']=="admin"){?>
        <ul class="navbar-nav px-3">
        <a class="nav-link" href="database.php">
          <span data-feather="database"></span>
          <br/>Database
        </a>
        </ul>
      <?php }else{?>
        <a class="navbar-brand col-sm-0 col-md-2 mr-0" href="">
        </a>
      <?php }?>
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
      <br/>Proforma <span align="center"class="sr-only">(current)</span>
    </a>
    <a class="nav-link" href="bon_de_commande.php">
      <span data-feather="file-text"></span>
      <br/>Bon de Commande
    </a>
    <a class="nav-link" href="projets.php">
      <span data-feather="layers"></span>
      <br/>Projets
    </a>
    <a class="nav-link" href="calendrier.php">
      <span data-feather="calendar"></span>
      <br/>Agenda
    </a>
    <a class="nav-link" href="objectifs.php">
      <span data-feather="bar-chart-2"></span>
      <br/>Objectifs
    </a>
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

<div class="container-fluid">
  <div class="row"><!--<span class="sr-only">(current)</span>-->
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
                <button class="nav-item" type="submit"><a class="nav-link" href="database.php?database=comptes" name="comptes">Comptes</a></button>
                <button class="nav-item" type="submit"><a class="nav-link" href="database.php?database=clients" name="comptes">Clients</a></button>
                <button class="nav-item" type="submit"><a class="nav-link" href="database.php?database=proformas" name="comptes">Proformas</a></button>
                <button class="nav-item" type="submit"><a class="nav-link" href="database.php?database=engins" name="comptes">Engins</a></button>
        <ul/>
        <br/>
      </div>
    </nav>


    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="container">
        <div class="row">

            <div class="col-lg-12">
              <div class="card mb-4 shadow-sm ">
                    <table class="table table-striped table-sm col-lg-12">

                  <?php switch($_GET['database']){
                    case "comptes":?>
                    <thead>
                        <tr>
                          <th>Statut</th><th>Nom</th><th>Prénom</th><th>Mobile</th><th>Email</th><th>Secteur</th><th>MDP</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $i=0;
                          while($i<count($comptes)){
                        ?>
                        <tr>
                          <td><?php echo $comptes[$i]['Statut'] ?></td>
                          <td><?php echo $comptes[$i]['nom'] ?></td>
                          <td><?php echo $comptes[$i]['prenom'] ?></td>
                          <td><?php echo $comptes[$i]['mobile'] ?></td>
                          <td><?php echo $comptes[$i]['email'] ?></td>    
                          <td><?php echo $comptes[$i]['Secteur'] ?></td>
                          <td><?php echo $comptes[$i]['mdp'] ?></td>
                        </tr>
                      <?php $i+=1;}?>
                      </tbody>


                  <?php break;
                      case "clients":?>
                       <thead>
                        <tr>
                          <th>NomSociete</th>
                          <th>CodeClient</th>
                          <th>NIF</th>
                          <th>Activite1</th>
                          <th>Activite2</th>
                          <th>Adresse</th>
                          <th>CodePostal</th>
                          <th>Ville</th>
                          <th>Wilaya</th>
                          <th>Pays</th>
                          <th>TelFixe</th>
                          <th>NomResp1</th>
                          <th>EmailResp1</th>
                          <th>PortableResp1</th>
                          <th>NomResp2</th>
                          <th>EmailResp2</th>
                          <th>PortableResp2</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $i=0;
                          while($i<count($clients)){
                        ?>
                        <tr>
                          <td><?php echo $clients[$i]['NomSociete'] ?></td>
                          <td><?php echo $clients[$i]['CodeClient'] ?></td>
                          <td><?php echo $clients[$i]['NIF'] ?></td>
                          <td><?php echo $clients[$i]['Activite1'] ?></td>
                          <td><?php echo $clients[$i]['Activite2'] ?></td>    
                          <td><?php echo $clients[$i]['Adresse'] ?></td>
                          <td><?php echo $clients[$i]['CodePostal'] ?></td>
                          <td><?php echo $clients[$i]['Ville'] ?></td>
                          <td><?php echo $clients[$i]['Wilaya'] ?></td>
                          <td><?php echo $clients[$i]['Pays'] ?></td>
                          <td><?php echo $clients[$i]['TelFixe'] ?></td>
                          <td><?php echo $clients[$i]['NomResp1'] ?></td>    
                          <td><?php echo $clients[$i]['EmailResp1'] ?></td>
                          <td><?php echo $clients[$i]['PortableResp1'] ?></td>
                          <td><?php echo $clients[$i]['NomResp2'] ?></td>    
                          <td><?php echo $clients[$i]['EmailResp2'] ?></td>
                          <td><?php echo $clients[$i]['PortableResp2'] ?></td>
                        </tr>
                        <?php $i+=1;}?>
                      </tbody>
                  <?php break;
                      case "proformas":?>
                       <thead>
                        <tr>
                          <th>Numéro</th>
                          <th>Date</th>
                          <th>Produits</th>
                          <th>Options</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>ifdqsh</td>
                          <td>dqqfdz</td>
                          <td>fzdqz</td>
                          <td>zqfqd</td>
                        </tr>
                      </tbody>

                  <?php break;
                      case "engins":?>
                       <thead>
                        <tr>
                          <th>Numéro</th>
                          <th>Date</th>
                          <th>Produits</th>
                          <th>Options</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>ifdqsh</td>
                          <td>dqqfdz</td>
                          <td>fzdqz</td>
                          <td>zqfqd</td>
                        </tr>
                      </tbody>

                  <?php default:
                      }?>
                  </table>
              </div>
            </div>


          </div>
        </div>
      </div>
    </main>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
        <script src="dashboard.js"></script></body>
</html>
