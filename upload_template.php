<?php
// Vérifier si le formulaire a été soumis
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Vérifie si le fichier a été uploadé sans erreur.
    if(isset($_FILES["brochure"]) && $_FILES["brochure"]["error"] == 0){
        $allowed = array("pdf" => "image/pdf");
        $filename = $_FILES["brochure"]["name"];
        $filetype = $_FILES["brochure"]["type"];
        $filesize = $_FILES["brochure"]["size"];

        // Vérifie l'extension du fichier
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Erreur : Veuillez sélectionner un format de fichier valide.");

        // Vérifie la taille du fichier - 5Mo maximum
        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: La taille du fichier est supérieure à la limite autorisée.");

        // Vérifie le type MIME du fichier
        if(in_array($filetype, $allowed)){
            // Vérifie si le fichier existe avant de le télécharger.
            if(file_exists("upload/" . $_FILES["brochure"]["name"])){
                echo $_FILES["brochure"]["name"] . " existe déjà.";
            } else{
                move_uploaded_file($_FILES["brochure"]["tmp_name"], "upload/" . $_FILES["brochure"]["name"]);
                echo "Votre fichier a été téléchargé avec succès.";
            } 
        } else{
            echo "Error: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer."; 
        }
    } else{
        echo "Error: " . $_FILES["brochure"]["error"];
    }
}
?>

<?php
$_SESSION["upload_progress_123"] = array(
 "start_time" => 1234567890,   // L'heure de la requête
 "content_length" => 57343257, // Longueur du contenu POST
 "bytes_processed" => 453489,  // Quantité d'octets reçus et traités
 "done" => false,              // true lorsque le gestionnaire POST a terminé, avec succès ou non
 "files" => array(
  0 => array(
   "field_name" => "file1",       // Nom du champ <input/>
   // Les 3 éléments suivants sont équivalents à ceux dans $_FILES
   "name" => "foo.avi",
   "tmp_name" => "/tmp/phpxxxxxx",
   "error" => 0,
   "done" => true,                // True lorsque le gestionnaire POST a terminé de gérer ce fichier
   "start_time" => 1234567890,    // L'heure de début de requête
   "bytes_processed" => 57343250, // Quantité d'octets reçus et traités pour ce fichier
  ),
 )
);