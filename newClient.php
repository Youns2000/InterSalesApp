<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  }
  $sql_activites='SELECT Activités
          FROM others
          ORDER BY id;';
  $sql_wilayas='SELECT nom
            FROM wilaya
            ORDER BY id;'; 

  $activites=array();
  $wilayas=array();

  $db = include 'db_mysql.php';

    $stmt = $db->prepare($sql_activites);
    $stmt->execute(array());
    $activites= $stmt->fetchAll();

    $stmt2 = $db->prepare($sql_wilayas);
    $stmt2->execute(array());
    $wilayas = $stmt2->fetchAll();
    unset($db);




  if(isset($_POST['nomSociete'])){
    $db = include 'db_mysql.php';
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
    <link href="my_css.css" rel="stylesheet">
  </head>
  <body>

<!----------------------------------------------------------- NAVBAR-HAUT--------------------------------------------------------------------->
<header>
          <nav class="navbar navbar-expand-lg navbar-dark">
            <ul class="navbar-nav session">
              <div class="dropdown" style="width:100%;">
                <button style="width: 100%;" class="btn btn-secondary dropdown-toggle bg-card-bleu-special" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <!-- <span class="navbar-toggler-icon"></span> -->
                        <?php echo "Session ouverte : ".$_SESSION['prenom'] ." ".$_SESSION['nom']?><br/>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2" style="width: 100%;">
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

                  <ul class="navbar-nav mr-auto ul-special session2">
                    <li class="nav-item">

                      <a class="btn btn-dark btn-lg special-menu " href="marketing.php?categ=Postes%20Premium">
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
                      <a class="btn btn-dark btn-lg special-menu active" href="newClient.php">
                      <svg width="1.5em" height="1.5em" viewBox="0 1 16 16" class="bi bi-people-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/>
                      </svg> CONTACTS </a>
                    </li>
                    <li class="nav-item">
                      <a class="btn btn-dark btn-lg special-menu" href="marques.php">
                      <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-bag-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8 1a2.5 2.5 0 0 0-2.5 2.5V4h5v-.5A2.5 2.5 0 0 0 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5z"/>
                      </svg> PARTENAIRES </a>
                    </li>

                  </ul>
                <!-- </div> -->
                <!-- <div class="col-lg-4"> -->
                  
          </nav>
</header>

<div class="container-fluid">
  <div class="row">

    <main role="main" class="col-md-9 ml-5 col-lg-10 padding-top-0">
        <form class="needs-validation" method="post" novalidate>
        <div class="mb-3"><h4 class="mb-3">Ajouter nouveau contact</h4></div>
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
          <input type="text" class="form-control" name="NIF" placeholder="" minlength="15" maxlength="15" required>
          <div class="invalid-feedback">
            Entrez un NIF valide.
          </div>
        </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label for="activite1">Activité</label>
            <!-- <input type="text" class="form-control" name="activite1" placeholder="" value="" required> -->
            <select class="custom-select d-block w-100" name="activite1" required>
              <option selected value>Activité...</option>
              <?php
              for ($i=0; $i<count($activites) and $activites[$i]['Activités']!=""; $i++) {
              ?>
              <option value = "<?php echo $activites[$i]['Activités']?>"><?php echo $activites[$i]['Activités']?></option>
            <?php }?>
            </select>
            <div class="invalid-feedback">
              Entrez une activité valide.
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="activite2">Activité 2 <span class="text-muted">(Optionnel)</span></label>
            <!-- <input type="text" class="form-control" name="activite2" placeholder="" value=""> -->
            <select class="custom-select d-block w-100" name="activite2">
              <option selected value>Activité 2...</option>
              <?php
              for ($i=0; $i<count($activites) and $activites[$i]['Activités']!=""; $i++) {
              ?>
              <option value = "<?php echo $activites[$i]['Activités']?>"><?php echo $activites[$i]['Activités']?></option>
            <?php }?>
            </select>
            <div class="invalid-feedback">
               Entrez une activité valide.
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="telephone">Téléphone</label>
            <input type="tel" class="form-control" name="telephone" placeholder="+213 00 00 00 00 00" minlength="8" maxlength="12" pattern="+213 [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}" required>
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
            <input type="number" class="form-control" name="codePostal" placeholder="16000" min="1" max="99999" required>
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
            <select class="custom-select d-block w-100" name="wilaya" required>
              <option selected value>Wilaya du client...</option>
              <?php
              for ($i=0; $i<count($wilayas); $i++) {
              ?>
              <option value = "<?php echo $wilayas[$i]['nom']?>"><?php echo $wilayas[$i]['nom']?></option>
            <?php }?>
            </select>
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
              <label for="telRes1">Téléphone</label>
              <input type="number" class="form-control" name="telRes1" placeholder="+213 00 00 00 00 00" value="" required>
              <div class="invalid-feedback">
                Entrez un numéro de téléphone valide.
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <label for="mailRes1">Email</label>
              <input type="email" class="form-control" name="mailRes1" placeholder="email@exemple.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
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
              <input type="text" class="form-control" name="mailRes2" placeholder="email@exemple.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" >
              <div class="invalid-feedback">
                Entrez une adresse email valide.
              </div>
            </div>
          </div>
          <hr class="mb-4">
          <button name="addClient" class="btn btn-primary btn-lg btn-block" type="submit">Ajouter</button>
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
        <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</html>
