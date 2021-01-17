<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 
  else{
    $_SESSION['currentPage'] = "projets";
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

  $sql_projets = 'SELECT id,user,nom,code,client,bft,dateCreation,description,etat, montant, objectif, offre, avancement, concurrence
          FROM projets
          -- WHERE user="'.$_SESSION['email'].'"
          ORDER BY id;';

  $sql_rapports = 'SELECT id,code,num,projet,commandes,visitesClient,offres,remarques
          FROM rapports
          ORDER BY id;';

  $sqlEnregistrer = 'INSERT INTO `proformas` (`id`, `DateCreation`, `DateValid`, `EmisPar` , `Client`, `DelaiLivraison`, `PortDest`, `Engins`, `Options`, `monnaie`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);';

  $sqlAddCateg = 'INSERT INTO `others` (`CatégoriesProduits`) VALUES (?);';

  $sqlAddEng = 'INSERT INTO `engins` (`Categorie`, `Marque`, `Type`, `Ref`, `Prix`, `prix_transport`, `Origine`, `Numero_serie`, `Annee_Fabrication`, `Type_Moteur`, `Numero_Serie_Moteur`, `ConfBase`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);';
  
  $sqlAddProjet = 'INSERT INTO `projets` (`nom`, `user`, `code`, `client`, `bft`, `dateCreation`, `description`, `etat`) VALUES (?,?,?,?,?,?,?,?);';

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

          $etats = ["Prosp","Demande","Offre","Nég","Conclu","LC Open"];

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
            //  print_r($projets);

             $stmt8 = $db->prepare($sql_rapports);
             $stmt8->execute(array());
             $rapports = $stmt8->fetchAll();
             unset($db);

            if(/*(isset($_POST['inputClientName']) or isset($_POST['inputClientWilaya']) or isset($_POST['inputClientCode'])) and*/ isset($_POST['visualiser'])){
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
                          // getProforma(false);
                          //header('Location: pdf_proforma.php');
                          //exit();
                      }
                      else{
                        $i++;
                      }
              }
            }
            else if(/*(isset($_POST['inputClientName']) or isset($_POST['inputClientWilaya']) or isset($_POST['inputClientCode'])) and*/ isset($_POST['enregistrer'])){
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

            else if(/*(isset($_POST['inputClientName']) or isset($_POST['inputClientWilaya']) or isset($_POST['inputClientCode'])) and*/ isset($_POST['envoyermail'])){
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
                // sendmail($str,'Proforma','proforma.pdf',true,getProforma(true));
                
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
            
          else if((isset($_POST['newProjetClientName']) or isset($_POST['newProjetClientWilaya']) or isset($_POST['newProjetClientCode'])) and isset($_POST['ajouterProjet']) and !in_array($_POST['newProjetName'], $projets)){
                    $i = 0;
                    $found = false;
                    while($i<count($clients)){
                      if( ($clients[$i]['NomSociete']==$_POST['newProjetClientName'] and $clients[$i]['Wilaya']==$_POST['newProjetClientWilaya']) or $clients[$i]['CodeClient']==$_POST['newProjetClientCode']){
                        $found = true;
                        break;
                      }
                      else $i++;
                    }
                    if($found == true){
                      try {
                       
                            $db = include 'db_mysql.php';
                            $codeProjet = $clients[$i]['CodeClient'].date("my").strval($projets[count($projets)-1]['id']+1);//code projet : code client + mois + annee + id projet
                             $stmtenr = $db->prepare($sqlAddProjet);
                             $stmtenr->execute(array( $_POST['newProjetName'], $_SESSION['email'],$codeProjet,$clients[$i]['CodeClient'],$_POST['newProjetB'].'/'.$_POST['newProjetF'].'/'.$_POST['newProjetT'],date("d/m/Y"),$_POST['newProjetText'],$_POST['newProjetEtat']));

                             $nb_insert = $stmtenr->rowCount();
                             unset($db);
                             header('Refresh: 0');
                             
                        }
                          catch (Exception $e){
                             print "Erreur ! " . $e->getMessage() . "<br/>";
                          }
                      }
                else{
                  ?>
                  <script type="text/javascript">
                    alert("Ce client n'existe pas");
                  </script>
                  <?php
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
                  $sth = $dbco->prepare('UPDATE projets SET description=?, etat=?, montant=?, objectif=?, offre=?, avancement=?, concurrence=?, bft=? WHERE code=?');
                  $sth->execute(array($_POST['projetText'],$_POST['etatsprojet'],$_POST['montant'],$_POST['objectif_vente'],"offre",$_POST['avancement'],$_POST['concurrence0'].'/'.$_POST['concurrence1'].'/'.$_POST['concurrence2'],$_POST['B'].'/'.$_POST['F'].'/'.$_POST['T'],$projets[$_GET['pr']]['code']));
                  unset($dbco);
                  header('Refresh: 0');
            }

            else if(isset($_POST['editerProformaProjet']) and isset($_POST['radioProforma']) and file_exists("proformas/".$_POST['radioProforma'].'.pdf')){
              $_SESSION['currentProforma'] = $_POST['radioProforma'];
              header('Location: proforma_visu.php?pr='.$_GET['pr']);
            }

            else if(isset($_POST['editerBonProjet']) and isset($_POST['boxBon']) and file_exists("bons/".$_POST['boxBon'].'.pdf')){
              $_SESSION['currentBon'] = $_POST['radioProforma'];
              header('Location: bon_de_commande_visu.php?pr='.$_GET['pr']);
            }

            else if(isset($_POST['editerRapportProjet']) and isset($_POST['boxRapport']) and file_exists("rapports/".$_POST['boxRapport'].'.pdf')){
              $_SESSION['currentRapport'] = $_POST['boxRapport'];
              header('Location: rapports_visu.php?pr='.$_GET['pr']);
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
    <link href="my_css.css" rel="stylesheet">
  </head>


<!------------------------------------------------------------------------MENU_HAUT--------------------------------------------------------------------------------->


<body>
<header>
          <?php include("header.php"); ?>
</header>

<!------------------------------------------------------------------------MENU_GAUCHE------------------------------------------------------------------------------------>

<form method="post">
    <div class="row">
      <nav class="col-md-0 d-md-block sidebar scroll">
        <div class="d-flex" id="wrapper">
            <div class="" id="sidebar-wrapper">
              <div class="list-group list-group-flush">
                <?php
                // $client_pr = array_unique(array_column($tmp,'client'));
                $cli = array();
                $i=0;
                while ($i<count($projets)) { 
                  if($projets[$i]['user']==$_SESSION['email']){
                    if(!in_array($projets[$i]['client'],$cli)){
                      array_push($cli,$projets[$i]['client']);
                      $c=0;
                      for (; $c < count($clients); $c++) if($clients[$c]['CodeClient']==$projets[$i]['client']) break;
                      if($projets[$_GET['pr']]['client']==$projets[$i]['client']){
                        echo '<a href="projets.php?pr='.$i.'" class="list-group-item list-group-item-action active">'.$clients[$c]['NomSociete'].'</a>';
                      }
                      else echo '<a href="projets.php?pr='.$i.'" class="list-group-item list-group-item-action bg-clair">'.$clients[$c]['NomSociete'].'</a>';
                    }
                  }
                  $i++;  
                }
                ?>
                <button type="button" data-backdrop="false" data-toggle="modal" data-target="#ajouter_c" class="btn btn-light bg-clair btn-sm"><span data-feather="plus-circle"></button>
                </div>
              </div>
              <!-----------------------------MODAL------------------------>
                    <div class="modal fade" id="ajouter_c" role="dialog">
                      <div class="modal-dialog modal-dialog-centered modal-xl">
                        
                        <!-- Modal content-->
                        <div class="modal-content">

                          <div class="modal-header">
                            <h4>CREATION NOUVEAU PROJET</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>

                          <div class="modal-body">
                            <p style="text-align:center;"><B>CLIENT</B></p>
                            <div style="margin-left: 18%;">
                              <div style="display: inline-block;"><p>Nom Societe : <input id="newProjetClientName" name="newProjetClientName" type="text" /></p></div>
                              <div style="display: inline-block;"><p>Wilaya : <input id="newProjetClientWilaya" name="newProjetClientWilaya" type="text"/></p></div>
                              <div style="display: inline-block;"><p>Code Client : <input id="newProjetClientCode" name="newProjetClientCode" type="text"/></p></div>
                            </div>

                            <hr width="100%" color="grey">

                            <p><B>PROJET</B></p>
                            <p>Nom : <input id="newProjetName" name="newProjetName" type="text" required/></p>
                            <!-- <p>Code : <input id="newProjetCode" name="newProjetCode" type="text" required/></p> -->
                            <p>B : <select class="custom-select d-block w-100" name="newProjetB" required>
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

                            <p><select class="custom-select-2 d-block w-100" name="newProjetEtat" >
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

      <div class="container-fluid content-row ">

        <div class="row">

          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-card-rouge shadow-sm h-100">
              <div class="card-header">
                <h5 class="card-title">Projets du client</h5>
              </div> 
              <?php 
              if(count($projets)>0){
              $p=0;
              for (; $p < count($projets); $p++){
                $style="bg-card-rouge";
                if($projets[$_GET['pr']]['client']==$projets[$p]['client'] and $projets[$p]['user']==$_SESSION['email']){ 
                  if($projets[$_GET['pr']]['code']==$projets[$p]['code']) $style="";?>
                   <button type="button" class="btn <?php echo $style;?> btn-danger " onclick="window.location.href='projets.php?pr=<?php echo $p;?>';" ><?php echo $projets[$p]['nom']."  ".$projets[$p]['dateCreation']?></button>
              <?php }}} ?>
              
            </div>
           </form>
          </div>   

<!----------------------------------------------------------------------STATUT DU PROJET------------------------------------------------------------------------------->
            <div class="col-lg-4">
            
            <div class="card mb-4 text-white bg-card-vert shadow-sm">
              <div class="card-header">
                <h5 class="card-title">Statut du Projet</h5>
              </div>
              
              <form method="post">
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <?php 
              
              for ($i=0; $i < 6; $i++) {
                $checked = "";
                $style = "" ;
                if(count($projets)>0 && $projets[$_GET['pr']]['etat']==$etats[$i]){
                  $checked = "checked";
                  $style = "border-color:#ffff";
                }
                echo "<label class=\"btn btn-sm btn-success\" style=\"".$style."\" for=\"".$etats[$i]."\">".$etats[$i];
                echo "<input type=\"radio\" class=\"btn-check\" name=\"etatsprojet\" id=\"".$etats[$i]."\" value=\"".$etats[$i]."\" autocomplete=\"off\" ".$checked.">";
                echo "</label>";

              }
              ?>
              </div>


              <div class="modal-body">

              <div class="row">
                  <label for="montant" style="font-size:12" class="col-form-label">Objectif de vente</label>
                  <div class="col-lg-9">
                    <input type="text" class="form-control-plaintext form-control-sm" name="objectif_vente" value="<?php echo $projets[$_GET['pr']]['objectif'];?>">
                  </div>
              </div>

              <div class="row">
                    <label for="montant" style="font-size:12" class="col-form-label">Montant</label>
                    <div  class="col-lg-3">
                      <input style="padding-right:0" type="text" class="form-control-plaintext form-control-sm" name="montant" value="<?php echo $projets[$_GET['pr']]['montant'];?>">
                    </div>

                    <label for="montant" style="font-size:12" class="col-form-label">Avancement</label>
                    <div class="col-lg-2">
                      <input type="text" class="form-control-plaintext form-control-sm" name="avancement" value="<?php echo $projets[$_GET['pr']]['avancement'];?>">
                    </div>

                    <?php $bft =  explode('/',$projets[$_GET['pr']]['bft']);?>
                    <label for="B" style="font-size:12" class="col-form-label">B&nbsp;</label>
                    <input type="text" style="width:15" class="form-control-plaintext form-control-sm" name="B" value="<?php if(isset($bft[0])) echo $bft[0];?>">
                    
                    <label for="F" style="font-size:12" class="col-form-label">F&nbsp;</label>
                    <input type="text" style="width:15" class="form-control-plaintext form-control-sm" name="F" value="<?php if(isset($bft[1])) echo $bft[1];?>">

                    <label for="T" style="font-size:12" class="col-form-label">T&nbsp;</label>
                    <input type="text" style="width:15" class="form-control-plaintext form-control-sm" name="T" value="<?php if(isset($bft[2])) echo $bft[2];?>">


              </div>

              <div class="row">

              <label style="font-size:12" class="col-form-label">Concurrence</label>
                <?php $concu =  explode('/',$projets[$_GET['pr']]['concurrence']);?>
                  <div class="col-lg-3">
                    <input type="text" class="form-control-plaintext form-control-sm" name="concurrence0" value="<?php if(isset($concu[0])) echo $concu[0];?>">
                  </div>
                  <div class="col-lg-3">
                    <input type="text" class="form-control-plaintext form-control-sm" name="concurrence1" value="<?php if(isset($concu[1])) echo $concu[1];?>">
                  </div>
                  <div class="col-lg-3">
                    <input type="text" class="form-control-plaintext form-control-sm" name="concurrence2" value="<?php if(isset($concu[2])) echo $concu[2];?>">
                  </div>

              </div>

              </div>

              <textarea class="form-control-plaintext" id="projetText" name="projetText" rows="3"><?php if(count($projets)>0){ echo $projets[$_GET['pr']]['description']; }?></textarea>
              <div class="d-flex flex-column mt-auto">
                <div class="btn-group" role="group">
                  <button type="submit" id="okprojetModif" name="okprojetModif" class="btn bg-card-vert btn-success">OK</button>
                </div>
              </div>
              </form>
            </div>
            
          </div>  

<!----------------------------------------------------------------------ACTIONS-------------------------------------------------------------------------------------->
          <div class="col-lg-4">
            <form method="post">
            <div class="card mb-4 text-white bg-card-jaune shadow-sm">
              <div class="card-header">
                <h5 class="card-title">Actions à faire</h5>
              </div>

            </div>
           </form>
          </div> 
       </div>    
      </div>

      <div class="container-fluid content-row h-100">
        <div class="row">


<!----------------------------------------------------------------------PROFORMAS-------------------------------------------------------------------------------------->
          <div class="col-lg-4 ">
            <form method="post">
            <div class="card mb-4 text-white bg-card-bleu shadow-sm h-100 ">
              <div class="card-header">
                <h5 class="card-title">Proformas</h5>
              </div>

              <?php
                if(count($projets)>0){
                for($prof=0;$prof<count($proformas);$prof++){
                if($proformas[$prof]['projet']==$projets[$_GET['pr']]['code']){
               ?>
              <div class="scroll">
                <p><input type="radio" name="radioProforma" value="<?php echo $proformas[$prof]['code'] ?>"><?php echo "Proforma "."N°".$proformas[$prof]['code']?></p>
              </div>
               <!-- <div class="form-check">
                <input class="form-check-input" type="radio" name="radioProforma" id="<?php echo "radioProforma".$proformas[$prof]['code'] ?>" value="<?php echo $proformas[$prof]['code'] ?>" >
                <label class="form-check-label" for="<?php echo "radioProforma".$proformas[$prof]['code'] ?>"><?php echo "Proforma "."N°".$proformas[$prof]['code']?></label>
              </div> -->
            <?php }}?>
            <div class="d-flex flex-column mt-auto">
              <div class="btn-group" role="group">
                <!-- <button type="button" class="btn bg-card-bleu btn-primary" onclick="window.location.href='proforma.php?pr=<?php echo $projets[$_GET['pr']]['id'];?>&categ=Postes%20Premium'">Nouveau</button> -->

                <button type="button" class="btn bg-card-bleu btn-primary" onclick="window.location.href='proforma.php?pr=<?php echo $_GET['pr']?>&categ=Postes%20Premium'">Nouveau</button>
                <button type="submit" class="btn bg-card-bleu btn-primary" name="editerProformaProjet">Editer</button>
              </div>
            </div>
          <?php } ?>
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
              <?php 
              if(count($projets)>0){
                for($prof=0;$prof<count($proformas);$prof++){
                if($proformas[$prof]['projet']==$projets[$_GET['pr']]['code']){
               ?>
               <p><input type="radio" name="boxBon" value="<?php echo $proformas[$prof]['code'] ?>"><?php echo "Bon de commande "."N°".$proformas[$prof]['code']?></p>
            <?php }}?>

              <div class="d-flex flex-column mt-auto">
                <div class="btn-group" role="group">
                  <button type="submit" class="btn bg-card-mauve btn-secondary" name="editerBonProjet">Editer</button>
                </div>
              </div>
              <?php } ?>
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
              <?php 
              if(count($projets)>0){
                for($rap=0;$rap<count($rapports);$rap++){
                if($rapports[$rap]['projet']==$projets[$_GET['pr']]['code']){
               ?>
               <p><input type="radio" name = "boxRapport" value="<?php echo $rapports[$rap]['code'] ?>"><?php echo "Rapport "."N°".$rapports[$rap]['num']?></p>
            <?php }}?>
            <div class="d-flex flex-column mt-auto">
              <div class="btn-group" role="group">
                <button type="button" class="btn bg-card-bleuvert btn-dark" onclick="window.location.href='rapports.php?pr=<?php echo $_GET['pr']?>'">Nouveau</button>
                <button type="submit" name="editerRapportProjet" class="btn bg-card-bleuvert btn-dark">Editer</button>
              </div>
            </div>
            <?php } ?>
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