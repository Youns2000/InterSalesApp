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
                     header('Refresh: 0');
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
              }
            }

            else if(isset($_POST['okprojetModif'])){
              $dbco = include 'db_mysql.php';
                  $sth = $dbco->prepare('UPDATE projets SET description=? , etat=? WHERE code=?');
                  $sth->execute(array($_POST['projetText'],$_POST['inlineRadioOptions'],$projets[$_GET['pr']]['code']));
                  unset($dbco);
                  header('Refresh: 0');
            }

            else if(isset($_POST['editerProformaProjet']) and isset($_POST['radioProforma']) and file_exists("proformas/".$_POST['radioProforma'].'.pdf')){
              $_SESSION['currentProforma'] = $_POST['radioProforma'];
              header('Location: proforma_visu.php?pr='.$_GET['pr']);
            }

            else if(isset($_POST['editerBonProjet']) and isset($_POST['boxBon']) and file_exists("bons/".$_POST['boxBon'].'.pdf')){
              $_SESSION['currentProforma'] = $_POST['radioProforma'];
              header('Location: bon_de_commande_visu.php?pr='.$_GET['pr']);
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
      .container-fluid{
        padding-top: 50px;
      }
      .special-menu{
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
        font-size: 15;
        background-color: rgba(52,58,64,0.8)
      }
      .color-menu:hover{
        background-color: #303234!important;
          border-color: #303234!important;
      }
      .color-menu,
      .color-menu:active,
      .color-menu:visited,
      .color-menu:focus {
          background-color: #50575D!important;
          border-color: #50575D!important;
      }
      li{
        margin-left: 20px;
      }
      
      .sidebar{
        margin-top:100px;
      }
      .navbar{
        padding-top: 0px;
        padding-bottom: 0px;
        padding-left: 0px;
      }

      .padding-top-0{
        padding-top: 0px;
      }
      .list-group{
        padding-top: 10px;
      }
      .ul-special{
        margin-left: 290px;
      }
      .btn-lg{      
        /*width: 250px;*/
      }
      
      .bg-clair{
        background-color: #f8f9fa85;
      }
      .list-group-item.active {
          z-index: 2;
          color: #fff;
          background-color: #1d2124;
          border-color: #1d2124;
      }
      .bg-card-rouge{
        background-color: rgb(123 42 50 / 75%)!important;
      }
      .bg-card-vert{
        background-color: rgb(56 128 72 / 75%)!important;
      }
      .bg-card-jaune{
        background-color: rgb(193 150 25 / 75%)!important;
      }
      .bg-card-bleu{
        background-color: rgb(23 84 150 / 75%)!important;
      }
      .bg-card-mauve{
        background-color: rgb(47 27 113 / 75%)!important;
      }
      .bg-card-bleuvert{
        background-color: rgb(26 123 117 / 75%)!important;
      }
      .session{
        margin-right: 2%;
        margin-top: 0.5%;
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
          <nav class="navbar navbar-expand-lg navbar-dark">

                  <ul class="navbar-nav mr-auto ul-special session">
                    <li class="nav-item">

                      <a class="btn btn-dark btn-lg special-menu" href="marketing.php?categ=Postes%20Premium">
                        <svg width="1.5em" height="1.5em" viewBox="0.5 1.5 16 16" class="bi bi-cart3" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                          <path fill-rule="evenodd" d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm7 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                        </svg> PRODUITS </a>
                    </li>
                    <li class="nav-item">
                      <a class="btn btn-dark btn-lg special-menu active" href="projets.php?pr=0">
                      <svg width="1.5em" height="1.5em" viewBox="0.5 1.5 16 16" class="bi bi-folder2-open" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M1 3.5A1.5 1.5 0 0 1 2.5 2h2.764c.958 0 1.76.56 2.311 1.184C7.985 3.648 8.48 4 9 4h4.5A1.5 1.5 0 0 1 15 5.5v.64c.57.265.94.876.856 1.546l-.64 5.124A2.5 2.5 0 0 1 12.733 15H3.266a2.5 2.5 0 0 1-2.481-2.19l-.64-5.124A1.5 1.5 0 0 1 1 6.14V3.5zM2 6h12v-.5a.5.5 0 0 0-.5-.5H9c-.964 0-1.71-.629-2.174-1.154C6.374 3.334 5.82 3 5.264 3H2.5a.5.5 0 0 0-.5.5V6zm-.367 1a.5.5 0 0 0-.496.562l.64 5.124A1.5 1.5 0 0 0 3.266 14h9.468a1.5 1.5 0 0 0 1.489-1.314l.64-5.124A.5.5 0 0 0 14.367 7H1.633z"/>
                      </svg> PROJETS <span class="sr-only">(current)</span></a>
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

<!------------------------------------------------------------------------MENU_GAUCHE------------------------------------------------------------------------------------>

<form method="post">
    <div class="row">
      <nav class="col-md-0 d-md-block sidebar">
        <div class="d-flex" id="wrapper">
            <div class="" id="sidebar-wrapper">
              <div class="list-group list-group-flush">
                <?php
                $i=0;
                while ($i<count($projets)) { 
                  if($_GET['pr']==$i) echo '<a href="projets.php?pr='.$i.'" class="list-group-item list-group-item-action active">'.$projets[$i]['nom'].'</a>';
                  else echo '<a href="projets.php?pr='.$i.'" class="list-group-item list-group-item-action bg-clair">'.$projets[$i]['nom'].'</a>';
                  $i++;
                }
                ?>
                <button type="button" data-backdrop="false" data-toggle="modal" data-target="#ajouter_c" class="btn btn-light bg-clair btn-sm"><span data-feather="plus-circle"></button>
                </div>
              </div>
              <!-----------------------------MODAL------------------------>
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
                            
                          </div>

                          <div class="modal-footer">
                            <button name = "ajouterProjet" type="submit" class="btn btn-default">OK</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  <!-----------------------------FIN_MODAL------------------------>
            </div>
            </nav>
          </div>
      </form>
    


<!----------------------------------------------------------------------CLIENT-------------------------------------------------------------------------------------->


<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4 padding-top-0">

      <div class="container-fluid content-row">

        <div class="row">

          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-card-rouge shadow-sm h-100">
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
            <div class="card mb-4 text-white bg-card-vert shadow-sm h-100">
              <div class="card-header">
                <h5 class="card-title">Projets du client</h5>
              </div> 
              <?php 
              $p=0;
              for (; $p < count($projets); $p++){
                if($projets[$_GET['pr']]['client']==$projets[$p]['client']){ ?>
                   <button type="button" class="btn bg-card-vert btn-success" onclick="window.location.href='projets.php?pr=<?php echo $p;?>';" ><?php echo $projets[$p]['nom']."  ".$projets[$p]['dateCreation']?></button>
              <?php }} ?>
            </div>
           </form>
          </div>  

<!----------------------------------------------------------------------LE_PROJET-------------------------------------------------------------------------------------->
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-card-jaune shadow-sm h-100">
              <div class="card-header">
                <h5 class="card-title">Le projet</h5>
              </div>
             <span>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="0" <?php if($projets[$_GET['pr']]['etat']==0) echo 'checked'?>>
                <label class="form-check-label" for="inlineRadio1">En Cours</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="1" <?php if($projets[$_GET['pr']]['etat']==1) echo 'checked'?>>
                <label class="form-check-label" for="inlineRadio2">Reporté</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="2" <?php if($projets[$_GET['pr']]['etat']==2) echo 'checked'?>>
                <label class="form-check-label" for="inlineRadio3">Terminé</label>
              </div> 
            </span>
              <textarea class="form-control" id="projetText" name="projetText" rows="7"><?php echo $projets[$_GET['pr']]['description']?></textarea>
              <div class="d-flex flex-column mt-auto">
                <div class="btn-group" role="group">
                  <button type="submit" id="okprojetModif" name="okprojetModif" class="btn bg-card-jaune btn-warning">OK</button>
                </div>
              </div>
            </div>
           </form>
          </div> 
       </div>    
      </div>

      <div class="container-fluid content-row">
        <div class="row">


<!----------------------------------------------------------------------PROFORMAS-------------------------------------------------------------------------------------->
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-card-bleu shadow-sm h-100">
              <div class="card-header">
                <h5 class="card-title">Proformas</h5>
              </div>
              <?php for($prof=0;$prof<count($proformas);$prof++){
                if($proformas[$prof]['projet']==$projets[$_GET['pr']]['code']){
               ?>
               <p><input type="radio" name="radioProforma" value="<?php echo $proformas[$prof]['code'] ?>"><?php echo "Proforma "."N°".$proformas[$prof]['code']?></p>
               <!-- <div class="form-check">
                <input class="form-check-input" type="radio" name="radioProforma" id="<?php echo "radioProforma".$proformas[$prof]['code'] ?>" value="<?php echo $proformas[$prof]['code'] ?>" >
                <label class="form-check-label" for="<?php echo "radioProforma".$proformas[$prof]['code'] ?>"><?php echo "Proforma "."N°".$proformas[$prof]['code']?></label>
              </div> -->
            <?php }}?>
            <div class="d-flex flex-column mt-auto">
              <div class="btn-group" role="group">
                <button type="button" class="btn bg-card-bleu btn-primary" onclick="window.location.href='proforma.php?pr=<?php echo $projets[$_GET['pr']]['id'];?>&categ=Postes%20Premium'">Nouveau</button>
                <button type="submit" class="btn bg-card-bleu btn-primary" name="editerProformaProjet">Editer</button>
              </div>
            </div>
            </div>
           </form>
          </div>  

<!----------------------------------------------------------------------BONS_DE_COMMANDE-------------------------------------------------------------------------------------->
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-card-mauve shadow-sm h-100">
              <div class="card-header">
                <h5 class="card-title">Bon de commande</h5>
              </div> 
              <?php for($prof=0;$prof<count($proformas);$prof++){
                if($proformas[$prof]['projet']==$projets[$_GET['pr']]['code']){
               ?>
               <p><input type="radio" name="boxBon" id="<?php echo $proformas[$prof]['code'] ?>"><?php echo "Bon de commande "."N°".$proformas[$prof]['code']?></p>
            <?php }}?>

              <div class="d-flex flex-column mt-auto">
                <div class="btn-group" role="group">
                  <button type="submit" class="btn bg-card-mauve btn-secondary" name="editerBonProjet">Editer</button>
                </div>
              </div>
            </div>
           </form>
          </div>  

<!----------------------------------------------------------------------RAPPORTS-------------------------------------------------------------------------------------->
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-card-bleuvert shadow-sm h-100">
              <div class="card-header">
                <h5 class="card-title">Rapports</h5>
              </div>
              <?php for($rap=0;$rap<count($rapports);$rap++){
                if($rapports[$rap]['projet']==$projets[$_GET['pr']]['code']){
               ?>
               <p><input type="radio" name = "boxRapport" id="<?php echo "boxRapport".$rapports[$rap]['id'] ?>"><?php echo "Rapport "."N°".($rap+1)?></p>
            <?php }}?>
            <div class="d-flex flex-column mt-auto">
              <div class="btn-group" role="group">
                <button type="button" class="btn bg-card-bleuvert btn-dark" onclick="window.location.href='rapports.php?pr=<?php echo $projets[$_GET['pr']]['id'];?>'">Nouveau</button>
                <button type="submit" class="btn bg-card-bleuvert btn-dark">Editer</button>
              </div>
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
<script src="dashboard.js"></script>
</body>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<script src="js/classie.js"></script>
<script src="js/borderMenu.js"></script>
<script src="js/navbar-top.js"></script>
</html>