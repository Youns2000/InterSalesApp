<?php
session_start();
if (!isset($_SESSION['index'])){
  header('Location: index.php');
  } 
        require('fonctions_ip.php');

        $sqlConnection = 'INSERT INTO `connections` (`id`, `pays`, `ville` , `nom`, `date`) VALUES (NULL, ?, ?, ?, ?);';

        if(isset($_POST['inputEmail']) and isset($_POST['inputPassword'])){
          $sql_data='SELECT id, email , mdp , nom , prenom , Statut FROM comptes WHERE email = ? ORDER BY id;';
          $data=array();
          $db = include 'db_mysql.php';

          try {
             $mail = $_POST['inputEmail'];
             $stmt = $db->prepare($sql_data);
             $stmt->execute(array($mail));
             $data= $stmt->fetchAll(PDO::FETCH_ASSOC);
             unset($db);
            //Azeddine@integral.fr
            if(count($data)==1 and password_verify($_POST['inputPassword'], $data[0]['mdp'])){
                $ip = get_user_ip();
                if($ip==false) echo "false";
                else echo "true"; 
 
                echo '$ip:'.$ip;

                $db = include 'db_mysql.php';
                $coords = get_user_coords();
                echo $coords;
                //$stmt = $db->prepare($sqlConnection);
                //$stmt->execute(array($coords['pays'],$coords['ville'],$data[0]['nom'],date("d/m/Y"),date('H:i:s')));
                //$stmt->execute(array('pays','ville','nom',date('Y-m-d H:i:s')));

                $_SESSION['email'] = $data[0]['email'];
                $_SESSION['nom'] = $data[0]['nom'];
                $_SESSION['id_compte'] = $data[0]['id'];
                $_SESSION['prenom'] = $data[0]['prenom'];
                $_SESSION['statut'] = $data[0]['Statut'];
                unset($db);
                header('Location: marketing.php?categ=Postes%20Premium');
                exit();
             } 
             else{
                echo 'Aucun resultat pour cette requete';
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


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
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
    <link rel="stylesheet" type="text/css" href="css/signin.css">
    
  </head>
  <body class="text-center">
    <form class="form-signin" method="post">
      <img class="mb-4" src="logos/logo integral_origin.jpg" alt="" width="310" height="150">

      <h1 class="h3 mb-3 font-weight-normal">Connection</h1>

      <label for="inputEmail" class="sr-only">Adresse Email</label>
      <input type="email" name="inputEmail" class="form-control" placeholder="Adresse Email" required autofocus>

      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" name="inputPassword" class="form-control" placeholder="Mot de passe" required>

      <button class="btn btn-lg btn-primary btn-block" type="submit">Entrer</button>
    </form>

  </body>
</html>
