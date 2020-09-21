<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  }
  if(isset($_POST['nomSociete'])){
    $sql = 'INSERT INTO `clients` (`id`, `NomSociete`, `CodeClient`, `NIF` , `Activite1`, `Activite2`, `Adresse`, `CodePostal`, `Ville`, `Wilaya`, `Pays`, `TelFixe`, `NomResp1`, `EmailResp1`, `PortableResp1`, `NomResp2`, `EmailResp2`, `PortableResp2`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?);';

     $stmt = $db->prepare($sql);
     //$codeclient='TE25E';
     $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
     $longueurMax = strlen($caracteres);
     $codeclient="";
     $codeclient.=$caracteres[rand(0, $longueurMax - 1)];
     $codeclient.=$caracteres[rand(0, $longueurMax - 1)];
     $codeclient.=rand(0, 9);
     $codeclient.=rand(0, 9);
     $codeclient.=$caracteres[rand(0, $longueurMax - 1)];

     $stmt->execute(array($_POST['nomSociete'],$codeclient,$_POST['NIF'],$_POST['activite1'],$_POST['activite2'],$_POST['adresse'],$_POST['codePostal'],$_POST['ville'],$_POST['wilaya'],'Algérie',$_POST['telephone'],$_POST['nomRes1'],$_POST['mailRes1'],$_POST['telRes1'],$_POST['nomRes2'],$_POST['mailRes2'],$_POST['telRes2']));

     $nb_insert = $stmt->rowCount();
     //echo $nb_insert.' insertion effectuée<br/>';
     unset($db);
   
  } 
  else{
    $sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';
    //Azeddine@integral.fr
    $db = include 'db_mysql.php';
    $data=array();
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
  }
  catch (Exception $e){
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
                <ul class="navbar-nav px-3">
                <a class="nav-link" href="newClient.php">
                  <span data-feather="users"></span>
                  <br/>Nouveau Client
                </a>
                </ul>
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
  <div class="row">

    <main role="main" class="col-md-9 ml-5 col-lg-10">
        <br/>
        <br/>
        <br/>
        <br/>
        <form class="needs-validation" method="post" novalidate>
        <div class="mb-3"><h4 class="mb-3">Société</h4></div>
        <div class="row">
        <div class="col-md-4 mb-3">
          <label for="nomSociete">Nom Société</label>
          <input type="text" class="form-control" name="nomSociete" placeholder="" required>
          <div class="invalid-feedback">
            Entrez un nom valide.
          </div>
        </div>
        <div class="col-md-8 mb-3">
          <label for="NIF">NIF</label>
          <input type="text" class="form-control" name="NIF" placeholder="" required>
          <div class="invalid-feedback">
            Entrez un NIF valide.
          </div>
        </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="activite1">Activité</label>
            <input type="text" class="form-control" name="activite1" placeholder="" value="" required>
            <div class="invalid-feedback">
              Entrez une activité valide.
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="activite2">Activité 2 <span class="text-muted">(Optionnel)</span></label>
            <input type="text" class="form-control" name="activite2" placeholder="" value="">
            <div class="invalid-feedback">
               Entrez une activité valide.
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="telephone">Téléphone</label>
            <input type="tel" class="form-control" name="telephone" placeholder="+213 00 00 00 00 00" value="" required>
            <div class="invalid-feedback">
              Entrez un numéro de téléphone valide.
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="adresse">Adresse</label>
            <input type="text" class="form-control" name="adresse" placeholder="" value="" required>
            <div class="invalid-feedback">
              Entrez une adresse valide.
            </div>
          </div>
          <div class="col-md-2 mb-3">
            <label for="codePostal">Code Postal</label>
            <input type="number" class="form-control" name="codePostal" placeholder="16000" value="" required>
            <div class="invalid-feedback">
              Entrez un code postal valide.
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label for="ville">Ville</label>
            <input type="text" class="form-control" name="ville" placeholder="Alger" value="" required>
            <div class="invalid-feedback">
              Entrez une ville valide.
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label for="wilaya">Wilaya</label>
            <input type="text" class="form-control" name="wilaya" placeholder="Alger" value="" required>
            <div class="invalid-feedback">
              Entrez une wilaya valide.
            </div>
          </div>
        </div>
          <hr class="mb-4">
          <div class="mb-3"><h4 class="mb-3">Responsable 1</h4></div>
          <div class="row">
            <div class="col-md-3 mb-3">
            <label for="nomRes1">Nom</label>
            <input type="text" class="form-control" name="nomRes1" placeholder="" required>
            <div class="invalid-feedback">
              Entrez un nom valide.
            </div>
          </div>
          <div class="col-md-3 mb-3">
              <label for="prenomRes1">Prénom</label>
              <input type="text" class="form-control" name="prenomRes1" placeholder="" value="" required>
              <div class="invalid-feedback">
                Entrez un prénom valide.
              </div>
            </div>
          <div class="col-md-3 mb-3">
              <label for="telRes1">Téléphone<span class="text-muted"> (Optionnel)</span></label>
              <input type="number" class="form-control" name="telRes1" placeholder="+213 00 00 00 00 00" value="">
              <div class="invalid-feedback">
                Entrez un numéro de téléphone valide.
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <label for="mailRes1">Email</label>
              <input type="email" class="form-control" name="mailRes1" placeholder="email@exemple.com" value="" required>
              <div class="invalid-feedback">
                Entrez une adresse email valide.
              </div>
            </div>
          </div>
          <hr class="mb-4">
          <div class="mb-3"><h4 class="mb-3">Responsable 2<span class="text-muted"> (Optionnel)</span></h4></div>
          <div class="row">
            <div class="col-md-3 mb-3">
            <label for="nomRes2">Nom</label>
            <input type="text" class="form-control" name="nomRes2" placeholder="" >
            <div class="invalid-feedback">
              Entrez un nom valide.
            </div>
          </div>
          <div class="col-md-3 mb-3">
              <label for="prenomRes2">Prénom</label>
              <input type="text" class="form-control" name="prenomRes2" placeholder="" value="" >
              <div class="invalid-feedback">
                Entrez un prénom valide.
              </div>
            </div>
          <div class="col-md-3 mb-3">
              <label for="telRes2">Téléphone</label>
              <input type="text" class="form-control" name="telRes2" placeholder="+213 00 00 00 00 00" value="" >
              <div class="invalid-feedback">
                Entrez un numéro de téléphone valide.
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <label for="mailRes2">Email</label>
              <input type="text" class="form-control" name="mailRes2" placeholder="email@exemple.com" value="" >
              <div class="invalid-feedback">
                Entrez une adresse email valide.
              </div>
            </div>
          </div>
          <hr class="mb-4">
          <button class="btn btn-primary btn-lg btn-block" type="submit">Ajouter</button>
        </form>
        </div>
    </main>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
        <script src="dashboard.js"></script>
      <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="/docs/4.4/assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="js/src/form-validation.js"></script></body>
</html>
