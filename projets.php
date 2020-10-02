<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 
  else{
    require('fonctions_panier.php');
    require('mail_php.php');
    require('pdf_proforma.php');
    require('fonctions_ip.php');

  $sql_categ='SELECT CatégoriesProduits, Ports, Devises
          FROM others
          ORDER BY id;';
  $sql_engins='SELECT id, Categorie, Marque , Type, Ref, Prix, prix_transport, Origine, ConfBase
          FROM engins
          ORDER BY id;';
  $sql_options='SELECT Engin, Nom, Prix, prix_transport
          FROM options
          ORDER BY id;';
  $sql_clients='SELECT NomSociete,CodeClient,Adresse,CodePostal,Ville,Wilaya,Pays,NIF,EmailResp1,EmailResp2,commercial
          FROM clients
          ORDER BY id;';
  $sql_pays ='SELECT code , alpha2 , alpha3, nom_en_gb, nom_fr_fr
          FROM pays
          ORDER BY id;';

  $sql_proformas = 'SELECT id,code,DateCreation,DateValid,EmisPar,Client,projet,DelaiLivraison,PortDest,Engins,Options, monnaie
          FROM proformas
          ORDER BY id;';

  $sql_projets = 'SELECT id,nom,code,client,bft,dateCreation,description,etat
          FROM projets
          ORDER BY id;';

  $sql_rapports = 'SELECT id,projet,commandes,visitesClient,offres,remarques
          FROM rapports
          ORDER BY id;';

  $sqlEnregistrer = 'INSERT INTO `proformas` (`id`, `DateCreation`, `DateValid`, `EmisPar` , `Client`, `DelaiLivraison`, `PortDest`, `Engins`, `Options`, `monnaie`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);';

  $sqlAddCateg = 'INSERT INTO `others` (`CatégoriesProduits`) VALUES (?);';

  $sqlAddEng = 'INSERT INTO `engins` (`Categorie`, `Marque`, `Type`, `Ref`, `Prix`, `prix_transport`, `Origine`, `Numero_serie`, `Annee_Fabrication`, `Type_Moteur`, `Numero_Serie_Moteur`, `ConfBase`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);';
  
  $sqlAddProjet = 'INSERT INTO `projets` (`nom`, `code`, `client`, `bft`, `dateCreation`, `description`, `etat`) VALUES (?,?,?,?,?,?,?);';

  $sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';

          $categ=array();
          $engins=array();
          $clients=array();
          $options=array();
          $pays=array();
          $data=array();
          $proformas=array();
          $projets=array();
          $rapports=array();
          $db = include 'db_mysql.php';

          try {
             $stmt = $db->prepare($sql_categ);
             $stmt->execute(array());
             $categ = $stmt->fetchAll();

             $stmt2 = $db->prepare($sql_engins);
             $stmt2->execute(array());
             $engins = $stmt2->fetchAll();

             $stmt3 = $db->prepare($sql_clients);
             $stmt3->execute(array());
             $clients = $stmt3->fetchAll();

             $stmt4 = $db->prepare($sql_options);
             $stmt4->execute(array());
             $options = $stmt4->fetchAll();

             $stmt5 = $db->prepare($sql_pays);
             $stmt5->execute(array());
             $pays = $stmt5->fetchAll();

             $stmt6 = $db->prepare($sql_proformas);
             $stmt6->execute(array());
             $proformas = $stmt6->fetchAll();

             $stmt7 = $db->prepare($sql_projets);
             $stmt7->execute(array());
             $projets = $stmt7->fetchAll();

             $stmt8 = $db->prepare($sql_rapports);
             $stmt8->execute(array());
             $rapports = $stmt8->fetchAll();
             unset($db);

            if((isset($_POST['inputClientName']) or isset($_POST['inputClientWilaya']) or isset($_POST['inputClientCode'])) and isset($_POST['visualiser'])){
                    $i = 0;
                    while($i<count($clients)){
                      if( ($clients[$i]['NomSociete']==$_POST['inputClientName'] and $clients[$i]['Wilaya']==$_POST['inputClientWilaya']) or $clients[$i]['NomSociete']==$_POST['inputClientCode'])
                      {
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
                          $_SESSION['port_dest'] = $_POST['getPorts'];
                          $_SESSION['Monnaie'] = $_POST['getDevise'];
                          $_SESSION['dateValid'] = $_POST['datevalidite'];
                          $_SESSION['delaiLiv'] = $_POST['delailivraison'];
                          $_SESSION['id_proforma'] = $proformas[count($proformas)-1]['id']+1;
                          for ($p=0; $p < count($pays); $p++) { 
                            $_SESSION['pays'][$p] = $pays[$p]['alpha2'];
                          }
                          for ($o=0; $o < count($options); $o++) { 
                            $_SESSION['options']['Engin'][$o] = $options[$o]['Engin'];
                            $_SESSION['options']['Nom'][$o] = $options[$o]['Nom'];
                            $_SESSION['options']['Prix'][$o] = $options[$o]['Prix'];
                            $_SESSION['options']['prix_transport'][$o] = $options[$o]['prix_transport'];
                          }
                          getProforma(false);
                          //header('Location: pdf_proforma.php');
                          //exit();
                      }
                      else{
                        $i++;
                      }
              }
            }
            else if((isset($_POST['inputClientName']) or isset($_POST['inputClientWilaya']) or isset($_POST['inputClientCode'])) and isset($_POST['enregistrer'])){
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
                     $stmtenr->execute(array(date("d/m/Y"),"100j",$_SESSION['email'],$_SESSION['CodeClient'],"100j",$_POST['getPortsEnr'],$str,$opt,$_POST['getDeviseEnr']));

                     $nb_insert = $stmtenr->rowCount();
                     unset($db);
                  }
                  catch (Exception $e){
                     print "Erreur ! " . $e->getMessage() . "<br/>";
                  }
                }
            }

            else if((isset($_POST['inputClientName']) or isset($_POST['inputClientWilaya']) or isset($_POST['inputClientCode'])) and isset($_POST['envoyermail'])){
              $i = 0;
              while($i<count($clients) and ($clients[$i]['NomSociete']!=$_POST['inputClientName'] or $clients[$i]['Wilaya']!=$_POST['inputClientWilaya']) and $clients[$i]['NomSociete']!=$_POST['inputClientCode']){
                $i++;
              }

              if( ($clients[$i]['NomSociete']==$_POST['inputClientName'] and $clients[$i]['Wilaya']==$_POST['inputClientWilaya']) or $clients[$i]['NomSociete']==$_POST['inputClientCode']){
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
                
              }
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
            
          else if(isset($_POST['ajouterProjet']) and !in_array($_POST['newProjetName'], $projets)){
            try {
                    $db = include 'db_mysql.php';
                     $stmtenr = $db->prepare($sqlAddProjet);
                     $stmtenr->execute(array( $_POST['newProjetName'], $_POST['newProjetCode'],$_POST['newProjetClient'],$_POST['newProjetB'].$_POST['newProjetF'].$_POST['newProjetT'],date("d/m/Y"),$_POST['newProjetText'],$_POST['newProjetEtat']));

                     $nb_insert = $stmtenr->rowCount();
                     unset($db);
                  }
                  catch (Exception $e){
                     print "Erreur ! " . $e->getMessage() . "<br/>";
                  }
          }


            else if(isset($_POST['nbConfBase'])){
              if(creationPanier()){
                $x=0;

                while($_GET['enginM']!=$engins[$x]['Marque'] and $_GET['enginT']!=$engins[$x]['Type']){
                  $x++;
                }

                $nbOptions=0;
                for ($i=0; $i < count($options); $i++) { 
                  if($options[$i]['Engin']==$engins[$x]['id']) $nbOptions++;
                }

                $optionsCode="";
                for ($i=0; $i < $nbOptions; $i++) { 
                  $str=$_GET['enginM'].$_GET['enginT'].'Option'.($i+1);
                  
                  $val = strval($_POST[$str]);
                  if($val==NULL) $val="0";
                  $optionsCode= $optionsCode.$val."/";
                }
                $optionsCode=$optionsCode."/";
                modifierQTeArticle($engins[$x]['Ref']."/".$engins[$x]['Categorie']."/".$engins[$x]['Marque']."/".$engins[$x]['Type']."/".$engins[$x]['Origine']."/".$engins[$x]['ConfBase'],$_POST['nbConfBase'],$engins[$x]['Prix'],$engins[$x]['prix_transport'],$optionsCode);
                //echo $_SESSION['panier']['options'][0];
              }
            }
            else if(isset($_POST['modifmdp'])){
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
        }
?>

<!--------------------------------------------------------------------------CSS/JAVASCRIPT-------------------------------------------------------------------------->

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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

    <!-- <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta name="keywords" content="navigation, menu, responsive, border, overlay, css transition" />
    <meta name="author" content="Codrops" />
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="css/demo.css" />
    <link rel="stylesheet" type="text/css" href="css/icons.css" />
    <link rel="stylesheet" type="text/css" href="css/style3.css" />
    <script src="js/modernizr.custom.js"></script> -->

    <style type="text/css">
      .form-group input[type="checkbox"] {
          display: none;
      }

      .form-group input[type="checkbox"] + .btn-group > label span {
          width: 20px;
      }

      .form-group input[type="checkbox"] + .btn-group > label span:first-child {
          display: none;
      }
      .form-group input[type="checkbox"] + .btn-group > label span:last-child {
          display: inline-block;   
      }

      .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
          display: inline-block;
      }
      .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
          display: none;   
      }
      .custom-select {
          width: 50px !important;
          display: inline-flex !important; 
      }
      .custom-select-2 {
          width: 150px !important;
          display: inline-flex !important; 
      }
    </style>

    <script type="text/javascript">
      function qteProduit(id){
        return document.getElementById(id).value;
      }

      function in_array(nom, wilaya, code , tab)
      {
        var i = 0;
        while(i<tab.length){
          if( (tab[i][0]==nom && tab[i][5]==wilaya) || tab[i][1]==code)
          {
              return true;
          }
          i++;
        }
        return false;
      }

    </script>   
  </head>


