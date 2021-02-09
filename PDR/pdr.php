<?php
session_start();
if (!isset($_SESSION['email'])){
  header('Location: ../index.php');
} 
$_SESSION['currentPage'] = "pdr";

$sql_pdr='SELECT * FROM pdr WHERE user="'.$_SESSION['email'].'" ORDER BY id';

$sqlNewPDR = 'INSERT INTO `pdr` (`user`,`categ`,`client`,`titre`,`actions`,`ref`,`numero_serie`,`date_creation`,`pieces`) 
                 VALUES (?,?,?,?,?,?,?,?,?);';

$sqlModifPDR = 'UPDATE pdr SET user=?, categ=?, client=?, titre=?, actions=?, ref=?, numero_serie=?, date_creation=?,pieces=? WHERE id=?;';

$sql_projets = 'SELECT id,user,nom,code,client,bft,dateCreation,description,etat, montant, objectif, offre, avancement, concurrence
          FROM projets
          WHERE user="'.$_SESSION['email'].'"
          ORDER BY id;';

$sql_todolist = 'SELECT * FROM todolist ORDER BY id;';

$pdr = array();
$projets = array();
$todolist = array();

try {

  $db = include '../db_mysql.php';

  $pdr_execute = $db->prepare($sql_pdr);
  $pdr_execute->execute(array());
  $pdr = $pdr_execute->fetchAll();

  $projets_execute = $db->prepare($sql_projets);
  $projets_execute->execute(array());
  $projets = $projets_execute->fetchAll();

  $todolist_execute = $db->prepare($sql_todolist);
  $todolist_execute->execute(array());
  $todolist = $todolist_execute->fetchAll();

  $total = array();
  $total['demandes'] = array();
  $total['sourcing'] = array();
  $total['envoyer'] = array();
  $total['commandesClient'] = array();
  $total['commandesFournisseur'] = array();
  $total['livraisons'] = array();

  foreach ($pdr as $key => $value) {
    array_push($total[$value['categ']],$value);
  }

  if(isset($_POST['SavePDR'])){
    $query = $db->prepare($sqlNewPDR);
    $query->execute(array($_SESSION['email'],$_POST['email']));

    header('Refresh: 0');
  }




  // if(isset($_POST['SaveAction'])){
  //   $query = $db->prepare($sqlNewAction);
  //   $query->execute(array($_SESSION['email'],$_POST['avancement'],$_POST['importance'],$_POST['partenaire'],$_POST['titre'],date("Y-m-d"),$_POST['date_prog'],"",$_POST['projet'],$_POST['detail']));

  //   header('Refresh: 0');
  // }
  // else if(isset($_POST['ModifAction'])){
  //   $query = $db->prepare($sqlModifAction);
  //   $query->execute(array($_SESSION['email'],$_POST['avancement'],$_POST['importance'],$_POST['partenaire'],$_POST['titre'],date("Y-m-d"),$_POST['date_prog'],"",$_POST['projet'],$_POST['detail'],$_POST['id_action']));
  //   header('Refresh: 0');
  // }

  unset($db);

} catch (Exception $e) {
  print "Erreur ! " . $e->getMessage() . "<br/>";
}

setlocale(LC_TIME, 'fr_FR', "French");

?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Younes Benreguieg">
    <title>Integral Sales App</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="apple-touch-icon" href="../logos/logo.JPG" sizes="180x180">
    <link rel="icon" href="../logos/logo.JPG" sizes="32x32" type="image/png">
    <link rel="icon" href="../logos/logo.JPG" sizes="16x16" type="image/png">
    <link rel="manifest" href="../favicons/manifest.json">
    <link rel="mask-icon" href="../favicons/safari-pinned-tab.svg" color="#563d7c">
    <link rel="icon" href="../logos/logo.JPG">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <meta name="msapplication-config" content="favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c">
    <link href="../css/style_menu.css" rel="stylesheet">
    <link href="../css/dashboard.css" rel="stylesheet">
    <link href="../css/carousel.css" rel="stylesheet">
    <link href="../my_css.css" rel="stylesheet">

    <style type="text/css">
      main{
        margin-top: 4%;
      }
    </style>
    <link rel="stylesheet" href="pdr.css"/>
    <link rel="stylesheet" href="fiche_pdr.css"/>
    <!-- <link rel="stylesheet" href="fiche_action.css"/> -->
  </head>
  <body>

<!-----------------------------------------------------------HEADER--------------------------------------------------------------------->
<header>
          <?php include("../header.php"); ?>
</header>
<!-----------------------------------------------------------MAIN--------------------------------------------------------------------->

