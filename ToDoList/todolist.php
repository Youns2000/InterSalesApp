<?php
session_start();
if (!isset($_SESSION['email'])){
  header('Location: ../index.php');
} 
$_SESSION['currentPage'] = "todolist";

$sql_todolist='SELECT * FROM todolist WHERE user="'.$_SESSION['email'].'" ORDER BY id';

$sqlNewAction = 'INSERT INTO `todolist` (`user`,`avancement`,`importance`,`partenaire`,`titre`,`date_creation`,`date_prog`,`fichiers`,`projet`,`detail`) 
                 VALUES (?,?,?,?,?,?,?,?,?,?);';

$sqlModifAction = 'UPDATE todolist SET user=?, avancement=?, importance=?, partenaire=?, titre=?, date_creation=?, date_prog=?,fichiers=?,projet=?, detail=? WHERE id=?;';

$sql_projets = 'SELECT id,user,nom,code,client,bft,dateCreation,description,etat, montant, objectif, offre, avancement, concurrence
          FROM projets
          WHERE user="'.$_SESSION['email'].'"
          ORDER BY id;';

$todolist = array();
$projets = array();

try {

  $db = include '../db_mysql.php';

  $todolist_execute = $db->prepare($sql_todolist);
  $todolist_execute->execute(array());
  $todolist = $todolist_execute->fetchAll();

  $projets_execute = $db->prepare($sql_projets);
  $projets_execute->execute(array());
  $projets = $projets_execute->fetchAll();

  $retard = array();
  $today = array();
  $demain = array();
  $ap = array();
  $apap = array();
  $reste = array();

  foreach ($todolist as $key => $value) {
    $date_tmp = strtotime($value['date_prog']);

    if($date_tmp<strtotime('today')){
      array_push($retard,$value);
    }
    else if($date_tmp==strtotime('today')){
      array_push($today,$value);
    }
    else if($date_tmp==strtotime('+1 day',strtotime("today"))){
      array_push($demain,$value);
    }
    else if($date_tmp==strtotime('+2 day',strtotime("today"))){
      array_push($ap,$value);
    }
    else if($date_tmp==strtotime('+3 day',strtotime("today"))){
      array_push($apap,$value);
    }
    else{
      array_push($reste,$value);
    }
  }

  if(isset($_POST['SaveAction'])){
    $query = $db->prepare($sqlNewAction);
    $query->execute(array($_SESSION['email'],$_POST['avancement'],$_POST['importance'],$_POST['partenaire'],$_POST['titre'],date("Y-m-d"),$_POST['date_prog'],"",$_POST['projet'],$_POST['detail']));

    header('Refresh: 0');
  }
  else if(isset($_POST['ModifAction'])){
    $query = $db->prepare($sqlModifAction);
    $query->execute(array($_SESSION['email'],$_POST['avancement'],$_POST['importance'],$_POST['partenaire'],$_POST['titre'],date("Y-m-d"),$_POST['date_prog'],"",$_POST['projet'],$_POST['detail'],$_POST['id_action']));
    header('Refresh: 0');
  }

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
    <link rel="stylesheet" href="todolist.css"/>
    <link rel="stylesheet" href="fiche_action.css"/>
  </head>
  <body>

<!-----------------------------------------------------------HEADER--------------------------------------------------------------------->
<header>
          <?php include("../header.php"); ?>
</header>
<!-----------------------------------------------------------MAIN--------------------------------------------------------------------->

<main>
    <?php include("fiche_action_modif.php"); ?>
    
    <div id="retard" class="total" style="margin-left: 10px;">
      <div class="header-style retard">
        <p class="p-header">Retard</p>
      </div>
      <div class="container">
      <?php for ($i=0; $i < count($retard); $i++) { ?>

        <div class="draggable" id="<?php echo $retard[$i]['id'];?>" draggable="true">
          <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $retard[$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifAction" >
          <p style="text-align: center; font-weight: bold;"><?php echo $retard[$i]['partenaire'];?></p>
          <p style="text-align: center; font-weight: bold;"><?php echo $retard[$i]['titre'];?></p>

          <?php if(intval($retard[$i]['avancement'])>0) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($retard[$i]['avancement'])>=30) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($retard[$i]['avancement'])>=70) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($retard[$i]['avancement'])>=100) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          </button>
        </div>

      <?php }?>
      </div>
    </div>

    <div id="today" class="total">
      <div class="header-style ajd">
        <p class="p-header">Aujourd'hui</p>
      </div>
      <div class="container">
          <?php for ($i=0; $i < count($today); $i++) { ?>

          <div class="draggable" id="<?php echo $today[$i]['id'];?>" draggable="true">
            <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $today[$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifAction" >
            <p style="text-align: center; font-weight: bold;"><?php echo $today[$i]['partenaire'];?></p>
            <p style="text-align: center; font-weight: bold;"><?php echo $today[$i]['titre'];?></p>

            <?php if(intval($today[$i]['avancement'])>0) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
            <?php if(intval($today[$i]['avancement'])>=30) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
            <?php if(intval($today[$i]['avancement'])>=70) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
            <?php if(intval($today[$i]['avancement'])>=100) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
            </button>
          </div>

          <?php }?>
      </div>
      
    </div>

    <div id="demain" class="total" >
      <div class="header-style demain" >
        <p class="p-header">Demain</p>
        <!-- <p style="color:white; text-align: center; font-size: small;">
            <?php setlocale(LC_TIME, 'fr_FR', "French"); 
                // $date = date('Y-m-d'); 
            echo strftime('%A %d %B %Y'/*, strtotime($date)*/);?>
        </p> -->
      </div>
      <div class="container">

        <?php for ($i=0; $i < count($demain); $i++) { ?>

          <div class="draggable" id="<?php echo $demain[$i]['id'];?>" draggable="true">
            <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $demain[$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifAction" >
            <p style="text-align: center; font-weight: bold;"><?php echo $demain[$i]['partenaire'];?></p>
            <p style="text-align: center; font-weight: bold;"><?php echo $demain[$i]['titre'];?></p>

            <?php if(intval($demain[$i]['avancement'])>0) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
            <?php if(intval($demain[$i]['avancement'])>=30) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
            <?php if(intval($demain[$i]['avancement'])>=70) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
            <?php if(intval($demain[$i]['avancement'])>=100) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
            </button>
          </div>

        <?php }?>

      </div>
    </div>

    <div id="ap" class="total">
      <div class="header-style ap-demain">
        <p class="p-header"><?php 
            echo utf8_encode(strftime("%A %d %B %Y", strtotime('+2 day')));?></p>
      </div>
      <div class="container">

      <?php for ($i=0; $i < count($ap); $i++) { ?>

        <div class="draggable" id="<?php echo $ap[$i]['id'];?>" draggable="true">
          <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $ap[$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifAction" >
          <p style="text-align: center; font-weight: bold;"><?php echo $ap[$i]['partenaire'];?></p>
          <p style="text-align: center; font-weight: bold;"><?php echo $ap[$i]['titre'];?></p>

          <?php if(intval($ap[$i]['avancement'])>0) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($ap[$i]['avancement'])>=30) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($ap[$i]['avancement'])>=70) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($ap[$i]['avancement'])>=100) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          </button>
        </div>

      <?php }?>

      </div>
    </div>

    <div id="apap" class="total">
      <div class="header-style apap-demain">
        <p class="p-header"><?php 
            echo utf8_encode(strftime("%A %d %B %Y", strtotime('+3 day')));?></p>
      </div>
      <div class="container">

      <?php for ($i=0; $i < count($apap); $i++) { ?>

        <div class="draggable" id="<?php echo $apap[$i]['id'];?>" draggable="true">
          <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $apap[$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifAction" >
          <p style="text-align: center; font-weight: bold;"><?php echo $apap[$i]['partenaire'];?></p>
          <p style="text-align: center; font-weight: bold;"><?php echo $apap[$i]['titre'];?></p>

          <?php if(intval($apap[$i]['avancement'])>0) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($apap[$i]['avancement'])>=30) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($apap[$i]['avancement'])>=70) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($apap[$i]['avancement'])>=100) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          </button>
        </div>

      <?php }?>

      </div>
    </div>

    <div id="reste" class="total">
      <div class="header-style reste">
        <p class="p-header">A Venir</p>
      </div>
      <div class="container">

      <?php for ($i=0; $i < count($reste); $i++) { ?>

        <div class="draggable" id="<?php echo $reste[$i]['id'];?>" draggable="true">
          <button type="button" class="open-EditFicheAction" style="border-radius: 3px; background-color: #fff;" data-id="<?php echo $reste[$i]['id']; ?>" data-backdrop="false" data-toggle="modal" data-target="#modifAction" >
          <p style="text-align: center; font-weight: bold;"><?php echo $reste[$i]['partenaire'];?></p>
          <p style="text-align: center; font-weight: bold;"><?php echo $reste[$i]['titre'];?></p>

          <?php if(intval($reste[$i]['avancement'])>0) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($reste[$i]['avancement'])>=30) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($reste[$i]['avancement'])>=70) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          <?php if(intval($reste[$i]['avancement'])>=100) echo "<div class=\"trait_vert\"></div>"; else echo "<div class=\"trait_rouge\"></div>";?>
          </button>
        </div>

      <?php }?>

      </div>
    </div>
    <div class="newAction">
        <button type="button" data-backdrop="false" data-toggle="modal" data-target="#newAction" style="margin-left:30%;margin-top:5%;background-color:#0a7500d5;" class="btn-sm btn-success justify-content-end">NOUVELLE ACTION</button>
        <?php include("fiche_action.php"); ?>
      </div>

    
</main>
<script src="todolist.js"></script>
<script>
var total = [];

total["retard"] = <?php echo json_encode($retard)?>;
total["today"] = <?php echo json_encode($today)?>;
total["demain"] = <?php echo json_encode($demain)?>;
total["ap"] = <?php echo json_encode($ap)?>;
total["apap"] = <?php echo json_encode($apap)?>;
total["reste"] = <?php echo json_encode($reste)?>;

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
</body>
</html>
