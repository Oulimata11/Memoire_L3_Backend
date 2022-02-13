<?php
try {
    require './config.php';
    $params=$edit_params;

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
    $primary_key= $connexion->query($query_primary_key)->fetch()["Column_name"];
    $condition="where $primary_key=".$params[$primary_key];
    // execution de la requete de modification
    $query=dynamicUpdate($table_name, $params,$condition);
    //$reponse["query"]=$query;
    $resultat=$connexion->exec($query);
    if ($resultat) {
        $reponse["status"] = true;
    } else {
        $reponse["status"] = false;
        $reponse["erreur"] = "Erreur $resultat";
    }
    echo json_encode($reponse);
} catch (\Throwable $th) {
    
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>