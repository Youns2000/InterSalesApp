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
  $sql_clients='SELECT NomSociete,CodeClient,Adresse,CodePostal,Ville,Wilaya,Pays,NIF,EmailResp1,EmailResp2
          FROM clients
          ORDER BY id;';
  $sql_pays ='SELECT code , alpha2 , alpha3, nom_en_gb, nom_fr_fr
          FROM pays
          ORDER BY id;';

  $sql_proformas = 'SELECT id,DateCreation,DateValid,EmisPar,Client,DelaiLivraison,PortDest,Engins,Options, monnaie
          FROM proformas
          ORDER BY id;';

  $sqlEnregistrer = 'INSERT INTO `proformas` (`id`, `DateCreation`, `DateValid`, `EmisPar` , `Client`, `DelaiLivraison`, `PortDest`, `Engins`, `Options`, `monnaie`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);';

  $sqlAddCateg = 'INSERT INTO `others` (`CatégoriesProduits`) VALUES (?);';

  $sqlAddEng = 'INSERT INTO `engins` (`Categorie`, `Marque`, `Type`, `Ref`, `Prix`, `prix_transport`, `Origine`, `Numero_serie`, `Annee_Fabrication`, `Type_Moteur`, `Numero_Serie_Moteur`, `ConfBase`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);';

  $sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';

          $categ=array();
          $engins=array();
          $clients=array();
          $options=array();
          $pays=array();
          $data=array();
          $proformas=array();
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <meta name="msapplication-config" content="favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c">
    <link href="css/style_menu.css" rel="stylesheet">
    <link href="css/dashboard.css" rel="stylesheet">

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

      function checkpass()
      {
      var ClientName = document.getElementById("inputClientName");
      var ClientWilaya = document.getElementById("inputClientWilaya");
      var ClientCode = document.getElementById("inputClientCode");
      var tab = <?php echo json_encode($clients); ?>;

        if(in_array(ClientName.value,ClientWilaya.value,ClientCode.value,tab))
        {
          document.getElementById("inputClientName").style.backgroundColor="51FE0C";
          document.getElementById("inputClientWilaya").style.backgroundColor="51FE0C";
          document.getElementById("inputClientCode").style.backgroundColor="51FE0C";
        }
        else
        {
          document.getElementById("inputClientName").style.backgroundColor="E13535";
          document.getElementById("inputClientWilaya").style.backgroundColor="E13535";
          document.getElementById("inputClientCode").style.backgroundColor="E13535";
        }
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
                  <a class="navbar col-sm-0 col-md-2 mr-0" href=""></a> 
                
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
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
              <?php
              $i=0;
              while ($i<count($categ)) {
                ?>
                <button class="nav-item" type=""><a class="nav-link" href="proforma.php?categ=<?php echo htmlspecialchars($categ[$i][0]); ?>" name=<?php echo str_replace(' ', '-',$categ[$i][0]) ?> ><?php echo $categ[$i][0] ?></a></button>
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



<!----------------------------------------------------------------------LISTE_ENGINS-------------------------------------------------------------------------------------->

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
                    <a class="nav-link" href="proforma.php?categ=<?php echo htmlspecialchars($_GET['categ']);?>&enginM=<?php echo htmlspecialchars($engins[$j]['Marque']);?>&enginT=<?php echo htmlspecialchars($engins[$j]['Type']);?>"> <?php echo $engins[$j]['Marque'].' '.$engins[$j]['Type']; ?> </a>
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

<!---------------------------------------------------------------------CONFIG/OPTIONS/SUBMIT--------------------------------------------------------------------------------->

          <div class="col-lg-8">
              
              <div calss="container">
                  <form class="form-horizontal" method="post" >
                  <div class="col-lg-20">
                    <div class="card mb-4 shadow-sm">
                      <div class="card-header">
                        <h5 class="card-title">Configuration de base</h5>
                      </div>
                      <div class="row">
                          <?php
                          if(isset($_GET['enginM'])){
                            $x=0;
                            while(($_GET['enginM']!=$engins[$x]['Marque'] or $_GET['enginT']!=$engins[$x]['Type']) and $x<count($engins)-1){
                              $x++;
                            }?>
                            <div class="col-lg-2">
                              <input type="number" name="nbConfBase" class="form-control" placeholder="" value=<?php echo qteArticle($engins[$x]['Ref']."/".$engins[$x]['Categorie']."/".$engins[$x]['Marque']."/".$engins[$x]['Type']."/".$engins[$x]['Origine']."/".$engins[$x]['ConfBase']) ?> >
                            </div>
                            <div class="col-lg-10">
                            <p align="justify"><?php echo $engins[$x]['ConfBase'];?></p>
                            </div>
                            <?php
                          }
                          ?>
                        </div>
                    </div>
                  </div>

                    <div class="col-lg-20">
                      <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                          <h5 class="card-title">Options</h5>
                        </div>
                        <div calss="container">
                        <?php
                          if(isset($_GET['enginM'])){
                                $x=0;
                                while(($_GET['enginM']!=$engins[$x]['Marque'] or $_GET['enginT']!=$engins[$x]['Type']) and $x<count($engins)-1){
                                      $x++;
                                }
                                $y=0;
                                $option=1;
                                while($y<count($options)){
                                  if($options[$y]['Engin']==$engins[$x]['id']){
                                    /*echo qteOption($engins[$x]['Ref']."/".$engins[$x]['Categorie']."/".$engins[$x]['Marque']."/".$engins[$x]['Type']."/".$engins[$x]['Origine']."/".$engins[$x]['ConfBase'],$option);*/
                                  ?>
                                      <div class="row">
                                        <div class="col-lg-2">
                                          <input type="number" name="<?php echo $_GET['enginM'].$_GET['enginT']."Option".($option) ?>" class="form-control" placeholder="" value=<?php echo qteOption($engins[$x]['Ref']."/".$engins[$x]['Categorie']."/".$engins[$x]['Marque']."/".$engins[$x]['Type']."/".$engins[$x]['Origine']."/".$engins[$x]['ConfBase'],$option) ?>>
                                        </div>
                                        <div class="col-lg-10">
                                          <p align="justify"><?php  echo $options[$y]['Nom']; ?></p>
                                        </div>
                                      </div>
                                      <br>
                        <?php
                        $option++;
                        }
                        $y++;
                        }
                        }?>
                      </div>
                      </div>
                    </div>
                    <button class="btn btn-lg btn-block btn-primary" type="submit">Sauvegarder</button>
                  </form>    
            </div>
            </div>

<!-------------------------------------------------------------CLIENT/VISUAL/SEND/SAVE-------------------------------------------------------------------------------------->

            <div class="col-lg-12">
              <form class="form-horizontal" method="post" >
                  <div class="form-group">
                  <div class="card-deck mb-3 text-center">
                        <!--value=<?php echo $clients[$_GET['cID']][0] ?>-->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                              <h5 class="card-title">Nom</h5>
                            </div>
                            <label for="inputClientName" class="sr-only">Nom</label>
                            <input type="text" name="inputClientName" id="inputClientName" class="form-control" placeholder="" onkeyup="checkpass();" value="<?php if(isset($_SESSION['NomSociete'])) { echo $_SESSION['NomSociete']; }?>" />
                        </div>
                        <!-- <p><?php if(isset($_SESSION['NomSociete'])) { echo $_SESSION['NomSociete']; } ?></p> -->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                              <h5 class="card-title">Wilaya</h5>
                            </div>
                            <label for="inputClientWilaya" class="sr-only">Wilaya</label>
                            <input type="text" name="inputClientWilaya" id="inputClientWilaya" class="form-control" placeholder="" onkeyup="checkpass();" />
                        </div>

                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                              <h5 class="card-title">Code Client</h5>
                            </div>
                            <label for="inputClientCode" class="sr-only">CodeClient</label>
                            <input type="text" name="inputClientCode" id="inputClientCode" class="form-control" placeholder="" onkeyup="checkpass();"/>
                        </div>
                  </div>
                </div>

                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-4">
                        <!--onclick="window.location.href = 'pdf_proforma.php';"-->
                        <!--<button name="visualiserButton" class="btn btn-lg btn-block btn-primary" type="submit">Visualiser</button>-->
                        <button type="button" data-toggle="modal" data-target="#myModal_visu" class="btn btn-lg btn-block btn-primary">Visualiser</button>
                        <div class="modal fade" id="myModal_visu" role="dialog">
                          <div class="modal-dialog modal-dialog-centered">
                          
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                <select class="custom-select d-block w-100" id="getPorts" name="getPorts">
                                  <option selected value>Choisir le port de destination...</option>
                                  <?php
                                  for ($i=0; $categ[$i]['Ports']!=""; $i++) {
                                  ?>
                                  <option value = "<?php echo $categ[$i]['Ports']?>"><?php echo $categ[$i]['Ports']?></option>
                                <?php }?>
                                </select>
                                <select class="custom-select d-block w-100" id="getDevise" name="getDevise">
                                  <option selected value>Choisir la devise du proforma...</option>
                                  <?php
                                  for ($i=0; $categ[$i]['Devises']!=""; $i++) {
                                  ?>
                                  <option value = "<?php echo $categ[$i]['Devises']?>"><?php echo $categ[$i]['Devises']?></option>
                                <?php }?>
                                </select>
                                <input id="datevalidite" class="form-control"  name="datevalidite" type="date" />
                                <input id="delailivraison" class="form-control" name="delailivraison" type="number" />
                              </div>
                              <div class="modal-footer">
                                <button  id = "visualiser" name="visualiser" type="submit" class="btn btn-default">OK</button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
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
                        <button type="button" data-toggle="modal" data-target="#myModal_enr" class="btn btn-lg btn-block btn-primary">Enregistrer</button>
                        <div class="modal fade" id="myModal_enr" role="dialog">
                          <div class="modal-dialog modal-dialog-centered">
                          
                            <!-- Modal content-->
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                <select class="custom-select d-block w-100" id="getPortsEnr" name="getPortsEnr">
                                  <option selected value>Choisir le port de destination...</option>
                                  <?php
                                  for ($i=0; $categ[$i]['Ports']!=""; $i++) {
                                  ?>
                                  <option value = "<?php echo $categ[$i]['Ports']?>"><?php echo $categ[$i]['Ports']?></option>
                                <?php }?>
                                </select>
                                <select class="custom-select d-block w-100" id="getDeviseEnr" name="getDeviseEnr">
                                  <option selected value>Choisir la devise du proforma...</option>
                                  <?php
                                  for ($i=0; $categ[$i]['Devises']!=""; $i++) {
                                  ?>
                                  <option value = "<?php echo $categ[$i]['Devises']?>"><?php echo $categ[$i]['Devises']?></option>
                                <?php }?>
                                </select>
                                <input id="datevalidite2" class="form-control"  name="datevalidite2" type="date" />
                                <input id="delailivraison2" class="form-control" name="delailivraison2" type="number" />
                              </div>
                              <div class="modal-footer">
                                <button name = "enregistrer" type="submit" class="btn btn-default">OK</button>
                              </div>
                            </div>
                          </div>
                        </div>
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
        <script src="dashboard.js"></script></body>
</html>