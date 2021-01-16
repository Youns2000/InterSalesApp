<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_FILES["brochure"]) && $_FILES["brochure"]["error"] == 0){
        $allowed = array("pdf" => "image/pdf");
        $filename = $_FILES["brochure"]["name"];
        $filetype = $_FILES["brochure"]["type"];
        $filesize = $_FILES["brochure"]["size"];

        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Erreur : Veuillez sélectionner un format de fichier valide.");

        $maxsize = 5 * 1024 * 1024;
        if($filesize > $maxsize) die("Error: La taille du fichier est supérieure à la limite autorisée.");

        // if(in_array($filetype, $allowed)){
            
            if(file_exists("upload/" . $_FILES["brochure"]["name"])){
                echo $_FILES["brochure"]["name"] . " existe déjà.";
            } else{
                move_uploaded_file($_FILES["brochure"]["tmp_name"], "upload/" . $_FILES["brochure"]["name"]);
                // echo "Votre fichier a été téléchargé avec succès.";
                header('Location: marketing.php?pr='.$_GET['pr']);
            } 
        // } else{
        //     echo "Error: Il y a eu un problème de téléchargement de votre fichier. Veuillez réessayer."; 
        // }
    } else{
        echo "Error: " . $_FILES["brochure"]["error"];
    }
}
?>