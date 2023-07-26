<?php
use Taf\TafAuth;
use Taf\TableQuery;
try {
    require './config.php';
    require '../TableQuery.php';
    require '../taf_auth/TafAuth.php';
    $taf_auth = new TafAuth();
    // toutes les actions nécéssitent une authentification
    $auth_reponse=$taf_auth->check_auth($reponse);
    if ($auth_reponse["status"] == false) {
        echo json_encode($auth_reponse);
        die;
    }
    
    $table_query=new TableQuery($table_name);
   /* 
        $params
        contient tous les parametres envoyés par la methode POST
     */

    if(empty($params)){
        $reponse["status"] = false;
        $reponse["erreur"] = "Parameters required";
        echo json_encode($reponse);
        exit;
    }

 
    $id_utilisateur=$params["id_utilisateur"];
    $ancien_password_utilisateur = addslashes($params["ancien_password_utilisateur"]);
    $new_password_utilisateur = addslashes($params["new_password_utilisateur"]);

    $result = $taf_config->get_db()->query("SELECT password_utilisateur FROM utilisateur
     WHERE id_utilisateur = '$id_utilisateur' and password_utilisateur=md5('$ancien_password_utilisateur')")->fetch(PDO::FETCH_ASSOC);

    if($result){
        $query = "update  utilisateur set password_utilisateur=md5('$new_password_utilisateur') where id_utilisateur=$id_utilisateur";
        if ($taf_config->get_db()->exec($query)) {
            $reponse["status"] = true;
            $params["id"] = $taf_config->get_db()->lastInsertId();
            $reponse["data"] = $params;
        } else {
            $reponse["status"] = false;
            $reponse["erreur"] = "Erreur d'insertion à la base de ";
        }
    }else{
        $reponse["status"] = false;
        $reponse["erreur"] = "Mot de passe nom conforme";
        echo json_encode($reponse);
        exit();
    }
    echo json_encode($reponse);

    
} catch (\Throwable $th) {
    
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>