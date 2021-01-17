<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 
  else{
    $_SESSION['currentPage'] = "rapports";
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

  $sql_rapports = 'SELECT id,code,num,projet,commandes,visitesClient,offres,remarques
          FROM rapports
          ORDER BY id;';

  $sqlRapportEnregistrer = 'INSERT INTO `rapports` (`id`, `projet`, `commandes`, `visitesClient`, `offres` , `remarques`) VALUES (NULL, ?, ?, ?, ?, ?);';
    
          $data=array();
          $categ=array();
          $engins=array();
          $rapports=array();
          $projets=array();
          $db = include 'db_mysql.php';

          try {
             $stmt = $db->prepare($sql_categ);
             $stmt->execute(array());
             $categ= $stmt->fetchAll();

             $stmt2 = $db->prepare($sql_engins);
             $stmt2->execute(array());
             $engins= $stmt2->fetchAll();

             $stmt3 = $db->prepare($sql_rapports);
             $stmt3->execute(array());
             $rapports= $stmt3->fetchAll();

             $stmt7 = $db->prepare($sql_projets);
             $stmt7->execute(array());
             $projets = $stmt7->fetchAll(); 
             unset($db);

            if(isset($_POST['visualiserButton'])){
                          $_SESSION['commandes'] = $_POST['commandes'];
                          $_SESSION['visites'] = $_POST['visites'];
                          $_SESSION['offres'] = $_POST['offres'];
                          $_SESSION['remarques'] = $_POST['remarques'];
                           //id_compte+num+currentProjetCode+mois+annee
                          $numero = intval($rapports[count($rapports)]['num'])+1;
                          $_SESSION['numeroRapport'] = $numero;
                          $_SESSION['currentRapport'] = 0;
                          if(intval($_SESSION['id_compte'])<10) $_SESSION['currentRapport'] = '0'.$_SESSION['id_compte'].strval($numero).$projets[$_GET['pr']]['code'];
                          else $_SESSION['currentRapport'] = $_SESSION['id_compte'].strval($numero).$projets[$_GET['pr']]['code']; 
                          getRapportPDF();
                          header('Location: rapports_visu.php?pr='.$_GET['pr']);
                          exit();             
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
    <link href="my_css.css" rel="stylesheet">

  </head>
  <body>

<!----------------------------------------------------------- NAVBAR-HAUT--------------------------------------------------------------------->
  <header>
    <?php include("header.php"); ?>
  </header>

<div class="container-fluid content-row">
  <div class="row"><!--<span class="sr-only">(current)</span>-->

    <main role="main" class="col-md-9 ml-sm-auto col-lg-12 px-4 padding-top-0">
      <form class="form-horizontal" method="post" >
      <div class="container-fluid">
      <div class="row">

        <div class="col-lg-6">
          <div class="card mb-4 shadow-sm">
              <div class="card-header">
                <h5 class="card-title">VISITES CLIENTS</h5>
              </div>
              <textarea class="form-control" name="visites" rows="10"></textarea>
            </div>
        </div>

        <div class="col-lg-6">
          <div class="card mb-4 shadow-sm">
              <div class="card-header">
                <h5 class="card-title">OFFRES</h5>
              </div>
              <textarea class="form-control" name="offres" rows="10"></textarea>
            </div>
        </div>

      </div>

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
