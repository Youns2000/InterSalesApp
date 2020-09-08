<?php
if ( ! function_exists('db_connexion')) {
        function db_connexion() {
          // une fois ouverte, on renvoie toujours la même connexion
          static $pdo;
          // on vérifie si la connexion n'a pas déjà été initialisée
          if ( ! ($pdo instanceof PDO)) {
           // tentative d'ouverture de la connexion MySQL
             try {
            //$pdo = new PDO('mysql:host=intersallx720.mysql.db;port=3306;dbname=intersallx720;charset=utf8','intersallx720', 'dbIntersallx720', [
              $pdo = new PDO('mysql:host=localhost;port=3308;dbname=integral;charset=utf8','root', '', [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false
                ]);
             } 
             catch (PDOException $e) {
                throw new InvalidArgumentException('Erreur connexion à la base de données : '.$e->getMessage());
                exit;
             }
          }
          // renvoi de la ressource : connexion à la base de données
          return $pdo;
        }
      }
      return db_connexion();
?>