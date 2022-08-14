<?php

use Taf\TafAuth;
use Taf\TableQuery;

try {
    require './config.php';
    require '../TableQuery.php';
    require '../taf_auth/TafAuth.php';
    $params=$_GET;
    $taf_auth = new TafAuth();
    // toutes les actions nécéssitent une authentification
    $taf_auth->check_auth($reponse);
    if ($reponse["status"] == false && count($params)==0) {
        echo json_encode($reponse);
        die;
    }
    
    $table_query=new TableQuery($table_name);

    $condition=$table_query->dynamicCondition($params,"=");
    // $reponse["condition"]=$condition;
    $query="select *from $table_name ".$condition;
    $reponse["data"] = $taf_config->get_db()->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>