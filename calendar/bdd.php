<?php
try
{
	$bdd = new PDO('mysql:host=localhost;port=3308;dbname=calendar;charset=utf8', 'root', '');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
