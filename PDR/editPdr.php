<?php


$bdd = include '../db_mysql.php';

$id = $_POST['id'];
$categ = $_POST['categ'];

$sql = "UPDATE pdr SET categ='".$categ."' WHERE id = ".$id;


$query = $bdd->prepare( $sql );
if ($query == false) {
	print_r($bdd->errorInfo());
	die ('Erreur prepare');
}
$sth = $query->execute();
if ($sth == false) {
	print_r($query->errorInfo());
	die ('Erreur execute');
}else{
	die ('OK');
}



	
?>
