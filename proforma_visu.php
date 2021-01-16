<?php
session_start();
if (!isset($_SESSION['email'])){
  header('Location: index.php');
} 

require('fonctions_panier.php');
require('mail_php.php');
require('pdf_proforma.php');
require('fonctions_ip.php');

$sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';

$sql_categ='SELECT CatégoriesProduits, Ports, Devises
          FROM others
          ORDER BY id;';
$sqlEnregistrer = 'INSERT INTO `proformas` (`id`, `code`, `DateCreation`, `DateValid`, `EmisPar` , `Client`, `projet` ,`DelaiLivraison`, `PortDest`, `Engins`, `Options`, `monnaie`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);';

$sql_projets = 'SELECT id,nom,code,client,bft,dateCreation,description,etat
          FROM projets
          ORDER BY id;';

$sql_clients='SELECT NomSociete,CodeClient,Adresse,CodePostal,Ville,Wilaya,Pays,NIF,EmailResp1,EmailResp2
          FROM clients
          ORDER BY id;';

$sql_proformas = 'SELECT id,code,DateCreation,DateValid,EmisPar,Client,projet,DelaiLivraison,PortDest,Engins,Options, monnaie
          FROM proformas
          ORDER BY id;';

$data=array();
$projets=array();
$categ=array();
$clients=array();
$proformas=array();
$db = include 'db_mysql.php';
try { 
  $db = include 'db_mysql.php';
    $mail = $_SESSION['email'];

    $stmt = $db->prepare($sql_data);
    $stmt->execute(array($mail));
    $data= $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $db->prepare($sql_categ);
    $stmt2->execute(array());
    $categ = $stmt2->fetchAll();

    $stmt3 = $db->prepare($sql_clients);
    $stmt3->execute(array());
    $clients = $stmt3->fetchAll();

    $stmt4 = $db->prepare($sql_proformas);
    $stmt4->execute(array());
    $proformas = $stmt4->fetchAll();

    $stmt7 = $db->prepare($sql_projets);
    $stmt7->execute(array());
    $projets = $stmt7->fetchAll();
    unset($db);

  if(isset($_POST['modifmdp'])){
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
  else if(isset($_POST['envoyermail'])){
              /*while($i<count($clients) and ($clients[$i]['NomSociete']!=$_POST['inputClientName'] or $clients[$i]['Wilaya']!=$_POST['inputClientWilaya']) and $clients[$i]['NomSociete']!=$_POST['inputClientCode']){
                $i++;
              }*/

              $i=0;
              $p=0;
              for (; $p < count($projets) and $projets[$p]['id']!=$_GET['pr']; $p++);
              for (; $i < count($clients); $i++) { 
                if($projets[$p]['client'] == $clients[$i]['CodeClient']) break;
              }

              //if( ($clients[$i]['NomSociete']==$_POST['inputClientName'] and $clients[$i]['Wilaya']==$_POST['inputClientWilaya']) or $clients[$i]['NomSociete']==$_POST['inputClientCode']){
              $str = "";
              if(isset($_POST['boxmailemail1'])) $str = $str.",".$_POST['boxmail_email1']; 
              if(isset($_POST['boxmailemail2'])) $str = $str.",".$_POST['boxmail_email2'];
              if(isset($_POST['boxmail_1'])) $str = $str.","."Azeddine@integral.fr"; 
              if(isset($_POST['boxmail_2'])) $str = $str.",".$_POST['text2'];
              if(isset($_POST['boxmail_3'])) $str = $str.",".$_POST['text3'];
              if(isset($_POST['boxmail_4'])) $str = $str.",".$_POST['text4'];
              if(isset($_POST['boxmail_email1']) or isset($_POST['boxmail_email2']) or isset($_POST['boxmail_1']) or isset($_POST['boxmail_2']) or isset($_POST['boxmail_3']) or isset($_POST['boxmail_4'])){
                $_SESSION['CodeClient'] = $clients[$i]['CodeClient'];
                $_SESSION['NomClient'] = $clients[$i]['NomSociete'];
                $_SESSION['AdresseClient'] = $clients[$i]['Adresse'];
                $_SESSION['WilayaClient'] = $clients[$i]['Wilaya'];
                $_SESSION['VilleClient'] = $clients[$i]['Ville'];
                $_SESSION['CodePostalClient'] = $clients[$i]['CodePostal'];
                $_SESSION['PaysClient'] = $clients[$i]['Pays'];
                $_SESSION['NIF'] = $clients[$i]['NIF'];
                $_SESSION['EmailResp1'] = $clients[$i]['EmailResp1'];
                $_SESSION['EmailResp2'] = $clients[$i]['EmailResp2'];
                sendmail($str,'Proforma','proforma.pdf',true,getProforma(true,false));
                $db = include 'db_mysql.php';
                       $stmtenr = $db->prepare($sqlEnregistrer);
                       $str = "";
                       $opt = "";
                       for ($i=0; $i < compterArticles(); $i++) {
                          $str = $str."(".$_SESSION['panier']['libelleProduit'][$i]."//".$_SESSION['panier']['qteProduit'][$i].")";
                          $opt = $opt."(".$_SESSION['panier']['options'][$i].")";
                       }
                       
                      
                       
                      if(intval($_SESSION['id_compte'])<10) $code_proforma = "0".$_SESSION['id_compte'].$_SESSION['id_proforma'].strval(date("my"));
                      else $code_proforma = $_SESSION['id_compte'].$_SESSION['id_proforma'].strval(date("my"));
                       
                      $existProf=false;
                      for ($er=0; $er < count($proformas); $er++) { 
                        if($proformas[$er]['code']==$code_proforma){
                          $existProf=true;
                          break;
                        }
                      }
                      if($existProf==false)
                      $stmtenr->execute(array($code_proforma,date("d/m/Y"),$_SESSION['dateValid'],$_SESSION['email'],$_SESSION['CodeClient'],$projets[$p]['code'],$_SESSION['delaiLiv'],$_SESSION['port_dest'],$str,$opt,$_SESSION['Monnaie']));

                       $nb_insert = $stmtenr->rowCount();
                       unset($db);
                       unset($_SESSION['panier']);
                       header('Location: projets.php?pr='.$_GET['pr']);
                       exit();
                
              }
              //}
            
          }
    else if(isset($_POST['enregistrer'])){
      if (compterArticles()>0) {
          try {
             $db = include 'db_mysql.php';

             $stmtenr = $db->prepare($sqlEnregistrer);
             $str = "";
             $opt = "";
             for ($i=0; $i < compterArticles(); $i++) {
                $str = $str."(".$_SESSION['panier']['libelleProduit'][$i]."//".$_SESSION['panier']['qteProduit'][$i].")";
                $opt = $opt."(".$_SESSION['panier']['options'][$i].")";
             }
             $p = 0;
             
             for (; $p < count($projets) and $projets[$p]['id']!=$_GET['pr']; $p++);
              
             if(intval($_SESSION['id_compte'])<10) $code_proforma = "0".$_SESSION['id_compte'].$_SESSION['id_proforma'].strval(date("my"));
             else $code_proforma = $_SESSION['id_compte'].$_SESSION['id_proforma'].strval(date("my"));
            
            $existProf=false;
            for ($er=0; $er < count($proformas); $er++) { 
              if($proformas[$er]['code']==$code_proforma){
                $existProf=true;
                break;
              }
            }
            if($existProf==false)
             $stmtenr->execute(array($code_proforma,date("d/m/Y"),$_SESSION['dateValid'],$_SESSION['email'],$_SESSION['CodeClient'],$projets[$p]['code'],$_SESSION['delaiLiv'],$_SESSION['port_dest'],$str,$opt,$_SESSION['Monnaie']));

             $nb_insert = $stmtenr->rowCount();
             unset($db);
             unset($_SESSION['panier']);
             header('Location: projets.php?pr='.$_GET['pr']);
             exit();
          }
          catch (Exception $e){
             print "Erreur ! " . $e->getMessage() . "<br/>";
          }
        }
    }
} catch (Exception $e) {
 print "Erreur ! " . $e->getMessage() . "<br/>";
}


?>



<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=yes, width=device-width">
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
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <meta name="msapplication-config" content="favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c">
    <link href="css/style_menu.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link href="css/navbar-top.css" rel="stylesheet">

    <link href="my_css.css" rel="stylesheet">

  </head>


<!------------------------------------------------------------------------MENU_HAUT--------------------------------------------------------------------------------->


<body>
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
                      <a class="btn btn-dark btn-lg special-menu active" href="projets.php?pr=0">
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
                      </svg> PARTENAIRES </a>
                    </li>

                  </ul>
                <!-- </div> -->
                <!-- <div class="col-lg-4"> -->
                  
          </nav>
</header>

              <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 padding-top-0">
                <div class="container justify-content-center">
                  <iframe src="<?php if(isset($_SESSION['currentProforma']) and $_SESSION['currentProforma']!="") echo "proformas/".$_SESSION['currentProforma'].".pdf"; ?>" width="900" height="800" align="middle"></iframe>
                </div>

                <form class="form-horizontal" method="post" >
                <div class="container justify-content-center">
                  <div class="row">
                  <!------------------------------------------------------------------------------------------------>
                  <div class="col-lg-4">
                    <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-lg btn-block btn-primary">Enregistrer & Envoyer</button>
                    <!-- Modal -->
                    <div class="modal fade" id="myModal" role="dialog">
                      <div class="modal-dialog modal-dialog-centered">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body">
                            <?php
                            if(isset($_SESSION['EmailResp1']) and $_SESSION['EmailResp1']!=""){ ?>
                              <p><input type="checkbox" value="boxmailemail1" id="boxmail_email1" name="boxmail_email1"><?php echo $_SESSION['EmailResp1'] ?></p>
                              <?php
                            }
                            if(isset($_SESSION['EmailResp2']) and $_SESSION['EmailResp2']!=""){?>
                              <p><input type="checkbox" value="boxmailemail2" id="boxmail_email2" name="boxmail_email2"><?php echo $_SESSION['EmailResp2'] ?></p>
                            <?php }?>
                            <p><input type="checkbox" value="boxmail1" id="boxmail_1" name="boxmail_1">Azeddine@integral.fr</p>
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


                  <div class="col-lg-4">
                    <button name = "enregistrer" type="submit" class="btn btn-lg btn-block btn-primary">Enregistrer</button>                    
                  </div>
                </div>

                </div>
              </form>
            </main>

            <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
            <script>window.jQuery || document.write('<script src="js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
            <script src="dashboard.js"></script>
          </body>
          <script type="text/javascript" src="js/jquery.min.js"></script>
          <script type="text/javascript" src="js/main.js"></script>
          </html>