<?php
try
{
	$bdd = new PDO('mysql:host=intersallx721.mysql.db;port=3306;dbname=intersallx721;charset=utf8','intersallx721', 'dbIntersallx721');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
