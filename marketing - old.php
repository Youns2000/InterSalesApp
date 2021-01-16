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
  $sql_options ='SELECT Engin, Nom, Prix, prix_transport, Origine
          FROM options
          ORDER BY id;';       
  $sqlAddCateg = 'INSERT INTO `others` (`CatégoriesProduits`) VALUES (?);';

  $sqlAddEng = 'INSERT INTO `engins` (`Categorie`, `Marque`, `Type`, `Ref`, `Prix`, `prix_transport`, `Origine`, `Numero_serie`, `Annee_Fabrication`, `Type_Moteur`, `Numero_Serie_Moteur`, `ConfBase`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);';

  $sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';
  
          $data=array();
          $categ=array();
          $engins=array();
          $pays=array();
          $options=array();
          $db = include 'db_mysql.php';

          try {
            
             $stmt = $db->prepare($sql_categ);
             $stmt->execute(array());
             $categ= $stmt->fetchAll();

             $stmt2 = $db->prepare($sql_engins);
             $stmt2->execute(array());
             $engins= $stmt2->fetchAll();

             $stmt3 = $db->prepare($sql_options);
             $stmt3->execute(array());
             $options= $stmt3->fetchAll();

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
              $exist = false;
                for ($e=0; $e < count($categ); $e++) { 
                  if($categ[$e]['CatégoriesProduits'] == $_POST['newCateg']){
                    $exist = true;
                    break;
                  }
                }
                  if($_POST['newCateg']!="" && $_POST['newCateg'][0]!=" " && $exist==false){
                      try {
                          $db = include 'db_mysql.php';
                           $stmtenr = $db->prepare($sqlAddCateg);
                           $stmtenr->execute(array($_POST['newCateg']));

                           $nb_insert = $stmtenr->rowCount();
                           if (!is_dir("produits/".$_POST['newCateg'])) mkdir("produits/".$_POST['newCateg']);

                           header('Refresh: 0');
                           unset($db);
                        }
                        catch (Exception $e){
                           print "Erreur ! " . $e->getMessage() . "<br/>";
                        }
                      }
            }

            else if(isset($_POST['ajouterEng'])){
              $exist = false;
                for ($e=0; $e < count($engins); $e++) { 
                  if($engins[$e]['Categorie'] == $_GET['categ'] && $engins[$e]['Marque'] == $_POST['marqueNew']){
                    $exist = true;
                    break;
                  }
                }
                if($exist==false){
                        try {

                          $db = include 'db_mysql.php';
                           $stmtenr = $db->prepare($sqlAddEng);
                           $stmtenr->execute(array( $_GET['categ'], $_POST['marqueNew'], $_POST['typeNew'],$_POST['referenceNew'],$_POST['prixNew'],$_POST['prix_transportNew'],$_POST['origineNew'],$_POST['numserieNew'],$_POST['anneefabNew'],$_POST['typemoteurNew'],$_POST['numseriemoteurNew'],$_POST['configNew']));

                           $nb_insert = $stmtenr->rowCount();
                           header('Refresh: 0');
                           unset($db);
                        }
                        catch (Exception $e){
                           print "Erreur ! " . $e->getMessage() . "<br/>";
                        }
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
      
      .container{
        padding-top: 0px;
      }

    </style>
    <link href="my_css.css" rel="stylesheet">
  </head>
  <body>

<!----------------------------------------------------------- NAVBAR-HAUT--------------------------------------------------------------------->
  <header>
          <nav class="navbar navbar-expand-lg navbar-dark">

                  <ul class="navbar-nav mr-auto ul-special session">
                    <li class="nav-item">

                      <a class="btn btn-dark btn-lg special-menu active" href="marketing.php?categ=Postes%20Premium">
                        <svg width="1.5em" height="1.5em" viewBox="0.5 1.5 16 16" class="bi bi-cart3" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                        </svg> PRODUITS <span class="sr-only">(current)</span> </a>
                    </li>
                    <li class="nav-item">
                      <a class="btn btn-dark btn-lg special-menu " href="projets.php?pr=0">
                      <svg width="1.5em" height="1.5em" viewBox="0 1 16 16" class="bi bi-folder-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"/>
                      </svg> PROJETS </a>
                    </li>
                    <li class="nav-item">
                      <a class="btn btn-dark btn-lg special-menu" href="calendar/calendrier.php">
                      <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-calendar-date" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                        <path d="M6.445 11.688V6.354h-.633A12.6 12.6 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61h.675zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82h-.684zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23z"/>
                      </svg> AGENDA </a>
                    </li>
                    <li class="nav-item">
                      <a class="btn btn-dark btn-lg special-menu" href="newClient.php">
                      <svg width="1.5em" height="1.5em" viewBox="0 1 16 16" class="bi bi-people-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                      </svg> CONTACTS </a>
                    </li>
                    <li class="nav-item">
                      <a class="btn btn-dark btn-lg special-menu" href="marques.php">
                      <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-bag-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8 1a2.5 2.5 0 0 0-2.5 2.5V4h5v-.5A2.5 2.5 0 0 0 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5z"/>
                      </svg> MARQUES </a>
                    </li>

                  </ul>
                <!-- </div> -->
                <!-- <div class="col-lg-4"> -->
                  <ul class="navbar-nav session">
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <!-- <span class="navbar-toggler-icon"></span> -->
                              <?php echo $_SESSION['prenom'] ." ".$_SESSION['nom']?><br/>
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <button type="button" data-backdrop="false" data-toggle="modal" data-target="#modal_modif_mdp" class="dropdown-item">Modifier mot de passe</button>
                        <a type="button" data-backdrop="false" href="deconnection.php" class="dropdown-item">Déconnection</a>
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
                      </ul>
          </nav>
</header>
<!----------------------------------------------------------- NAVBAR-GAUCHE--------------------------------------------------------------------->
<form method="post">
    <div class="row">
      <nav class="col-md-0 d-md-block sidebar">
        <div class="d-flex" id="wrapper">
            <div class="" id="sidebar-wrapper">
              <div class="list-group list-group-flush">
                <?php
                $i=0;
                while ($i<count($categ)) { 
                  if($_GET['categ']==htmlspecialchars($categ[$i][0])) echo '<a href="marketing.php?categ='.htmlspecialchars($categ[$i][0]).'" name="' .str_replace(' ', '-',$categ[$i][0]) .'" class="list-group-item list-group-item-action bg-clair active">'.$categ[$i][0].'</a>';
                  else echo '<a href="marketing.php?categ='.htmlspecialchars($categ[$i][0]).'" name="' .str_replace(' ', '-',$categ[$i][0]) .'" class="list-group-item list-group-item-action bg-clair">'.$categ[$i][0].'</a>';
                  $i++;
                }
                ?>
                

          <?php if($_SESSION['statut']=="admin"){ ?>
                
                <button type="button" data-backdrop="false" data-toggle="modal" data-target="#ajouter_c" class="btn btn-light bg-clair btn-sm"><span data-feather="plus-circle"></button>

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
              
          <?php } ?>
            </div>
          </div>
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
                      <a class="<?php  if(isset($_GET['enginM'] ) and htmlspecialchars($engins[$j]['Marque'])." ".htmlspecialchars($engins[$j]['Type']) == $_GET['enginM']." ".$_GET['enginT']){ echo "list-group-item list-group-item-action bg-clair active";} else{ echo "nav-link";} ?> " href="marketing.php?categ=<?php echo htmlspecialchars($_GET['categ']);?>&enginM=<?php echo htmlspecialchars($engins[$j]['Marque']);?>&enginT=<?php echo htmlspecialchars($engins[$j]['Type']);?>"> <?php echo $engins[$j]['Marque'].' '.$engins[$j]['Type']; ?> </a>
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
                        <h4>CREATION NOUVEAU PRODUIT</h4>
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

                <div class="col-lg-20">
                <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <!-- <h5 class="card-title">Vidéos</h5> -->
                    <!-- <button class="btn btn-block"></button> -->
                    <button type="button" data-backdrop="false" data-toggle="modal" data-target="#configShow" class="btn btn-light btn-block"><h5 class="card-title">Configuration</h5></button>
                    <!--------------------------------------------------MODAL SHOW CONFIG------------------------------------------------------------------->
                    <div class="modal fade bd-example-modal-lg" id="configShow" role="dialog">
                      <div class="modal-dialog modal-dialog-centered modal-lg">
                      
                        <!-- Modal content-->
                        <div class="modal-content">

                          <div class="modal-header">
                            <h5 align="justify">CONFIGURATION</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <!-- <div class="row">
                                <div class="col-md-3 ml-auto"> -->
                            <?php
                          if(isset($_GET['enginM'])){
                            $x=0;
                            while(($_GET['enginM']!=$engins[$x]['Marque'] or $_GET['enginT']!=$engins[$x]['Type']) and $x<count($engins)-1){
                              $x++;
                            }}?>

                          <div class="modal-body">
                            <div class="row">
                              <!-- <div class="ml-auto"> -->
                                <table class="table">
                                  <thead class="thead-dark">
                                    <tr>
                                      <th scope="col">Configuration de base</th>
                                      <th scope="col">Prix</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php if(isset($_GET['enginM'])){ ?>
                                    <tr>
                                      <td><?php echo "<p>".$engins[$x]['ConfBase']."</p>"; ?></td>
                                      <td><?php echo "<B>".$engins[$x]['Prix']."€</B>"; ?></td>
                                    </tr>
                                    <?php } ?>

                                  </tbody>
                                </table>

                                <table class="table">
                                  <thead class="thead-light">
                                    <tr>
                                      <th scope="col">Options</th>
                                      <th scope="col">Prix</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <?php 
                                    if(isset($_GET['enginM'])){
                                    for ($op=0; $op < count($options); $op++) { 
                                      if($options[$op]['Engin']==$engins[$x]['id']){
                                      echo "<tr>";
                                      echo "<td>".$options[$op]['Nom']."</td>";
                                      echo "<td> <B>".$options[$op]['Prix']."€</B> </td>";
                                      echo "</tr>";
                                      }
                                    }
                                    }?>

                                  </tbody>
                                </table>
                        <!-- </div> -->
                        </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--------------------------------------------------------------------------------------------------------------------------------------->
                  </div>
                </div>
              </div>

              <br/>
              <br/>


                <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <h5 class="card-title">Brochures</h5>
                  </div>
                    <div>

                    <button type="button" data-backdrop="false" data-toggle="modal" data-target="#ajouter_brochure" class="btn btn-light btn-sm"><span data-feather="plus-circle"></button>

                    <div class="modal fade" id="ajouter_brochure" role="dialog">
                      <div class="modal-dialog modal-dialog-centered">
                      
                        <!-- Modal content-->
                        <div class="modal-content">

                          <div class="modal-header">
                            <h2>Ajouter Brochure</h2>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>

                          <div class="modal-body">
                            <form action="upload.php" method="post" enctype="multipart/form-data">
                                    <label for="fileUpload">Brochure:</label>
                                    <input type="file" name="brochure" id="fileUpload">
                                    <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="123" />
                                    <input type="submit" name="submit" value="Envoyer">
                                    <p><strong>Note:</strong> Seuls les formats .pdf sont autorisés jusqu'à une taille maximale de 5 Mo.</p>
                                </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <br/>
              <br/>

              <div class="col-lg-20">
                <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <h5 class="card-title">Images</h5>
                  </div>
                  <div class="text-align: left">
                  <?php 
                      if(isset($_GET['enginM'])){
                        if(is_dir("produits/".strtoupper($_GET['categ'])) or is_dir("produits/".$_GET['categ'])){
                          if(is_dir("produits/".strtoupper($_GET['categ']))) $currentCateg = strtoupper($_GET['categ']);
                          if(is_dir("produits/".$_GET['categ'])) $currentCateg = $_GET['categ'];
                          $dir = "produits/".$currentCateg."/".$_GET['enginM']." ".$_GET['enginT']."/PHOTOS";
                          if(is_dir($dir)){
                            $images = scandir($dir);
                            foreach ($images as $photo) {
                              if($photo!="." && $photo!="..")
                              echo '<a href="'.$dir."/".$photo.'" onclick="window.open(this.href); return false;"><img src="'.$dir."/".$photo.'" style="display:inline-block;" height="120" width="200" alt="Bootstrap" /></a>';
                            }
                          }
                      }
                    }
                      ?>
                    </div>
                </div>
              </div>

              <br/>
              <br/>

              <div class="col-lg-20">
                <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <h5 class="card-title">Vidéos</h5>
                  </div>
                  <div class="text-align: left">
                  <?php 
                      if(isset($_GET['enginM'])){
                        if(is_dir("produits/".strtoupper($_GET['categ'])) or is_dir("produits/".$_GET['categ'])){
                          if(is_dir("produits/".strtoupper($_GET['categ']))) $currentCateg = strtoupper($_GET['categ']);
                          if(is_dir("produits/".$_GET['categ'])) $currentCateg = $_GET['categ'];
                            $dir = "produits/".$currentCateg."/".$_GET['enginM']." ".$_GET['enginT']."/VIDEOS";
                            if(is_dir($dir)){
                              $videos = scandir($dir);
                              foreach ($videos as $video) {
                                if($video!="." && $video!="..")
                                echo '<a href="'.$dir."/".$video.'" onclick="window.open(this.href); return false;"><iframe class="embed-responsive-item" src="'.$dir."/".$video.'" style="display:inline-block;" height="200" width="370" alt="Bootstrap" ></iframe></a>';
                              }
                            }
                          }
                        }
                      ?>
                    </div>
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
