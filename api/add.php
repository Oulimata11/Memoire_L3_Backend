<?php
try {
    require './config.php';
    $params=$add_params;
    
    if(count($params)==0){
        $reponse["status"] = false;
        $reponse["erreur"] = "Parameters required";
        echo json_encode($reponse);
        exit;
    }
    $query=dynamicInsert($table_name, $params);
    // $reponse["query"]=$query;
    if ($connexion->exec($query)) {
        $reponse["status"] = true;
    } else {
        $reponse["status"] = false;
        $reponse["erreur"] = "Erreur d'insertion à la base de ";
    }
    echo json_encode($reponse);
} catch (\Throwable $th) {
    
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>