<!------------------------------------------------------------------------MENU_HAUT--------------------------------------------------------------------------------->


<body>
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
</header>

<!------------------------------------------------------------------------MENU_GAUCHE------------------------------------------------------------------------------------>

<form method="post">
<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
              <?php
              $i=0;
              while ($i<count($projets)) {
                ?>
                <button class="nav-item" type=""><a class="nav-link" href="projets.php?pr=<?php echo $i; ?>" name=<?php echo str_replace(' ', '-',$projets[$i]['nom']) ?> ><?php echo $projets[$i]['nom'] ?></a></button>
                <?php
                $i++;
              }
              ?>
        </ul>


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
                <p><B>CLIENT</B></p>
                <p>Code Client : <input id="newProjetClient" name="newProjetClient" type="text"/></p>

                <hr width="100%" color="grey">

                <p><B>PROJET</B></p>
                <p>Nom : <input id="newProjetName" name="newProjetName" type="text"/></p>
                <p>Code : <input id="newProjetCode" name="newProjetCode" type="text"/></p>
                <p>B : <select class="custom-select d-block w-50" name="newProjetB" required>
                          <option selected value>...</option>
                          <option value ="1" >1</option>
                          <option value ="2" >2</option>
                          <option value ="3" >3</option>
                  </select>
                  F : <select class="custom-select d-block w-100" name="newProjetF" required>
                          <option selected value>...</option>
                          <option value ="1" >1</option>
                          <option value ="2" >2</option>
                          <option value ="3" >3</option>
                  </select>
                  T : <select class="custom-select d-block w-100" name="newProjetT" required>
                          <option selected value>...</option>
                          <option value ="1" >1</option>
                          <option value ="2" >2</option>
                          <option value ="3" >3</option>
                  </select></p>

                  <p><select class="custom-select-2 d-block w-100" name="newProjetEtat" required>
                                            <option selected value>Choisir un état...</option>
                                            <option value ="0" >En cours</option>
                                            <option value ="1" >Reporté</option>
                                            <option value ="2" >Terminé</option>
                  </select></p>
                
                <textarea class="form-control" id="newProjetText" name="newProjetText" rows="3"></textarea>
                
                <!-- <hr width="100%" color="grey"> -->
              </div>

              <div class="modal-footer">
                <button name = "ajouterProjet" type="submit" class="btn btn-default">OK</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </nav>
  </div>
