<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 
  else{
    require('fonctions_panier.php');
    require('mail_php.php');
    require('pdf_bon.php');
    require('fpdf/fpdf.php');
    

  $sql_categ='SELECT CatégoriesProduits
          FROM others
          ORDER BY id;';
  $sql_engins='SELECT id, Categorie, Marque , Type, Ref, Prix, prix_transport ,Origine, ConfBase
          FROM engins
          ORDER BY id;';
  $sql_options='SELECT Engin, Nom, Prix
          FROM options
          ORDER BY id;';
  $sql_clients='SELECT NomSociete,CodeClient,Adresse,CodePostal,Ville,Wilaya,Pays,NIF,EmailResp1,EmailResp2
          FROM clients
          ORDER BY id;';
  $sql_proformas = 'SELECT id,code,DateCreation,DateValid,EmisPar,Client,projet,DelaiLivraison,PortDest,Engins,Options, monnaie
          FROM proformas
          ORDER BY id;';
   $sql_pays ='SELECT code , alpha2 , alpha3, nom_en_gb, nom_fr_fr
          FROM pays
          ORDER BY id;';             
  $sqlEnregistrer = 'INSERT INTO `proformas` (`id`, `DateCreation`, `DateValid`, `EmisPar` , `Client`, `DelaiLivraison`, `PortDest`, `Engins`, `Options`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?);';

  $sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';


          $categ=array();
          $engins=array();
          $clients=array();
          $options=array();
          $pays=array();
          $proformas=array();
          $db = include 'db_mysql.php';

          try {

             $stmt = $db->prepare($sql_categ);
             $stmt->execute(array());
             $categ = $stmt->fetchAll();

             $stmt2 = $db->prepare($sql_engins);
             $stmt2->execute(array());
             $engins= $stmt2->fetchAll();

             $stmt3 = $db->prepare($sql_clients);
             $stmt3->execute(array());
             $clients = $stmt3->fetchAll();

             $stmt4 = $db->prepare($sql_options);
             $stmt4->execute(array());
             $options = $stmt4->fetchAll();

             $stmt5 = $db->prepare($sql_proformas);
             $stmt5->execute(array());
             $proformas = $stmt5->fetchAll();

             $stmt6 = $db->prepare($sql_pays);
             $stmt6->execute(array());
             $pays = $stmt6->fetchAll();
             unset($db);

             $cocher = -1;
             for ($i=0; $i < count($proformas); $i++) { 
                if($proformas[$i]['EmisPar']==$_SESSION['email'] and isset($_POST['boxproforma'.$proformas[$i]['id']])){
                  $cocher = $proformas[$i]['id'];
                  break;
                }
             }

            if((isset($_POST['inputClientName']) or isset($_POST['inputClientWilaya']) or isset($_POST['inputClientCode'])) and isset($_POST['visualiserButton']) and $cocher!=-1){

                    $i = 0;
                    while($i<count($clients)){
                      if( ($clients[$i]['NomSociete']==$_POST['inputClientName'] and $clients[$i]['Wilaya']==$_POST['inputClientWilaya']) or $clients[$i]['NomSociete']==$_POST['inputClientCode'])
                      {

                        $num=0;
                        for (; $num < count($proformas); $num++){
                           if($proformas[$num]['id']==$cocher) break;
                        }
                        if($proformas[$num]['Client']==$clients[$i]['CodeClient']){

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
                          $_SESSION['port_dest'] = $proformas[$num]['PortDest'];
                          $_SESSION['Monnaie'] = $proformas[$num]['monnaie'];
                          unset($_SESSION['panier']);
                          creationPanier();
                          $i=0;
                          for (; $i < count($proformas); $i++) { 
                            if($proformas[$i]['id']==$cocher) break;
                          }
                          $y = 1;
                          while(($y+1) < strlen($proformas[$i]['Engins'])){
                            $ref = "";
                            for (; $proformas[$i]['Engins'][$y]!='/'; $y++) { 
                              $ref = $ref.$proformas[$i]['Engins'][$y];
                            }
                            $eng = 0;
                            for (; $eng < count($engins); $eng++) {
                                if($engins[$eng]['Ref']==$ref) break;
                            }
                            $y++;
                            while ($proformas[$i]['Engins'][$y]!='/' or $proformas[$i]['Engins'][$y+1]!='/') { 
                                $y++;
                            }
                            $y+=2;
                            $strqte = "";
                            for (; $proformas[$i]['Engins'][$y] !=')' ; $y++) { 
                                $strqte = $strqte.$proformas[$i]['Engins'][$y];
                            }
                            $qte = intval($strqte);
                            modifierQTeArticle($engins[$eng]['Ref']."/".$engins[$eng]['Categorie']."/".$engins[$eng]['Marque']."/".$engins[$eng]['Type']."/".$engins[$eng]['Origine']."/".$engins[$eng]['ConfBase'],$qte,$engins[$eng]['Prix'],$engins[$eng]['prix_transport'],$proformas[$i]['Options']);
                          }
                          getBon(false);
                          //header('Location: pdf_bon.php');
                          exit();
                        }
                        else{
                          break;
                        }
                      }
                      else{
                        $i++;
                      }
              }
                
            }
            else if((isset($_POST['inputClientName']) or isset($_POST['inputClientWilaya']) or isset($_POST['inputClientCode'])) and isset($_POST['enregistrerBoutton'])){
              if (compterArticles()>0) {
                  try {
                    $db = include 'db_mysql.php';
                     $stmtenr = $db->prepare($sqlEnregistrer);
                     $str = "";
                     for ($i=0; $i < compterArticles(); $i++) { 
                        $str = $str."(".$_SESSION['panier']['libelleProduit'][$i]."//".$_SESSION['panier']['qteProduit'][$i].")";
                     }
                     echo $str;
                     $stmtenr->execute(array(date("d/m/Y"),"100j",$_SESSION['email'],$_SESSION['CodeClient'],"100j","roterdam",$str,"options"));

                     $nb_insert = $stmtenr->rowCount();
                     //echo $nb_insert.' insertion effectuée<br/>';
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
                $_SESSION['NIF'] = $clients[$i]['NIF'];
                $_SESSION['EmailResp1'] = $clients[$i]['EmailResp1'];
                $_SESSION['EmailResp2'] = $clients[$i]['EmailResp2'];
                //sendmail($str,'Proforma','proforma.pdf',true,getProforma());
                echo getBon(true);
              }
              }
            
          }

            else if(isset($_POST['nbConfBase'])){
              if(creationPanier()){
                $x=0;
                while($_GET['enginM']!=$engins[$x]['Marque'] and $_GET['enginT']!=$engins[$x]['Type']){
                  $x++;
                }
                modifierQTeArticle($engins[$x]['Ref']."/".$engins[$x]['Categorie']."/".$engins[$x]['Marque']."/".$engins[$x]['Type']."/".$engins[$x]['Origine']."/".$engins[$x]['ConfBase'],$_POST['nbConfBase'],$engins[$x]['Prix']);
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
                  echo "ca a marché";
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

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------->


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


<!------------------------------------------------------------------------------------------------------------------------------------------------------------------->


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


    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="container">
        <div class="row">
            <div class="col-lg-10">
              <form class="form-horizontal" method="post" >

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------->
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
                        <!--<p><?php if(isset($_SESSION['NomSociete'])) { echo $_SESSION['NomSociete']; } ?></p>-->
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

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                <div class="card mb-4 shadow-sm ">
                  <div class="card-header">
                    <h5>GENERER BON DE COMMANDE A PARTIR DE LA PROFORMA :</h5>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-striped table-sm">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Numéro</th>
                          <th>Date</th>
                          <th>Produits</th>
                          <th>Options</th>
                        </tr>
                        
                      </thead>
                      <tbody>
                        <?php 
                        for ($i=0; $i < count($proformas); $i++){
                          if($proformas[$i]['EmisPar']==$_SESSION['email']){
                          
                            ?>
                        <tr>
                          <td><input type="checkbox" name="boxproforma<?php echo $proformas[$i]['id']?>"></td>
                          <td><?php echo $proformas[$i]['id']?></td>
                          <td><?php echo $proformas[$i]['DateCreation']?></td>
                          <?php
                          $y = 1;
                          $eng = 0;
                          while(($y+1) < strlen($proformas[$i]['Engins'])){
                            $ref = "";
                            for (; $proformas[$i]['Engins'][$y]!='/'; $y++) { 
                              $ref = $ref.$proformas[$i]['Engins'][$y];
                            }
                            for ($eng = 0; $eng < count($engins); $eng++) {
                                if($engins[$eng]['Ref']==$ref) break;
                            }
                            $y++;
                            while ($proformas[$i]['Engins'][$y]!='/' or $proformas[$i]['Engins'][$y+1]!='/') { 
                                $y++;
                            }
                            $y+=2;
                            $strqte = "";
                            for (; $proformas[$i]['Engins'][$y] !=')' ; $y++) { 
                                $strqte = $strqte.$proformas[$i]['Engins'][$y];
                            }
                            $qte = intval($strqte);
                          
                          ?>

                          <td><?php echo $ref." ".$engins[$eng]['Categorie']." ".$engins[$eng]['Marque'] ?></td>
                          <td>
                          <?php
                          }
                          $op_array = array();
                          $op=0;
                          //while($proformas[$i]['Options'][$op+1]!="/"){
                          while($op<strlen($proformas[$i]['Options'])){
                            $str="";
                            for (; $proformas[$i]['Options'][$op]!="/"; $op++) {
                              $str = $str.$proformas[$i]['Options'][$op];
                            }
                            $op++;
                            //echo $str;
                            array_push($op_array, intval($str));
                          }
                          $a = 0;
                          while($a<count($options)){
                            $o = array_shift($op_array);
                            if ($options[$a]['Engin']==$engins[$eng]['id'] && $o>0) {
                          ?>
                          <li><?php echo $o." ".$options[$a]['Nom']?></li>
                        <?php } $a++;}?>
                        </td>
                        </tr>
                      <?php }}?>
                      </tbody>
                    </table>
                  </div>
                </div>

<!------------------------------------------------------------------------------------------------------------------------------------------------------------------->

                  <div class="form-group">
                    <div class="row">
                      <div class="col-lg-4">
                      </div>
                      <div class="col-lg-4">
                        <!--onclick="window.location.href = 'pdf_bon.php';"-->
                        <button name="visualiserButton" class="btn btn-lg btn-block btn-primary" type="submit">Visualiser</button>
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
                                <p><input type="checkbox" value="boxmailemail1" id="boxmail_email1" name="boxmail_email1"> <?php echo $_SESSION['EmailResp1'] ?></p>
                                <?php
                                }
                                if(isset($_SESSION['EmailResp2']) and $_SESSION['EmailResp2']!=""){?>
                                <p><input type="checkbox" value="boxmailemail2" id="boxmail_email2" name="boxmail_email2"> <?php echo $_SESSION['EmailResp2'] ?></p>
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
                      <!--<div class="col-lg-4">
                        <button name="enregistrerBoutton" class="btn btn-lg btn-block btn-primary" type="submit">Enregistrer</button>
                      </div>-->
                    </div>
                  </div>



            </form>
            </div>
            
      </div>
    </main>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
        <script src="dashboard.js"></script></body>
</html>