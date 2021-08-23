<?php

function exist_engin($engins,$marque,$type){
    $exist = false;
    for ($e=0; $e < count($engins); $e++) { 
        if($engins[$e]['Marque'] == $marque && $engins[$e]['Type'] == $type){
        $exist = true;
        break;
        }
    }
    return $exist;
}

function RepEfface($dir)
    {
    $handle = opendir($dir);
    while($elem = readdir($handle)) //ce while vide tous les repertoire et sous rep
    {
        if(is_dir($dir.'/'.$elem) && substr($elem, -2, 2) !== '..' && substr($elem, -1, 1) !== '.') //si c'est un repertoire
        {
            RepEfface($dir.'/'.$elem);
        }
        else
        {
            if(substr($elem, -2, 2) !== '..' && substr($elem, -1, 1) !== '.')
            {
                unlink($dir.'/'.$elem);
            }
        }
    }
    
    $handle = opendir($dir);
    while($elem = readdir($handle)) //ce while efface tous les dossiers
    {
        if(is_dir($dir.'/'.$elem) && substr($elem, -2, 2) !== '..' && substr($elem, -1, 1) !== '.') //si c'est un repertoire
        {
            RepEfface($dir.'/'.$elem);
            rmdir($dir.'/'.$elem);
        }    
    
    }
    rmdir($dir); //ce rmdir eface le repertoire principale
   }

?>