<main>
    <?php include("fiche_pdr_modif.php"); ?>
    
    <div id="demandes" class="total" style="margin-left: 10px;">
      <div class="header-style demandes">
        <p class="p-header">Demandes Clients</p>
      </div>
      <div class="container">
      <?php for ($i=0; $i < count($total['demandes']); $i++) { ?>

        <div class="draggable" id="<?php echo $total['demandes'][$i]['id'];?>" draggable="true">
          <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $$total['demandes'][$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifPDR" >
          <p style="text-align: center; font-weight: bold;"><?php echo $$total['demandes'][$i]['client'];?></p>
          <p style="text-align: center; font-weight: bold;"><?php echo $$total['demandes'][$i]['titre'];?></p>
          </button>
        </div>

      <?php }?>
      </div>
    </div>

    <div id="sourcing" class="total">
      <div class="header-style sourcing">
        <p class="p-header">Sourcing</p>
      </div>
      <div class="container">
          <?php for ($i=0; $i < count($total['sourcing']); $i++) { ?>

          <div class="draggable" id="<?php echo $total['sourcing'][$i]['id'];?>" draggable="true">
            <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $total['sourcing'][$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifPDR" >
            <p style="text-align: center; font-weight: bold;"><?php echo $total['sourcing'][$i]['client'];?></p>
            <p style="text-align: center; font-weight: bold;"><?php echo $total['sourcing'][$i]['titre'];?></p>
            </button>
          </div>

          <?php }?>
      </div>
      
    </div>

    <div id="envoyer" class="total" >
      <div class="header-style envoyer" >
        <p class="p-header">Offres envoyées</p>
      </div>
      <div class="container">

        <?php for ($i=0; $i < count($total['envoyer']); $i++) { ?>

          <div class="draggable" id="<?php echo $total['envoyer'][$i]['id'];?>" draggable="true">
            <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $total['envoyer'][$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifPDR" >
            <p style="text-align: center; font-weight: bold;"><?php echo $total['envoyer'][$i]['client'];?></p>
            <p style="text-align: center; font-weight: bold;"><?php echo $total['envoyer'][$i]['titre'];?></p>
            </button>
          </div>

        <?php }?>

      </div>
    </div>

    <div id="commandesClient" class="total">
      <div class="header-style commandesClient">
        <p class="p-header">Commandes Clients</p>
      </div>
      <div class="container">

      <?php for ($i=0; $i < count($total['commandesClient']); $i++) { ?>

        <div class="draggable" id="<?php echo $total['commandesClient'][$i]['id'];?>" draggable="true">
          <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $total['commandesClient'][$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifPDR" >
          <p style="text-align: center; font-weight: bold;"><?php echo $total['commandesClient'][$i]['client'];?></p>
          <p style="text-align: center; font-weight: bold;"><?php echo $total['commandesClient'][$i]['titre'];?></p>
          </button>
        </div>

      <?php }?>

      </div>
    </div>

    <div id="commandesFournisseur" class="total">
      <div class="header-style commandesFournisseur">
        <p class="p-header">Commandes Fournisseur</p>
      </div>
      <div class="container">

      <?php for ($i=0; $i < count($total['commandesFournisseur']); $i++) { ?>

        <div class="draggable" id="<?php echo $total['commandesFournisseur'][$i]['id'];?>" draggable="true">
          <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $total['commandesFournisseur'][$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifPDR" >
          <p style="text-align: center; font-weight: bold;"><?php echo $total['commandesFournisseur'][$i]['client'];?></p>
          <p style="text-align: center; font-weight: bold;"><?php echo $total['commandesFournisseur'][$i]['titre'];?></p>
          </button>
        </div>

      <?php }?>

      </div>
    </div>

    <div id="livraisons" class="total">
      <div class="header-style livraisons">
        <p class="p-header">Livraisons effectuées</p>
      </div>
      <div class="container">

      <?php for ($i=0; $i < count($total['livraisons']); $i++) { ?>

        <div class="draggable" id="<?php echo $total['livraisons'][$i]['id'];?>" draggable="true">
          <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $total['livraisons'][$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifPDR" >
          <p style="text-align: center; font-weight: bold;"><?php echo $total['livraisons'][$i]['client'];?></p>
          <p style="text-align: center; font-weight: bold;"><?php echo $total['livraisons'][$i]['titre'];?></p>
          </button>
        </div>

      <?php }?>

      </div>
    </div>

    <div class="newPDR">
        <button type="button" data-backdrop="false" data-toggle="modal" data-target="#newPDR" style="margin-left:30%;margin-top:5%;background-color:#640000d5;color:white;" class="btn-sm justify-content-end">Nouvelle Demande</button>
        <?php include("fiche_pdr.php"); ?>
      </div>

    
</main>
<script src="pdr.js"></script>
<script>
var total = [];

total["demandes"] = <?php echo json_encode($total['demandes'])?>;
total["sourcing"] = <?php echo json_encode($total['sourcing'])?>;
total["envoyer"] = <?php echo json_encode($total['envoyer'])?>;
total["commandesClient"] = <?php echo json_encode($total['commandesClient'])?>;
total["commandesFournisseur"] = <?php echo json_encode($total['commandesFournisseur'])?>;
total["livraisons"] = <?php echo json_encode($total['livraisons'])?>;

$(document).on("click", ".open-EditFicheAction", function () {
    var ActionId = $(this).data('id');
    var categ = $(this).parent().parent().parent().attr("id");
    var index = 0;

    for (; index < total[categ].length; index++) 
      if(total[categ][index]['id']==ActionId) break;
    var action = total[categ][index];
    $("#modifAction #titre").val( action['titre'] );
    $("#modifAction #partenaire").val( action['partenaire'] );
    $("#modifAction #projet").val( action['projet'] );

    let date_creation = new Date(action['date_creation']);
    let date_prog = new Date(action['date_prog']);
    let date_diff = (date_prog - date_creation)/86400000;
    $("#modifAction #date_creation").text(action['date_creation']);
    $("#modifAction #date_prog").val( action['date_prog']);
    $("#modifAction #depuis").text( date_diff+" jours" );

    $("#modifAction #id_action").val( ActionId );

    $("#modifAction #detail").val( action['detail'] );

    $("#modifAction .carre1 ").children().children().children('.btn').each(function(){
      if($(this).hasClass("active")) $(this).removeClass('active');
    });
    $("#modifAction .carre2 ").children().children('.btn').each(function(){
      if($(this).hasClass("active")) $(this).removeClass('active');
    });
    $("#modifAction #avancement"+action['avancement']).addClass('active');
    $("#modifAction #avancement"+action['avancement']).children().prop("checked", true);

    $("#modifAction #importance"+action['importance']).addClass('active');
    $("#modifAction #importance"+action['importance']).children().prop("checked", true);
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
</body>
</html>
