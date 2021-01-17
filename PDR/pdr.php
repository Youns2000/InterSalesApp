<?php
session_start();
if (!isset($_SESSION['email'])){
  header('Location: ../index.php');
} 
$_SESSION['currentPage'] = "pdr";
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
  </head>
  <body>

<!-----------------------------------------------------------HEADER--------------------------------------------------------------------->
<header>
          <?php include("../header.php"); ?> 
</header>
<!-----------------------------------------------------------MAIN--------------------------------------------------------------------->


<main>
<?php  ?>
    
    <div id="retard" class="total" style="margin-left: 10px;">
      <div class="header-style retard">
        <p class="p-header">Retard</p>
      </div>
      <div class="container">

              <div class="draggable" draggable="true">
                <p style="text-align: center; font-weight: bold;">Partenaire</p>
                <p style="text-align: center; font-weight: bold;">1</p>

                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_rouge"></div>
              </div>

      </div>
    </div>

    <div id="ajd" class="total">
      <div class="header-style ajd">
        <p class="p-header">Aujourd'hui</p>
      </div>
      <div class="container">

              <div class="draggable" draggable="true">
                <p style="text-align: center; font-weight: bold;">Partenaire</p>
                <p style="text-align: center; font-weight: bold;">2</p>

                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_rouge"></div>

              </div>

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

              <div class="draggable" draggable="true">
                <p style="text-align: center; font-weight: bold;">Partenaire</p>
                <p style="text-align: center; font-weight: bold;">3</p>

                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_rouge"></div>

              </div>

      </div>
    </div>

    <div id="ap-demain" class="total">
      <div class="header-style ap-demain">
        <p class="p-header"><?php 
            setlocale(LC_TIME, 'fr_FR', "French"); 
            echo strftime("%A %d %B %Y", strtotime('tomorrow'));?></p>
      </div>
      <div class="container">

              <div class="draggable" draggable="true">
                <p style="text-align: center; font-weight: bold;">Partenaire</p>
                <p style="text-align: center; font-weight: bold;">3</p>

                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_rouge"></div>

              </div>

      </div>
    </div>

    <div id="apap-demain" class="total">
      <div class="header-style apap-demain">
        <p class="p-header"><?php 
            setlocale(LC_TIME, 'fr_FR', "French"); 
            echo strftime("%A %d %B %Y", strtotime('+2 day'));?></p>
      </div>
      <div class="container">

              <div class="draggable" draggable="true">
                <p style="text-align: center; font-weight: bold;">Partenaire</p>
                <p style="text-align: center; font-weight: bold;">3</p>

                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_rouge"></div>

              </div>

      </div>
    </div>

    <div id="reste" class="total">
      <div class="header-style reste">
        <p class="p-header">A Venir</p>
      </div>
      <div class="container">

              <div class="draggable" draggable="true">
                <p style="text-align: center; font-weight: bold;">Partenaire</p>
                <p style="text-align: center; font-weight: bold;">3</p>

                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_vert"></div>
                <div class="trait_rouge"></div>

              </div>

      </div>
    </div>
</main>
<script src="todolist.js"></script>
</body>
</html>
