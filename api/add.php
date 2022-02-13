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
    // pour charger l'heure courante
    // $params["date_enregistrement"]=date("Y-m-d H:i:s");
    $query=dynamicInsert($table_name, $params);
    // $reponse["query"]=$query;
    if ($connexion->exec($query)) {
        $reponse["status"] = true;
        $params["id"]=$connexion->lastInsertId();
        $reponse["data"] = $params;
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