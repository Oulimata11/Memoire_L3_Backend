<?php

use Taf\TafAuth;
use Taf\TableQuery;
try {
    require './config.php';
    require '../TableQuery.php';
    require '../taf_auth/TafAuth.php';
    $taf_auth = new TafAuth();
    // toutes les actions nécéssitent une authentification
    $taf_auth->check_auth($reponse);
    if ($reponse["status"] == false) {
        echo json_encode($reponse);
        die;
    }
    
    $table_query=new TableQuery($table_name);
    $params=$_POST;
    
    if(empty($params)){
        $reponse["status"] = false;
        $reponse["erreur"] = "Parameters required";
        echo json_encode($reponse);
        exit;
    }
    // pour charger l'heure courante
    // $params["date_enregistrement"]=date("Y-m-d H:i:s");
    $query=$table_query->dynamicInsert($params);
    // $reponse["query"]=$query;
    if ($taf_config->get_db()->exec($query)) {
        $reponse["status"] = true;
        $params["id"]=$taf_config->get_db()->lastInsertId();
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