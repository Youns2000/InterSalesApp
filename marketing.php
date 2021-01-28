<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  }
  else{
  $_SESSION['currentPage'] = "marketing";
  require('tools.php');
  $sql_categ='SELECT CatégoriesProduits, Ports, Devises
          FROM others
          ORDER BY id;';
  $sql_engins='SELECT id, Categorie, Marque , Type, Ref, Prix, prix_transport, Origine, Numero_serie, Annee_Fabrication, Type_Moteur, Numero_Serie_Moteur, ConfBase
          FROM engins
          ORDER BY id;';
  $sql_pays ='SELECT code , alpha2 , alpha3, nom_en_gb, nom_fr_fr
          FROM pays
          ORDER BY id;';        
  $sql_options ='SELECT id, Engin, Nom, Prix, prix_transport, Origine
          FROM options
          ORDER BY id;';  
  $sql_data='SELECT email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';

  $sqlAddCateg = 'INSERT INTO `others` (`CatégoriesProduits`) VALUES (?);';
  $sqlAddEng = 'INSERT INTO `engins` (`Categorie`, `Marque`, `Type`, `Ref`, `Prix`, `prix_transport`, `Origine`, `Numero_serie`, `Annee_Fabrication`, `Type_Moteur`, `Numero_Serie_Moteur`, `ConfBase`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);';
  $sqlAddOption = 'INSERT INTO `options` (`Engin`, `Nom`, `Prix`, `prix_transport`, `Origine`) VALUES (?,?,?,?,?);';
  
          $data=array();
          $categ=array();
          $engins=array();
          $pays=array();
          $options=array();
          $db = include 'db_mysql.php';

          try {
            
             $stmt = $db->prepare($sql_categ);
             $stmt->execute(array());
             $categ= $stmt->fetchAll();

             $stmt2 = $db->prepare($sql_engins);
             $stmt2->execute(array());
             $engins= $stmt2->fetchAll();

             $stmt3 = $db->prepare($sql_options);
             $stmt3->execute(array());
             $options= $stmt3->fetchAll();

             $stmt5 = $db->prepare($sql_pays);
             $stmt5->execute(array());
             $pays = $stmt5->fetchAll();
             unset($db);

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

            else if(isset($_POST['ajouterCateg'])){
              $exist = false;
                for ($e=0; $e < count($categ); $e++) { 
                  if($categ[$e]['CatégoriesProduits'] == $_POST['newCateg']){
                    $exist = true;
                    break;
                  }
                }
                  if($_POST['newCateg']!="" && $_POST['newCateg'][0]!=" " && $exist==false){
                      try {
                          $db = include 'db_mysql.php';
                           $stmtenr = $db->prepare($sqlAddCateg);
                           $stmtenr->execute(array($_POST['newCateg']));

                           $nb_insert = $stmtenr->rowCount();
                           if (!is_dir("produits/".$_POST['newCateg'])) mkdir("produits/".$_POST['newCateg']);

                           header('Refresh: 0');
                           unset($db);
                        }
                        catch (Exception $e){
                           print "Erreur ! " . $e->getMessage() . "<br/>";
                        }
                      }
            }

            else if(isset($_POST['ajouterEng'])){
              // $exist = false;
              //   for ($e=0; $e < count($engins); $e++) { 
              //     echo $engins[$e]['Categorie']."/".$_GET['categ'].",".$engins[$e]['Marque']."/".$_POST['marqueNew'];
              //     if($engins[$e]['Categorie'] == $_GET['categ'] && $engins[$e]['Marque'] == $_POST['marqueNew']){
              //       $exist = true;
              //       break;
              //     }
              //   }
              $exist = exist_engin($engins,$_POST['marqueNew'],$_POST['typeNew']);
                echo "exist:".$exist;
                if($exist==false){
                        try {

                          $db = include 'db_mysql.php';
                          $stmtenr = $db->prepare($sqlAddEng);
                          $stmtenr->execute(array($_GET['categ'], $_POST['marqueNew'], $_POST['typeNew'],$_POST['referenceNew'],$_POST['prixNew'],$_POST['prix_transportNew'],$_POST['origineNew'],$_POST['numserieNew'],$_POST['anneefabNew'],$_POST['typemoteurNew'],$_POST['numseriemoteurNew'],$_POST['configNew']));
                          $enginID = $db->lastInsertId();
                          $stmtenropt = $db->prepare($sqlAddOption);
                          for ($op=0; $op < 10; $op++) { 
                            $stmtenropt->execute(array($enginID,$_POST['nomOption'.$op],$_POST['prixOption'.$op],$_POST['prixTransportOption'.$op],$_POST['origineOption'.$op]));
                          }
                          
                          $nb_insert = $stmtenr->rowCount();
                          header('Refresh: 0');
                          unset($db);
                        }
                        catch (Exception $e){
                           print "Erreur ! " . $e->getMessage() . "<br/>";
                        }
                  }
                
            }   

            else if (isset($_POST['submitbrochure'])) {
                  if(isset($_FILES["brochure"]) && $_FILES["brochure"]["error"] == 0){
                      $allowed = array("pdf" => "image/pdf");
                      $filename = $_FILES["brochure"]["name"];
                      $filetype = $_FILES["brochure"]["type"];
                      $filesize = $_FILES["brochure"]["size"];

                      $ext = pathinfo($filename, PATHINFO_EXTENSION);
                      $maxsize =  5 * 1024 * 1024 * 1024 * 1024;

                      if(!array_key_exists($ext, $allowed)){
                        echo "<script>alert(\"Veuillez sélectionner un fichier au format pdf.\")</script>";
                      }
                      
                      else if($filesize > $maxsize){
                        echo "<script>alert(\"La taille du fichier est supérieure à la limite autorisée.\")</script>";
                      }

                      else{
                        if(is_dir("produits/".strtoupper($_GET['categ'])) or is_dir("produits/".$_GET['categ'])){
                          if(is_dir("produits/".strtoupper($_GET['categ']))) $currentCateg = strtoupper($_GET['categ']);
                          if(is_dir("produits/".$_GET['categ'])) $currentCateg = $_GET['categ'];
                          $dir = "produits/".$currentCateg."/".$_GET['enginM']." ".$_GET['enginT']."/BROCHURES"; 
                          if(is_dir($dir)){

                              if(file_exists($dir."/" . $_FILES["brochure"]["name"])){
                                echo "<script>alert(\"".$_FILES["brochure"]["name"]." existe déjà.\")</script>";
                              } else{
                                
                                    move_uploaded_file($_FILES["brochure"]["tmp_name"], $dir."/" . $_FILES["brochure"]["name"]);
                                    echo "<script>alert(\"".$_FILES["brochure"]["name"]." téléchargé avec succès!\")</script>";
                              }                          
                          }
                          else{
                                echo "<script>alert(\"Dossier inexistant\")</script>";
                              }
                        }

                      }
                  }
            }

            else if (isset($_POST['submitphoto'])) {
                  if(isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0){
                      $allowed = array("png" => "image/png", "jpg" => "image/jpg");
                      $filename = $_FILES["photo"]["name"];
                      $filetype = $_FILES["photo"]["type"];
                      $filesize = $_FILES["photo"]["size"];

                      $ext = pathinfo($filename, PATHINFO_EXTENSION);
                      $maxsize =  5 * 1024 * 1024 * 1024 * 1024;

                      if(!array_key_exists($ext, $allowed)){
                        echo "<script>alert(\"Veuillez sélectionner un fichier au format video.\")</script>";
                      }
                      
                      else if($filesize > $maxsize){
                        echo "<script>alert(\"La taille du fichier est supérieure à la limite autorisée.\")</script>";
                      }

                      else{
                        if(is_dir("produits/".strtoupper($_GET['categ'])) or is_dir("produits/".$_GET['categ'])){
                          if(is_dir("produits/".strtoupper($_GET['categ']))) $currentCateg = strtoupper($_GET['categ']);
                          if(is_dir("produits/".$_GET['categ'])) $currentCateg = $_GET['categ'];
                          $dir = "produits/".$currentCateg."/".$_GET['enginM']." ".$_GET['enginT']."/PHOTOS"; 
                          if(is_dir($dir)){

                              if(file_exists($dir."/" . $_FILES["photo"]["name"])){
                                echo "<script>alert(\"".$_FILES["photo"]["name"]." existe déjà.\")</script>";
                              } else{
                                
                                    move_uploaded_file($_FILES["photo"]["tmp_name"], $dir."/" . $_FILES["photo"]["name"]);
                                    echo "<script>alert(\"".$_FILES["photo"]["name"]." téléchargé avec succès!\")</script>";
                                  
                                  
                                }
                            
                              
                          } 
                          else{
                                echo "<script>alert(\"Dossier inexistant\")</script>";
                              }
                        }

                    } 
                }
            }

            else if (isset($_POST['submitvideo'])) {
                  if(isset($_FILES["video"]) && $_FILES["video"]["error"] == 0){
                      $allowed = array("mp4" => "video/mp4", "mov" => "video/mov", "avi" => "video/avi");
                      $filename = $_FILES["video"]["name"];
                      $filetype = $_FILES["video"]["type"];
                      $filesize = $_FILES["video"]["size"];

                      $ext = pathinfo($filename, PATHINFO_EXTENSION);
                      $maxsize =  5 * 1024 * 1024 * 1024 * 1024;

                      if(!array_key_exists($ext, $allowed)){
                        echo "<script>alert(\"Veuillez sélectionner un fichier au format png ou jpg.\")</script>";
                      }
                      
                      else if($filesize > $maxsize){
                        echo "<script>alert(\"La taille du fichier est supérieure à la limite autorisée.\")</script>";
                      }

                      else{
                        if(is_dir("produits/".strtoupper($_GET['categ'])) or is_dir("produits/".$_GET['categ'])){
                          if(is_dir("produits/".strtoupper($_GET['categ']))) $currentCateg = strtoupper($_GET['categ']);
                          if(is_dir("produits/".$_GET['categ'])) $currentCateg = $_GET['categ'];
                          $dir = "produits/".$currentCateg."/".$_GET['enginM']." ".$_GET['enginT']."/VIDEOS"; 
                          if(is_dir($dir)){

                              if(file_exists($dir."/" . $_FILES["video"]["name"])){
                                echo "<script>alert(\"".$_FILES["video"]["name"]." existe déjà.\")</script>";
                              } else{
                                
                                    move_uploaded_file($_FILES["video"]["tmp_name"], $dir."/" . $_FILES["video"]["name"]);
                                    echo "<script>alert(\"".$_FILES["video"]["name"]." téléchargé avec succès!\")</script>";
                                }                              
                          }
                          else{
                                echo "<script>alert(\"Dossier inexistant\")</script>";
                              }
                        }
                    } 
                }
            }

            else if(isset($_POST['configModif']) and isset($_GET['enginM'])){ //modification de produit
                      $dbco = include 'db_mysql.php';
                      $x=0; //current Engin
                      while($x<count($engins) and ($_GET['enginM']!=$engins[$x]['Marque'] or $_GET['enginT']!=$engins[$x]['Type'])){
                        $x++;
                      }

                      if(is_dir("./produits/".$_GET['categ'])) $categorie = $_GET['categ'];
                      else $categorie = strtoupper($_GET['categ']);

                      $old_dir = "./produits/".$categorie."/".$_GET['enginM']." ".$_GET['enginT'];
                      $new_dir = "./produits/".$categorie."/".$_POST['marqueModif']." ".$_POST['typeModif'];

                      
                      // sleep(2);
                      if(is_dir($new_dir)){
                        RepEfface($new_dir);
                      }
                      rename($old_dir,$new_dir);
                      // sleep(5);
                      

                      $mdengin = $dbco->prepare('UPDATE engins SET Marque=?, Type=?, Ref=?, Prix=?, prix_transport=?, Origine=?, Numero_serie=?, Annee_Fabrication=?, Type_Moteur=?, Numero_Serie_Moteur=?, ConfBase=? WHERE id=?');
                      $mdengin->execute(array($_POST['marqueModif'],$_POST['typeModif'],$_POST['referenceModif'],$_POST['prixEngin'],$_POST['prixTransportEngin'], $_POST['origineModif'], $_POST['numSerieModif'], $_POST['anneeFabModif'], $_POST['typeMoteurModif'], $_POST['numSerieMoteurModif'], $_POST['configBase'], $engins[$x]['id']));

                      $firstOpt=0;
                      $scroll=0;
                      while ($scroll<count($options)) {
                        if($options[$scroll]['Engin']==$engins[$x]['id']){
                          $firstOpt=$options[$scroll]['id'];
                          break;
                        }
                        $scroll++;
                      }
                                    
                      $opt=0;
                      $mdoptions = $dbco->prepare('UPDATE options SET Nom=? , Prix=? , prix_transport=? WHERE id=?');
                      for ($op=0; $op < count($options); $op++) {
                        if($options[$op]['Engin']==$engins[$x]['id']){
                          $mdoptions->execute(array($_POST["nomOption".$options[$op]['id']."Engin".$engins[$x]['id']] , $_POST["prixOption".$options[$op]['id']."Engin".$engins[$x]['id']] , $_POST["prixTransportOptionModif".$options[$op]['id']."Engin".$engins[$x]['id']] , $firstOpt));
                          $opt++;
                          $firstOpt++;
                        }
                      } 
                      unset($dbco);
                      // header('Refresh: 0');
                      header('Location: marketing.php?categ='.$_GET['categ'].'&enginM='.$_POST['marqueModif'].'&enginT='.$_POST['typeModif']);
                      // if(is_dir($old_dir)){
                      //   RepEfface($old_dir);
                      // }
              
            }

            else if(isset($_POST['supprimerEngin']) and isset($_GET['enginM'])){
                      $dbco = include 'db_mysql.php';
                      $x=0;
                      while($x<count($engins) and ($_GET['enginM']!=$engins[$x]['Marque'] or $_GET['enginT']!=$engins[$x]['Type'])){
                        $x++;
                      }

                      $id = $engins[$x]['id'];

                      if(is_dir("./produits/".$_GET['categ'])) $dir = "./produits/".$_GET['categ']."/".$engins[$x]['Marque']." ".$engins[$x]['Type'];
                      else $dir = "./produits/".strtoupper($_GET['categ'])."/".$engins[$x]['Marque']." ".$engins[$x]['Type'];

                      RepEfface($dir);


                      $mdengin = $dbco->prepare('DELETE FROM `engins` WHERE id=?');
                      $mdengin->execute(array($id));

                      $mdengin = $dbco->prepare('DELETE FROM `options` WHERE Engin=?');
                      $mdengin->execute(array($id));
                      
                      

                      unset($dbco);
                      // header('Refresh: 0');
                      header('Location: marketing.php?categ='.$_GET['categ']);
                      // header('Location: marketing.php?categ=Postes%20Premium');
            }

          } catch (Exception $e) {
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

    <style type="text/css">
      
      .container{
        padding-top: 0px;
      }
      .carousel-inner img {
          /*width: 100%;
          height: 300px;*/
          margin-top: 5%;
      }

      #custCarousel .carousel-indicators {
          position: static;
          margin-top: 20px;
          margin-bottom: 15%;
      }

      #custCarousel .carousel-indicators>li {
          width: 100px
      }

      #custCarousel .carousel-indicators li img {
          display: block;
          opacity: 0.5
      }

      #custCarousel .carousel-indicators li.active img {
          opacity: 1
      }

      #custCarousel .carousel-indicators li:hover img {
          opacity: 0.75
      }

      .carousel-item img {
          height: 300px;
      }

      .carousel-control-prev-icon,
      .carousel-control-next-icon {
        height: 80px;
        width: 80px;
        outline: black;
        background-size: 100%, 100%;
        border-radius: 50%;
        border: 0px solid black;
        background-image: none;
      }

      .carousel-control-next-icon:after
      {
        content: '>';
        font-size: 55px;
        color: black;
      }

      .carousel-control-prev-icon:after {
        content: '<';
        font-size: 55px;
        color: black;
      }
      .pad{
        padding-top: 50px;
      }
      .pad1{
        padding-top: 50px;
        -ms-flex: 0 0 16%;
        flex: 0 0 16%;
        max-width: 16%;
      }
      .pad2{
        padding-top: 60px;
        -ms-flex: 0 0 65%;
        flex: 0 0 65%;
        max-width: 65%;
      }
      .d-flex{
        display: block !important;
      }
      
      nav > .nav.nav-tabs{

        border: none;
          color:#fff;
          background:#272e38;
          border-radius:0;

      }
      nav > div a.nav-item.nav-link,
      nav > div a.nav-item.nav-link.active
      {
        border: none;
          padding: 18px 25px;
          color:#fff;
          background:#272e38;
          border-radius:0;
      }

      nav > div a.nav-item.nav-link.active:after
       {
        content: "";
        position: relative;
        bottom: -53px;
        left: -20%;
        border: 15px solid transparent;
        border-top-color: #366092 ;
      }
      .tab-content{
        background: #fdfdfd;
          line-height: 25px;
          border: 1px solid #ddd;
          /*border-top:5px solid #e74c3c;
          border-bottom:5px solid #e74c3c;*/
          padding:30px 25px;
      }

      nav > div a.nav-item.nav-link:hover,
      nav > div a.nav-item.nav-link:focus
      {
        border: none;
          background: #366092;
          color:#fff;
          border-radius:0;
          transition:background 0.20s linear;
      }

    </style>

    <link href="my_css.css" rel="stylesheet">

    <script type="text/javascript">
      document.getElementById('number').addEventListener('input', function (e) {
      var target = e.target, position = target.selectionEnd, length = target.value.length;
      target.value = target.value.replace(/[^\dA-Z]/g, '').replace(/(.{4})/g, '$1 ').trim();
      target.selectionEnd = position += ((target.value.charAt(position - 1) === ' ' && target.value.charAt(length - 1) === ' ' && length !== target.value.length) ? 1 : 0);
      });
    </script>
    
  </head>
  <body>

