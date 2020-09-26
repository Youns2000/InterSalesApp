<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 
  else{
    require('mail_php.php');
    require('pdf_rapports.php');

  $sql_categ='SELECT CatégoriesProduits
          FROM others
          ORDER BY id;';
  $sql_engins='SELECT Categorie, Marque , Type
          FROM engins
          ORDER BY id;';
  $sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';

  $sql_projets = 'SELECT id,nom,code,client,bft,dateCreation,description,etat
          FROM projets
          ORDER BY id;';

  $sqlRapportEnregistrer = 'INSERT INTO `rapports` (`id`, `projet`, `commandes`, `visitesClient`, `offres` , `remarques`) VALUES (NULL, ?, ?, ?, ?, ?);';
    
          $data=array();
          $categ=array();
          $engins=array();
          $projets=array();
          $db = include 'db_mysql.php';

          try {
             $stmt = $db->prepare($sql_categ);
             $stmt->execute(array());
             $categ= $stmt->fetchAll();

             $stmt2 = $db->prepare($sql_engins);
             $stmt2->execute(array());
             $engins= $stmt2->fetchAll();

             $stmt7 = $db->prepare($sql_projets);
             $stmt7->execute(array());
             $projets = $stmt7->fetchAll(); 
             unset($db);

            if(isset($_POST['visualiserButton'])){
                          $_SESSION['commandes'] = $_POST['commandes'];
                          $_SESSION['visites'] = $_POST['visites'];
                          $_SESSION['offres'] = $_POST['offres'];
                          $_SESSION['remarques'] = $_POST['remarques'];
                          getRapportPDF();
                          //exit();             
            }       
            else if(isset($_POST['envoyermail'])){
              $str = "";
              if(isset($_POST['boxmail_1'])) $str = $str.","."Azeddine@integral.fr"; 
              if(isset($_POST['boxmail_2'])) $str = $str.",".$_POST['text2']; 
              if(isset($_POST['boxmail_3'])) $str = $str.",".$_POST['text3']; 
              if(isset($_POST['boxmail_4'])) $str = $str.",".$_POST['text4']; 
              if(isset($_POST['boxmail_1']) or isset($_POST['boxmail_2']) or isset($_POST['boxmail_3']) or isset($_POST['boxmail_4'])){
                $_SESSION['commandes'] = $_POST['commandes'];
                $_SESSION['visites'] = $_POST['visites'];
                $_SESSION['offres'] = $_POST['offres'];
                $_SESSION['remarques'] = $_POST['remarques'];
                sendmail($str,'Rapport','rapport.pdf',true,getRapportCode());
                $db = include 'db_mysql.php';
                $stmtenr = $db->prepare($sqlRapportEnregistrer);
                $p = 0;
                for (; $p < count($projets) and $projets[$p]['id']!=$_GET['pr']; $p++);
                $stmtenr->execute(array($projets[$p]['code'],$_POST['commandes'],$_POST['visites'],$_POST['offres'],$_POST['remarques']));
                unset($db);
                
              }
            } 

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
  <header>
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
                <ul class="navbar-nav px-3">
                <a class="nav-link" href="projets.php?pr=0">
                  <span data-feather="file"></span>
                  <br/>Projets <span align="center"class="sr-only">(current)</span>
                </a>
                </ul>
                <!-- <a class="nav-link" href="bon_de_commande.php">
                  <span data-feather="file-text"></span>
                  <br/>Bon de Commande
                </a> -->
                <a class="nav-link" href="calendrier.php">
                  <span data-feather="calendar"></span>
                  <br/>Agenda
                </a>
               <!-- <a class="nav-link" href="objectifs.php">
                  <span data-feather="bar-chart-2"></span>
                  <br/>Objectifs
                </a>  -->
                <!-- <a class="nav-link" href="rapports.php">
                  <span data-feather="file-text"></span>
                  <br/>Rapports
                </a> -->
                <ul class="navbar-nav px-3">
                  <li class="nav-item text-nowrap">
                    <br/><p style="color:#49FF00">Session Ouverte<br/>
                    <?php echo $_SESSION['prenom'] ." ".$_SESSION['nom']?><br/>
                    <a style="color:#FF0000" href="deconnection.php">Déconnection</a></p>
                  </li>
                </ul>
    
          </div>
  </nav>
</header>

<div class="container-fluid">
  <div class="row"><!--<span class="sr-only">(current)</span>-->

    <main role="main" class="col-md-9 ml-sm-auto col-lg-12 px-4">
      <form class="form-horizontal" method="post" >
      <div class="container">
      <div class="row">

        <div class="col-lg-6">
          <div class="card mb-4 shadow-sm">
              <div class="card-header">
                <h5 class="card-title">COMMANDES</h5>
              </div>
              <textarea class="form-control" name="commandes" rows="10"></textarea>
            </div>
        </div>

        <div class="col-lg-6">
          <div class="card mb-4 shadow-sm">
              <div class="card-header">
                <h5 class="card-title">VISITES CLIENTS</h5>
              </div>
              <textarea class="form-control" name="visites" rows="10"></textarea>
            </div>
        </div>

      </div>

      <div class="row">

      <div class="col-lg-6">
        <div class="card mb-4 shadow-sm">
              <div class="card-header">
                <h5 class="card-title">OFFRES</h5>
              </div>
              <textarea class="form-control" name="offres" rows="10"></textarea>
            </div>
        </div>

        <div class="col-lg-6">
          <div class="card mb-4 shadow-sm">
              <div class="card-header">
                <h5 class="card-title">REMARQUES</h5>
              </div>
              <textarea class="form-control" name="remarques" rows="10"></textarea>
            </div>
        </div>

      </div>
      
      <div class="form-group">
                    <div class="row">
                      <div class="col-lg-4">
                      </div>
                      <div class="col-lg-4">
                        <!--onclick="window.location.href = 'pdf_proforma.php';"-->
                        <button name="visualiserButton" class="btn btn-lg btn-block btn-primary" type="submit">Visualiser</button>
                      </div>
                      <div class="col-lg-4">
                        <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-lg btn-block btn-primary">Envoyer</button>
                        <!-- Modal -->
                        <div class="modal fade" id="myModal" role="dialog">
                          <div class="modal-dialog modal-dialog-centered">
                          
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                <p><input type="checkbox" value="boxmail1" id="boxmail_1" name="boxmail_1"> Azeddine@integral.fr</p>
                                <p><input type="checkbox" value="boxmail2" id="boxmail_2" name="boxmail_2"> <input id="text2" name="text2" type="text" /></p>
                                <p><input type="checkbox" value="boxmail3" id="boxmail_3" name="boxmail_3"> <input id="text3" name="text3" type="text" /></p>
                                <p><input type="checkbox" value="boxmail4" id="boxmail_4" name="boxmail_4"> <input id="text4" name="text4" type="text" /></p>
                              </div>
                              <div class="modal-footer">
                                <button name = "envoyermail" type="submit" class="btn btn-default">Envoyer</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- Modal -->
                      </div>
                      
                    </div>
        </div>

    </div>
    </form>
    </main>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
        <script src="dashboard.js"></script></body>
</html>