</div>
</form>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <!-- <div class="container">
      <nav id="bt-menu" class="bt-menu">
        <a href="#" class="bt-menu-trigger"><span>Menu</span></a>
        <ul>
          <li><a href="#" class="bt-icon icon-user-outline">About</a></li>
          <li><a href="#" class="bt-icon icon-sun">Skills</a></li>
          <li><a href="#" class="bt-icon icon-windows">Work</a></li>
          <li><a href="#" class="bt-icon icon-speaker">Blog</a></li>
          <li><a href="#" class="bt-icon icon-star">Clients</a></li>
          <li><a href="#" class="bt-icon icon-bubble">Contact</a></li>
        </ul>
      </nav>
    </div> -->


      <div class="container">
        <div class="row">


<!----------------------------------------------------------------------CLIENT-------------------------------------------------------------------------------------->
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-danger shadow-sm">
              <div class="card-header">
                <h5 class="card-title">Client</h5>
              </div>
              <?php 
              $z=0;
              for (; $z < count($clients) and $clients[$z]['CodeClient']!=$projets[$_GET['pr']]['client']; $z++);
              ?>
                <p>Nom du client : <B><?php echo $clients[$z]['NomSociete']?></B></p>
                <p>Wilaya : <B><?php echo $clients[$z]['Wilaya']?></B></p>
                <p>Code client : <B><?php echo $clients[$z]['CodeClient']?></B></p>
                <p>Code Projet : <B><?php echo $projets[$_GET['pr']]['code']?></B></p>
                <p>BFT du projet : <B><?php echo $projets[$_GET['pr']]['bft']?></B></p>
                <!-- BFT du projet :
                <div class="input-group-sm ">
                    <div class="input-group-prepend">
                      <span class="input-group-text">B</span>
                      <FONT size="4pt"><B><?php echo $projets[$_GET['pr']]['bft']?></B></FONT>
                    </div>
                </div> -->
            </div>
           </form>
          </div>  

