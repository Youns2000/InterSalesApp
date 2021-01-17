<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
    $_SESSION['currentPage'] = "newClient";
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
         <?php include("header.php"); ?> 
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
