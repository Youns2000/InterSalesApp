<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 

   $sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';
  
    $data=array();
    $db = include 'db_mysql.php';
    try { 
      if(isset($_POST['modifmdp'])){
              $db = include 'db_mysql.php';
                $mail = $_SESSION['email'];
                $stmt = $db->prepare($sql_data);
                $stmt->execute(array($mail));
                $data= $stmt->fetchAll(PDO::FETCH_ASSOC);
                unset($db);
                if(count($data)==1 and password_verify($_POST['mdpactu'], $data[0]['mdp']) and $_POST['newmdp']==$_POST['confirm']){
                  $dbco = include 'db_mysql.php';
                  $sth = $dbco->prepare('UPDATE comptes SET mdp=? WHERE email=?');
                  $sth->execute(array(password_hash($_POST['newmdp'], PASSWORD_DEFAULT),$_SESSION['email']));
                  unset($dbco);
                }
                else{
                  echo "mdp incorect";
                }
            } 
      } catch (Exception $e) {
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
  </head>
  <body>

<!----------------------------------------------------------- NAVBAR-HAUT--------------------------------------------------------------------->
   <nav class="navbar navbar-dark fixed-top bg-dark  p-0 shadow navbar-expand-md">
          <a class="navbar col-sm-0 col-md-0 mr-0" href=""></a> 
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="true" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

          <div class="collapse navbar-collapse col-md-0" id="navbarCollapse">
            <!-- <ul class="navbar p-0"> -->
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <span class="navbar-toggler-icon"></span>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <button type="button" data-backdrop="false" data-toggle="modal" data-target="#modal_modif_mdp" class="dropdown-item">Modifier mot de passe</button>
                </div>
              </div>
            <!-- /////////////////MODAL/////////////////////// -->
              <form method="post">
                    <div class="modal fade" id="modal_modif_mdp" role="dialog">
                      <div class="modal-dialog modal-dialog-centered">
                      
                        <!-- Modal content-->
                        <div class="modal-content">

                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>

                          <div class="modal-body">
                            <p>Mot de passe actuel : <input type="password" id="mdpactu" name="mdpactu" type="text"/></p>
                            <p>Nouveau mot de passe : <input type="password" id="newmdp" name="newmdp" type="text"/></p>
                            <p>confirmer mot de passe : <input type="password" id="confirm" name="confirm" type="text"/></p>
                          </div>

                          <div class="modal-footer">
                            <button name = "modifmdp" type="submit" class="btn btn-default">OK</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </form>
                  <!-- /////////////////END_MODAL/////////////////////// -->
                  <a class="navbar col-sm-0 col-md-2 mr-0" href=""></a> 
                
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
                <ul class="navbar-nav px-3">
                <a class="nav-link" href="calendrier.php">
                  <span data-feather="calendar"></span>
                  <br/>Agenda
                </a>
                </ul>
                <!-- <a class="nav-link" href="objectifs.php">
                  <span data-feather="bar-chart-2"></span>
                  <br/>Objectifs
                </a> -->
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
    
          </div>
  </nav>


<div class="container-fluid">
  <div class="row"><!--<span class="sr-only">(current)</span>-->
    


   
      	<br/>
      	<br/>
      	<br/>
      	<br/>
      	<br/>

    	<iframe src="https://calendar.google.com/calendar/embed?height=600&amp;wkst=1&amp;bgcolor=%23ffffff&amp;ctz=Europe%2FParis&amp;src=MGtyZHIyZTZnYXVtY2IydHU4Z2V0ZGFxcG9AZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&amp;color=%23402175" style="border:solid 1px #777" width="1300" height="810" frameborder="0" scrolling="yes"></iframe>

    
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
        <script src="dashboard.js"></script>
      </body>
</html>