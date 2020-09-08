<?php
if(isset($_POST['mailform'])){
	$destinataire='younes.benreg@gmail.com, younes.benreguieg@epita.fr';
	$from='Integral SARL <no-reply@intersalesapp.com>';
	$sujet='Proforma';
	$chemin_fichier= 'doc.pdf'; // chemin relatif par rapport au fichier de script envoyant l'email, j'ai pas testé en chemin absolu
	$message='';

	$boundary = "_".md5 (uniqid (rand()));

	//on selectionne le fichier à partir d'un chemin relatif 
	    $attached_file = file_get_contents($chemin_fichier); //file name ie: ./image.jpg
	    $attached_file = chunk_split(base64_encode($attached_file));
	//on recupere ici le nom du fichier
	    $pos=strrpos($chemin_fichier,"/");
	    if($pos!==false)$file_name=substr($chemin_fichier,$pos+1);
	    else $file_name=$chemin_fichier;

	//on recupere ici le type du fichier
	    $pos=strrpos($chemin_fichier,".");
	    if($pos!==false)$file_type="/".substr($chemin_fichier,$pos+1);
	    else $file_type="";

	    //echo "file_type=$file_type";
	    $attached = "\n\n". "--" .$boundary . "\nContent-Type: application".$file_type."; name=\"$file_name\"\r\nContent-Transfer-Encoding: base64\r\nContent-Disposition: attachment; filename=\"$file_name\"\r\n\n".$attached_file . "--" . $boundary . "--";

	//on formate les headers
	    $headers ="From: ".$from." \r\n";
	    $headers .= "MIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

	//on formate le corps du message
	    $body = "--". $boundary ."\nContent-Type: text/plain; charset=ISO-8859-1\r\n\n".$message . $attached;

	//on envoie le mail
	$res = mail($destinataire,$sujet,$body,$headers);
}
?>
<form method="POST" action="">
	<input type="submit" value="Recevoir un mail !" name="mailform"/>
</form>