<?php
  session_start();
  if (!isset($_SESSION['email'])){
    header('Location: index.php');
  } 
  else{
    require('fonctions_panier.php');
    

  $sql_categ='SELECT CatégoriesProduits
          FROM others
          ORDER BY id;';
  $sql_engins='SELECT Categorie, Marque , Type, Prix, ConfBase
          FROM engins
          ORDER BY id;';
  $sql_options='SELECT Engin, Nom, Prix
          FROM options
          ORDER BY id;';
  $sql_clients='SELECT NomSociete,CodeClient,Adresse,CodePostal,Ville,Wilaya,Pays,NIF
          FROM clients
          ORDER BY id;';

          $categ=array();
          $engins=array();
          $clients=array();
          $options=array();
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
             unset($db);

             if(creationPanier()){

             }

            if(isset($_POST['inputClientName']) or isset($_POST['inputClientWilaya']) or isset($_POST['inputClientCode'])){

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
                          $_SESSION['NIF'] = $clients[$i]['NIF'];
                          header('Location: pdf.php');
                          exit();
                      }
                      else{
                        $i++;
                      }
              }
                
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

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="logos/logo.JPG" sizes="180x180">
    <link rel="icon" href="logos/logo.JPG" sizes="32x32" type="image/png">
    <link rel="icon" href="logos/logo.JPG" sizes="16x16" type="image/png">
    <link rel="manifest" href="favicons/manifest.json">
    <link rel="mask-icon" href="favicons/safari-pinned-tab.svg" color="#563d7c">
    <link rel="icon" href="logos/logo.JPG">
    <meta name="msapplication-config" content="favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c">

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
            //window.location.href = "proforma.php?categ=<?php echo $_GET['categ'] ?>"+"&cID="+i;
            //sessionStorage.setItem('cID',i);
            /*xhr.send("cID=" + escape(i));*/
              
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
        //if( (tab[0].includes(ClientName.value) && tab[5].includes(ClientWilaya.value)) || tab[1].includes(ClientCode.value))
        //if(tab[0][0]==ClientName.value)
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

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        padding-top:100px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none; 
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
  </head>
  <body>
  <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-0 col-md-2 mr-0" href="#">
      <!--<span data-feather="home"></span>-->
      <!--<img class="mb-0" src="logos/logo.JPG" width="212" height="90">-->
      
    </a>
    
      <a class="nav-link" href="marketing.php?categ=Postes%20Premium">
        <span data-feather="shopping-cart" align="center"></span>
        <br/>Marketing 
      </a>
    
    <a class="nav-link" href="newClient.php">
      <span data-feather="users"></span>
      <br/>Nouveau Client
    </a>
    <ul class="navbar-nav px-3">
    <a class="nav-link" href="proforma.php?categ=Postes%20Premium">
      <span data-feather="file"></span>
      <br/>Proforma <span align="center"class="sr-only">(current)</span>
    </a>
    </ul>
    <a class="nav-link" href="#">
      <span data-feather="file-text"></span>
      <br/>Bon de Commande
    </a>
    <a class="nav-link" href="#">
      <span data-feather="layers"></span>
      <br/>Projets
    </a>
    <a class="nav-link" href="calendrier.php">
      <span data-feather="calendar"></span>
      <br/>Agenda
    </a>
    <a class="nav-link" href="objectifs.php">
      <span data-feather="bar-chart-2"></span>
      <br/>Objectifs
    </a>
    <a class="nav-link" href="#">
      <span data-feather="file-text"></span>
      <br/>Rapports
    </a>
    <ul class="navbar-nav px-3">
      <li class="nav-item text-nowrap">
        <br/><p style="color:#49FF00">Session Ouverte<br/>
        <?php echo $_SESSION['prenom'] ." ".$_SESSION['nom']?><br/>
        <a style="color:#FF0000" href="deconnection.php">Déconnection</a></p>
      </li>
    </ul>
  </nav>