<!----------------------------------------------------------------------PROJETS_DU_CLIENT------------------------------------------------------------------------------->
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-success shadow-sm">
              <div class="card-header">
                <h5 class="card-title">Projets du client</h5>
              </div> 
              <?php 
              $p=0;
              for (; $p < count($projets); $p++){
                if($projets[$_GET['pr']]['client']==$projets[$p]['client']){ ?>
                   <button type="button" class="btn btn-success" onclick="window.location.href='projets.php?pr=<?php echo $p;?>';" ><?php echo $projets[$p]['nom']."  ".$projets[$p]['dateCreation']?></button>
              <?php }} ?>
            </div>
           </form>
          </div>  

<!----------------------------------------------------------------------LE_PROJET-------------------------------------------------------------------------------------->
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-warning shadow-sm">
              <div class="card-header">
                <h5 class="card-title">Le projet</h5>
              </div>
             <span>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" <?php if($projets[$_GET['pr']]['etat']==0) echo 'checked'?>>
                <label class="form-check-label" for="inlineRadio1">En Cours</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" <?php if($projets[$_GET['pr']]['etat']==1) echo 'checked'?>>
                <label class="form-check-label" for="inlineRadio2">Reporté</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3" <?php if($projets[$_GET['pr']]['etat']==2) echo 'checked'?>>
                <label class="form-check-label" for="inlineRadio3">Terminé</label>
              </div> 
            </span>
              <textarea class="form-control" id="projetText" name="projetText" rows="3"><?php echo $projets[$_GET['pr']]['description']?></textarea>
              <div class="btn-group" role="group">
                <button type="button" id="okprojetModif" name="okprojetModif" class="btn btn-warning">OK</button>
              </div>
            </div>
           </form>
          </div> 
       </div>    
      </div>

      <div class="container">
        <div class="row">


