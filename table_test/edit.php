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
    // condition sur la modification
    $condition=$table_query->dynamicCondition(json_decode($params["condition"]),'=');
    // execution de la requete de modification
    $query=$table_query->dynamicUpdate(json_decode($params["data"]),$condition);
    //$reponse["query"]=$query;
    $resultat=$taf_config->get_db()->exec($query);
    if ($resultat) {
        $reponse["status"] = true;
    } else {
        $reponse["status"] = false;
        $reponse["erreur"] = "Erreur! ou pas de moification";
    }
    echo json_encode($reponse);
} catch (\Throwable $th) {
    
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>