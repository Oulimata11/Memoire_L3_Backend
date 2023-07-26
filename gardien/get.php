<?php

use Taf\TafAuth;
use Taf\TableQuery;

try {
    require './config.php';
    require '../TableQuery.php';
    require '../taf_auth/TafAuth.php';
    $taf_auth = new TafAuth();
    /* 
        $params
        contient tous les parametres envoyés par la methode POST
     */
    // toutes les actions nécéssitent une authentification
    $auth_reponse=$taf_auth->check_auth();
    if ($auth_reponse["status"] == false && count($params)==0) {
        echo json_encode($auth_reponse);
        die;
    }
    
    $table_query=new TableQuery($table_name);

    $condition=$table_query->dynamicCondition($params,"=");
    // $reponse["condition"]=$condition;
    $query="SELECT g.*,
    (SELECT MAX(note) FROM note n WHERE n.id_gardien = g.id_gardien) AS derniere_note,
    (SELECT COUNT(id_absence) FROM absence a WHERE a.id_gardien = g.id_gardien) AS nombre_absence,
    (SELECT MAX(date_debut_conges) FROM conges c WHERE c.id_gardien = g.id_gardien) AS derniere_date_conge
FROM gardien g; ";
    $reponse["data"] = $taf_config->get_db()->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>