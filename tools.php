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

?>