<!----------------------------------------------------------- NAVBAR-HAUT--------------------------------------------------------------------->
  <header>
  <?php include("header.php"); ?>
</header>
<!----------------------------------------------------------- NAVBAR-GAUCHE--------------------------------------------------------------------->

    <div class="row">
      <!-- <nav class="col-md-0 d-md-block sidebar"> -->
      <div class="col-lg-2 pad1">
        <div class="d-flex" id="wrapper">
            <div class="" id="sidebar-wrapper">
              <div class="list-group list-group-flush">
                <?php
                $i=0;

                while ($i<count($categ)) {
                  if($_GET['categ']==htmlspecialchars($categ[$i][0])) echo '<a href="marketing.php?categ='.htmlspecialchars($categ[$i][0]).'" name="' .str_replace(' ', '-',$categ[$i][0]) .'" class="list-group-item list-group-item-action bg-clair active" style="padding-top: 0.1rem; padding-bottom: 0.1rem; padding-left:0.1rem;"> '."<img style=\"margin-right: 10px;\" src=\"logos/categories/".$categ[$i][0].".JPG\" height=\"40px\" width=\"40px\"/>".$categ[$i][0].'</a>';
                  else echo '<a href="marketing.php?categ='.htmlspecialchars($categ[$i][0]).'" name="' .str_replace(' ', '-',$categ[$i][0]) .'" class="list-group-item list-group-item-action bg-clair" style="padding-top: 0.1rem; padding-bottom: 0.1rem; padding-left:0.6rem">'."<img src=\"logos/categories/".$categ[$i][0].".JPG\" style=\"margin-right: 10px;\" height=\"40px\" width=\"40px\"/>".$categ[$i][0].'</a>';
                  $i++;
                }
                ?>
                

          <?php if($_SESSION['statut']=="admin"){ ?>
                
                <button type="button" data-backdrop="false" data-toggle="modal" data-target="#ajouter_c" class="btn btn-light bg-clair btn-sm"><span data-feather="plus-circle"></button>
                <div class="modal fade" id="ajouter_c" role="dialog">
                  <div class="modal-dialog modal-dialog-centered">
                  
                    <!-- Modal content-->
                    <div class="modal-content">
                    <form method="post">

                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>

                      <div class="modal-body">
                        <p>Nouvelle Catégorie : <input id="newCateg" name="newCateg" type="text"/></p>
                      </div>

                      <div class="modal-footer">
                        <button name = "ajouterCateg" type="submit" class="btn btn-default">OK</button>
                      </div>
                      </form>
                    </div>
                  </div>
                </div>
              
          <?php } ?>
            </div>
          </div>
      </div>
    <!-- </nav> -->
    </div>
   
      <div class="col-lg-2 pad">
        <div class="d-flex" id="wrapper">
            <div class="" id="sidebar-wrapper">
              <div class="list-group list-group-flush">
                <?php
                $j=0;
                while($j<count($engins)){
                  if($engins[$j]['Categorie']==$_GET['categ']){
                    ?>
                      <a <?php  
                          if(isset($_GET['enginM'] ) and htmlspecialchars($engins[$j]['Marque'])." ".htmlspecialchars($engins[$j]['Type']) == $_GET['enginM']." ".$_GET['enginT']){
                              echo "class=\"list-group-item list-group-item-action bg-clair active float\" ";
                            } 
                              else{ 
                                echo "class=\"list-group-item list-group-item-action bg-clair float\" ";
                              } 
                              ?>  
                              href="marketing.php?categ=<?php echo htmlspecialchars($_GET['categ']);?>&enginM=<?php echo htmlspecialchars($engins[$j]['Marque']);?>&enginT=<?php echo htmlspecialchars($engins[$j]['Type']);?>"> <?php echo $engins[$j]['Marque'].' '.$engins[$j]['Type']; ?> </a>
                    <?php
                  }
                  $j++;
                } ?>
                

          <?php if($_SESSION['statut']=="admin"){  ?>
                <button type="button" data-backdrop="false" data-toggle="modal" data-target="#ajouter_eng" class="btn btn-light bg-clair btn-sm"><span data-feather="plus-circle"/></button>

                <div class="modal fad" id="ajouter_eng" role="dialog">
                  <div class="modal-dialog modal-dialog-centered  modal-xl">
                  
                    <!-- Modal content-->
                    <div class="modal-content">
                    <form method="post">

                      <div class="modal-header">
                        <h4>CREATION NOUVEAU PRODUIT</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                      </div>

                      <div class="modal-body">
                        <div class="row">
                          <div class="col-6">
                            <p><B>  Marque  : </B><input class="form-control" id="marqueNew" name="marqueNew" type="text" required/></p>
                            <p><B>   Type   : </B><input class="form-control" id="typeNew" name="typeNew" type="text"/></p>
                            <p><B>Référence : </B><input class="form-control" id="referenceNew" name="referenceNew" type="text"/></p>
                            <p><B>Prix : </B><input class="form-control" id="prixNew" name="prixNew" type="text"/></p>
                            <p><B>Prix Transport : </B><input class="form-control" id="prix_transportNew" name="prix_transportNew" type="text"/></p>
                            <select class="custom-select d-block w-100" name="origineNew" required>
                              <option selected value>Choisir l'origine du produit...</option>
                              <?php
                              for ($i=0; $pays[$i]['nom_fr_fr']!=""; $i++) {
                              ?>
                              <option value = "<?php echo $pays[$i]['nom_fr_fr']?>"><?php echo $pays[$i]['nom_fr_fr']?></option>
                            <?php }?>
                            </select>
                            </br>
                            <p><B>Numéro de série : </B><input class="form-control" id="numserieNew" name="numserieNew" type="text"/></p>
                            <p><B>Année de fabrication : </B><input class="form-control" id="anneefabNew" name="anneefabNew" type="text"/></p>
                            <p><B>Type moteur : </B><input class="form-control" id="typemoteurNew" name="typemoteurNew" type="text"/></p>
                            <p><B>Numéro de série moteur : </B><input class="form-control" id="numseriemoteurNew" name="numseriemoteurNew" type="text"/></p>
                            <label for="configNew"><B>Configuration :</B></label>
                            <textarea class="form-control" id="configNew" name="configNew" rows="3"></textarea>
                          </div> 
                          <div class="col-6">
                            <?php
                            for ($i=0; $i < 10; $i++) {
                              echo "<p>  <B>Option ".($i+1).":</B>"  ;             
                              echo "<input class=\"form-control\" placeholder=\"Description\" name=\"nomOption".$i."\" type=\"text\"/>";
                              echo "<td><input type=\"number\"  step=\"0.01\" style=\"font-weight: bold; width:150;\" placeholder=\"Prix\" name=\"prixOption".$i."\"/><B>€</B></td>&nbsp&nbsp&nbsp;";
                              echo "<td><input type=\"number\"  step=\"0.01\" style=\"font-weight: bold; width:150;\" placeholder=\"Prix Transport\" name=\"prixTransportOption".$i."\"/><B>€</B></td>";
                              echo "<select class=\"custom-select d-block w-100\" name=\"origineOption".$i."\" >";
                              echo "<option selected value>Choisir l'origine de l'option...</option>";
                              for ($j=0; $pays[$j]['nom_fr_fr']!=""; $j++) {   
                                echo "<option value = ".$pays[$j]['nom_fr_fr'].">".$pays[$j]['nom_fr_fr']."</option>";
                              }
                              echo "</select>";
                              echo "</p>";
                            }
                            ?>
                          </div>   
                        </div>
                      </div>

                      <div class="modal-footer">
                        <button name = "ajouterEng" type="submit" class="btn btn-default">Ajouter le produit</button>
                      </div>
                    </form>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>
          </div>
      </div>
    <!-- </nav> -->
    </div>
    
<!----------------------------------------------------------------------CONFIGURATION---------------------------------------------------------------------------------->

    <!-- <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4"> -->
      <div class="col-lg-7 pad2">
      <div class="container">
        <!-- <div class="row"> -->
          
          <!-- <div class="col-lg-9"> -->

            <div class="card mb-4 shadow-sm">
                  <div class="card-header">
                    <nav>
                      <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-config-tab" data-toggle="tab" href="#nav-config" role="tab" aria-controls="nav-config" aria-selected="true">CONFIGURATION</a>
                        <a class="nav-item nav-link" id="nav-brochures-tab" data-toggle="tab" href="#nav-brochures" role="tab" aria-controls="nav-brochures" aria-selected="false">BROCHURES</a>
                        <a class="nav-item nav-link" id="nav-photos-tab" data-toggle="tab" href="#nav-photos" role="tab" aria-controls="nav-photos" aria-selected="false">PHOTOS</a>
                        <a class="nav-item nav-link" id="nav-videos-tab" data-toggle="tab" href="#nav-videos" role="tab" aria-controls="nav-videos" aria-selected="false">VIDEOS</a>
                      </div>
                    </nav>
                  </div>


                  <div class="tab-content" id="nav-tabContent">

                    <div class="tab-pane fade show active" id="nav-config" role="tabpanel" aria-labelledby="nav-config-tab">
                      <form method="post">
                        <?php
                            if(isset($_GET['enginM'])){
                              $x=0;
                              while(($_GET['enginM']!=$engins[$x]['Marque'] or $_GET['enginT']!=$engins[$x]['Type']) and $x<count($engins)){
                                $x++;
                              }
                            }

                              if(isset($_GET['categ']) and isset($_GET['enginM'])){
                                echo "<input type=\"text\" name=\"marqueModif\" style=\"font-weight: bold; width:30%;\" value=\"".$engins[$x]['Marque']."\"/>";
                                echo "<input type=\"text\" name=\"typeModif\" style=\"font-weight: bold; width:30%;\" value=\"".$engins[$x]['Type']."\"/>";
                                
                                echo "<div class=\"row\">";
                                echo "<div class=\"col-lg-6\">";
                                echo "<B>Référence:</B></br><input type=\"text\" name=\"referenceModif\" style=\" width:100%;\" value=\"".$engins[$x]['Ref']."\"/>";
                                echo "<B>Numéro de série:</B></br><input type=\"text\" name=\"numSerieModif\" style=\" width:100%;\" value=\"".$engins[$x]['Numero_serie']."\"/>";
                                echo "<B>Origine:</B></br>";
                                echo "<select class=\"custom-select d-block w-100\" name=\"origineModif\" >";
                                echo "<option selected value>Choisir l'origine du produit...</option>";
                                for ($i=0; $pays[$i]['nom_fr_fr']!=""; $i++) {
                                  if($engins[$x]['Origine']==$pays[$i]['nom_fr_fr']) echo "<option value = \"".$pays[$i]['nom_fr_fr']."\"selected>".$pays[$i]['nom_fr_fr']."</option>";
                                  else echo "<option value = \"".$pays[$i]['nom_fr_fr']."\">".$pays[$i]['nom_fr_fr']."</option>";
                                }
                                echo "</select>";
                                echo "</div>";
                                echo "<div class=\"col-lg-6\">";
                                echo "<B>Année de fabrication:</B></br><input type=\"number\" id=\"number\" name=\"anneeFabModif\" style=\" width:100%;\" value=\"".$engins[$x]['Annee_Fabrication']."\"/>";
                                echo "<B>Type moteur:</B></br><input type=\"text\" name=\"typeMoteurModif\" style=\" width:100%;\" value=\"".$engins[$x]['Type_Moteur']."\"/>";
                                echo "<B>Numéro de série moteur:</B></br><input type=\"text\" name=\"numSerieMoteurModif\" style=\" width:100%;\" value=\"".$engins[$x]['Numero_Serie_Moteur']."\"/>";
                                echo "</div>";
                                echo "</div>";
                              }
                              ?>

                            <div class="modal-body">
                              <div class="row">
                                
                                  <table class="table">
                                    <thead class="thead-dark">
                                      <tr>
                                        <th scope="col">Configuration de base</th>
                                        <th scope="col">Prix</th>
                                        <?php if($_SESSION['statut']=="admin") echo "<th scope=\"col\">Prix Transport</th>"; ?>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                      if(isset($_GET['enginM'])){
                                        if($_SESSION['statut']=="admin"){
                                          echo "<tr>";
                                          echo "<td style=\"width:55%;\"><textarea class=\"form-control\" name=\"configBase\" rows=\"4\">".$engins[$x]['ConfBase']."</textarea></td>";
                                          echo "<td><input type=\"number\" id=\"number\" name=\"prixEngin\" style=\"font-weight: bold; width:90%;\" value=\"".$engins[$x]['Prix']."\"/><B>€</B></td>";
                                          echo "<td><input type=\"number\" id=\"number\" name=\"prixTransportEngin\"step=\"0.01\" style=\"font-weight: bold; width:90%;\" value=\"".$engins[$x]['prix_transport']."\"/><B>€</B></td>";
                                          echo "</tr>";
                                        }
                                        else{
                                          echo "<tr>";
                                          echo "<td><p>".$engins[$x]['ConfBase']."</p></td>";
                                          echo "<td><B>".$engins[$x]['Prix']."€</B></td>";
                                          echo "</tr>";
                                        }
                                      } ?>

                                    </tbody>
                                  </table>

                                  <table class="table">
                                    <thead class="thead-light">
                                      <tr>
                                        <th scope="col">Options</th>
                                        <th scope="col">Prix</th>
                                        <?php if($_SESSION['statut']=="admin") echo "<th scope=\"col\">Prix Transport</th>"; ?>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php 
                                      if(isset($_GET['enginM'])){
                                      for ($op=0; $op < count($options); $op++) {
                                        if($options[$op]['Engin']==$engins[$x]['id']){
                                          if($_SESSION['statut']=="admin"){
                                            echo "<tr>";
                                            echo "<td width=\"70%\"><input type=\"texte\" style=\"width:90%;\" name=\"nomOption".$options[$op]['id']."Engin".$engins[$x]['id']."\" value=\"".$options[$op]['Nom']."\"/></td>";
                                            echo "<td><input type=\"number\" id=\"number\" step=\"0.01\" style=\"font-weight: bold; width:90%;\" name=\"prixOption".$options[$op]['id']."Engin".$engins[$x]['id']."\"value=\"".$options[$op]['Prix']."\"/><B>€</B></td>";
                                            echo "<td><input type=\"number\" id=\"number\" step=\"0.01\" style=\"font-weight: bold; width:90%;\" name=\"prixTransportOptionModif".$options[$op]['id']."Engin".$engins[$x]['id']."\"value=\"".$options[$op]['prix_transport']."\"/><B>€</B></td>";
                                            echo "</tr>";
                                          }
                                          else{
                                            echo "<tr>";
                                            echo "<td>".$options[$op]['Nom']."</td>";
                                            echo "<td> <B>".$options[$op]['Prix']."€</B> </td>";
                                            echo "</tr>";
                                          }
                                        }
                                      }
                                      }?>

                                    </tbody>
                                  </table>
                                  <!-- <div class="row"> -->
                                    
                                    <div class="col-lg-9">
                                      <button type="submit" style="background-color: #343a40;" class="btn-lg btn-block btn-secondary justify-content-end" name="configModif">Modifier le produit</button>
                                    </div>
                                    <div class="col-lg-3">
                                      <!-- <button type="submit" name="supprimerEngin" style="color:red; color:hover=#a41111" onclick="return confirm('Are you sure?');" class="close" aria-label="Close">Supprimer</button> -->
                                      <button type="submit"  class="btn-lg btn-block btn-danger justify-content-end" onclick="return confirm('Etes-vous sûre?');" name="supprimerEngin">Supprimer</button>
                                    </div>
                                  <!-- </div> -->
                          </div>
                        </div>
                      </form> 
                    </div>
                    <!------------------------------------------------------------BROCHURES-------------------------------------------------------------->

                    <div class="tab-pane fade" id="nav-brochures" role="tabpanel" aria-labelledby="nav-brochures-tab">
                      <?php
                          if(isset($_GET['enginM'])){
                            if(!is_dir("produits/".strtoupper($_GET['categ'])) and !is_dir("produits/".$_GET['categ'])) mkdir("produits/".strtoupper($_GET['categ']));
                            if(is_dir("produits/".strtoupper($_GET['categ']))) $currentCateg = strtoupper($_GET['categ']);
                            if(is_dir("produits/".$_GET['categ'])) $currentCateg = $_GET['categ'];

                            $dir = "produits/".$currentCateg."/".$_GET['enginM']." ".$_GET['enginT'];
                            if(!is_dir($dir)) mkdir($dir);

                            $dir = $dir."/BROCHURES";
                            if(!is_dir($dir)) mkdir($dir);
                            $brochures = scandir($dir);
                            foreach ($brochures as $broch){
                              if($broch!="." && $broch!="..")
                              echo '<p><a class="btn btn-light" href="'.$dir."/".$broch.'" onclick="window.open(this.href); return false;">'.$broch.'</a></p>';
                            }
                          }
                          ?>
                      <?php if(isset($_GET['enginM']) and $_SESSION['statut']=="admin"){ ?> <button type="button" data-backdrop="false" data-toggle="modal" data-target="#ajouter_brochure" class="btn btn-light btn-sm"><span data-feather="plus-circle"></button> <?php } ?>

                      <div class="modal fade" id="ajouter_brochure" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">
                        
                          <!-- Modal content-->
                          <div class="modal-content">
                            <div class="modal-header">
                              <h2>Ajouter Brochure</h2>
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">
                              <form method="post" enctype="multipart/form-data">
                                      <label for="brochure">Brochure:</label>
                                      <input type="file" name="brochure" id="brochure">
                                      <!-- <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="123" /> -->
                                      <input type="submit" name="submitbrochure" value="Envoyer">
                                      <p><strong>Note:</strong> Seuls les formats .pdf sont autorisés.</p>
                                  </form>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!------------------------------------------------------------PHOTOS-------------------------------------------------------------->

                    <div class="tab-pane fade" id="nav-photos" role="tabpanel" aria-labelledby="nav-photos-tab">

                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                  <?php 
                                      if(isset($_GET['enginM'])){
                                        if(!is_dir("produits/".strtoupper($_GET['categ'])) and !is_dir("produits/".$_GET['categ'])) mkdir("produits/".strtoupper($_GET['categ']));
                                        if(is_dir("produits/".strtoupper($_GET['categ']))) $currentCateg = strtoupper($_GET['categ']);
                                        if(is_dir("produits/".$_GET['categ'])) $currentCateg = $_GET['categ'];
            
                                        $dir = "produits/".$currentCateg."/".$_GET['enginM']." ".$_GET['enginT'];
                                        if(!is_dir($dir)) mkdir($dir);
                                        
                                        $dir = $dir."/PHOTOS";
                                        if(!is_dir($dir)) mkdir($dir);
                                        $images = scandir($dir);
                                        $i = 0;
                                        $y = 0; ?>

                                    <div id="custCarousel" class="carousel slide" data-ride="carousel" align="center">

                                        <div class="carousel-inner">
                                          <?php foreach ($images as $photo) { ?>
                                            <?php if($photo!="." && $photo!=".."){ ?><div class="carousel-item <?php if($i==2) { ?> active <?php } ?>"> <img src="<?php echo $dir."/".$photo ?>" alt="Hills"> </div> <?php } ?>
                                          <?php  $i++; } ?>

                                        </div> 
                                        <a class="carousel-control-prev" href="#custCarousel" data-slide="prev"> <span class="carousel-control-prev-icon"></span> </a> <a class="carousel-control-next" href="#custCarousel" data-slide="next"> <span class="carousel-control-next-icon"></span> </a>
                                        <ol class="carousel-indicators list-inline">
                                          <?php foreach ($images as $photo) { ?>
                                            <?php if($photo!="." && $photo!=".."){ ?>
                                            <li class="list-inline-item active"> <a id="carousel-selector-0" class="selected" data-slide-to="<?php echo $y-2?>" data-target="#custCarousel"> <img src="<?php echo $dir."/".$photo ?>" class="img-fluid"> </a> </li>
                                            <?php } ?>
                                            <?php  $y++; } ?>
                                        </ol>
                                    </div>
                                    <?php
                                        }
                                      ?>
                                      <?php if(isset($_GET['enginM']) and $_SESSION['statut']=="admin"){ ?> <button type="button" data-backdrop="false" data-toggle="modal" data-target="#ajouter_photo" class="btn btn-light btn-sm"><span data-feather="plus-circle"></button> <?php } ?>

                                      <div class="modal fade" id="ajouter_photo" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered">
                                        
                                          <!-- Modal content-->
                                          <div class="modal-content">

                                            <div class="modal-header">
                                              <h2>Ajouter Image</h2>
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            </div>

                                            <div class="modal-body">
                                              <form method="post" enctype="multipart/form-data">
                                                      <label for="photo">Image:</label>
                                                      <input type="file" name="photo" id="photo">
                                                      <!-- <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="123" /> -->
                                                      <input type="submit" name="submitphoto" value="Envoyer">
                                                      <p><strong>Note:</strong> Seuls les formats .jpg et .png sont autorisés.</p>
                                                  </form>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!------------------------------------------------------------VIDEOS-------------------------------------------------------------->

                    <div class="tab-pane fade" id="nav-videos" role="tabpanel" aria-labelledby="nav-videos-tab">
                      <div class="text-align: left">
                      <?php 
                          if(isset($_GET['enginM']))
                          {
                            if(!is_dir("produits/".strtoupper($_GET['categ'])) and !is_dir("produits/".$_GET['categ'])) mkdir("produits/".strtoupper($_GET['categ']));
                            if(is_dir("produits/".strtoupper($_GET['categ']))) $currentCateg = strtoupper($_GET['categ']);
                            if(is_dir("produits/".$_GET['categ'])) $currentCateg = $_GET['categ'];

                            $dir = "produits/".$currentCateg."/".$_GET['enginM']." ".$_GET['enginT'];
                            if(!is_dir($dir)) mkdir($dir);

                            $dir = $dir."/VIDEOS";
                            if(!is_dir($dir)) mkdir($dir);
                            $videos = scandir($dir);
                            foreach ($videos as $video) {
                              if($video!="." && $video!="..")
                              echo "<video controls height=\"200\" width=\"370\" > <source src=\"".$dir."/".utf8_decode($video)."\"/></video>";
                            }

                          }
                          ?>
                          </div>

                          <?php if(isset($_GET['enginM']) and $_SESSION['statut']=="admin"){ ?> <button type="button" data-backdrop="false" data-toggle="modal" data-target="#ajouter_video" class="btn btn-light btn-sm"><span data-feather="plus-circle"></button> <?php } ?>

                          <div class="modal fade" id="ajouter_video" role="dialog">
                            <div class="modal-dialog modal-dialog-centered">
                            
                              <!-- Modal content-->
                              <div class="modal-content">

                                <div class="modal-header">
                                  <h2>Ajouter Video</h2>
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">
                                  <form method="post" enctype="multipart/form-data">
                                          <label for="video">Video:</label>
                                          <input type="file" name="video" id="video">
                                          <!-- <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="123" /> -->
                                          <input type="submit" name="submitvideo" value="Envoyer">
                                          <p><strong>Note:</strong> Seuls les formats .mov, .mp4 et .avi sont autorisés.</p>
                                      </form>
                                </div>
                              </div>
                            </div>
                          </div>

                          
                        </div>
                        
                    </div>
                  


          </div>
        <!-- </div> -->
            


            <!-- </div> -->
            </div>

          </div>
        </div>
        </div>
      </div>
   <!--  </main> -->
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      <script>window.jQuery || document.write('<script src="js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
        <script src="dashboard.js"></script></body>
        <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
</html>
