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

$data=array();
$projets=array();
$categ=array();
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
              $i = 0;
              while($i<count($clients) and ($clients[$i]['NomSociete']!=$_POST['inputClientName'] or $clients[$i]['Wilaya']!=$_POST['inputClientWilaya']) and $clients[$i]['NomSociete']!=$_POST['inputClientCode']){
                $i++;
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
                sendmail($str,'Proforma','proforma.pdf',true,getProforma(true));
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
                       $stmtenr->execute(array(date("d/m/Y"),$_SESSION['dateValid'],$_SESSION['email'],$_SESSION['CodeClient'],$projets[$p]['code'],$_SESSION['delaiLiv'],$_POST['getPortsEnr'],$str,$opt,$_POST['getDeviseEnr']));

                       $nb_insert = $stmtenr->rowCount();
                       unset($db);
                       unset($_SESSION['panier']);
                
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
             if(intval($_SESSION['id_compte'])<10) $code_proforma = '0'.$_SESSION['id_compte'].$_SESSION['id_proforma'].date("my");
             else $code_proforma = $_SESSION['id_compte'].$_SESSION['id_proforma'].date("my");
              
             $stmtenr->execute(array($code_proforma,date("d/m/Y"),$_SESSION['dateValid'],$_SESSION['email'],$_SESSION['CodeClient'],$projets[$p]['code'],$_SESSION['delaiLiv'],$_SESSION['port_dest'],$str,$opt,$_SESSION['Monnaie']));

             $nb_insert = $stmtenr->rowCount();
             unset($db);
             unset($_SESSION['panier']);
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

  <!----------------------------------------------------------- NAVBAR-HAUT --------------------------------------------------------------------->
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
        <!-- ///////////////////MODAL/////////////////////// -->
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

              <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

                <div class="container justify-content-center">
                  <iframe src="<?php if(isset($_SESSION['currentProforma']) and $_SESSION['currentProforma']!="") echo "proformas/".$_SESSION['currentProforma'];?>" width="900" height="800" align="middle"></iframe>
                </div>

                <form class="form-horizontal" method="post" >
                <div class="row align-items-center">
                  <!------------------------------------------------------------------------------------------------>
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