<?php
use Taf\TableQuery;
try {
    require './config.php';
    require '../TableQuery.php';
    $table_query=new TableQuery($table_name);
    $params=$_POST;


    if(count($params)==0){
        $reponse["status"] = false;
        $reponse["erreur"] = "Parameters required";
        echo json_encode($reponse);
        exit;
    }
    // pour charger l'heure courante
    // $params["date_enregistrement"]=date("Y-m-d H:i:s");
    // recupération de a clé primaire de la table pour la condition de modification
    $query_primary_key="SHOW KEYS FROM $table_name WHERE Key_name = 'PRIMARY'";
    $primary_key= $taf_config->get_db()->query($query_primary_key)->fetch()["Column_name"];
    $condition="where $primary_key=".$params[$primary_key];
    // execution de la requete de modification
    $query=$table_query->dynamicUpdate($params,$condition);
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