<div class="container-fluid">
  <div class="row">
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <div class="sidebar-sticky">
        <ul class="nav flex-column">
              <?php
              $i=0;
              while ($i<count($categ)) {
                ?>
                <button class="nav-item" type="submit"><a class="nav-link" href="proforma.php?categ=<?php echo htmlspecialchars($categ[$i][0]); ?>" name=<?php echo str_replace(' ', '-',$categ[$i][0]) ?> ><?php echo $categ[$i][0] ?></a></button>
                <?php
                $i++;
              }
              ?>
        <ul/>
        <br/>
          <a href="#" >
            AJOUTER  <span data-feather="plus-circle"></span>
          </a>

      </div>
    </nav>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
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
                    <a class="nav-link" href="proforma.php?categ=<?php echo htmlspecialchars($_GET['categ']);?>&enginM= <?php echo htmlspecialchars($engins[$j]['Marque']); ?>&enginT=<?php echo htmlspecialchars($engins[$j]['Type']);?>"> <?php echo $engins[$j]['Marque'].' '.$engins[$j]['Type']; ?> </a>
                    <?php
                  }
                  $j++;
                }
              ?>
            </ul>
            </div>
          </div>

          <div class="col-lg-8">

            <form class="form-horizontal" method="post" >
              <div calss="container">

                <div class="form-group">
                  <div class="card-deck mb-3 text-center">
                        <!--value=<?php echo $clients[$_GET['cID']][0] ?>-->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                              <h5 class="card-title">Nom</h5>
                            </div>
                            <label for="inputClientName" class="sr-only">Nom</label>
                            <input type="text" name ="inputClientName" id="inputClientName" class="form-control" placeholder="" onkeyup="checkpass();" value="<?php if(isset($_SESSION['NomSociete'])) { echo $_SESSION['NomSociete']; }?>" />
                        </div>
                        <p><?php if(isset($_SESSION['NomSociete'])) { echo $_SESSION['NomSociete']; } ?></p>
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
                

                <br/>
                <br/>

                <div class="form-group">
                  <div class="col-lg-20">
                    <div class="card mb-4 shadow-sm">
                      <div class="card-header">
                        <h5 class="card-title">Configuration de base</h5>
                      </div>
                      <div class="row">
                          <?php
                          if(isset($_GET['enginM'])){
                            $x=0;
                            while($_GET['enginM']!=$engins[$x]['Marque'] and $_GET['enginT']!=$engins[$x]['Type']){
                              $x++;
                            }?>
                            <div class="col-lg-2">
                              <input type="number" id="nbConfBase" class="form-control" placeholder="" value="0" onkeyup="">
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
                </div>

                <br/>
                <br/>
                  
                  <div class="form-group">
                    <div class="col-lg-20">
                      <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                          <h5 class="card-title">Options</h5>
                        </div>
                        <form method="post">
                        <div calss="container">
                        <?php
                          if(isset($_GET['enginM'])){
                                $x=0;
                                while($_GET['enginM']!=$engins[$x]['Marque'] and $_GET['enginT']!=$engins[$x]['Type']){
                                      $x++;
                                }
                                $y=0;
                                while($engins[$x]['Option'.($y+1)]!="0"){?>
                               
                                      <div class="row">
                                        <div class="col-lg-2">
                                          <input type="number" id="<?php echo $_GET['enginM'].$_GET['enginT'] ?>Option<?php echo ($y+1) ?>" class="form-control" placeholder="" value="0" onkeyup="<?php ajouterArticle($_GET['enginM'].$_GET['enginT']."Option".($y+1),1,$engins[$x]['Prix']) ?>">
                                        </div>
                                        <div class="col-lg-10">
                                        <p align="justify"><?php echo $engins[$x]['Option'.($y+1)];?></p>
                                        </div>
                                      </div>
                                      <br>
                        <?php 
                        $y++;
                        }
                        }?>
                      </div>
                      </form>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="card-deck mb-4 text-center">
                      <div class="card mb-4 shadow-sm">
                        <!--onclick="window.location.href = 'pdf.php';"-->
                        <button class="btn btn-lg btn-block btn-primary" type="submit" >Visualiser</button>
                      </div>
                      <div class="card mb-4 shadow-sm">
                        <button type="button" class="btn btn-lg btn-block btn-primary">Envoyer</button>
                      </div>
                      <div class="card mb-4 shadow-sm">
                        <button type="button" class="btn btn-lg btn-block btn-primary">Enregistrer</button>
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