<!----------------------------------------------------------------------PROFORMAS-------------------------------------------------------------------------------------->
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-primary shadow-sm">
              <div class="card-header">
                <h5 class="card-title">Proformas</h5>
              </div>
              <?php for($prof=0;$prof<count($proformas);$prof++){
                if($proformas[$prof]['projet']==$projets[$_GET['pr']]['code']){
               ?>
               <p><input type="radio" name="radioProforma" id="<?php echo "radioProforma".$proformas[$prof]['code'] ?>"><?php echo "Proforma "."N°".$proformas[$prof]['code']?></p>
               <!-- <div class="form-check">
                <input class="form-check-input" type="radio" name="radioProforma" id="<?php echo "radioProforma".$proformas[$prof]['code'] ?>" value="<?php echo $proformas[$prof]['code'] ?>" >
                <label class="form-check-label" for="<?php echo "radioProforma".$proformas[$prof]['code'] ?>"><?php echo "Proforma "."N°".$proformas[$prof]['code']?></label>
              </div> -->
            <?php }}?>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-primary" onclick="window.location.href='proforma.php?pr=<?php echo $projets[$_GET['pr']]['id'];?>&categ=Postes%20Premium'">Nouveau</button>
                <button type="button" class="btn btn-primary" onclick="window.location.href='proforma.php?pf=<?php if(isset($_POST['radioProforma'])) echo $_POST['radioProforma']; ?>&pr=<?php echo $projets[$_GET['pr']]['id'];?>&categ=Postes%20Premium'">Editer</button>
              </div>
            </div>
           </form>
          </div>  

<!----------------------------------------------------------------------BONS_DE_COMMANDE-------------------------------------------------------------------------------------->
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-secondary shadow-sm">
              <div class="card-header">
                <h5 class="card-title">Bon de commande</h5>
              </div> 
              <?php for($prof=0;$prof<count($proformas);$prof++){
                if($proformas[$prof]['projet']==$projets[$_GET['pr']]['code']){
               ?>
               <p><input type="radio" name="boxBon" id="<?php echo "boxBon".$proformas[$prof]['code'] ?>"><?php echo "Bon de commande "."N°".$proformas[$prof]['code']?></p>
            <?php }}?>
            </div>
           </form>
          </div>  

<!----------------------------------------------------------------------RAPPORTS-------------------------------------------------------------------------------------->
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-dark shadow-sm">
              <div class="card-header">
                <h5 class="card-title">Rapports</h5>
              </div>
              <?php for($rap=0;$rap<count($rapports);$rap++){
                if($rapports[$rap]['projet']==$projets[$_GET['pr']]['code']){
               ?>
               <p><input type="radio" name = "boxRapport" id="<?php echo "boxRapport".$rapports[$rap]['id'] ?>"><?php echo "Rapport "."N°".($rap+1)?></p>
            <?php }}?>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-dark" onclick="window.location.href='rapports.php?pr=<?php echo $projets[$_GET['pr']]['id'];?>'">Nouveau</button>
                <button type="button" class="btn btn-dark">Editer</button>
              </div>
            </div>
           </form>
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
<script src="js/classie.js"></script>
<script src="js/borderMenu.js"></script>
</html>