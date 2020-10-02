<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 
  else{

  $sql_categ='SELECT CatégoriesProduits, Ports, Devises
          FROM others
          ORDER BY id;';
  $sql_engins='SELECT id, Categorie, Marque , Type, Ref, Prix, prix_transport, Origine, ConfBase
          FROM engins
          ORDER BY id;';
  $sql_pays ='SELECT code , alpha2 , alpha3, nom_en_gb, nom_fr_fr
          FROM pays
          ORDER BY id;';        
  $sqlAddCateg = 'INSERT INTO `others` (`CatégoriesProduits`) VALUES (?);';

  $sqlAddEng = 'INSERT INTO `engins` (`Categorie`, `Marque`, `Type`, `Ref`, `Prix`, `prix_transport`, `Origine`, `Numero_serie`, `Annee_Fabrication`, `Type_Moteur`, `Numero_Serie_Moteur`, `ConfBase`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);';

  $sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';
  
          $data=array();
          $categ=array();
          $engins=array();
          $pays=array();
          $db = include 'db_mysql.php';

          try {
            
             $stmt = $db->prepare($sql_categ);
             $stmt->execute(array());
             $categ= $stmt->fetchAll();

             $stmt2 = $db->prepare($sql_engins);
             $stmt2->execute(array());
             $engins= $stmt2->fetchAll();

             $stmt5 = $db->prepare($sql_pays);
             $stmt5->execute(array());
             $pays = $stmt5->fetchAll();
             unset($db);

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

            else if(isset($_POST['ajouterCateg'])){
            if($_POST['newCateg']!="" && $_POST['newCateg'][0]!=" "){
            try {
                    $db = include 'db_mysql.php';
                     $stmtenr = $db->prepare($sqlAddCateg);
                     $stmtenr->execute(array($_POST['newCateg']));

                     $nb_insert = $stmtenr->rowCount();
                     unset($db);
                  }
                  catch (Exception $e){
                     print "Erreur ! " . $e->getMessage() . "<br/>";
                  }
                }
          }

            else if(isset($_POST['ajouterEng'])){
              try {
                      $db = include 'db_mysql.php';
                       $stmtenr = $db->prepare($sqlAddEng);
                       $stmtenr->execute(array( $_GET['categ'], $_POST['marqueNew'], $_POST['typeNew'],$_POST['referenceNew'],$_POST['prixNew'],$_POST['prix_transportNew'],$_POST['origineNew'],$_POST['numserieNew'],$_POST['anneefabNew'],$_POST['typemoteurNew'],$_POST['numseriemoteurNew'],$_POST['configNew']));

                       $nb_insert = $stmtenr->rowCount();
                       unset($db);
                    }
                    catch (Exception $e){
                       print "Erreur ! " . $e->getMessage() . "<br/>";
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

    <style type="text/css">
      .nav-link-clicked {
        color : #000000e6;
      }
    </style>

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
                  <a class="navbar col-sm-0 col-md-4 mr-0" href=""></a> 
                <ul class="navbar-nav px-3">
                  <a class="nav-link" href="marketing.php?categ=Postes%20Premium">
                    <span data-feather="shopping-cart" align="center"></span>
                    <br/>Marketing 
                  </a>
                </ul>
                <a class="nav-link" href="newClient.php">
                  <span data-feather="users"></span>
                  <br/>Nouveau Client
                </a>
                <a class="nav-link" href="projets.php?pr=0">
                  <span data-feather="file"></span>
                  <br/>Projets <span align="center"class="sr-only">(current)</span>
                </a>

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
                <a class="navbar col-sm-0 col-md-4 mr-0" href=""></a> 
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
<!----------------------------------------------------------- NAVBAR-GAUCHE--------------------------------------------------------------------->
<form method="post">
<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
              <?php
              $i=0;
              while ($i<count($categ)) {
                ?>
                <button class="nav-item" type=""><a class="nav-link" href="marketing.php?categ=<?php echo htmlspecialchars($categ[$i][0]); ?>" name=<?php echo str_replace(' ', '-',$categ[$i][0]) ?> ><?php echo $categ[$i][0] ?></a></button>
                <?php
                $i++;
              }
              ?>
        </ul> 
        <?php if($_SESSION['statut']=="admin"){ ?>
        <div>
        <button type="button" data-backdrop="false" data-toggle="modal" data-target="#ajouter_c" class="btn btn-light btn-sm"><span data-feather="plus-circle"></button>

        <div class="modal fade" id="ajouter_c" role="dialog">
          <div class="modal-dialog modal-dialog-centered">
          
            <!-- Modal content-->
            <div class="modal-content">

              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>

              <div class="modal-body">
                <p>Nouvelle Catégorie : <input id="newCateg" name="newCateg" type="text"/></p>
              </div>

              <div class="modal-footer">
                <button name = "ajouterCateg" type="submit" class="btn btn-default">OK</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
      </div>
    </nav>
  </div>
</div>
</form>
<!-------------------------------------------------------------------------------------------------------------------------------->

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 shadow-sm">
              <div class="card-header">
                <h5 class="card-title"><?php echo $_GET['categ'] ?></h5>
              </div>
              <ul class="nav flex-column">
              <?php
                $j=0;
                while($j<count($engins)){
                  if($engins[$j]['Categorie']==$_GET['categ']){
                    ?>
                      <a class="<?php  if(isset($_GET['enginM'] ) and htmlspecialchars($engins[$j]['Marque'])." ".htmlspecialchars($engins[$j]['Type']) == $_GET['enginM']." ".$_GET['enginT']){ echo "nav-link-clicked";} else{ echo "nav-link";} ?> " href="marketing.php?categ=<?php echo htmlspecialchars($_GET['categ']);?>&enginM=<?php echo htmlspecialchars($engins[$j]['Marque']);?>&enginT=<?php echo htmlspecialchars($engins[$j]['Type']);?>"> <?php echo $engins[$j]['Marque'].' '.$engins[$j]['Type']; ?> </a>
                    <?php
                  }
                  $j++;
                }
              if($_SESSION['statut']=="admin"){  ?>
                <div>
                <button type="button" data-backdrop="false" data-toggle="modal" data-target="#ajouter_eng" class="btn btn-light btn-sm"><span data-feather="plus-circle"></button>

                <div class="modal fade" id="ajouter_eng" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                  
                    <!-- Modal content-->
                    <div class="modal-content">

                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>

                      <div class="modal-body">
                        <p>  Marque  : <input class="form-control" id="marqueNew" name="marqueNew" type="text" required/></p>
                        <p>   Type   : <input class="form-control" id="typeNew" name="typeNew" type="text"/></p>
                        <p>Référence : <input class="form-control" id="referenceNew" name="referenceNew" type="text"/></p>
                        <p>Prix : <input class="form-control" id="prixNew" name="prixNew" type="text"/></p>
                        <p>Prix Transport : <input class="form-control" id="prix_transportNew" name="prix_transportNew" type="text"/></p>
                        <select class="custom-select d-block w-100" name="origineNew" required>
                          <option selected value>Choisir l'origine du produit...</option>
                          <?php
                          for ($i=0; $pays[$i]['nom_fr_fr']!=""; $i++) {
                          ?>
                          <option value = "<?php echo $pays[$i]['nom_fr_fr']?>"><?php echo $pays[$i]['nom_fr_fr']?></option>
                        <?php }?>
                        </select>
                        <p>Numéro de série : <input class="form-control" id="numserieNew" name="numserieNew" type="text"/></p>
                        <p>Année de fabrication : <input class="form-control" id="anneefabNew" name="anneefabNew" type="text"/></p>
                        <p>Type moteur : <input class="form-control" id="typemoteurNew" name="typemoteurNew" type="text"/></p>
                        <p>Numéro de série moteur : <input class="form-control" id="numseriemoteurNew" name="numseriemoteurNew" type="text"/></p>
                        <label for="configNew">Configuration :</label>
                        <textarea class="form-control" id="configNew" name="configNew" rows="3"></textarea>
                      </div>

                      <div class="modal-footer">
                        <button name = "ajouterEng" type="submit" class="btn btn-default">OK</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php } ?>
            </ul>
            </div>
            </form>
          </div>
          <div class="col-lg-8">
              <div class="col-lg-20">
                <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <h5 class="card-title">Brochures</h5>
                  </div>
                  <br/>
                </div>
              </div>
              <br/>
              <br/>
              <div class="col-lg-20">
                <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <h5 class="card-title">Images</h5>
                  </div>
                  <br/>
                </div>
              </div>
              <br/>
              <br/>
              <div class="col-lg-20">
                <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <h5 class="card-title">Vidéos</h5>
                  </div>
                  <br/>
                </div>
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
        <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</html>
